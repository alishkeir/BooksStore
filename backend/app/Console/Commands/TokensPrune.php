<?php

namespace App\Console\Commands;

use Alomgyar\Customers\Customer;
use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class TokensPrune extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kitörli azokat a tokeneket, ahol lejárt az idő';

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
        PersonalAccessToken::where('created_at', '<', now()->subWeeks(4))->delete();
//        $tokens = PersonalAccessToken::where('created_at', '<', now()->subWeek(1))->get();
//        foreach ($tokens as $token) {
//            $customer = Customer::find($token->tokenable_id)->first();
//            if ($customer->remember_token == 0) {
//                $token->delete();
//            }
//            $this->info($token->id);
//        }
    }
}
