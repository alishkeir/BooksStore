<?php

namespace App\Jobs;

use Alomgyar\Customers\Customer;
use App\Mail\FacebookUserConversionMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ConvertFacebookUserToNormalUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $password = Str::random(8);
        $this->customer->update(['provider_id' => null, 'password' => bcrypt($password)]);
        Mail::to($this->customer->email)
            ->send(new FacebookUserConversionMail($this->customer, $password));
    }
}
