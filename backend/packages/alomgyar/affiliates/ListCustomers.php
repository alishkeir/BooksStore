<?php

namespace Alomgyar\Affiliates;

use Alomgyar\Customers\Customer;
use App\Helpers\HumanReadable;
use App\Services\AffiliateService;
use DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListCustomers extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $s;
    public $perPage = 25;
    public $sortField = 'id';
    public $sortAsc = false;
    protected $affiliateService;
    public function mount()
    {
        $this->affiliateService = new AffiliateService();
    }
    public function render()
    {
        $model = Customer::query()
            ->has('affiliate')
            ->select('*')
            ->addSelect(DB::raw("CONCAT(firstname, ' ', lastname) as name"))
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        foreach ($model as $customer) {
            $customer->balance = HumanReadable::formatHUF($this->affiliateService->getCustomerBalance($customer));
            $customer->totalRedeems = HumanReadable::formatHUF($this->affiliateService->getCustomerTotalRedeems($customer));

        }
        return view('affiliates::components.listcustomers', [
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
}
