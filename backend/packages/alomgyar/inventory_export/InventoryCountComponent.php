<?php

namespace Alomgyar\InventoryExport;

use Alomgyar\Products\Product;
use Alomgyar\Warehouses\Warehouse;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InventoryCountComponent extends Component
{
    public $productISBN;

    public $warehouseID;

    public $createdByID;

    public $lastAddedBooks;

    public function mount()
    {
        $this->createdByID = auth()->id();
        if ($shopID = auth()->user()->shop_id) {
            $this->warehouseID = Warehouse::query()->where('shop_id', $shopID)->value('id');
        } else {
            $this->redirect('/');
        }
        $this->lastAddedBooks = collect();
    }

    public function render()
    {
        return view('inventory_export::components.inventory-count');
    }

    public function count()
    {
        if (! $this->productISBN) {
            $this->dispatchBrowserEvent('toast-message', ['message' => 'Írja be a könyvet isbn!', 'type' => 'error']);

            return;
        }

        $product = Product::query()->where('isbn', $this->productISBN)->first(['id', 'title', 'isbn']);

        if (! $product) {
            $this->dispatchBrowserEvent('toast-message', ['message' => 'Nem található könyv a megadott ISBN számmal!', 'type' => 'error']);

            return;
        }

        $re = InventoryZero::query()->updateOrCreate([
            'product_id' => $product->id,
            'warehouse_id' => $this->warehouseID,
            'deleted_at' => null,
        ], [
            'stock' => DB::raw('stock+1'),
            'state' => InventoryZero::STATE_ACTIVE,
            'created_by_id' => $this->createdByID,
        ]);

        $this->addToList($product->isbn, $product->title);
        $this->reset(['productISBN']);
    }

    private function addToList($isbn, $title)
    {
        if ($this->lastAddedBooks->count() == 10) {
            $this->lastAddedBooks->shift();
        }

        $this->lastAddedBooks->push([
            'isbn' => $isbn,
            'title' => $title,
        ]);
    }
}
