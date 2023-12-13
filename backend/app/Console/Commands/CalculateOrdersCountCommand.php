<?php

namespace App\Console\Commands;

use App\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateOrdersCountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate orders count per store';

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
            $this->calculateOrdersCount($store);
        }
    }

    private function calculateOrdersCount($store)
    {
        DB::table('orders')
          ->select(DB::raw('sum(quantity) as orders_count, order_items.product_id'))
          ->where('status', Order::STATUS_COMPLETED)
          ->where('store', $store)
          ->join('order_items', function ($join) {
              $join->on('order_items.order_id', '=', 'orders.id');
          })
          ->groupBy('order_items.product_id')
          ->orderBy('order_items.product_id')
          ->chunk(100, function ($orders) use ($store) {
              foreach ($orders as $order) {
                  DB::table('product')
                    ->where('id', $order->product_id)
                    ->update([sprintf('orders_count_%d', $store) => $order->orders_count]);
              }
          });

        $this->info($store.' orders counted');
    }
}
