<?php

namespace App;

use Alomgyar\Countries\Country;
use Alomgyar\Customers\Address;
use Alomgyar\Customers\Customer;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\Orders\Laravel\Services\OrderMailerService;
use Alomgyar\Product_movements\ProductMovement;
use Alomgyar\Products\Product;
use Alomgyar\Shops\Shop;
use Alomgyar\Warehouses\Inventory;
use Alomgyar\Warehouses\Warehouse;
use App\Entity\ValueObject\ShippingData;
use App\Helpers\AffiliateHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use SzamlaAgent\Buyer;
use SzamlaAgent\Currency;
use SzamlaAgent\Document\Invoice\CorrectiveInvoice;
use SzamlaAgent\Document\Invoice\Invoice;
use SzamlaAgent\Item\InvoiceItem;
use SzamlaAgent\Language;
use SzamlaAgent\SzamlaAgentAPI;
use TCPDF;

require_once app_path().'/Components/Szamlazz/autoload.php';

class Order extends Model
{
    use LogsActivity, HasFactory;

    const STATUS_DRAFT = 0;

    const STATUS_NEW = 1;

    const STATUS_PROCESSING = 2;

    const STATUS_WAITING_FOR_SHIPPING = 3;

    const STATUS_SHIPPING = 4;

    const STATUS_LANDED = 5;

    const STATUS_COMPLETED = 6;

    const STATUS_RETURNED = 7;

    const STATUS_DELETED = 8;

    const STATUS_PAYMENT_WAITING = 0;

    const STATUS_PAYMENT_ERROR = 1;

    const STATUS_PAYMENT_CANCELLED = 2;
    const STATUS_PAYMENT_PAID = 3;

    const AFFILIATE_CALCULATED = 1;

    const AFFILIATE_NOT_CALCULATED = 0;


    public static array $barionPrefix = ['A', 'O', 'N', 'A'];

    private $invoiceStatus;

    public $data;

    protected $fillable = [
        'customer_id',
        'order_number',
        'payment_token',
        'guest_token',
        'payment_status',
        'status',
        'shipping_fee',
        'payment_fee',
        'total_amount',
        'total_quantity',
        'has_ebook',
        'store',
        'country_id',
        'payment_method_id',
        'shipping_method_id',
        'invoice_url',
        'shipping_data',
        'attachments',
        'boxprovider',
        'payment_date',
        'message',
    ];

    public static array $invoice = [
        'cash' => Invoice::PAYMENT_METHOD_CASH,
        'cash_on_delivery' => Invoice::PAYMENT_METHOD_CASH_ON_DELIVERY,
        'transfer' => invoice::PAYMENT_METHOD_TRANSFER,
        'card' => Invoice::PAYMENT_METHOD_BANKCARD,
    ];

    public $statusMatrix = [
        self::STATUS_DRAFT => ['allowed' => []],
        self::STATUS_RETURNED => ['allowed' => []],
        self::STATUS_DELETED => ['allowed' => []],
        self::STATUS_NEW => [
            'title' => 'Megrendelve',
            'allowed' => ['all' => self::STATUS_PROCESSING],
        ],
        self::STATUS_PROCESSING => [
            'title' => 'Feldolgozás alatt',
            'allowed' => ['shipping_method_id:3' => self::STATUS_LANDED, 'all' => self::STATUS_WAITING_FOR_SHIPPING],
            'disallowed' => ['shipping_method_id:3' => [self::STATUS_PAYMENT_PAID]],
            'email' => 'status_processing',
        ],
        self::STATUS_WAITING_FOR_SHIPPING => [ //összekészítve
            'title' => 'Összekészítve',
            'allowed' => ['shipping_method_id:3' => self::STATUS_COMPLETED, 'all' => self::STATUS_SHIPPING],
            'functions' => ['updateStock', 'createInvoice'],
            'check' => ['checkPaymentStatus'],
            'email' => 'status_waiting_for_shipping',
        ],
        self::STATUS_SHIPPING => [
            'title' => 'Szállítás alatt',
            'allowed' => ['shipping_method_id:1' => self::STATUS_COMPLETED, 'all' => self::STATUS_LANDED],
            'functions' => ['checkPaymentStatus'],
            'email' => 'status_shipping',
        ],
        self::STATUS_LANDED => [
            'title' => 'Átvehető',
            'allowed' => ['shipping_method_id:3' => self::STATUS_WAITING_FOR_SHIPPING, 'all' => self::STATUS_COMPLETED],
            'disallowed' => ['shipping_method_id:3' => [self::STATUS_COMPLETED]],
            'functions' => ['checkShippingMethod'],
            'email' => 'status_landed',
        ],
        self::STATUS_COMPLETED => [
            'title' => 'Sikeres, kész',
            'allowed' => [],
            'email' => 'status_completed',
        ],
    ];

