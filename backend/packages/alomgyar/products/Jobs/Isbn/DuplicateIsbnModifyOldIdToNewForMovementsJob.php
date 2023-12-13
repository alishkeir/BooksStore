<?php

namespace Alomgyar\Products\Jobs\Isbn;

use Alomgyar\Products\Services\IsbnService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DuplicateIsbnModifyOldIdToNewForMovementsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $oldId;

    public $newId;

    public function __construct($oldId, $newId)
    {
        $this->oldId = $oldId;
        $this->newId = $newId;
    }

    public function handle()
    {
        // MAGIC

        (new IsbnService)->modify($this->oldId, $this->newId);
    }
}
