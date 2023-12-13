<?php

namespace Alomgyar\Carousels;

use Livewire\Component;
use Livewire\WithPagination;

class CardsComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 25;

    public $sortField = 'order';

    public $sortAsc = true;

    public $status;

    public $selectedShopId = 0;

    public function render()
    {
        return view('carousels::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return Carousel::query()
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->where('shop_id', $this->selectedShopId)
            ->get();
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
        $carousel = Carousel::find($id);
        $carousel->status = ! $carousel->status;
        $carousel->save();
    }

    public function destroy($id)
    {
        $carousel = Carousel::find($id);
        $carousel->delete();

        $this->dispatchBrowserEvent('toast-message', 'Carousel '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        \Log::info(json_encode($newOrders));

        foreach ($newOrders as $item) {
            $carousel = Carousel::find($item['value']);
            $carousel->order = $item['order'];
            $res = $carousel->save();

            \Log::info(($res ? 'true' : 'false').$carousel->id.'->'.$carousel['order']);
        }
    }
}
