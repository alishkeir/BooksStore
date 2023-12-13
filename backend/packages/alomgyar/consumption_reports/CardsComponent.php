<?php

namespace Alomgyar\Consumption_reports;

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
        return view('consumption_reports::components.cards', ['model' => $this->model]);
    }

    public function getModelProperty()
    {
        return ConsumptionReport::query()
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
        $consumption_report = ConsumptionReport::find($id);
        $consumption_report->status = ! $consumption_report->status;
        $consumption_report->save();
    }

    public function destroy($id)
    {
        $consumption_report = ConsumptionReport::find($id);
        $consumption_report->delete();

        $this->dispatchBrowserEvent('toast-message', 'Consumption_report '.__('messages.deleted'));
    }

    public function updateOrder($newOrders)
    {
        foreach ($newOrders as $consumption_report) {
            $consumption_report = ConsumptionReport::find($consumption_report['value']);
            $consumption_report->order = $consumption_report['order'];
            $consumption_report->save();
        }
    }
}
