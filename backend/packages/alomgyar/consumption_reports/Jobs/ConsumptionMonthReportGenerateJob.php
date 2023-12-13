<?php

namespace Alomgyar\Consumption_reports\Jobs;

use Alomgyar\Consumption_reports\Reports\GeneralConsumptionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ConsumptionMonthReportGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $startDate;

    public $endDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('product_movements_items')->update(['remaining_quantity_from_report' => null]);
        GeneralConsumptionReport::getConsumptionReport($this->startDate, $this->endDate, false);
    }
}
