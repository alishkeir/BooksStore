<?php

namespace Alomgyar\Products\Jobs\Isbn;

use Alomgyar\Products\Services\IsbnService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleGroupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $isbnGroup;

    public function __construct($isbnGroup)
    {
        $this->isbnGroup = $isbnGroup;
    }

    public function handle()
    {
        (new IsbnService)->isbnGroupHandle($this->isbnGroup);
    }
}
