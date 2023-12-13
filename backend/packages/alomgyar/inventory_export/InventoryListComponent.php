<?php

namespace Alomgyar\InventoryExport;

use Alomgyar\Warehouses\Inventory;
use Alomgyar\Warehouses\Warehouse;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryListComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = false;

    public $warehouseID;

    protected $listeners = [
        'setWarehouseID' => 'setWarehouseID',
    ];

    public function mount($warehouseID = null)
    {
        $this->warehouseID = $warehouseID;
    }

    public function render()
    {
        $term = trim($this->s);
        $query = InventoryZero::query()
            ->with('product:id,isbn,title')
            ->active()
            ->when($this->warehouseID, function ($query) {
                $query->where('warehouse_id', $this->warehouseID);
            })
            ->when(! empty($term), function ($query) use ($term) {
                $query->whereHas('product', function ($q) use ($term) {
                    $q->where('title', 'like', '%'.$term.'%');
                });
            });

        $warehouseProducts = $query->paginate($this->perPage);

        $shouldContain = 'álomgyár könyvesbolt';
        $warehouses = Warehouse::query()
            ->select('id', 'shop_id', 'title')
            ->with('shop:id,title')
            ->where('warehouse.title', 'LIKE', "%$shouldContain%")
            ->orWhere('id', Warehouse::WEBSHOP_ID)
            ->orWhere('id', Warehouse::FAIR_EVENT_ID)
            ->get();

        return view('inventory_export::components.inventory-list')
            ->withWarehouses($warehouses)
            ->withWarehouseProducts($warehouseProducts);
    }

    public function deleteItem($item)
    {
        $item->delete();
    }

    public function setWarehouseID($id)
    {
        $this->warehouseID = $id;
    }

    public function updateStoreInventory()
    {
        //update store inventory based counted data
        $countedData = InventoryZero::query()
            ->where('warehouse_id', $this->warehouseID)
            ->active()
            ->selectRaw('product_id,warehouse_id,stock, state as status')
            ->get()
            ->toArray();

        if (! count($countedData)) {
            return;
        }

        //delete past inventory data
        Inventory::query()->where('warehouse_id', $this->warehouseID)->delete();

        Inventory::query()->upsert($countedData, ['product_id', 'warehouse_id'], ['stock', 'status']);

        InventoryZero::query()
            ->where('warehouse_id', $this->warehouseID)
            ->update([
                'stock' => 0,
                'state' => InventoryZero::STATE_ARCHIVE,
            ]);
        $this->dispatchBrowserEvent('toast-message', ['message' => 'Az üzlet készlete frissítve', 'type' => 'success']);
    }
}
