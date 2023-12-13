<?php

namespace Alomgyar\Suppliers;

use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = false;

    public function render()
    {
        $term = trim($this->s);

        $model = Supplier::withTrashed()
            ->search($term)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('suppliers::components.listcomponent', [
            'model' => $model,
        ]);
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

    public function restore($supplier)
    {
        Supplier::withTrashed()->find($supplier)->restore();
        $this->dispatchBrowserEvent('toast-message', ['message' => 'Beszállító '.__('messages.restored')]);
    }
}
