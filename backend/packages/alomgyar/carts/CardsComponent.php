<?php

namespace Alomgyar\Carts;

use Livewire\Component;
use Livewire\WithPagination;

class CardsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = true;

    public $status;

    public function render()
    {
        return view('carts::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Cart::query()
            ->search(trim($this->s))
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
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
        $cart = Cart::find($id);
        $cart->status = ! $cart->status;
        $cart->save();
    }

    public function destroy($id)
    {
        $cart = Cart::find($id);
        $cart->delete();

        $this->dispatchBrowserEvent('toast-message', 'Cart '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $cart) {
            $cart = Cart::find($cart['value']);
            $cart->order = $cart['order'];
            $cart->save();
        }
    }
}
