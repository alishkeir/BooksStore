<?php

namespace Alomgyar\Customers\Listeners;

use Alomgyar\Customers\Events\CustomerSuccessfulPasswordResetEvent;
use Alomgyar\Customers\Notifications\CustomerSuccessfulPasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSuccessfulPasswordResetToCustomerListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     *
     * @return void
     */
    public function handle(CustomerSuccessfulPasswordResetEvent $event)
    {
        $event->customer->notify(new CustomerSuccessfulPasswordReset());
    }
}
