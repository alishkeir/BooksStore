<?php

namespace Alomgyar\Promotions;

use Livewire\Component;
use Livewire\WithPagination;

class CardsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'order';

    public $sortAsc = true;

    public $status;

    public function render()
    {
        return view('promotions::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Promotion::query()
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
        $promotion = Promotion::find($id);
        $promotion->status = ! $promotion->status;
        $promotion->save();
    }

    public function destroy($id)
    {
        $promotion = Promotion::find($id);
        $promotion->delete();

        $this->dispatchBrowserEvent('toast-message', 'Promotion '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $item) {
            $promotion = Promotion::find($item['value']);
            $promotion->order = $item['order'];
            $promotion->save();
        }
    }
}
