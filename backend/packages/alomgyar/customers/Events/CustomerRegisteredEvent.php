<?php

namespace Alomgyar\Customers\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerRegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The authenticated customer.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $customer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
    }
}
