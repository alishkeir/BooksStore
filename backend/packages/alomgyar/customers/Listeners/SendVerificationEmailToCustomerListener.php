<?php

namespace Alomgyar\Customers\Listeners;

use Alomgyar\Customers\Events\CustomerRegisteredEvent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationEmailToCustomerListener implements ShouldQueue
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
    public function handle(CustomerRegisteredEvent $event)
    {
        if ($event->customer instanceof MustVerifyEmail && ! $event->customer->hasVerifiedEmail()) {
            $event->customer->sendEmailVerificationNotification();
        }
    }
}
