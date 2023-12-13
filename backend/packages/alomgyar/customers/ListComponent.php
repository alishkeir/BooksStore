<?php

namespace Alomgyar\Customers;

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

    public $filters = ['store_0' => 1, 'store_1' => 1, 'store_2' => 1];

    public function render()
    {
        $term = trim($this->s);

        $model = Customer::query()->search($term);

        if ($this->filters['store_0'] != 1) {
            $model = $model->where('store', '!=', 0);
        }
        if ($this->filters['store_1'] != 1) {
            $model = $model->where('store', '!=', 1);
        }
        if ($this->filters['store_2'] != 1) {
            $model = $model->where('store', '!=', 2);
        }
        $model = $model->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->paginate($this->perPage);

        return view('customers::components.listcomponent', [
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

    public function updatingS()
    {
        $this->resetPage();
    }
}
