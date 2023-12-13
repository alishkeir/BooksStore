<?php

namespace Alomgyar\Consumption_reports;

use Alomgyar\Consumption_reports\Reports\GeneralConsumptionReport;
use Alomgyar\Suppliers\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ConsumptionReportComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $startDate;

    public $endDate;

    public int $perPage = 25;

    public string $sortField = 'id';

    public bool $sortAsc = false;

    public int $supplierID = 0;

//    protected $report;
    public Collection $suppliers;

    public string $period = '';

    public array $months = ['január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december'];

    public array $enMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function mount()
    {
        $this->startDate = date('Y-m-d', strtotime('First day of this month')).' 00:00:00';
        $this->endDate = date('Y-m-d', strtotime('Last day of this month')).' 23:59:59';
        $this->period = Carbon::createFromDate($this->startDate)->locale(config('app.locale'), config('app.fallback_locale'))->format('Y. F');
    }

    public function render()
    {
        $reportSuppliers = $this->report?->keys() ?? [];
        $this->suppliers = Supplier::withTrashed()->get()->whereIn('id', $reportSuppliers);
        $model = $this->supplierID && isset($this->report[$this->supplierID]) ? collect($this->report[$this->supplierID]) : $this->report;
        $details = [];
        if (isset($model['details'])) {
            $details = $model['details'];
            unset($model['details']);
        }

        return view('consumption_reports::components.consumption-report-component', [
            'model' => $model,
            'details' => $details,
        ]);
    }

    public function getReportProperty()
    {
        return GeneralConsumptionReport::getConsumptionReport($this->startDate, $this->endDate, true);
    }
}
