<?php

namespace Alomgyar\Consumption_reports;

use Alomgyar\Orders\Laravel\Services\OrderMailerService;
use Alomgyar\Product_movements\ProductMovement;
use Alomgyar\Products\Product;
use Alomgyar\Warehouses\Inventory;
use Alomgyar\Warehouses\Warehouse;
use App\Helpers\HumanReadable;
use App\Imports\MerchantConsumptionReportImport;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use SzamlaAgent\Buyer;
use SzamlaAgent\Currency;
use SzamlaAgent\Document\Invoice\Invoice;
use SzamlaAgent\Document\Receipt\Receipt;
use SzamlaAgent\Header\ReceiptHeader;
use SzamlaAgent\Item\InvoiceItem;
use SzamlaAgent\Item\ReceiptItem;
use SzamlaAgent\Language;
use SzamlaAgent\SzamlaAgentAPI;

require_once app_path().'/Components/Szamlazz/autoload.php';

class MerchantImportComponent extends Component
{
    public int $warehouseId = 0;

    public $fulfillment;

    public $paymentDue;

    public $created_at;

    public int $status = 1;

    public string $importfile = '';

    public string $importfileSize = '';

    public array $counts = [];

    public $causerType = 'App\User';

    public $causerId;

    public $purchasePrice;

    public array $info = [];

    public array $isbns = [];

    public string $invoice_url = '';

    public string $reference_nr = '';

    public int $causer_id = 0;

    public string $causer_type = 'App\User';

    public int $destination_id = 0;

    public int $source_id = 0;

    public string $source_type = 'merchant';

    public int $destination_type = ProductMovement::DESTINATION_TYPE_VOID;

    public $warehouse;

    public $products;

    public string $comment_general = '';

    public string $comment_bottom = '';

    public string $comment_void = '';

    public string $comment = '';

    public string $docType = 'invoice';

    protected $listeners = [
        'setWarehouseId' => 'setWarehouseId',
        'setImportFile' => 'setImportFile',
    ];

    protected $rules = [
        'warehouseId' => ['required'],
        'status' => ['required'],
        'created_at' => ['required'],
    ];

    protected $validationAttributes = [];

    public function mount()
    {
        $this->created_at = now()->format('Y-m-d');
    }

    public function render()
    {
        $this->warehouse = Warehouse::find($this->warehouseId);

        return view('consumption_reports::components.merchantcomponent')->layout('admin::layouts.master');
    }

    public function setWarehouseId($id)
    {
        $this->warehouseId = $id;
    }

