<?php

namespace Alomgyar\Customers\Listeners;

use Alomgyar\Customers\Events\CustomerSubscribeToNewsletterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class SubscribeCustomerToNewsletterListener implements ShouldQueue
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
    public function handle(CustomerSubscribeToNewsletterEvent $event)
    {
        $req = Request::create(
            config('pam.api_prefix').request('store').'/newsletter',
            'POST',
            [
                'body' => [
                    'email' => $event->customer->email,
                    'marketing_accepted' => 1,
                ],
            ],
            [],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );
        //        dd($req);
        app()->handle($req);
    }
}
