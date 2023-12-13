<?php

namespace App\Console\Commands;

use Alomgyar\Customers\Customer;
use App\Jobs\ConvertFacebookUserToNormalUserJob;
use Illuminate\Console\Command;

class ConvertFacebookUsersToNormalUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert-facebook-users-to-normal-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace facebook users with normal users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customers = Customer::select(['id', 'email', 'provider_id', 'store'])
            ->whereNotNull('provider_id')
            ->whereRaw('LENGTH(provider_id) < 21')
            ->get();

        foreach ($customers as $customer) {
            dispatch(new ConvertFacebookUserToNormalUserJob($customer));
        }
    }
}