    protected $casts = [
        'shipping_data' => 'array',
        'attachments' => 'array',
    ];

    protected static $logAttributes = ['*'];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query->where('orders.id', 'like', '%'.$term.'%')
                    ->orWhere('orders.order_number', 'like', '%'.$term.'%')
                    ->orWhere('orders.email', 'like', '%'.$term.'%')
                    ->orWhere('orders.total_amount', 'like', ''.$term.'%');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeNotPayed($query)
    {
        return $query->where('payment_status', self::STATUS_PAYMENT_WAITING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopePayed($query)
    {
        return $query->where('payment_status', self::STATUS_PAYMENT_PAID);
    }

    public function scopeShipping($query)
    {
        return $query->where('status', self::STATUS_SHIPPING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_RETURNED);
    }
    public function scopeValidForAffiliate($query)
    {
        return $query->where('affiliate_calculated', self::AFFILIATE_CALCULATED)->orWhere(function($query) {
            $query->whereIn('payment_status',[self::STATUS_PAYMENT_WAITING,self::STATUS_PAYMENT_PAID])
                ->where('status', '<=',self::STATUS_COMPLETED)
                ->where('status', '>',self::STATUS_PROCESSING)
                ->where('valid_for_affiliate_since', '<', now());
        });
    }

    //Fizetett státusz
    //0-draft, 1-started, 2-waiting, 3-late, 4-failed, 5-done
    public function getPaymentStatusHtmlAttribute()
    {
        switch ($this->payment_status) {
            case self::STATUS_PAYMENT_WAITING:
                return '<span class="d-block badge bg-orange-600" title="Fizetésre vár">Fizetésre vár</span>';
            case self::STATUS_PAYMENT_ERROR:
                return '<span class="d-block badge bg-orange-600" title="Fizetési probléma">Fizetési probléma</span>';
            case self::STATUS_PAYMENT_CANCELLED:
                return '<span class="d-block badge bg-orange-600" title="Fizetés visszamondva">Fizetés visszamondva</span>';
            case self::STATUS_PAYMENT_PAID:
                return '<span class="d-block badge bg-success-600" title="Fizetve">Fizetve</span>';
        }
    }

    //Rendelés státusz
    public function getStatusHtmlAttribute()
    {
        switch ($this->status) {
            case self::STATUS_DRAFT:
                return '<span class="d-block badge bg-grey-600" title="Piszkozat">Piszkozat</span>';
            case self::STATUS_NEW:
                return '<span class="d-block badge bg-grey-600" title="Megrendelve">Megrendelve</span>';
            case self::STATUS_PROCESSING:
                return '<span class="d-block badge bg-info-600" title="Feldolgozás alatt">Feldolgozás alatt</span>';
            case self::STATUS_WAITING_FOR_SHIPPING:
                return '<span class="d-block badge bg-blue-600" title="Összekészítve">Összekészítve</span>';
            case self::STATUS_SHIPPING:
                return '<span class="d-block badge bg-orange-600" title="Szállítás alatt">Szállítás alatt</span>';
            case self::STATUS_COMPLETED:
                return '<span class="d-block badge bg-success-600" title="Teljesítve">Teljesítve</span>';
            case self::STATUS_RETURNED:
                return '<span class="d-block badge bg-danger-600" title="Visszaküldve">Visszaküldve</span>';
            case self::STATUS_LANDED:
                return '<span class="d-block badge bg-success-600" title="Átvehető">Átvehető</span>';
            case self::STATUS_DELETED:
                return '<span class="d-block badge bg-grey-600" title="Törölt">Törölt</span>';
            default:
                return '<span class="d-block badge bg-grey-600" title="Ismeretlen">Ismeretlen</span>';
        }
    }

    public function isAllowedStatus($status, $boolean = false)
    {
        if (in_array($status, $this->statusMatrix[$this->status]['allowed'])) {
            $key = array_search($status, $this->statusMatrix[$this->status]['allowed']);
            $allowed = null;
            if ($key !== 'all') {
                $keys = explode(':', $key);
                if ($keys[0] != false && $this->{$keys[0]} != $keys[1]) {
                    $allowed = $boolean ? false : 'disabled';
                }
                //var_dump($keys);
            }
            if (isset($this->statusMatrix[$this->status]['check'])) {
                foreach ($this->statusMatrix[$this->status]['check'] as $function) {
                    if (! $this->{$function}()) {
                        return $boolean ? false : 'disabled';
                    }
                }
            }
            $allowed = $boolean ? true : '';

            if (isset($this->statusMatrix[$this->status]['disallowed'])) {
                foreach ($this->statusMatrix[$this->status]['disallowed'] as $key => $disallowed) {
                    if ($key !== 'all') {
                        $keys = explode(':', $key);

                        if ($keys[0] != false && $this->{$keys[0]} == $keys[1]) {
                            if (in_array($status, $disallowed)) {
                                $allowed = $boolean ? false : 'disabled';
                            }
                        }
                        //var_dump($keys);
                    }
                }
            }
        } else {
            $allowed = $boolean ? false : 'disabled';
        }

        return $allowed;
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PAYMENT_WAITING:
                return [
                    'text' => 'Fizetésre vár',
                    'color' => '#666666',
                ];
            case self::STATUS_NEW:
                return [
                    'text' => 'Megrendelve',
                    'color' => '#666666',
                ];
            case self::STATUS_PROCESSING:
                return [
                    'text' => 'Feldolgozás alatt',
                    'color' => '#666666',
                ];
            case self::STATUS_WAITING_FOR_SHIPPING:
                return [
                    'text' => 'Összekészítve',
                    'color' => '#666666',
                ];
            case self::STATUS_SHIPPING:
                return [
                    'text' => 'Szállítás alatt',
                    'color' => '#039be5',
                ];
            case self::STATUS_COMPLETED:
                return [
                    'text' => 'Teljesítve',
                    'color' => '#43a047',
                ];
            case self::STATUS_RETURNED:
                return [
                    'text' => 'Visszaküldve',
                    'color' => '#666666',
                ];
            case self::STATUS_LANDED:
                return [
                    'text' => 'Átvehető',
                    'color' => '#43a047',
                ];
            case self::STATUS_DELETED:
                return [
                    'text' => 'Törölt',
                    'color' => '#43a047',
                ];
            default:
                return [
                    'text' => 'Piszkozat',
                    'color' => '#666666',
                ];
        }
    }

    public function onlyEbook()
    {
        $onlyEbook = true;
        foreach ($this->orderItems as $item) {
            if ($item->product != false && $item->product->type == Product::BOOK) {
                $onlyEbook = false;
            }
        }

        return $onlyEbook;
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function billingAddress()
    {
        return $this->hasOne(Address::class, 'role_id')->where([
            ['type', 'billing'],
            ['role', 'order'],
        ]);
    }

    public function shippingAddress()
    {
        //TODO: shipping address függ a shipping_method-tól
        return $this->hasOne(Address::class, 'role_id')->where([
            ['type', 'shipping'],
            ['role', 'order'],
        ]);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function setStatus($status, $silentMode = false, $invoiceType = 'szamla')
    {
        if ($invoiceType !== false) {
            $this->invoiceStatus = $invoiceType;
        }

        if ($this->isAllowedStatus($status, true)) {
            if (isset($this->statusMatrix[$status]['functions'])) {
                foreach ($this->statusMatrix[$status]['functions'] as $function) {
                    if (! $this->{$function}()) {
                        return false;
                    }
                    $this->status = $status;
                    $this->save();
                }
            }
            if (isset($this->statusMatrix[$status]['email']) && ! empty($this->statusMatrix[$status]['email'])) {
                if (! $silentMode) {
                    if (isset($this->customer)) {
                        OrderMailerService::create()->sendStatusEmail($this, $this->statusMatrix[$status]['email']);

                        //subscribing to products author(s) if needed
                        if ($status == Order::STATUS_PROCESSING) {
                            foreach ($this->orderItems as $item) {
                                $p = Product::find($item->product_id);
                                foreach ($p->author ?? [] as $author) {
                                    Customer::subscribe($this->customer, $author->id);
                                }
                            }
                        }
                    }
                }
            }

            $this->status = $status;
            $this->save();

            return true;
        } else {
            return false;
        }
    }

    public function checkPaymentStatus()
    {
        //var_dump($this->paymentMethod->method_id) ;
        if ($this->paymentMethod->method_id == 'transfer' && $this->payment_status !== self::STATUS_PAYMENT_PAID) {
            return false;
        } else {
            return true;
        }
    }

    public function checkShippingMethod()
    {
        if ($this->shippingMethod->method_id == 'home') {
            return false;
        }

        return true;
    }

    public function updateStock($storno = false, $correctiveItems = false)
    {
        // szállítólevél és készletlevonás
        if ($storno && ! $correctiveItems && $model = ProductMovement::where('destination_id', $this->id)->whereIn('destination_type', [1, 2])->first()) {
            $model->destination_id = null;
            $model->comment_general = $this->order_number.' sz. rendelés sztornózva';
            $model->is_canceled = 1;

            $productMovement = ProductMovement::create([
                'reference_nr' => ProductMovement::generateReferenceNr(),
                'causer_type' => 'App\User',
                'causer_id' => Auth()->id(),
                'source_type' => 'storno',
                'source_id' => $model->source_id,
                'destination_type' => $model->destination_type,
                'comment_general' => $this->order_number.' sz. rendelés sztornó bizonylata',
            ]);
        } elseif (ProductMovement::where('destination_id', $this->id)->whereIn('destination_type', [1, 2])->exists() && $correctiveItems == false) {
            return true;
        } else {
            $model = new ProductMovement();
            $model->reference_nr = ProductMovement::generateReferenceNr();
            $model->causer_type = 'Customer';
            $model->causer_id = $this->customer?->id;
            $model->source_type = $correctiveItems ? 'corrective' : 'shop';
            $model->source_id = $this->shipping_data['shop']['selected_shop']['id'] ?? false;
            $model->destination_type = $this->store === 3 ? 2 : 1;
            $model->destination_id = $this->id;
        }
        if ($model->save()) {
            if ($correctiveItems ?? false) {
                $this->data = $this->collectCorrectiveData($model, $correctiveItems);
            } else {
                $this->data = $this->collectData($model, $storno);
            }

            $dataInventory = $this->collectDataInventory($this->data, $storno, $correctiveItems);

            ProductMovement::addItems($model, $this->data);

            Inventory::updateInventory($dataInventory, $storno);

            return true;
        }

        return false;
    }

    private function collectData($model, $storno = false): array
    {
        return $this->orderItems()->select('product_id', 'quantity', 'price')->get()->transform(function ($item) use ($model, $storno) {
            $item->product_movements_id = $model->id;
            $item->stock_in = $storno ? $item->quantity : 0;
            $item->status = 1;
            $item->stock_out = $storno ? 0 : $item->quantity;
            $item->sale_price = $item->price;
            //            $item->created_at           = now();
            $item->updated_at = now();
            unset($item->quantity);
            unset($item->price);

            return $item;
        })->toArray();
    }

    private function collectCorrectiveData($model, $correctiveItems): array
    {
        foreach (($correctiveItems ?? []) as $item) {
            $item['product_movements_id'] = $model->id;
            $item['product_id'] = $item['product_id'];
            $item['status'] = 1;
            $item['sale_price'] = $item['price'];
            $item['created_at'] = now();
            $item['updated_at'] = now();
            if ($item['quantity'] > 0) {
                $item['stock_out'] = $item['quantity'];
                $item['stock_in'] = 0;
            } else {
                $item['stock_in'] = abs($item['quantity']);
                $item['stock_out'] = 0;
                $item['storno'] = true;
            }
            unset($item['quantity']);
            unset($item['price']);
            $returnArray[] = $item;
        }

        return $returnArray;
    }

    private function collectDataInventory($data, $storno = false)
    {
        //return array_map(function ($item) use ($data, $storno) {
        foreach ($data as $i => $item) {
            if ($item['storno'] ?? false) {
                $storno = true;
            }
            if ($storno) {
                $item['destination_id'] = $this->getWarehouseID();
            } else {
                $item['source_id'] = $this->getWarehouseID();
            }
            $data[$i] = $item;
            unset($this->data[$i]['storno']);
        }

        return $data;
    }

    private function getWarehouseID()
    {
        $gps = Warehouse::whereType(1)->firstOrFail();

        if ($this->shipping_method_id === ShippingMethod::where('method_id', 'shop')->first()->id) {
            if (isset($this->shipping_data['shop']['selected_shop']['id'])) {
                $wh = Warehouse::where('shop_id', $this->shipping_data['shop']['selected_shop']['id'])->first();
                // $inventory = Inventory::where([
                //     'product_id'   => $item['product_id'],
                //     'warehouse_id' => $wh->id,
                // ])->first();
                // if ($inventory) {
                return $wh->id ?? $gps->id;
                // }
            }
        }

        return $gps->id; // Ez a GPS, azaz a fő raktár
    }

    public function createInvoice()
    {
        activity()
            ->useLog('szamla')
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->log('Számla készítés indul');

        if ($this->shipping_method_id == 3) {
            $this->payment_status = self::STATUS_PAYMENT_PAID;
            $this->save();
        }
        $billingAddress = $this->billingAddress;

        if (empty($billingAddress) || $this->invoiceStatus == 'nyugta') {
            activity()
                ->useLog('szamla')
                ->performedOn($this)
                ->causedBy(auth()->user())
                ->withProperties(['order' => $this])
                ->log('Nincs billing address, ezért nem készül számla');
            $this->createReceipt();

            return true;
        }

        if ($this->invoiceStatus == 'nyugta') {
            $this->createReceipt();

            return true;
        }

        //return ['order' => $this];
        $szamlaKey = env('SZAMLAZZ_'.$this->store, 'qwd4jiyr796mzq9jcbybc67fpsezrzw4imwe32ta4v');

        $agent = SzamlaAgentAPI::create($szamlaKey, true, 0);

        $invoice = new Invoice(Invoice::INVOICE_TYPE_P_INVOICE);
        $header = $invoice->getHeader();
        $header->setOrderNumber($this->order_number);
        $header->setPaymentMethod(self::$invoice[$this->paymentMethod->method_id] ?? Invoice::PAYMENT_METHOD_BANKCARD);
        $header->setCurrency(Currency::CURRENCY_HUF);
        $header->setLanguage(Language::LANGUAGE_HU);
        $header->setPaid(true);
        $header->setFulfillment(! empty($this->payment_date) ? $this->payment_date : date('Y-m-d'));
        // Számla fizetési határideje
        $header->setPaymentDue(date('Y-m-d'));

        if (env('APP_ENV') == 'live') {
            $header->setPrefix($this->getInvoicePrefix());
        } else {
            $header->setComment('Éles környezetben '.$this->getInvoicePrefix().' előtaggal generálódna a számla');
        }

        if (! empty(trim($billingAddress->business_name)) && $billingAddress->business_name != 1) {
            $buyerName = $billingAddress->business_name;
            $buyerNameMethod = 'business';
        } else {
            $buyerName = $billingAddress->last_name.' '.$billingAddress->first_name;
            $buyerNameMethod = 'personal';
        }

        activity()
            ->useLog('szamla')
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->withProperties(['buyerName' => $buyerName])
            ->log('Vevő neve (' . $buyerNameMethod . ')');

        // vevő létrehozása
        $buyer = new Buyer(
            $buyerName,
            $billingAddress->zip_code,
            $billingAddress->city,
            $billingAddress->address
        );

        activity()
            ->useLog('szamla')
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->withProperties(['buyer' => $buyer])
            ->log('Vevő adatok');

        $buyer->setCountry($this->country?->name);
        if (! empty($billingAddress->vat_number)) {
            $buyer->setTaxNumber($billingAddress->vat_number);
        }
        if (isset($this->customer->email)) {
            $buyer->setEmail($this->customer->email);
        }

        $invoice->setBuyer($buyer);

        foreach ($this->orderItems as $item) {
            $tax_rate = $item->product->tax_rate ?? 5;
            $netto = $item->price / (1 + ($tax_rate / 100));

            $invoiceItem = new InvoiceItem($item->product->title, $netto, $item->quantity, 'db', $tax_rate.'');
            // Tétel nettó értéke
            $invoiceItem->setNetPrice(round($netto * $item->quantity));
            // Tétel ÁFA értéke
            // áfa érték = tétel nettó értéke x áfakulcs mértéke / 100.
            $invoiceItem->setVatAmount(round(($netto * $tax_rate / 100) * $item->quantity));
            // Tétel bruttó értéke
            $invoiceItem->setGrossAmount($item->price * $item->quantity);
            // Tétel hozzáadása a számlához
            $invoice->addItem($invoiceItem);
        }
        /* szállítási költség hozzáadása */
        if ($this->shipping_fee > 0) {
            $sNetto = round($this->shipping_fee / (1 + (5 / 100)));
            //$sNetto       = $this->shipping_fee - (($this->shipping_fee/100)*5);
            $shippingItem = new InvoiceItem(
                'Kényelmi költség ('.$this->shippingMethod->name.')',
                $sNetto,
                1,
                'db',
                '5'
            );
            // Tétel nettó értéke
            $shippingItem->setNetPrice($sNetto);
            // Tétel ÁFA értéke
            $shippingItem->setVatAmount($this->shipping_fee - $sNetto);
            // Tétel bruttó értéke
            $shippingItem->setGrossAmount($this->shipping_fee);
            // Tétel hozzáadása a számlához
            $invoice->addItem($shippingItem);
        }

        /*  fizetési költség hozzáadása */

        if ($this->payment_fee > 0) {
            $pNetto = round($this->payment_fee / (1 + (5 / 100)));
            //$sNetto       = $this->shipping_fee - (($this->shipping_fee/100)*5);
            $paymentItem = new InvoiceItem($this->shippingMethod->name, $pNetto, 1, 'db', '5');
            // Tétel nettó értéke
            $paymentItem->setNetPrice($pNetto);
            // Tétel ÁFA értéke
            $paymentItem->setVatAmount($this->payment_fee - $pNetto);
            // Tétel bruttó értéke
            $paymentItem->setGrossAmount($this->payment_fee);
            // Tétel hozzáadása a számlához
            $invoice->addItem($paymentItem);
        }

        try {
            $result = $agent->generateInvoice($invoice);
            if ($result->isSuccess()) {
                $this->invoice_url = $result->getDocumentNumber();
                $attachments = $this->attachments;
                $attachments[] = $result->getDocumentNumber();
                $this->attachments = $attachments;
                $this->save();
                activity()
                    ->useLog('szamla')
                    ->performedOn($this)
                    ->causedBy(auth()->user())
                    ->withProperties(['order' => $this])
                    ->log('Számla elkészült');

                return true;
            } else {
                // email küldés - számlagenerálás sikertelen option('contact_email', 'janos.ecsedy@skvad.com')
                activity()
                    ->useLog('szamla')
                    ->performedOn($this)
                    ->causedBy(auth()->user())
                    ->withProperties(['order' => $this])
                    ->log('Számla nem készült el, mert a válasz sikertelen');
                OrderMailerService::create()->sendInvoiceGenerationError();
            }
        } catch (Exception $e) {
            // emailküldés - számlagenerálás problémába ütközött option('contact_email', 'janos.ecsedy@skvad.com')
            OrderMailerService::create()->sendInvoiceGenerationFail($e->getMessage());
            activity()
                ->useLog('szamla')
                ->performedOn($this)
                ->causedBy(auth()->user())
                ->withProperties(['error' => $e->getMessage()])
                ->log('Számla nem készült el');

            return $e->getMessage();
        }
    }

    //HELYESBÍTŐ
    public function createCorrectiveInvoice($correctiveItems)
    {
        if ($this->shipping_method_id == 3) {
            $this->payment_status = self::STATUS_PAYMENT_PAID;
            $this->save();
        }
        $billingAddress = $this->billingAddress;
        if (empty($billingAddress)) {
            $billingAddress = Address::where([['role_id', $this->customer_id], ['role', 'customer']])->first();
        }

        $szamlaKey = env('SZAMLAZZ_'.$this->store, 'qwd4jiyr796mzq9jcbybc67fpsezrzw4imwe32ta4v');

        $agent = SzamlaAgentAPI::create($szamlaKey, true, 0);

        $invoice = new CorrectiveInvoice(Invoice::INVOICE_TYPE_P_INVOICE);
        $header = $invoice->getHeader();
        $header->setOrderNumber($this->order_number);
        $header->setCurrency(Currency::CURRENCY_HUF);
        $header->setLanguage(Language::LANGUAGE_HU);
        $header->setCorrectivedNumber($this->invoice_url);

        if (env('APP_ENV') == 'live') {
            $header->setPrefix($this->getInvoicePrefix());
        } else {
            $header->setComment('Éles környezetben '.$this->getInvoicePrefix().' előtaggal generálódna a számla');
        }
        $buyerName = ! empty($billingAddress->business_name) ? $billingAddress->business_name : $billingAddress->last_name.' '.$billingAddress->first_name;

        // vevő létrehozása
        $buyer = new Buyer(
            $buyerName,
            $billingAddress->zip_code,
            $billingAddress->city,
            $billingAddress->address
        );
        $buyer->setCountry($this->country?->name);
        if (! empty($billingAddress->vat_number)) {
            $buyer->setTaxNumber($billingAddress->vat_number);
        }
        if (isset($this->customer->email)) {
            $buyer->setEmail($this->customer->email);
        }

        $invoice->setBuyer($buyer);

        foreach (($correctiveItems ?? []) as $item) {
            $itemProduct = Product::find($item['product_id']);

            $tax_rate = $itemProduct->tax_rate ?? 5;
            $netto = $item['price'] / (1 + ($tax_rate / 100));

            $invoiceItem = new InvoiceItem($itemProduct->title, $netto, $item['quantity'], 'db', $tax_rate.'');
            // Tétel nettó értéke
            $invoiceItem->setNetPrice(round($netto * $item['quantity']));
            // Tétel ÁFA értéke
            // áfa érték = tétel nettó értéke x áfakulcs mértéke / 100.
            $invoiceItem->setVatAmount(round(($netto * $tax_rate / 100) * $item['quantity']));
            // Tétel bruttó értéke
            $invoiceItem->setGrossAmount($item['price'] * $item['quantity']);
            // Tétel hozzáadása a számlához
            $invoice->addItem($invoiceItem);
        }

        try {
            $result = $agent->generateCorrectiveInvoice($invoice); //generateCorrectiveInvoice
            if ($result->isSuccess()) {
                //$this->invoice_url = $result->getDocumentNumber();
                $attachments = $this->attachments;
                $attachments[] = $result->getDocumentNumber();
                $this->attachments = $attachments;
                $this->save();

                return true;
            } else {
                // email küldés - számlagenerálás sikertelen option('contact_email', 'janos.ecsedy@skvad.com')
                OrderMailerService::create()->sendInvoiceGenerationError();
            }
        } catch (Exception $e) {
            // emailküldés - számlagenerálás problémába ütközött option('contact_email', 'janos.ecsedy@skvad.com')
            OrderMailerService::create()->sendInvoiceGenerationFail($e->getMessage());

            return $e->getMessage();
        }
    }

    public function createReceipt()
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Nyugta');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(1, 4, 1);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetFont('dejavusans', '', 8);
        $pdf->AddPage();

        $pdf->writeHTML(view('orders::receipt', ['items' => $this->orderItems, 'order' => $this]), true, false, true, false, '');

        $pdf->lastPage();

        $pdf->Output(app_path().'/Components/Szamlazz/pdf/NYUGTA-'.$this->order_number.'.pdf', 'F');
        //$this->invoice_url = $this->order_number;
        $attachments = $this->attachments;
        $attachments[] = 'NYUGTA-'.$this->order_number;
        $this->attachments = $attachments;
        $this->save();

        return true;
    }

    private function getInvoicePrefix()
    {
        if ($this->store == 3) {
            $shopId = ($this->shipping_data['shop']['selected_shop']['id'] ?? false);
            $wh = Warehouse::where('shop_id', $shopId)->first();
            if (! empty($wh)) {
                return $wh->invoice_prefix;
            } else {
                return Order::$barionPrefix[$this->store];
            }
        } else {
            return Order::$barionPrefix[$this->store];
        }
    }

    public function getShippingDetailsAttribute()
    {
        if (is_array($this->shipping_data)) {
            return $this->handleShippingDataArray();
        } else {
            return $this->handleShippingDataObject();
        }
    }

    private function handleShippingDataObject()
    {
        if (empty($this->shipping_data)) {
            return [];
        }

        return json_decode($this->shipping_data);
    }

    private function handleShippingDataArray()
    {
        if (empty($this->shipping_data)) {
            return [];
        }

        return json_decode(collect($this->shipping_data ?? [])->collapse()->collapse()->toJson());
    }

   // SETTING SHIPPING OBJECT ATTRIBUTE
   public function getShippingObjectAttribute(): ?ShippingData
   {
       $shippingDetails = $this->getShippingDetailsAttribute();

       // BASED ON THE DETAILS
       // IT WAS HANDLED IF IT IS A BOX, OR SHOP

       // IF IT HAS A PROVIDER ID, IT IS HANDLED AS A BOX
       if (! is_array($shippingDetails) && property_exists($shippingDetails, 'provider_id')) {
           $shippingData = ShippingData::parseBox($shippingDetails);

       // EVERY OTHER CASE IT WAS HANDLED AS A SHOP
       } else {

           if($this->shipping_data['type'] == ShippingMethod::SHOP)
           {

               $shop = Shop::find($shippingDetails->id);

               // NOW I EXTENDED, IT CAN BE A SHOP BASED ON THE ID
               if ($shop) {
                   // OK, LET IT BE A SHOP
                   $shippingData = ShippingData::parseShop($shop);
               }
           } else {
               // ON THE OTHER HAND, HANDLE IT AS AN ADDRESS
               $shippingData = ShippingData::parseAddress($shippingDetails, $this->shipping_data['type']);

           }
       }

       return $shippingData;
   }

    private function decorateOpening($opening_hours): string
    {
        return $opening_hours;
    }

    protected static function booted()
    {
        static::updating(function ($model) {
            $cacheKey = 'order-with-informations'.$model->orderNumber;
            Cache::forget($cacheKey);
        });
    }
}
