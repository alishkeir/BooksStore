<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogValidated implements ShouldQueue
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
    public function handle(Validated $event)
    {
        unset($event->credentials['password']);
        activity('auth')
            ->withProperties($event)
            ->log('LogValidated');
    }
}
