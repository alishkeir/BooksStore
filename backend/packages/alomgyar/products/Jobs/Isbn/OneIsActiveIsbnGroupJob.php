<?php

namespace Alomgyar\Products\Jobs\Isbn;

use Alomgyar\Products\Services\OneActiveIsbnService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OneIsActiveIsbnGroupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $isbnGroup;

    public function __construct($isbnGroup)
    {
        $this->isbnGroup = $isbnGroup;
    }

    public function handle()
    {
        (new OneActiveIsbnService)->handle($this->isbnGroup);
    }
}
