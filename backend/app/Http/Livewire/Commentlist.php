<?php

namespace App\Http\Livewire;

use Alomgyar\Comments\Comment;
//use App\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Commentlist extends Component
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
        return view('livewire.commentlist', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        $query = Comment::query()
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

        if ($this->filters['store_0'] != 1) {
            $query->where('store', '!=', 0);
        }
        if ($this->filters['store_1'] != 1) {
            $query->where('store', '!=', 1);
        }
        if ($this->filters['store_2'] != 1) {
            $query->where('store', '!=', 2);
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
