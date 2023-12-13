<?php

namespace Alomgyar\Methods;

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
        return view('methods::components.cards-payment', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return PaymentMethod::query()
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
        $methods = PaymentMethod::find($id);
        $methods->status = ! $methods->status;
        $methods->save();
    }

    public function destroy($id)
    {
        $methods = PaymentMethod::find($id);
        $methods->delete();

        $this->dispatchBrowserEvent('toast-message', 'Methods '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $methods) {
            $methods = PaymentMethod::find($methods['value']);
            $methods->order = $methods['order'];
            $methods->save();
        }
    }
}
