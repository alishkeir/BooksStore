<?php

namespace Alomgyar\Consumption_reports;

use Alomgyar\Consumption_reports\Reports\LegalOwnerConsumptionReport;
use Alomgyar\Legal_owners\LegalOwner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ConsumptionReportLegalOwnerComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $startDate;

    public $endDate;

    public int $perPage = 25;

    public string $sortField = 'id';

    public bool $sortAsc = false;

    public int $legalOwnerId = 0;

    public Collection $legalOwners;

    public string $period = '';

    public function mount()
    {
        $this->startDate = date('Y-m-d', strtotime('First day of this month')).' 00:00:00';
        $this->endDate = date('Y-m-d', strtotime('Last day of this month')).' 23:59:59';
    }

    public function render()
    {
        $reportLegalOwners = $this->report?->keys() ?? [];
        $this->legalOwners = LegalOwner::withTrashed()->get()->whereIn('id', $reportLegalOwners);
        $model = $this->legalOwnerId && isset($this->report[$this->legalOwnerId]) ? collect($this->report[$this->legalOwnerId]) : $this->report;
        $this->period = Carbon::createFromDate($this->startDate)->locale(config('app.locale'), config('app.fallback_locale'))->format('Y. F');
        $details = [];
        if (isset($model['details'])) {
            $details = $model['details'];
            unset($model['details']);
        }

        return view('consumption_reports::components.consumption-report-legal-owner', [
            'model' => $model,
            'details' => $details,
        ])->layout('admin::layouts.master');
    }

    public function getReportProperty(): bool|\Illuminate\Support\Collection|null
    {
        return LegalOwnerConsumptionReport::getConsumptionReport($this->startDate, $this->endDate, true);
    }
}
