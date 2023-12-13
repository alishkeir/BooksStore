<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogVerified implements ShouldQueue
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
    public function handle(Verified $event)
    {
        unset($event->credentials['password']);
        activity('auth')
            ->withProperties($event)
            ->log('LogVerified');
    }
}
