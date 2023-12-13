<?php

namespace Skvadcom\Logs;

use Livewire\Component;
use Livewire\WithPagination;

class LogComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = false;

    public function render()
    {
        if (! auth()->user()->hasRole(['skvadmin', 'admin', 'super admin'])) {
            abort(403, 'Jogosultsági probléma');
        }

        $term = trim($this->s);

        $model = Log::query()
            ->search($term)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('logs::listcomponent', [
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
