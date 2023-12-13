<?php

namespace App\Console\Commands;

use Alomgyar\Products\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Arukereso extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml:arukereso';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xml generálása az árúkeresőhöz';

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
        $this->makeXml(0);
        $this->makeXml(1);

        exit();
    }

    private function makeXml($store)
    {
        $name = [
            0 => '',
            1 => '-olcsokonyvek',
        ];

        $xml = new \SimpleXMLElement('<?xml version="1.0"  encoding="UTF-8"?><products></products>');

        $baseUrl = $store == 0 ? env('ALOM_URL', env('APP_URL')) : env('OLCSO_URL', env('APP_URL'));

        $products = DB::table('product')->leftJoin('product_price', function ($leftJoin) use ($store) {
            $leftJoin->on('product.id', '=', 'product_price.product_id');
            $leftJoin->where('product_price.store', $store);
        })->where([['product.status', Product::STATUS_ACTIVE], ['product.type', Product::BOOK], ['store_'.$store, 1], ['product.state', '<>', Product::STATE_PRE], ['product.published_at', '<=', date('Y-m-d H:i:s')]])->get()->toArray();

        foreach ($products as $key => $product) {
            // code...
            $ob = $xml->addChild('product');
            $ob->addChild('identifier', $product->id);
            $ob->addChild('manufacturer', htmlspecialchars($product->authors));
            $ob->addChild('name', htmlspecialchars($product->authors.' - '.$product->title));
            $ob->addChild('category', 'Könyv');
            $ob->addChild('ean_code', $product->isbn);
            $ob->addChild('originalprice', round($product->price_list));
            $ob->addChild('price', round($product->price_sale));
            $ob->addChild('net_price', round($product->price_sale) * ((100 - ($product->tax_rate ?? 5)) / 100));
            $ob->addChild('product_url', $baseUrl.('/konyv/'.$product->slug));
            $ob->addChild('description', htmlspecialchars($product->description));

            $productCover = '';
            if (isset($product->cover)) {
                if (str_contains($product->cover, 'https://') || str_contains($product->cover, 'http://')) {
                    $productCover = $product->cover;
                } else {
                    $productCover = env('BACKEND_URL', 'https://pamadmin.hu').'/storage/'.$product->cover;
                }
            }
            $ob->addChild('image_url', $productCover);
            $ob->addChild('delivery_time', '2-4 munkanap');
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $xml = $dom->saveXML();
        if (! file_exists(getcwd().'/storage/app/public/sync/')) {
            mkdir(getcwd().'/storage/app/public/sync/', 0777, true);
        }
        $dom->save(getcwd().'/storage/app/public/sync/arukereso'.$name[$store].'.xml');

        return true;
    }
}
