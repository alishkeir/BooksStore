<?php

namespace Alomgyar\Methods;

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

        $model = Methods::query()
            ->search($term)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('methods::components.listcomponent', [
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
