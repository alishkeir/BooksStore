<?php

namespace Alomgyar\Managment_templates;

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
        return view('managment_templates::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Managment_template::query()
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
        $managment_template = Managment_template::find($id);
        $managment_template->status = ! $managment_template->status;
        $managment_template->save();
    }

    public function destroy($id)
    {
        $managment_template = Managment_template::find($id);
        $managment_template->delete();

        $this->dispatchBrowserEvent('toast-message', 'Managment_template '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $managment_template) {
            $managment_template = Managment_template::find($managment_template['value']);
            $managment_template->order = $managment_template['order'];
            $managment_template->save();
        }
    }
}
