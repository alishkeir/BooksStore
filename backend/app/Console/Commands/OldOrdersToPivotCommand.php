<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OldOrdersToPivotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:orderspivot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old álomgyár orders to pivot table';

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
        $archives = DB::select(DB::raw('
        SELECT product_to_cart_cart_id, 
        product_to_cart_product_id as product_id, 
        cart_user_id as old_user_id, 
        customers.id as customer_id, 
        old_order.order_code,
        old_order.order_createdate as order_create_date
        FROM old_product_to_cart
        LEFT JOIN old_cart ON product_to_cart_cart_id = old_cart.cart_id
        LEFT JOIN customers ON customers.old_id = cart_user_id
        LEFT JOIN old_order ON old_order.order_cart_id = old_cart.cart_id
        WHERE old_product_to_cart.product_to_cart_createdate > 2020-10-01
        AND customers.id IS NOT NULL
        AND old_cart.cart_user_id IS NOT NULL
        AND old_order.order_code IS NOT NULL
        '));
        foreach ($archives as $archive) {
            $addr[] = "('".$archive->product_id."', '".$archive->customer_id."', '".$archive->old_user_id."', '".$archive->order_code."', '".$archive->order_create_date."', NOW())";
            //dd($addr);

            if (count($addr) == 100) {
                $sql = '
                INSERT INTO archive_orders_for_recommenders (product_id, customer_id, old_user_id, order_code, order_create_date, created_at) 
                VALUES '.implode(', ', $addr).'
                ';
                DB::statement($sql);
                $addr = [];
            }
        }

        $this->info('Migráció sikeresen lefutott');
    }
}
