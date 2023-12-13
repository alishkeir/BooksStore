<?php

namespace App\Console\Commands;

use Alomgyar\Products\ApiProduct;
use Alomgyar\Products\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GoogleMerchant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml:google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xml generálása a google merchant-nak';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('MAX_EXECUTION_TIME', '-1');
        set_time_limit(-1);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->makePreorderList(0);
        $this->makePreorderList(1);
        $this->makeXml(0);
        $this->makeXml(1);

        exit();
    }

    public function makeXml($store)
    {
        $name = [
            0 => '',
            1 => '-olcsokonyvek',
        ];

        $xml = new SimpleXMLExtended('<?xml version="1.0"  encoding="UTF-8"?><rss version="2.0"
xmlns:g="http://base.google.com/ns/1.0"></rss>');
        $xml = $xml->addChild('channel');
        $xml->addChild('title')->addCData('Álomgyár könyvesboltok');
        $xml->addChild('link')->addCData('alomgyar.hu');
        $xml->addChild('description')->addCData('A könyv egy mágikus eszköz. Az író a bűvész, a toll pedig a varázspálca. A jó „bűvész” magán tudja tartani a figyelmet és az olvasó reméli, hogy még sokáig a bűvkörben marad(hat). A 2012-ben alapított Álomgyár Kiadó ezeket a bűvészeket keresi. Azokat, akik fantáziájukkal olyan helyekre merészkednek, ahová mások nem képesek. Akik le merik írni, amit a többiek nem és ezt világgá is akarják kürtölni. Az Álomgyár elsőkönyves írókat keres, nekik segít írásaik könyvvé formálásában, kiadásában, terjesztésében. De bárki jelentkezhet kéziratával és megtaláljuk az utat, ami a könyvespolcokhoz vezet!');

        $baseUrl = $store == 0 ? env('ALOM_URL', env('APP_URL')) : env('OLCSO_URL', env('APP_URL'));

        $products = ApiProduct::with('subcategories', 'author', 'prices')
        ->where([['product.status', Product::STATUS_ACTIVE], ['product.type', Product::BOOK], ['product.state', '<>', Product::STATE_PRE], ['product.store_'.$store, 1], ['product.published_at', '<=', date('Y-m-d H:i:s')]])
        ->chunk(1000, function ($products) use ($xml, $store, $baseUrl, $name) {
            foreach ($products as $key => $product) {
                $subcats = array_map(function ($cat) {
                    return $cat['id'];
                }, $product->subcategories->toArray());

                $categories = ApiProduct::getCategoriesBySubcategories($product->subcategories);

                $categoryList = [];
                foreach ($categories as $cat) {
                    $text = $cat->title;

                    foreach ($cat->subcategories as $sub) {
                        if (in_array($sub->id, $subcats)) {
                            $text .= ' &gt; '.$sub->title;
                        }
                    }
                    $categoryList[] = $text;
                }

                $ob = $xml->addChild('item');
                $ob->addChild('g:id', $product->id, 'http://base.google.com/ns/1.0');
                $ob->addChild('g:price', round($product->price($store)->price_sale).' HUF', 'http://base.google.com/ns/1.0');
                $ob->addChild('g:title', '', 'http://base.google.com/ns/1.0')->addCData($product->authors.' - '.$product->title);
                $ob->addChild('g:description', '', 'http://base.google.com/ns/1.0')->addCData(strip_tags($product->description));
                $ob->addChild('g:brand', '', 'http://base.google.com/ns/1.0')->addCData($product->authors);

                $productCover = '';
                if (isset($product->cover)) {
                    if (str_contains($product->cover, 'https://') || str_contains($product->cover, 'http://')) {
                        $productCover = $product->cover;
                    } else {
                        $productCover = env('BACKEND_URL', 'https://pamadmin.hu').'/storage/'.$product->cover;
                    }
                }
                $ob->addChild('g:image_link', '', 'http://base.google.com/ns/1.0')->addCData($productCover);
                $ob->addChild('g:link', '', 'http://base.google.com/ns/1.0')->addCData($baseUrl.('/konyv/'.$product->slug));
                // $ob->addChild("ido","2-4 munkanap");
                // $ob->addChild("g:id",$product->product_cbs,"http://base.google.com/ns/1.0");
                $ob->addChild('g:gtin', $product->isbn, 'http://base.google.com/ns/1.0');
                $ob->addChild('g:availability', 'in stock', 'http://base.google.com/ns/1.0');
                $ob->addChild('g:product_type', '', 'http://base.google.com/ns/1.0')->addCData(implode(' &amp; ', $categoryList));
                $ob->addChild('g:google_product_category', '', 'http://base.google.com/ns/1.0')->addCData('784');

                $ob->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');
            }

            $dom = dom_import_simplexml($xml)->ownerDocument;
            $dom->formatOutput = true;
            $xml = $dom->saveXML();
            if (! file_exists(getcwd().'/storage/app/public/sync/')) {
                mkdir(getcwd().'/storage/app/public/sync/', 0777, true);
            }
            $dom->save(getcwd().'/storage/app/public/sync/google_merchant'.$name[$store].'.xml');
        });
    }

    public function makePreorderList($store)
    {
        $name = [
            0 => '',
            1 => '-olcsokonyvek',
        ];
        $baseUrl = $store == 0 ? env('ALOM_URL', env('APP_URL')) : env('OLCSO_URL', env('APP_URL'));
        $file = fopen(getcwd().'/storage/app/public/sync/google_preorder'.$name[$store].'.csv', 'w');
        fputcsv($file, ['Page URL', 'Custom label']);
        //DB::enableQueryLog();
        $products = Product::where(
            [
                ['product.status', Product::STATUS_ACTIVE],
                ['product.type', Product::BOOK],
                ['product.state', '=', Product::STATE_PRE],
                ['product.store_'.$store, 1],
                ['product.release_year', '>=', '2022'],
            ]
        )->get();
        //var_dump(DB::getQueryLog());
        foreach ($products as $product) {
            fputcsv($file, [$baseUrl.('/konyv/'.$product->slug), 'elojegyezheto']);
        }
        fclose($file);
    }
}

class SimpleXMLExtended extends \SimpleXMLElement
{
    public function addCData($cdata_text)
    {
        $node = dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
}
