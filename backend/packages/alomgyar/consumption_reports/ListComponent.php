<?php

namespace Alomgyar\Consumption_reports;

use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = false;

    public function render()
    {
        $model = ConsumptionReport::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->paginate($this->perPage);

        return view('consumption_reports::components.listcomponent', [
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
}
