<?php

namespace Alomgyar\Publishers;

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
        return view('publishers::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Publisher::query()
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
        $publisher = Publisher::find($id);
        $publisher->status = ! $publisher->status;
        $publisher->save();
    }

    public function destroy($id)
    {
        $publisher = Publisher::find($id);
        $publisher->delete();

        $this->dispatchBrowserEvent('toast-message', 'Publisher '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $publisher) {
            $publisher = Publisher::find($publisher['value']);
            $publisher->order = $publisher['order'];
            $publisher->save();
        }
    }
}
