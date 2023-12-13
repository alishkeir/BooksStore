<?php

namespace Alomgyar\PickUpPoints\Job;

use Illuminate\Contracts\Queue\ShouldQueue;

class PickUpPointScraperJob implements ShouldQueue
{
    private string $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function handle(): void
    {
        (new $this->className)->collect();
    }
}
