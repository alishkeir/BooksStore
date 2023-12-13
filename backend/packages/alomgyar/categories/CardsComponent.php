<?php

namespace Alomgyar\Categories;

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
        return view('categories::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Category::query()
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
        $category = Category::find($id);
        $category->status = ! $category->status;
        $category->save();
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        $this->dispatchBrowserEvent('toast-message', 'Category '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $category) {
            $category = Category::find($category['value']);
            $category->order = $category['order'];
            $category->save();
        }
    }
}
