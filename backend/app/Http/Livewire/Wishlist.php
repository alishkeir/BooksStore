<?php

namespace App\Http\Livewire;

use App\WishItem;
//use App\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Wishlist extends Component
{
    use WithPagination;

    public $product;

    public $customer;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = false;

    public $status;

    public $filters = ['store_0' => 1, 'store_1' => 1, 'store_2' => 1];

    public function render()
    {
        return view('livewire.wishlist', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        $query = WishItem::query()
            ->search(trim($this->s))
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        //Ha termékhez nézzük
        if ($this->product ?? false) {
            $query->where('product_id', $this->product);
        }
        //Ha ügyfélhez nézzük
        if ($this->customer ?? false) {
            $query->where('customer_id', $this->customer);
        }

        return $query->paginate($this->perPage);
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

    public function changeStatus($id)
    {
        //$order = Order::find($id);
        //$order->status = !$order->status;
        //$order->save();
    }
}
