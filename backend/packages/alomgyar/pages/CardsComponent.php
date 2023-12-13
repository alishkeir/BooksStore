<?php

namespace Alomgyar\Pages;

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
        return view('pages::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Page::query()
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
        $page = Page::find($id);
        $page->status = ! $page->status;
        $page->save();
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();

        $this->dispatchBrowserEvent('toast-message', 'Page '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $page) {
            $page = Page::find($page['value']);
            $page->order = $page['order'];
            $page->save();
        }
    }
}
