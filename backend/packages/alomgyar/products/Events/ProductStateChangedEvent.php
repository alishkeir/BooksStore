<?php

namespace Alomgyar\Products\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductStateChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inventory;

    public function __construct(array $inventory)
    {
        $this->inventory = $inventory;
    }
}
