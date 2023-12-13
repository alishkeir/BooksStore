<?php

namespace Alomgyar\Templates;

use Livewire\Component;
use Livewire\WithPagination;

class CardsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 3000;

    public $sortField = 'section';

    public $sortAsc = true;

    public $status;

    public function render()
    {
        return view('templates::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        $model = Templates::query()
            ->search(trim($this->s))
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->get();

        foreach ($model as $m) {
            $resp[$m->section][$m->slug][$m->store] = $m;
        }

        return $resp;
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
        $templates = Templates::find($id);
        $templates->status = ! $templates->status;
        $templates->save();
    }

    public function destroy($id)
    {
        $templates = Templates::find($id);
        $templates->delete();

        $this->dispatchBrowserEvent('toast-message', 'Templates '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $templates) {
            $templates = Templates::find($templates['value']);
            $templates->order = $templates['order'];
            $templates->save();
        }
    }
}
