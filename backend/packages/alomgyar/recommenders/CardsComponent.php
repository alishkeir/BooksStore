<?php

namespace Alomgyar\Recommenders;

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
        return view('recommenders::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Recommender::query()
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
        $recommenders = Recommender::find($id);
        $recommenders->status = ! $recommenders->status;
        $recommenders->save();
    }

    public function destroy($id)
    {
        $recommenders = Recommender::find($id);
        $recommenders->delete();

        $this->dispatchBrowserEvent('toast-message', 'Recommenders '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $recommenders) {
            $recommenders = Recommender::find($recommenders['value']);
            $recommenders->order = $recommenders['order'];
            $recommenders->save();
        }
    }
}