    public function save()
    {
        $validator = Validator::make(
            [
                'warehouseId' => $this->warehouseId,
                'status' => $this->status,
                'created_at' => $this->created_at,
            ],
            $this->rules,
            []
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast-message', ['message' => 'Hiba történt!', 'type' => 'error']);
            $this->validate();
        } else {
            $this->reference_nr = ProductMovement::generateReferenceNr();
            $this->causer_id = Auth()->id();
            $this->comment_void = $this->warehouse->title.' kereskedő fogyásjelentése alapján';
            $this->source_id = $this->warehouseId;

            $model = ProductMovement::createByReferenceNumber($this);

            if ($model) {
                $data = $this->collectData($model);
                $dataInventory = $this->collectDataInventory($data);
                ProductMovement::addItems($model, $data);
                Inventory::updateInventory($dataInventory);

                if ($this->docType == 'invoice') {
                    $this->createInvoice();
                } else {
                    $this->createReceipt();
                }
                $this->saveMerchantReport();
            }

            $this->dispatchBrowserEvent('toast-message', ['message' => 'Sikeres kereskedői fogyás rögzítés!']);
            $this->reset();
        }
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function dehydrate()
    {
        $this->dispatchBrowserEvent('restartSelect2');
    }

    public function setImportFile($value)
    {
        $this->importfile = $value;
        $this->importfileSize = HumanReadable::bytesToHuman(Storage::size('public/imports/'.$this->importfile));
    }

    public function importConsumptionReport()
    {
        $products = Excel::toArray(
            new MerchantConsumptionReportImport,
            Storage::disk('local')->path('public/imports/'.$this->importfile)
        );

        $counts['product'] = $counts['quantity'] = $counts['price'] = $i = 0;

        foreach ($products as $tabs) {
            foreach ($tabs as $product) {
                $isbn = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $product[0]);
                $quantity = $product[1];
                $vat = $product[2];
                $netto = $product[3];
                $brutto = $product[4];
                $discount = $product[5];

                if ($isbn) {
                    $resp = [];
                    //                    if (( $vat ?? 0 ) > 0 && ( $netto ?? 0 ) > 0) {
                    if (($vat ?? 0) > 0) {
                        //                        if (( $quantity ?? 0 ) > 0) {
                        $importable = 1;
                        //                        } else {
                        if (! is_numeric(($quantity ?? null))) {
                            $resp[] = 'A mennyiséghez csak szám adható meg.';
                            $importable = 0;
                        }
                        //                            else {
                        //                                $resp[] = 'A mennyiség nullánál nagyobb kell legyen.';
                        //                            }
                        //                        }
                    } else {
                        //                        $resp[]     = 'Hiányzik a nettó ár és/vagy az ÁFA.';
                        $resp[] = 'Hiányzik az ÁFA.';
                        $importable = 0;
                    }

                    if ($importable) {
                        $brutto = is_numeric($brutto) ? $brutto : ($netto * ($vat + 100) / 100);
                        $counts['product']++;
                        $counts['quantity'] += $quantity;
                        $counts['price'] += $brutto * $quantity;
                        $this->isbns[] = $isbn;
                        $this->info[$i]['isbn'] = $isbn;
                        $this->info[$i]['vat'] = $vat;
                        $this->info[$i]['brutto'] = $brutto;
                        $this->info[$i]['quantity'] = $quantity;
                        $this->info[$i]['discount'] = $discount;
                    } else {
                        $counts['bad'][$i]['isbn'] = $isbn;
                        $counts['bad'][$i]['resp'] = $resp;
                    }
                }
                $i++;
            }
        }
        $this->info = array_values($this->info);
        $this->dispatchBrowserEvent('toast-message', ['message' => 'Az ellenőrzés lefutott!']);
        $this->counts = $counts;
        $this->products = Product::whereIn('isbn', $this->isbns)->get();
    }

