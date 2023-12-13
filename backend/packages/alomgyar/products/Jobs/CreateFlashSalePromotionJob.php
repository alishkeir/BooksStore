<?php

namespace Alomgyar\Products\Jobs;

use App\Services\FlashDealToPromotionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateFlashSalePromotionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $source;

    public $target;

    public $store;

    public $fromDate;

    public $toDate;

    public $createdById;

    public function __construct($source, $target, $store, $fromDate, $toDate, $createdById)
    {
        $this->source = $source;
        $this->target = $target;
        $this->store = $store;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->createdById = $createdById;
    }

    public function handle()
    {
        (new FlashDealToPromotionService)->create($this->source, $this->target, $this->store, $this->fromDate, $this->toDate, $this->createdById);
    }
}
