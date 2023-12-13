<?php

namespace Alomgyar\Posts;

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
        return view('posts::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Post::query()
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
        $post = Post::find($id);
        $post->status = ! $post->status;
        $post->save();
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        $this->dispatchBrowserEvent('toast-message', 'Post '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $post) {
            $post = Post::find($post['value']);
            $post->order = $post['order'];
            $post->save();
        }
    }
}