    private function createInvoice()
    {
        activity()
            ->useLog('szamla')
            ->causedBy(auth()->user())
            ->log('Kereskedői számla készítés indul');

        //return ['order' => $this];
        $szamlaKey = env('SZAMLAZZ_0', 'qwd4jiyr796mzq9jcbybc67fpsezrzw4imwe32ta4v');

        $agent = SzamlaAgentAPI::create($szamlaKey, true, 0);

        $invoice = new Invoice(Invoice::INVOICE_TYPE_P_INVOICE);
        $cityPrefix = strtoupper($this->warehouse->billing_city[0] ?? '');
        $namePrefix = strtoupper(substr($this->warehouse->billing_business_name ?? '', 0, 3));
        $orderNumber = 'I'.$cityPrefix.$namePrefix.date('md');
        $header = $invoice->getHeader();
        //$header->setOrderNumber($orderNumber);
        $header->setPaymentMethod(Invoice::PAYMENT_METHOD_TRANSFER);
        $header->setCurrency(Currency::CURRENCY_HUF);
        $header->setLanguage(Language::LANGUAGE_HU);
        $header->setPaid(false);
        $header->setFulfillment($this->fulfillment);
        // Számla fizetési határideje
        $header->setPaymentDue($this->paymentDue);

        if (app()->environment('live')) {
            $header->setPrefix(ProductMovement::getPrefixAttribute(0, $this->warehouseId));
            $header->setComment($this->comment);
        } else {
            $header->setComment($this->comment.'Éles környezetben '.ProductMovement::getPrefixAttribute(0, $this->warehouseId).' előtaggal generálódna a számla');
        }

        $this->warehouse = Warehouse::find($this->warehouseId);

        // vevő létrehozása
        $buyer = new Buyer(
            $this->warehouse->billing_business_name,
            $this->warehouse->billing_zip_code,
            $this->warehouse->billing_city,
            $this->warehouse->billing_address
        );

        activity()
            ->useLog('szamla')
            ->causedBy(auth()->user())
            ->withProperties(['buyer' => $buyer])
            ->log('Kereskedői számla Vevő adatok');

        $buyer->setCountry('Magyarország');
        $buyer->setTaxNumber($this->warehouse->billing_vat_number);
        $buyer->setEmail($this->warehouse->email);

        $invoice->setBuyer($buyer);

        foreach ($this->info as $item) {
            $product = $this->products->where('isbn', $item['isbn'])->first();
            if ($product) {
                $tax_rate = $item['vat'] ?? $product->tax_rate ?? 5;
                $netto = $item['brutto'] / (1 + ($tax_rate / 100)) ?? $item->price / (1 + ($tax_rate / 100));
                $quantity = $item['quantity'];

                $invoiceItem = new InvoiceItem($product->title, $netto, $quantity, 'db', $tax_rate.'');
                // Tétel nettó értéke
                $invoiceItem->setNetPrice(round($netto * $quantity));
                // Tétel ÁFA értéke
                // áfa érték = tétel nettó értéke x áfakulcs mértéke / 100.
                $invoiceItem->setVatAmount(round(($netto * $tax_rate / 100) * $quantity));
                // Tétel bruttó értéke
                $invoiceItem->setGrossAmount($item['brutto'] * $quantity);
                // Tétel hozzáadása a számlához
                $invoice->addItem($invoiceItem);
            }
        }

        try {
            $result = $agent->generateInvoice($invoice);
            if ($result->isSuccess()) {
                $this->invoice_url = $result->getDocumentNumber();
                activity()
                    ->useLog('szamla')
                    ->causedBy(auth()->user())
                    ->withProperties(['merchantConsumptionReport' => $this])
                    ->log('Kereskedői számla elkészült');

                return true;
            } else {
                // email küldés - számlagenerálás sikertelen option('contact_email', 'janos.ecsedy@skvad.com')
                activity()
                    ->useLog('szamla')
                    ->causedBy(auth()->user())
                    ->withProperties(['merchantConsumptionReport' => $this])
                    ->log('Kereskedői számla nem készült el, mert a válasz sikertelen');
                OrderMailerService::create()->sendInvoiceGenerationFail('Kereskedői számla nem készült el, mert a válasz sikertelen');
            }
        } catch (Exception $e) {
            // emailküldés - számlagenerálás problémába ütközött option('contact_email', 'janos.ecsedy@skvad.com')
            OrderMailerService::create()->sendInvoiceGenerationFail('Kereskedői számla '.$e->getMessage());
            activity()
                ->useLog('szamla')
                ->causedBy(auth()->user())
                ->withProperties(['error' => $e->getMessage()])
                ->log('Kereskedői számla nem készült el');

            return $e->getMessage();
        }
    }

    private function collectData($model)
    {
        foreach ($this->info as $item) {
            $product = $this->products->where('isbn', $item['isbn'])->first();
            if ($product) {
                $quantity = $item['quantity'];
                $brutto = $item['brutto'];
                $discount = $item['discount'];
                $data[] = [
                    'product_movements_id' => $model->id,
                    'product_id' => $product->id,
                    'stock_in' => 0,
                    'status' => $this->status,
                    'stock_out' => $quantity,
                    'sale_price' => $brutto,
                    'discount' => $discount,
                    'created_at' => $this->created_at ?? now(),
                    'updated_at' => now(),
                ];
            }
        }

        return $data;
    }

    private function collectDataInventory($data)
    {
        return array_map(function ($item) {
            $item['source_id'] = $this->warehouseId;

            return $item;
        }, $data);
    }

    private function saveMerchantReport()
    {
        MerchantReport::create([
            'warehouse_id' => $this->warehouseId,
            'merchant_name' => $this->warehouse->title,
            'merchant_email' => $this->warehouse->email,
            'quantity' => $this->counts['quantity'],
            'total_amount' => $this->counts['price'],
            'invoice_url' => $this->invoice_url,
            'comment' => $this->comment,
            'created_by' => Auth()->id(),
        ]);
    }

