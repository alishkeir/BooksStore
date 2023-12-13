<?php

namespace Alomgyar\Customers;

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
        return view('customers::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Customer::query()
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
        $customer = Customer::find($id);
        $customer->status = ! $customer->status;
        $customer->save();
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        $this->dispatchBrowserEvent('toast-message', 'Customer '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $customer) {
            $customer = Customer::find($customer['value']);
            $customer->order = $customer['order'];
            $customer->save();
        }
    }
}
