<?php

namespace Alomgyar\Consumption_reports;

use Alomgyar\Consumption_reports\Reports\AuthorConsumptionReport;
use Alomgyar\Writers\Writer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ConsumptionReportAuthorComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $startDate;

    public $endDate;

    public int $perPage = 25;

    public string $sortField = 'id';

    public bool $sortAsc = false;

    public int $writerId = 0;

    public Collection $writers;

    public string $period = '';

    public function mount()
    {
        $this->startDate = date('Y-m-d', strtotime('First day of this month')).' 00:00:00';
        $this->endDate = date('Y-m-d', strtotime('Last day of this month')).' 23:59:59';
    }

    public function render()
    {
        $reportWriters = $this->report?->keys() ?? [];
        $this->writers = Writer::withTrashed()->get()->whereIn('id', $reportWriters);
        $model = $this->writerId && isset($this->report[$this->writerId]) ? collect($this->report[$this->writerId]) : $this->report;
        $this->period = Carbon::createFromDate($this->startDate)->locale(config('app.locale'), config('app.fallback_locale'))->format('Y. F');
        $details = [];

        if (isset($model['details'])) {
            $details = $model['details'];
            unset($model['details']);
        }

        return view('consumption_reports::components.consumption-report-author', [
            'model' => $model,
            'details' => $details,
        ])->layout('admin::layouts.master');
    }

    public function getReportProperty(): \Illuminate\Support\Collection
    {
        return AuthorConsumptionReport::getConsumptionReport($this->startDate, $this->endDate, true);
    }
}
