<?php

namespace Alomgyar\Warehouses;

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
        return view('warehouses::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Warehouse::query()
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
        $warehouse = Warehouse::find($id);
        $warehouse->status = ! $warehouse->status;
        $warehouse->save();
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::find($id);
        $warehouse->delete();

        $this->dispatchBrowserEvent('toast-message', 'Warehouse '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $warehouse) {
            $warehouse = Warehouse::find($warehouse['value']);
            $warehouse->order = $warehouse['order'];
            $warehouse->save();
        }
    }
}
