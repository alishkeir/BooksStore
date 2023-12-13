<?php

namespace Alomgyar\Product_movements;

use Alomgyar\Shops\Shop;
use Alomgyar\Warehouses\Inventory;
use Alomgyar\Warehouses\Warehouse;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $byType;

    public $byWarehouse;

    public $byShop;

    public $minQuantity;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = false;

    public $productId;

    public $from;

    public $to;

    public bool $loading = false;

    public function mount($productId = 0)
    {
        $this->productId = $productId;
    }

    public function render()
    {
        $term = trim($this->s);

        $model = ProductMovement::search($term)
                                ->when(! (is_null($this->byType) || $this->byType == ''), function ($q) {
                                    $q->where('destination_type', $this->byType);
                                })
                                ->when(! (is_null($this->byWarehouse) || $this->byWarehouse == ''), function ($q) {
                                    $q->where(function ($q) {
                                        $q->where(['source_id' => $this->byWarehouse, 'source_type' => 'warehouse'])
                                          ->orWhere(function ($q) {
                                              $q->where(['destination_id' => $this->byWarehouse])->whereIn('destination_type', [0, 3]);
                                          });
                                    });
                                })
                                ->when(! (is_null($this->byShop) || $this->byShop == ''), function ($q) {
                                    $q->where(function ($q) {
                                        $q->where(['source_id' => $this->byShop, 'source_type' => 'shop'])
                                          ->orWhere(function ($q) {
                                              $q->where(['destination_id' => $this->byShop])->whereIn('destination_type', [1, 2]);
                                          });
                                    });
                                })
                                ->when(isset($this->minQuantity), function ($q) {
                                    $q->where(function ($q) {
                                        $q->where('stock_in', '>=', $this->minQuantity)->orWhere('stock_out', '>=', $this->minQuantity);
                                    });
                                })
                                ->when($this->productId, function ($q) {
                                    $q->whereHas('productItems', function ($query) {
                                        $query->where('product_id', $this->productId);
                                    });
                                })
                                ->when($this->from, function ($q) {
                                    $q->where('product_movements.created_at', '>', $this->from);
                                })
                                ->when($this->to, function ($q) {
                                    $q->where('product_movements.created_at', '<', $this->to);
                                })
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate($this->perPage);

        return view('product_movements::components.listcomponent', [
            'model' => $model,
        ]);
    }

    public function getWarehousesProperty()
    {
        return Warehouse::active()->get();
    }

    public function getShopsProperty()
    {
        return Shop::active()->get();
    }

    public function sortBy($column)
    {
        if ($this->sortField === $column) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $column;
    }

    public function updatingS()
    {
        $this->resetPage();
    }

    public function storno($id)
    {
        $productMovement = ProductMovement::find($id);

        if (in_array($productMovement->destination_type, [ProductMovement::DESTINATION_TYPE_SHOP_ORDER, ProductMovement::DESTINATION_TYPE_WEBSHOP_ORDER])) {
            $this->dispatchBrowserEvent('toast-message', ['message' => 'Rendelés bizonylatot a rendelés oldalon tudsz sztornózni', 'type' => 'error']);

            return;
        }

        DB::beginTransaction();

        $model = ProductMovement::create([
            'reference_nr' => ProductMovement::generateReferenceNr(),
            'causer_type' => 'App\User',
            'causer_id' => Auth()->id(),
            'source_type' => 'storno',
            'source_id' => $productMovement->destination_id,
            'destination_type' => $productMovement->destination_type,
            'destination_id' => $productMovement->source_id,
            'comment_general' => $productMovement->reference_nr.' sz. bizonylat sztornó bizonylata ('.$productMovement->id.')',
        ]);

        if ($model) {
            $data = $this->collectData($productMovement, $model->id, true);
            $dataInventory = $this->collectDataInventory($data, $model);
            ProductMovement::addItems($model, $data);
            if (! Inventory::updateInventory($dataInventory, true)) {
                DB::rollBack();
            }
            $productMovement->update(['is_canceled' => 1]);
            $this->reset();
            $this->productId = 0;

            $this->dispatchBrowserEvent('toast-message', ['message' => 'Bizonylat sztornó elkészült, a termék mennyiségek visszaállítva!']);
        } else {
            DB::rollBack();
        }

        DB::commit();
    }

    private function collectData($model, $newID, $storno = false)
    {
        return $model->productItems()
                     ->select('product_id', 'stock_in', 'stock_out')
                     ->get()
                     ->transform(function ($item) use ($model, $newID, $storno) {
                         $stockIn = $item->stock_out;
                         $stockOut = $item->stock_in;
                         if ($storno && $item->stock_out > 0) {
                             $stockIn = $item->stock_out;
                             $stockOut = $item->stock_out;
                         }

                         if ($storno && $item->stock_in > 0) {
                             $stockIn = $item->stock_in;
                             $stockOut = $item->stock_in;
                         }

                         if ($storno && $model->source_type === 'supplier') {
                             $stockIn = 0;
                         }

                         $item->product_movements_id = $newID;
                         $item->stock_in = $stockIn;
                         $item->stock_out = $stockOut;
                         $item->created_at = now();
                         $item->updated_at = now();

                         return $item;
                     })->toArray();
    }

    private function collectDataInventory($data, $model, $storno = false)
    {
        return array_map(function ($item) use ($model) {
            if ($item['stock_in'] !== 0 && $model->destination_id !== 0) {
                $item['destination_id'] = $model->destination_id;
            }
            if ($item['stock_out'] !== 0 && $model->source_id !== 0) {
                $item['source_id'] = $model->source_id;
            }

            return $item;
        }, $data);
    }
}
