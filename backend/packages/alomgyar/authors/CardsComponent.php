<?php

namespace Alomgyar\Authors;

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
        return view('authors::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Author::query()
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
        $author = Author::find($id);
        $author->status = ! $author->status;
        $author->save();
    }

    public function destroy($id)
    {
        $author = Author::find($id);
        $author->delete();

        $this->dispatchBrowserEvent('toast-message', 'Author '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $author) {
            $author = Author::find($author['value']);
            $author->order = $author['order'];
            $author->save();
        }
    }
}
