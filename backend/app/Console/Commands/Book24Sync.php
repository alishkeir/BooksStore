<?php

namespace App\Console\Commands;

use Alomgyar\Synchronizations\SyncBook24;
use Alomgyar\Synchronizations\SyncBook24UpdatePrice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Book24Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:book24
                            {mode : saveNewProducts, setPreorderable, justDownload, setInactives, justDownloadStockInfo, updateProductStock, updateProductPrices}
                            {--limited}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Book24 könyvek szinkronizálása';

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
        switch ($this->argument('mode')) {
            case 'justDownload':
                $this->justDownload();
                break;
            case 'saveNewProducts':
                $this->saveNewProducts();
                break;
            case 'updateProductPrices':
                $this->updateProductPrices();
                break;
            default:
                echo 'something missing'.PHP_EOL;
                break;
        }

        return 0;
    }

    public function justDownload()
    {
        if (! $this->option('limited')) {
            echo date('H:i:s').' Book24 PRODUCT feed Letöltés  elkezdve'.PHP_EOL;
        }
        SyncBook24::downloadXml(config('api-endpoints.book24.product_feed'));
        Cache::put('book24LastDownload', Carbon::now());
        if (! $this->option('limited')) {
            echo date('H:i:s').' Book24 PRODUCT feed Letöltés  befejezve'.PHP_EOL;
        }
    }

    public function saveNewProducts()
    {
        $limited = $this->option('limited');
        $streamer = new SyncBook24('products_list.xml', $limited);
        $r = $streamer->parse();
        if ($limited) {
            return;
        }
        if ($r) {
            echo date('H:i:s').' Book24 PRODUCT feed Szinkronizáció  befejezve'.PHP_EOL;
            echo $r.PHP_EOL;
        } else {
            echo date('H:i:s').' Hiba'.PHP_EOL;
        }
    }

    public function updateProductPrices()
    {
        $streamer = new SyncBook24UpdatePrice('products_list.xml');

        if ($r = $streamer->parse()) {
            echo date('H:i:s').' Book24 UPDATE PRICE feed Szinkronizáció  befejezve'.PHP_EOL;
            echo $r.PHP_EOL;
        } else {
            echo date('H:i:s').' Hiba'.PHP_EOL;
        }
    }
}
