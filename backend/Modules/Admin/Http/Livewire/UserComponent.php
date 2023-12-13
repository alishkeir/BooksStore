<?php

namespace Modules\Admin\Http\Livewire;

use App\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'id';

    public $sortAsc = true;

    public function render()
    {
        if (! auth()->user()->hasRole(['skvadmin', 'admin', 'super admin'])) {
            abort(403, 'Jogosultsági probléma');
        }

        $term = trim($this->s);

        $model = User::query()
            ->search($term)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('admin::user.listcomponent', [
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

    public function setTab($value)
    {
        $this->s = '';
        $this->perPage = 25;
        $this->sortField = 'id';
        $this->sortAsc = true;
        $this->tab = $value;
        $this->resetPage();
    }
}
