<?php

namespace Alomgyar\Customers\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerVerifiedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;

    public function __construct($customer)
    {
        $this->customer = $customer;
    }
}
