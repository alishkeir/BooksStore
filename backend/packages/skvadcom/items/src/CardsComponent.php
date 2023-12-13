<?php

namespace Skvadcom\Items;

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
        return view('items::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Item::query()
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
        $item = Item::find($id);
        $item->status = ! $item->status;
        $item->save();
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        $item->delete();

        $this->dispatchBrowserEvent('toast-message', 'Item '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $item) {
            $item = Item::find($item['value']);
            $item->order = $item['order'];
            $item->save();
        }
    }
}
