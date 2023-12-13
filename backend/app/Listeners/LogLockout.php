<?php

namespace App\Listeners;

use App\Notifications\LockedOutNotification;
use App\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogLockout implements ShouldQueue
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
    public function handle(Lockout $event)
    {
        unset($event->credentials['password']);
        activity('auth')
            ->withProperties($event)
            ->log('LogLockout');
        if ($user = User::where('email', $event->request->email)->first()) {
            $user->notify(new LockedOutNotification);
        }
    }
}
