<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculatePreOrdersCountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:preorders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate preorders count per store';

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
        foreach ([0, 1, 2] as $store) {
            $this->calculatePreOrdersCount($store);
        }
    }

    private function calculatePreOrdersCount(int $store)
    {
        DB::table('customer_preorders')
            ->select(DB::raw('count(customer_id) as preorders_count, customer_preorders.product_id'))
            ->join('customers', function ($join) {
                $join->on('customers.id', '=', 'customer_preorders.customer_id');
            })
            ->where('customers.store', $store)
            ->groupBy('customer_preorders.product_id')
            ->orderBy('customer_preorders.product_id')
            ->chunk(100, function ($preorders) use ($store) {
                foreach ($preorders as $preorder) {
                    DB::table('product')
                        ->where('id', $preorder->product_id)
                        ->update([sprintf('preorders_count_%d', $store) => $preorder->preorders_count]);
                }
            });

        DB::table('public_preorders')
            ->select(DB::raw('count(email) as preorders_count, product_id'))
            ->where('store', $store)
            ->groupBy('product_id')
            ->orderBy('product_id')
            ->chunk(100, function ($preorders) use ($store) {
                foreach ($preorders as $preorder) {
                    DB::table('product')
                        ->where('id', $preorder->product_id)
                        ->update([sprintf('preorders_count_%d', $store) => DB::raw(sprintf('preorders_count_%d', $store).' + '.$preorder->preorders_count)]);
                }
            });

        $this->info($store.' preorders counted');
    }
}
