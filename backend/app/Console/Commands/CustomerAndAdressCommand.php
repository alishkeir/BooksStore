<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CustomerAndAdressCommand extends Command
{
    private $take;

    private $all;

    private $page = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:customer-address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customer id to addresses table';

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
        $this->take = 100;
        $customersCount = DB::table('customers')
                            ->where('store', 1)
                            ->where('id', '>', 71500)
                            ->count();
        $this->all = $customersCount / $this->take;

        for ($i = 0; $i <= $this->all; $i++) {
            $customers = DB::select(DB::raw('SELECT * FROM customers WHERE store = 1 AND id > 71500 ORDER BY id ASC LIMIT '.$this->page * $this->take.', '.$this->take.' '));
            foreach ($customers as $customer) {
                DB::select(DB::raw('UPDATE addresses SET role_id = '.$customer->id.' WHERE role_id = '.$customer->old_id.' AND id > 71500 '));
            }
            $this->page += 1;
            $this->info($this->page, $this->page * $this->take);
        }
        $this->info('Migráció sikeresen lefutott');
    }
}
