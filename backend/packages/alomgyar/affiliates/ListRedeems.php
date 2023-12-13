<?php

namespace Alomgyar\Affiliates;

use App\Services\GeneratePdfService;
use Livewire\Component;
use Livewire\WithPagination;

class ListRedeems extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $s;
    public $perPage = 25;
    public $sortField = 'id';
    public $sortAsc = false;

    public function render()
    {
        $model = AffiliateRedeem::query()
            ->paginate($this->perPage);
        // dd($model);
        return view('affiliates::components.listredeems', [
            'model' => $model,
        ]);
    }

    public function sortBy($column)
    {
        if ($this->sortField === $column) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $column;
    }

    public function updatingS()
    {
        $this->resetPage();
    }
    public function regeneratePdf($id)
    {
        $redeem = AffiliateRedeem::find($id);
        (new GeneratePdfService)->generateRedeemPdf($redeem);
        $this->emit('pdfRegenerated');
    }

}
