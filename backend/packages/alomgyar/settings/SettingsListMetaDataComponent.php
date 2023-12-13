<?php

namespace Alomgyar\Settings;

use Livewire\Component;
use Livewire\WithPagination;

class SettingsListMetaDataComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 300;

    public $sortField = 'id';

    public $sortAsc = true;

    public $section = 'eltalanos';

    public $input = [];

    protected $listeners = ['saveSetting'];

    public function mount()
    {
        $model = SettingsMetaData::select(['id', 'page', 'section', 'title', 'description'])->get();
        foreach ($model as $metadata) {
            if ($metadata->secondary == 'checkbox' && $metadata->primary == '0') {
            } else {
                $this->input[$metadata->id] = $metadata->primary;
            }
        }
    }

    public function render()
    {
        $model = SettingsMetaData::where('status', 1)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        if ($this->section) {
            $model = $model->where('section', $this->section);
        }
        $model = $model->paginate($this->perPage);

        return view('metadata::components.listcomponent', [
            'model' => $model,
        ]);
    }

    public function setFilter($filter)
    {
        $this->section = $filter;
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
