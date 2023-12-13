<?php

namespace Alomgyar\Consumption_reports\Jobs;

use Alomgyar\Consumption_reports\ConsumptionReport;
use App\Exports\ConsumptionReportExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ConsumptionMonthReportExcelGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $report;

    public $numberOfBooks;

    public $period;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($report, $numberOfBooks, $period)
    {
        $this->report = $report;
        $this->numberOfBooks = $numberOfBooks;
        $this->period = $period;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $files = [];
        foreach ($this->report as $supplier_id => $items) {
            $this->numberOfBooks += $items->sum('total_sales');
            $details = [];
            if (isset($items['details'])) {
                $details = $items['details'];
                unset($items['details']);
            }

            if (Excel::store(
                new ConsumptionReportExport($items, $details),
                'consumption-reports/'.$file = Str::slug($details['supplier_name']).'-'.$this->period.'-consumption-report.xlsx',
                'local'
            )) {
                $files[] = $file;
            }
        }
        ConsumptionReport::updateOrCreate(
            ['period' => $this->period],
            [
                'number_of_books' => $this->numberOfBooks,
                'number_of_suppliers' => $this->report->count(),
                'link_to_report' => $files,
            ]
        );
    }
}
