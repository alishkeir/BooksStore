<?php

namespace Alomgyar\Countries;

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
        return view('countries::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Country::query()
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
        $country = Country::find($id);
        $country->status = ! $country->status;
        $country->save();
    }

    public function destroy($id)
    {
        $country = Country::find($id);
        $country->delete();

        $this->dispatchBrowserEvent('toast-message', 'Country '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $country) {
            $country = Country::find($country['value']);
            $country->order = $country['order'];
            $country->save();
        }
    }
}