    private function createReceipt()
    {
        activity()
            ->useLog('szamla')
            ->causedBy(auth()->user())
            ->log('Kereskedői számla készítés indul');

        //return ['order' => $this];
        $szamlaKey = env('SZAMLAZZ_0', 'qwd4jiyr796mzq9jcbybc67fpsezrzw4imwe32ta4v');

        $agent = SzamlaAgentAPI::create($szamlaKey, true, 0);

        $receipt = new Receipt();
        $header = new ReceiptHeader();
        $header->setPaymentMethod(Invoice::PAYMENT_METHOD_TRANSFER);
        $header->setCurrency(Currency::CURRENCY_HUF);
        $header->setPrefix(ProductMovement::getPrefixAttribute(0, $this->warehouseId));

        if (app()->environment('live')) {
            $header->setComment($this->comment);
        } else {
            $header->setComment($this->comment.'Éles környezetben '.ProductMovement::getPrefixAttribute(0, $this->warehouseId).' előtaggal generálódna a számla');
        }

        $receipt->setHeader($header);

        $this->warehouse = Warehouse::find($this->warehouseId);

        // vevő létrehozása
        $buyer = new Buyer(
            $this->warehouse->billing_business_name,
            $this->warehouse->billing_zip_code,
            $this->warehouse->billing_city,
            $this->warehouse->billing_address
        );

        activity()
            ->useLog('szamla')
            ->causedBy(auth()->user())
            ->withProperties(['buyer' => $buyer])
            ->log('Kereskedői számla Vevő adatok');

        $buyer->setCountry('Magyarország');
        $buyer->setTaxNumber($this->warehouse->billing_vat_number);
        $buyer->setEmail($this->warehouse->email);

        $receipt->setBuyer($buyer);

        foreach ($this->info as $item) {
            $product = $this->products->where('isbn', $item['isbn'])->first();
            if ($product) {
                $tax_rate = $item['vat'] ?? $product->tax_rate ?? 5;
                $netto = $item['brutto'] / (1 + ($tax_rate / 100)) ?? $item->price / (1 + ($tax_rate / 100));
                $quantity = $item['quantity'];

                $receiptItem = new ReceiptItem($product->title, $netto, $quantity, 'db', $tax_rate.'');
                // Tétel nettó értéke
                $receiptItem->setNetPrice(round($netto * $quantity));
                // Tétel ÁFA értéke
                // áfa érték = tétel nettó értéke x áfakulcs mértéke / 100.
                $receiptItem->setVatAmount(round(($netto * $tax_rate / 100) * $quantity));
                // Tétel bruttó értéke
                $receiptItem->setGrossAmount($item['brutto'] * $quantity);
                // Tétel hozzáadása a számlához
                $receipt->addItem($receiptItem);
            }
        }

        try {
            $result = $agent->generateReceipt($receipt);
            if ($result->isSuccess()) {
                $this->invoice_url = $result->getDocumentNumber();
                activity()
                    ->useLog('szamla')
                    ->causedBy(auth()->user())
                    ->withProperties(['merchantConsumptionReport' => $this])
                    ->log('Kereskedői számla elkészült');

                return true;
            } else {
                // email küldés - számlagenerálás sikertelen option('contact_email', 'janos.ecsedy@skvad.com')
                activity()
                    ->useLog('szamla')
                    ->causedBy(auth()->user())
                    ->withProperties(['merchantConsumptionReport' => $this])
                    ->log('Kereskedői számla nem készült el, mert a válasz sikertelen');
                OrderMailerService::create()->sendInvoiceGenerationFail('Kereskedői számla nem készült el, mert a válasz sikertelen');
            }
        } catch (Exception $e) {
            // emailküldés - számlagenerálás problémába ütközött option('contact_email', 'janos.ecsedy@skvad.com')
            OrderMailerService::create()->sendInvoiceGenerationFail('Kereskedői számla '.$e->getMessage());
            activity()
                ->useLog('szamla')
                ->causedBy(auth()->user())
                ->withProperties(['error' => $e->getMessage()])
                ->log('Kereskedői számla nem készült el');

            return $e->getMessage();
        }
    }
}
