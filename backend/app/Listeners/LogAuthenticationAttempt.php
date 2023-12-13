<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogAuthenticationAttempt implements ShouldQueue
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
     * @return void
     */
    public function handle(Attempting $event)
    {
        unset($event->credentials['password']);
        activity('auth')
            ->withProperties($event)
            ->log('LogAuthenticationAttempt');
    }
}
