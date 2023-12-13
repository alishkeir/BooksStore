<?php

namespace App\Console\Commands;

use Alomgyar\Products\Product;
use Alomgyar\Warehouses\Inventory;
use App\Jobs\UpdateStockQuantityForInventoryJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportStockQuantityToDesiredWarehouseFromFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:desired-wh-quantity-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // $inventories = Inventory::where('warehouse_id', 20)->delete();

        // $products = Product::pluck('id', 'isbn');

        // $stockQuantities = (new FastExcel)->import(storage_path('import/warehouse/20_raktarkeszlet.xlsx'));

        // if (count($stockQuantities) > 0) {
        //     foreach ($stockQuantities->chunk(1000) as $chunk) {
        //         foreach ($chunk as $key => $line) {
        //             if (isset($products[$line['isbn']])) {
        //                 UpdateStockQuantityForInventoryJob::dispatch($products[$line['isbn']], $line['quantity'], 20);
        //             } else {
        //                 Log::channel('warehouse')->info('No product with ISBN: ' . $line['isbn']);
        //             }
        //         }
        //     }
        // }
    }
}
