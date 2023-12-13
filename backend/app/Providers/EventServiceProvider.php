<?php

namespace App\Providers;

use Alomgyar\Customers\Events\CustomerRegisteredEvent;
use Alomgyar\Customers\Events\CustomerSubscribeToNewsletterEvent;
use Alomgyar\Customers\Events\CustomerSuccessfulPasswordResetEvent;
use Alomgyar\Customers\Events\CustomerVerifiedEvent;
use Alomgyar\Customers\Listeners\SendSuccessfulPasswordResetToCustomerListener;
use Alomgyar\Customers\Listeners\SendVerificationEmailToCustomerListener;
use Alomgyar\Customers\Listeners\SendVerifiedEmailToCustomerListener;
use Alomgyar\Customers\Listeners\SubscribeCustomerToNewsletterListener;
use Alomgyar\Products\Events\ProductAvailableEvent;
use Alomgyar\Products\Events\ProductOrderableEvent;
use Alomgyar\Products\Events\ProductStateChangedEvent;
use Alomgyar\Products\Listeners\ProductAvailableListener;
use Alomgyar\Products\Listeners\ProductOrderableListener;
use Alomgyar\Products\Listeners\ToggleProductStateListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ProductStateChangedEvent::class => [
            ToggleProductStateListener::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CustomerRegisteredEvent::class => [
            SendVerificationEmailToCustomerListener::class, // CR: get rid of email verification
        ],
        CustomerSuccessfulPasswordResetEvent::class => [
            SendSuccessfulPasswordResetToCustomerListener::class,
        ],
        CustomerVerifiedEvent::class => [
            SendVerifiedEmailToCustomerListener::class,
        ],
        CustomerSubscribeToNewsletterEvent::class => [
            SubscribeCustomerToNewsletterListener::class,
        ],
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\LogRegisteredUser',
        ],

        'Illuminate\Auth\Events\Attempting' => [
            'App\Listeners\LogAuthenticationAttempt',
        ],

        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedLogin',
        ],

        'Illuminate\Auth\Events\Validated' => [
            'App\Listeners\LogValidated',
        ],

        'Illuminate\Auth\Events\Verified' => [
            'App\Listeners\LogVerified',
        ],

        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],

        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\LogLockout',
        ],

        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\LogPasswordReset',
        ],
        ProductAvailableEvent::class => [
            ProductAvailableListener::class,
        ],
        ProductOrderableEvent::class => [
            ProductOrderableListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
