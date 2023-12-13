<?php

namespace Alomgyar\Warehouses;

use Alomgyar\Product_movements\ProductMovement;
use Alomgyar\Products\Product;
use Alomgyar\Suppliers\Supplier;
use App\Helpers\HumanReadable;
use App\Imports\ProductPriceImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class StockInComponent extends Component
{
    public $product_id;

    public int $status = 1;

    public string $tab = 'tab-supplier';

    public string $destinationTab = 'tab-warehouse';

    public $source_id;

    public $source_type;

    public $stock_in = 0;

    public int $stock_out = 0;

    public int $destination_id = 0;

    public $destination_type;

    public $importfile;

    public $importfileSize;

    public $count;

    public $bulkProducts;

    public $badProducts = [];

    public $comment_void;

    public $comment_general;

    public $comment_bottom;

    public $reference_nr;

    public $causer_type = 'App\User';

    public $causer_id;

    public $purchase_price;

    public $search;
    // public $warehouses;
    // public $selectedWarehouse;

    protected $listeners = [
        'setProductId' => 'setProductId',
        'setImportFile' => 'setImportFile',
        'setSourceId' => 'setSourceId',
        'setDestinationId' => 'setDestinationId',
        'setSupplierId' => 'setSupplierId',
    ];

    protected $rules = [
        'product_id' => ['required'],
        'source_id' => ['required', 'numeric', 'min:1'],
        'stock_in' => ['required', 'numeric'],
        'status' => ['required'],
        'destination_id' => ['required', 'numeric'],
        'comment_void' => ['nullable', 'string'],
        'comment_general' => ['nullable', 'string'],
        'comment_bottom' => ['nullable', 'string'],
    ];

    protected $validationAttributes = [
        'source_id' => 'forrás',
        'destination_id' => 'cél',
        'stock_in' => 'mennyiség',
    ];

    public function mount($productId = 0)
    {
        $this->product_id = $productId;
    }

    public function render()
    {
        $product = Product::select('id', 'isbn', 'title')->find($this->product_id);

        return view('warehouses::components.stock-in')
            ->layout('admin::layouts.master')
            ->with(compact('product'));
    }

    public function getWarehousesProperty()
    {
        return Warehouse::active()
            ->when($this->search, function ($query, $search) {
                return $query->where('title', 'like', '%'.$search.'%');
            })
            ->get();
    }

    public function getSuppliersProperty()
    {
        return Supplier::active()->get();
    }

    public function setProductId($id)
    {
        $this->product_id = $id;
        $this->reset(['tab', 'destinationTab', 'stock_in', 'source_id', 'destination_id']);
    }

    public function setSourceId($id)
    {
        $this->source_id = $id;
    }

    public function setDestinationId($id)
    {
        $this->destination_id = $id;
    }

    public function setSupplierId($id)
    {
        $this->source_id = $id;
    }

    public function setTab($value)
    {
        $this->tab = $value;
        $this->reset(['stock_in', 'source_id']);
    }

    public function setDestinationTab($value)
    {
        $this->destinationTab = $value;
        $this->reset(['destination_id', 'comment_void']);
    }

    public function save()
    {
        $validator = Validator::make(
            [
                'product_id' => $this->product_id,
                'source_id' => $this->source_id,
                'stock_in' => $this->stock_in,
                'stock_out' => $this->stock_out,
                'status' => $this->status,
                'destination_id' => $this->destination_id,
            ],
            $this->rules,
            [
                'source_id.numeric' => 'Kérlek válassz egy forrást',
                'source_id.min' => 'Kérlek válassz egy forrást',
                'destination_id.numeric' => 'Kérlek válassz egy célt',
            ]
        );

        if ($this->tab === 'tab-warehouse' && Warehouse::find($this->source_id)->productInventory($this->product_id) < $this->stock_in) {
            return $this->addError('stock_in', 'Nincs elég termék a kiválasztott raktárban');
        }

        if ($this->destinationTab === 'tab-warehouse' && $this->destination_id == 0) {
            return $this->addError('destination_id', 'Cél megadása kötelező');
        }

        if ($this->stock_in <= 0 && ! $this->bulkProducts) {
            return $this->addError('stock_in', 'Pozitív egész számot adj meg');
        }

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast-message', ['message' => 'Hiba történt!', 'type' => 'error']);
            $this->validate();
        } else {
            $this->reference_nr = ProductMovement::generateReferenceNr();
            $this->causer_id = Auth()->id();
            $this->source_type = $this->tab === 'tab-supplier' ? 'supplier' : 'warehouse';
            $this->destination_type = $this->destinationType();

            if ($this->destinationTab === 'tab-other') {
                $this->stock_out = $this->stock_in;
                $this->stock_in = 0;
            }

            $model = ProductMovement::createByReferenceNumber($this);
            if ($model) {
                $data = $this->collectData($model);
                $dataInventory = $this->collectDataInventory($data);
                ProductMovement::addItems($model, $data);
                Inventory::updateInventory($dataInventory);
            }

            $this->reset();
            $this->dispatchBrowserEvent('toast-message', ['message' => 'Sikeres bizonylat rögzítés!']);
        }
    }

    public function updatedStockIn()
    {
        return $this->checkQuantity();
    }

    public function updatedSourceId()
    {
        return $this->checkQuantity();
    }

    private function checkQuantity()
    {
        if ($this->tab === 'tab-warehouse') {
            if (! isset($this->source_id)) {
                return $this->addError('soure_id', 'Kérlek válassz egy raktárat!');
            }

            if (Warehouse::find($this->source_id)->productInventory($this->product_id) < $this->stock_in) {
                $this->validateOnly('stock_in');

                return $this->addError('stock_in', 'Nincs elég termék a kiválasztott raktárban');
            }
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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

    public function importProduct()
    {
        $products = Excel::toArray(
            new ProductPriceImport,
            Storage::disk('local')->path('public/imports/'.$this->importfile)
        );

        $counts['good'] = $counts['bad'] = $i = 0;

        $isbn_set = [];
        foreach ($products as $tabs) {
            foreach ($tabs as $product) {
                $isbn_set[] = $product[0];
            }
        }

        $realProducts = Product::select('isbn', 'id')->whereIn('isbn', $isbn_set)->book()->get();

        foreach ($realProducts as $rp) {
            $isset[$rp->isbn] = $rp->id;
        }

        foreach ($products as $tabs) {
            foreach ($tabs as $product) {
                $isbn = $product[0];

                if ($isbn) {
                    $resp = [];
                    $importable = 0;
                    if (($product[2] ?? 0) > 0) {
                        $importable = 1;
                    } else {
                        $resp[] = 'Hiányzik a mennyiség';
                        $importable = 0;
                    }
                    if (! is_numeric(($product[2] ?? 0))) {
                        $resp[] = 'A mennyiséghez csak szám adható meg';
                        $importable = 0;
                    }
                    if (! isset($isset[$isbn])) {
                        $resp[] = 'Nincs ilyen termék';
                        $importable = 0;
                    }

                    if ($importable) {
                        $counts['good']++;
                        $importableProducts[] = [
                            'product_id' => $isset[$isbn] ?? null,
                            'isbn' => $product[0],
                            'title' => $product[1],
                            'stock_in' => $product[2],
                            'stock_out' => 0,
                            'status' => $this->status,
                            'resp' => $resp,
                            'importable' => $importable,
                        ];
                    } else {
                        $counts['bad']++;
                        $badProducts[] = [
                            'product_id' => $realproduct->id ?? null,
                            'isbn' => $product[0],
                            'title' => $product[1],
                            'stock_in' => $product[2],
                            'stock_out' => 0,
                            'status' => $this->status,
                            'resp' => $resp,
                            'importable' => $importable,
                        ];
                    }
                }
                $i++;
            }
        }

        $this->dispatchBrowserEvent('toast-message', ['message' => 'Az ellenőrzés lefutott!']);
        $this->count = $counts;
        $this->bulkProducts = $importableProducts ?? [];
        $this->badProducts = $badProducts ?? [];
    }

    private function destinationType()
    {
        if ($this->destinationTab === 'tab-warehouse' && $this->tab === 'tab-warehouse') {
            if ($this->warehouses->where('id', $this->destination_id)->first()->is_merchant) {
                return ProductMovement::DESTINATION_TYPE_MERCHANT;
            } else {
                return ProductMovement::DESTINATION_TYPE_BETWEEN_WAREHOUSES;
            }
        } else {
            if ($this->destinationTab === 'tab-warehouse' && $this->tab === 'tab-supplier') {
                return ProductMovement::DESTINATION_TYPE_ACQUISITION;
            } else {
                return ProductMovement::DESTINATION_TYPE_VOID;
            }
        }
    }

    private function collectData($model)
    {
        if ($this->bulkProducts) {
            return array_filter(array_map(function ($item) use ($model) {
                if (isset($item['importable']) && $item['importable']) {
                    unset($item['resp']);
                    unset($item['isbn']);
                    unset($item['title']);
                    unset($item['importable']);
                    unset($item['importing']);
                    unset($item['id']);
                    $item['product_movements_id'] = $model->id;
                    //                    $item['created_at']           = time();
                    $item['updated_at'] = now();

                    return $item;
                }
            }, $this->bulkProducts));
        } else {
            return [
                [
                    'product_movements_id' => $model->id,
                    'product_id' => $this->product_id,
                    'stock_in' => (int) $this->stock_in,
                    'status' => $this->status,
                    'stock_out' => $this->stock_out,
                    //                    'created_at'           => now(),
                    'updated_at' => now(),
                ],
            ];
        }
    }

    private function collectDataInventory($data)
    {
        return array_map(function ($item) {
            if ($this->tab === 'tab-warehouse') {
                $item['source_id'] = $this->source_id;
            }
            if ($this->destinationTab === 'tab-warehouse') {
                $item['destination_id'] = $this->destination_id;
            }

            return $item;
        }, $data);
    }
}
