<?php

namespace App\Console\Commands;

use Alomgyar\Products\Mails\ProductsHasLowStockMail;
use Alomgyar\Products\Product;
use Alomgyar\Settings\Settings;
use Alomgyar\Warehouses\Warehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendMailFromLowSotckProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:lowstockmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiküldi az admin e-mail címére, hogy mely könyvekből van kevés készlet.';

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
        //$items = DB::table('product')->select('id')->get();
        $warehouses = Warehouse::select('id')->where('type', 1)->orWhere('secondary_type', 1)->get();
        $watchedWarehauseIds = [];

        foreach ($warehouses as $model) {
            $watchedWarehauseIds[] = $model->id;
        }

        if (! count($watchedWarehauseIds)) {
            return false;
        }

        $items = DB::table('product')
        ->select(
            'product.id',
            'product.title',
            'product.slug',
            'product.isbn',
            'product.low_stock',
            DB::raw('SUM(inventories.stock) AS stock')
        )
        ->join('inventories', 'inventories.product_id', '=', 'product.id')
        ->where([['product.status', 1], ['product.type', Product::BOOK]])
        ->whereIn('inventories.warehouse_id', $watchedWarehauseIds)
        ->whereNull('product.deleted_at')
        ->groupBy('product.id')
        ->get();

        $sendItems = [];

        foreach ($items as $item) {
            if ($item->stock >= Product::STOCK_LIMIT) {
                if ($item->low_stock) {
                    DB::table('product')->where('id', $item->id)->update(['low_stock' => 0]);
                }

                continue;
            }

            if (! $item->low_stock) {
                $sendItems[] = $item;
                DB::table('product')->where('id', $item->id)->update(['low_stock' => 1]);
            }
        }

        if ($sendItems) {
            $recipient = Settings::where('key', 'order_mail_bcc_alomgyar')->first();
            if (! $recipient) {
                $emailAddress = 'webshop@alomgyar.hu';
            } else {
                $emailAddress = $recipient->primary ?? 'webshop@alomgyar.hu';
            }
            Mail::to($emailAddress, 'Álomgyár admin')->queue(new ProductsHasLowStockMail($sendItems, $emailAddress));
        }
    }
}
