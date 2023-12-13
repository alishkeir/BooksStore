<?php

namespace App\Console\Commands;

use Alomgyar\Authors\Author;
use Alomgyar\Products\Product;
use Alomgyar\Products\ProductPrice;
use Alomgyar\Subcategories\Subcategory;
use Alomgyar\Synchronizations\SyncController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DibookSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:dibook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dibook ebook könyvek szinkronizálása';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('MAX_EXECUTION_TIME', '-1');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo 'started reading xml'.PHP_EOL;
        $xml = simplexml_load_file('https://dibook.hu/api/book/list?alomgyar');
        echo 'xml loaded'.PHP_EOL;
        $modded = 0;
        $new = 0;
        $notReleased = 0;
        if ($xml) {
            DB::table('product')->whereNotNull('dibook_id')->whereNotNull('deleted_at')->update(['dibook_sync' => 0]);
            echo 'updated dibook products for sync'.PHP_EOL;

            $obj = json_encode($xml);
            $arr = json_decode($obj, true);
            echo 'json encoded'.PHP_EOL;
            $stat = ['author' => 0, 'price' => 0];
            $bookArray = array_chunk($arr['konyv'], 1000);
            echo 'chunking array done'.PHP_EOL;
            foreach ($bookArray as $i => $books) {
                $idArray = [];
                echo 'chunk '.$i.PHP_EOL;
                $dibookIds = $existingEbooks = [];
                foreach ($books as $key => $book) {
                    array_push($dibookIds, $book['id']);
                }
                $checkEbooks = Product::whereIn('dibook_id', $dibookIds)->withTrashed()->get();

                foreach ($checkEbooks ?? [] as $ebookExist) {
                    $existingEbooks[$ebookExist->dibook_id] = $ebookExist;
                }
                foreach ($books as $key => $book) {
                    //ignore books without price
                    if (empty($book['ar']) || $book['ar'] == 0) {
                        continue;
                    }
                    //$ebook = Product::where('dibook_id', $book['id'])->first();
                    if ($book['release_date'] > Carbon::now()) {
                        //echo $book['cim']. ' - ' . $book['release_date']. PHP_EOL;
                        $notReleased += 1;

                        continue;
                    }
                    if (! isset($existingEbooks[$book['id']])) {
                        //echo 'nobook '.$i.'/'.$key.PHP_EOL;
                        $new++;
                        $ebook = new Product();
                        $ebook->title = str_contains($book['cim'],'(e-könyv)')
                            ? htmlspecialchars($book['cim'])
                            : htmlspecialchars($book['cim']).' (e-könyv)';
                        $slug = Str::slug($book['cim']);

                        $findSlug = Product::where('slug', $slug.'-ekonyv')->first();
                        /* slug ellenőrzése */
                        if (! empty($findSlug)) {
                            for ($i = 1; $i < 100; $i++) {
                                $findSlug = Product::where('slug', $slug.'-'.$i.'-ekonyv')->first();
                                if (empty($findSlug)) {
                                    $ebook->slug = $slug.'-'.$i.'-ekonyv';
                                    break;
                                }
                            }
                        } else {
                            $ebook->slug = $slug.'-ekonyv';
                        }
                        $ebook->description = is_array($book['szoveg']) && count($book['szoveg']) < 1 ? '' : $book['szoveg'];
                        $ebook->type = 1;
                        $ebook->isbn = str_replace('-', '', $book['isbn']);
                        $ebook->number_of_pages = is_array($book['oldalszam']) && count($book['oldalszam']) < 1 ? null : $book['oldalszam'];
                        $ebook->release_year = is_array($book['kiadas_eve']) && count($book['kiadas_eve']) < 1 ? null : $book['kiadas_eve'];
                        $ebook->cover = $book['kep'];

                        $ebook->store_0 = 1;
                        $ebook->store_1 = 0;
                        $ebook->store_2 = 0;
                        $ebook->status = 1;
                        $ebook->tax_rate = 27;
                        $ebook->state = 0;
                        //dd($book['szerzo']);
                        $getAuthor = ($book['szerzo']) ? $this->handleAuthor($book['szerzo']) : false;
                        if ($getAuthor ?? false) {
                            $ebook->authors = $getAuthor->title;
                        }
                        //$ebook->

                        $ebook->dibook_id = $book['id'];
                        $ebook->dibook_sync = 1;

                        // CHECK ONLY HERE, IF THE BOOK ISBN EXISTS
                        if (DB::table('product')->where('isbn', $ebook->isbn)->exists()) {
                            Log::channel('isbn')->info('Dibook: '.$ebook->isbn.' is duplicated, not saved because of isbn: '.$ebook->isbn.' exists.');

                            continue;
                        }
                        if ($ebook->save()) {
                            if (! empty($book['kategoria'])) {
                                $ebook->subcategories()->sync($this->handleCategory($book['kategoria']));
                            }
                            if ($getAuthor ?? false) {
                                $ebook->author()->sync($getAuthor->id);
                            }

                            if (! empty($book['ar'])) {
                                $price = ProductPrice::updateOrCreate([
                                    'product_id' => $ebook->id,
                                    'store' => 0,
                                ], [
                                    'price_list_original' => $book['ar'],
                                    'price_sale_original' => round($book['ar'] * 0.95),
                                    'price_list' => $book['ar'],
                                    'price_sale' => round($book['ar'] * 0.95),
                                    'discount_percent' => 5,
                                    'price_cart' => 0,
                                ]);
                            }
                        }
                    } else {
                        $ebook = $existingEbooks[$book['id']];
                        $mod = false;
                        //if(isset($book['szerzo'][0]) && $ebook->authors==''){
                        if (isset($book['szerzo'][0]) && empty($ebook->authors)) {
                            $getAuthor = ($book['szerzo']) ? $this->handleAuthor($book['szerzo']) : false;
                            if ($getAuthor ?? false) {
                                $ebook->author()->sync($getAuthor->id);
                                $ebook->authors = $getAuthor->title;
                                $mod = true;
                                $stat['author']++;
                            }
                        }

                        if ($ebook->isbn != str_replace('-', '', $book['isbn'])) {
                            $ebook->isbn = str_replace('-', '', $book['isbn']);
                            $mod = true;
                        }
                        if ($ebook->cover !== $book['kep'] && ! is_array($book['kep'])) {
                            $ebook->cover = $book['kep'];
                            $mod = true;
                        }

                        if ($ebook->status == 0) {
                            $ebook->status = 1;
                            echo 'statusTo1 '.$ebook->dibook_id.'('.$i.'/'.$key.')'.PHP_EOL;
                            $mod = true;
                        }

                        if (! empty($book['ar'])) {
                            $price = ProductPrice::updateOrCreate([
                                'product_id' => $ebook->id,
                                'store' => 0,
                            ], [
                                'price_list_original' => $book['ar'],
                                'price_sale_original' => round($book['ar'] * 0.95),
                                'price_list' => $book['ar'],
                                'price_sale' => round($book['ar'] * 0.95),
                                'discount_percent' => 5,
                                'price_cart' => 0,
                            ]);
                        }

                        if ($mod == true) {
                            $modded++;
                            $ebook->save();
                        }
                    }
                    $idArray[] = $ebook->id;
                }
                DB::table('product')->whereIn('id', $idArray)->update(['dibook_sync' => 1]);
                echo $modded.' updated, '.$new.' new '.', '.$notReleased.' skipped - not released'.PHP_EOL;
            }
        } else {
            echo 'no xml';
        }

        DB::table('product')->whereNotNull('dibook_id')->where([['dibook_sync', 0], ['type', Product::EBOOK]])->update(['status' => 0]);
    }

    public function justDownload()
    {
        echo date('H:i:s').' DiBook PRODUCT feed Letöltés  elkezdve'.PHP_EOL;
        SyncController::downloadXmlAs(config('api-endpoints.dibook.product_feed'), config('api-endpoints.dibook.product_feed_name'));
        echo date('H:i:s').' DiBook PRODUCT feed Letöltés  befejezve'.PHP_EOL;
    }

    private function handleAuthor($author, $name = false)
    {
        // MAKE THE AUTHORS NAME IN RESERVED ORDER
        $nameWords = explode(' ', $author);
        $reversedNameWords = array_reverse($nameWords);
        $reversedName = implode(' ', $reversedNameWords);

        // WE'VE GOT THE ORIGINAL AUTHOR SLUG
        // NEED THE RESERVED AUTHOR SLUG ALSO
        // TRY TO GET AUTHOR FROM DB

        $slug = Str::slug($author);
        $reversedSlug = Str::slug($reversedName);
        $getAuthor = Author::where('slug', $slug)->withTrashed()->first();

        // IF NOT FOUND
        // TRY TO GET THE RESERVED ORDER ONE
        if (! $getAuthor) {
            $getAuthor = Author::where('slug', $reversedSlug)->withTrashed()->first();
        }
        // IF AUTHOR EXISTS, JUST DELETED, THEN MAKE ACTIVE AGAIN
        if ($getAuthor && $getAuthor->deleted_at) {
            $getAuthor->deleted_at = null;
            $getAuthor->save();
        }
        // ANYWAY, IF NOT FOUND, CREATE IT
        if (! $getAuthor) {
            echo $slug.PHP_EOL;
            $getAuthor = new Author();
            $getAuthor->title = $author;
            $getAuthor->slug = $slug;
            $getAuthor->status = 1;
            $getAuthor->save();
        }

        return $getAuthor;
    }

    private function handleImage($url, $name = false)
    {
        if (! $url) {
            return false;
        }

        $imgName = substr($url, strrpos($url, '/') + 1, strrpos($url, '.') - strrpos($url, '/') - 1);
        if (strlen($name) > 100) {
            $name = substr($name, 0, 100);
        }
        $imgSizes = @getimagesize($url);

        if (! $imgSizes) {
            return false;
        }

        $newName = $name.'-'.$imgName.'_'.$imgSizes[0].'-'.$imgSizes[1].'.'.pathinfo($url, PATHINFO_EXTENSION);
        //app_path().'/storage/app/public/products/';
        if (! is_file(base_path().'/storage/app/public/product/cover/'.$newName)) {
            if (! file_exists(base_path().'/storage/app/public/product/cover')) {
                mkdir(base_path().'/storage/app/public/product/cover', 0777, true);
            }

            file_put_contents(base_path().'/storage/app/public/product/cover/'.$newName, file_get_contents($url));
        }

        return 'product/cover/'.$newName;
    }

    private function handleCategory($cat)
    {
        if (is_array($cat)) {
            if (count($cat) > 0) {
                $c = $cat[0];
            } else {
                return false;
            }
        } else {
            $c = $cat;
        }

        $getCategory = Subcategory::where('slug', Str::slug($c))->first();

        if (empty($getCategory)) {
            $getCategory = new Subcategory();
            $getCategory->title = $c;
            $getCategory->slug = Str::slug($c, '-');
            $getCategory->status = 1;
            $getCategory->save();
        }

        return $getCategory->id;
    }

    public static function downloadXml($from, $to = '')
    {
        file_put_contents('dibook.xml', fopen($from, 'r'));

        return true;
    }
}
