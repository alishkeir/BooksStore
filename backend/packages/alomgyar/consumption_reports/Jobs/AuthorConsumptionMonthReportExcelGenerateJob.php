<?php

namespace Alomgyar\Consumption_reports\Jobs;

use Alomgyar\Consumption_reports\ConsumptionReport;
use App\Exports\AuthorConsumptionExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AuthorConsumptionMonthReportExcelGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $report;

    public $period;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($report, $period)
    {
        $this->report = $report;
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

        foreach ($this->report as $writerId => $items) {
            $details = [];
            if (isset($items['details'])) {
                $details = $items['details'];
                unset($items['details']);
            }

            if (Excel::store(
                new AuthorConsumptionExport($items, $details),
                'author-consumption-reports/'.$file = Str::slug($details['writer_name']).'-'.$this->period.'-consumption-report.xlsx',
                'local'
            )) {
                $files[] = $file;
            }
        }

        $generated = false;

        $consumptionReport = ConsumptionReport::where('period', $this->period)->first();

        if ($consumptionReport) {
            $updatedConsumptionReport = ConsumptionReport::where('period', $this->period)->update(['link_to_author_report' => $files]);
            $generated = true;
        } else {
            $createdConsumptionReport = ConsumptionReport::create([
                'period' => $this->period,
                'link_to_author_report' => $files,
                'number_of_books' => 0,
                'number_of_suppliers' => 0,
                'link_to_report' => [],
            ]);
            $generated = true;
        }
    }
}
