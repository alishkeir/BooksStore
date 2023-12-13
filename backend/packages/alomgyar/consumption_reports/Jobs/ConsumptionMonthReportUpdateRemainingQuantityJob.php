<?php

namespace Alomgyar\Consumption_reports\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ConsumptionMonthReportUpdateRemainingQuantityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $itemId;

    public $remainingQuantity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($itemId, $remainingQuantity)
    {
        $this->itemId = $itemId;
        $this->remainingQuantity = $remainingQuantity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('product_movements_items')->where('id', $this->itemId)->update(['remaining_quantity_from_report' => $this->remainingQuantity]);
    }
}
