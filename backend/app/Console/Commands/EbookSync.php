<?php

namespace App\Console\Commands;

use Alomgyar\Authors\Author;
use Alomgyar\Products\Product;
use Alomgyar\Products\ProductPrice;
use Alomgyar\Subcategories\Subcategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EbookSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ebook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ebook könyvek szinkronizálása';

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
        $xml = simplexml_load_file('https://dibook.hu/api/book/list?alomgyar');
        if ($xml) {
            DB::table('product')->whereNotNull('dibook_id')->update(['dibook_sync' => 0]);

            $obj = json_encode($xml);
            $arr = json_decode($obj, true);
            $bookArray = array_chunk($arr['konyv'], 1000);
            echo 'started'.PHP_EOL;
            foreach ($bookArray as $books) {
                $idArray = [];
                foreach ($books as $key => $book) {
                    //echo $book['cim'].PHP_EOL;
                    $ebook = Product::where('dibook_id', $book['id'])->first();

                    if (empty($ebook)) {
                        $ebook = new Product();
                        $ebook->title = $book['cim'];
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
                        $ebook->description = $book['szoveg'];
                        $ebook->type = 1;
                        $ebook->isbn = str_replace('-', '', $book['isbn']);
                        $ebook->number_of_pages = $book['oldalszam'];
                        $ebook->release_year = $book['kiadas_eve'];
                        $ebook->cover = $book['kep'];
                        $ebook->store_0 = 1;
                        $ebook->status = 1;
                        $ebook->dibook_id = $book['id'];
                        $ebook->dibook_sync = 1;

                        if ($ebook->save()) {
                            if (! empty($book['kategoria'])) {
                                $ebook->subcategories()->sync($this->handleCategory($book['kategoria']));
                            }

                            if (! empty($book['szerzo'])) {
                                $ebook->author()->sync($this->handleAuthor($book['szerzo']));
                            }
                            $price_data['product_id'] = $ebook->id;
                            $price_data['store'] = 0;
                            ProductPrice::create($price_data);
                            $prices = [
                                0 => [
                                    'price_list_original' => $book['ar'],
                                    'price_sale_original' => round($book['ar'] * 0.95),
                                    'price_list' => $book['ar'],
                                    'price_sale' => round($book['ar'] * 0.95),
                                    'price_cart' => 0,
                                ],
                            ];
                            $ebook->price(0)->update($prices[0]);
                        }
                    // szerzőértesítő
                    } else {
                        $mod = false;
                        if ($ebook->isbn != str_replace('-', '', $book['isbn'])) {
                            $ebook->isbn = str_replace('-', '', $book['isbn']);
                            $mod = true;
                        }

                        if (! isset($ebook->price(0)->price_list)) {
                            $price_data['product_id'] = $ebook->id;
                            $price_data['store'] = 0;
                            ProductPrice::create($price_data);
                        }

                        if ($ebook->status == 0) {
                            $ebook->status = 1;
                            $mod = true;
                        }

                        if ($ebook->price(0)->price_list != $book['ar']) {
                            $prices = [
                                0 => [
                                    'price_list_original' => $book['ar'],
                                    'price_sale_original' => round($book['ar'] * 0.95),
                                    'price_list' => $book['ar'],
                                    'price_sale' => round($book['ar'] * 0.95),
                                    'price_cart' => 0,
                                ],
                            ];

                            $ebook->price(0)->update($prices[0]);
                        }
                        if ($this->handleCategory($book['kategoria'])) {
                            $ebook->subcategories()->sync($this->handleCategory($book['kategoria']));
                        }

                        if ($mod == true) {
                            $ebook->save();
                        }
                    }
                    $idArray[] = $ebook->id;
                }
                DB::table('product')->whereIn('id', $idArray)->update(['dibook_sync' => 1]);
            }
        } else {
            echo 'no xml';
        }

        DB::table('product')->where('dibook_sync', 0)->update(['status' => 0]);
    }

    private function handleAuthor($author)
    {
        $getAuthor = Author::where('title', $author)->first();

        if (empty($getAuthor)) {
            $getAuthor = new Author();
            $getAuthor->title = $author;
            $getAuthor->slug = Str::slug($author);
            $getAuthor->status = 1;
            $getAuthor->save();
        }

        return $getAuthor->id;
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
}
