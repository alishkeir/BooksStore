<?php

namespace Alomgyar\Synchronizations;

use Alomgyar\Authors\Author;
use Alomgyar\Countries\Country;
use Alomgyar\Customers\Address;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\Products\Product;
use Alomgyar\Publishers\Publisher;
use App\Order;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class SyncComponent extends Component
{
    public $resp;

    public $page = -1;

    public $take = 100;

    public $running;

    public $all = 1;

    protected $listeners = [
        'migrateProducts', 'migrateProductImage', 'migrateProductImage2', 'migrateOrders', 'migrateOaddress',
        'migrateSubcatToProduct', 'migrateAuthor', 'migrateCategory', 'migrateProductMeta', 'migrateUsers',
        'migrateEbookOrders', 'migrateSocial', 'migrateOrdersCustomers', 'fixProducts',
    ];

    public function render()
    {
        return view('synchronizations::components.synccomponent');
    }

    public function runProductSync()
    {
        $this->resp[] = date('H:i:s').' Szinkronizáció  elkezdve';
        $streamer = new SyncController('products_list.xml');

        $streamer->downloadXml('https://www.book24.hu/cache/products_list.xml');

        if ($r = $streamer->parse()) {
            $this->resp[] = date('H:i:s').' Szinkronizáció  befejezve';
        } else {
            $this->resp[] = "Couldn't find root node";
        }
        $this->resp[] = $r;
    }

    public function downloadXml()
    {
        $this->resp[] = date('H:i:s').' Letöltés  elkezdve';
        SyncController::downloadXml('https://www.book24.hu/cache/products_list.xml');

        $this->resp[] = date('H:i:s').' Letöltés  befejezve';
    }

    //meta field id: 1-Kiadó 2-Terjedelem 3-Év
    public function migrateProducts()
    {
        $this->take = 1000;
        $this->all = OldProduct::count() / $this->take;

        $oldproducts = $this->page >= 0 ? OldProduct::skip(($this->page * $this->take))->take($this->take)->get() : null;

        $dupes = DB::select(DB::raw('
        SELECT COUNT(product_eancode) as `count`, product_eancode
        FROM `old_product`
        WHERE product_active = 1 AND product_deleted != 1
        GROUP BY product_eancode
        ORDER BY `count` DESC
        LIMIT 0, 250
        '));
        foreach ($dupes as $dupe) {
            if ($dupe->count > 1) {
                $dupeIsbn[$dupe->product_eancode] = true;
            }
        }

        if ($this->running ?? false) {
            foreach ($oldproducts as $old) {
                if ($old->product_name == '') {
                    $old->product_name = 'N/A';
                    $old->product_active = 0;
                }
                //try {
                if ($old->product_discountPrice == 0 || $old->product_regularPrice == 0) {
                    //$this->resp[]='nulla az ár'.$old->product_id.' '.$old->product_name.' '.$old->product_discountPrice.' '.$old->product_regularPrice;
                    //continue;
                    $old->product_active = 0;
                }
                if (! is_numeric($old->product_eancode)) {
                    continue;
                }
                //$slugproduct = Product::where('slug', $old->product_slug)->select('id', 'slug')->first();
                $slugproduct = DB::select(DB::raw("
                    SELECT id FROM product WHERE slug = '".$old->product_slug."' LIMIT 0, 1
                    "));
                if ($slugproduct[0] ?? false || isset($slugs[$old->product_slug])) {
                    $old->product_slug = $old->product_eancode.'-'.$old->product_slug.'-V'.rand(1, 30);
                    if (($slugproduct[0]->id ?? false) == $old->product_id || ! is_numeric($old->product_eancode)) {
                        continue;
                    }
                }
                if (is_numeric($old->product_author_id)) {
                    $newItem = [
                        'product_id' => $old->product_id,
                        'author_id' => $old->product_author_id,
                        'primary' => 1,
                    ];
                    DB::table('product_author')->insert($newItem);
                }

                $author_ids = [];
                array_push($author_ids, $old->product_author_id);

                $otherauthors = explode(',',
                    $old->sauthors);    //4573||karina-halle||Karina Halle,15588||kendall-ryan||Kendall Ryan,10549||meghan-march||Meghan March
                foreach ($otherauthors as $oauthor) {
                    $oauthor2 = explode('||', $oauthor);
                    if (is_numeric($oauthor2[0])) {
                        $this->resp[] = 'extra szerző '.$oauthor2[0];
                        $newItem = [
                            'product_id' => $old->product_id,
                            'author_id' => $oauthor2[0],
                            'primary' => 0,
                        ];
                        DB::table('product_author')->insert($newItem);
                        array_push($author_ids, $oauthor2[0]);
                    }
                }
                $author_names = Author::whereIn('id', $author_ids)->select('title')->get();
                $names = [];
                foreach ($author_names as $name) {
                    $names[] = $name->title;
                }

                $old->product_publishdate = $old->product_publishdate == '' ? null : $old->product_publishdate;

                if (isset($dupeIsbn[$old->product_eancode]) && $old->product_type == 2) {
                    $old->product_eancode = '1'.$old->product_eancode;
                }
                $newProduct = [
                    'id' => $old->product_id,
                    'title' => $old->product_name,
                    'slug' => $old->product_slug,
                    'status' => $old->product_deleted == 1 ? 0 : $old->product_active,
                    'type' => $old->product_type == 2 ? 1 : 0, //ekony vagy nem
                    'tax_rate' => $old->product_priceTaxRate,
                    'release_year' => substr($old->product_releasedate, 0, 4),
                    'created_at' => $old->product_createdate,
                    'updated_at' => $old->product_modifydate == '0000-00-00 00:00:00' ? null : $old->product_modifydate,
                    'published_at' => $old->product_publishdate == '0000-00-00 00:00:00' ? null : $old->product_publishdate,
                    //                    'orders_count' => intval($old->purchase_count),
                    'isbn' => $old->product_eancode,
                    'publisher_id' => 0,
                    'description' => $old->product_description,
                    'authors' => implode(', ', $names),
                    'dibook_id' => $old->product_type == 2 ? (str_replace('E-', '', $old->product_sku) ?? 0) : 0,
                    'dibook_sync' => $old->product_type == 2 ? ($old->product_stockType == 4 ? 0 : 1) : 0,
                    'book24_id' => $old->product_type == 1 ? (intval($old->product_sku) ?? 0) : null,
                    'book24_sync' => $old->product_type == 1 ? ($old->product_stockType == 4 ? 0 : 1) : 0,
                    'store_0' => 1,
                    'store_1' => 1,
                    'store_2' => 1,
                    //'number_of_pages'
                ];
                if ($old->product_type == 2) {
                    $newProduct['store_1'] = 0;
                    $newProduct['store_2'] = 0;
                }
                $addrProduct[] = $this->transformToInsert($newProduct);
                $slugs[$old->product_slug] = true;

                if ($old->product_discountPrice == 0 || $old->product_regularPrice == 0) {
                    $discount_percent = 0;
                } else {
                    $discount_percent = 100 - (($old->product_discountPrice / $old->product_regularPrice) * 100);
                }

                $newProductPrice = [
                    'product_id' => $old->product_id,
                    'price_list' => $old->product_regularPrice,
                    'price_sale' => $old->product_discountPrice,
                    'price_cart' => $old->product_just4u_price ?? 0,
                    'discount_percent' => $discount_percent,
                    'price_list_original' => $old->product_regularPrice,
                    'price_sale_original' => $old->product_discountPrice,
                    'store' => 0,
                ];

                //alom
                //DB::table('product_price')->insert($newProductPrice);
                $addr[] = "('".$newProductPrice['product_id']."', '".$newProductPrice['price_list']."', '".$newProductPrice['price_sale']."', '".$newProductPrice['price_cart']."', '".$newProductPrice['discount_percent']."', ".$newProductPrice['price_list_original'].', '.$newProductPrice['price_sale_original'].', '.$newProductPrice['store'].')';
                if ($old->product_type == 1) {
                    //nagyker
                    $addr[] = "('".$newProductPrice['product_id']."', '".$newProductPrice['price_list']."', '".$newProductPrice['price_sale']."', '".$newProductPrice['price_cart']."', '".$newProductPrice['discount_percent']."', ".$newProductPrice['price_list_original'].', '.$newProductPrice['price_sale_original'].', 2)';
                    //olcso
                    $olcsoProductPrice = $newProductPrice;
                    $olcsoProductPrice['discount_percent'] = 25;
                    $olcsoProductPrice['price_sale'] = round($olcsoProductPrice['price_list'] - (($olcsoProductPrice['price_list'] / 100) * $olcsoProductPrice['discount_percent']));
                    $olcsoProductPrice['price_sale_original'] = $olcsoProductPrice['price_sale'];
                    $olcsoProductPrice['price_list_original'] = $olcsoProductPrice['price_list'];
                    $olcsoProductPrice['store'] = 1;
                    //DB::table('product_price')->insert($olcsoProductPrice);
                    $addr[] = "('".$olcsoProductPrice['product_id']."', '".$olcsoProductPrice['price_list']."', '".$olcsoProductPrice['price_sale']."', '".$olcsoProductPrice['price_cart']."', '".$olcsoProductPrice['discount_percent']."', ".$olcsoProductPrice['price_list_original'].', '.$olcsoProductPrice['price_sale_original'].', '.$olcsoProductPrice['store'].')';
                }
                $resp[] = 'price';
            }
            $sql = '
            INSERT INTO `product` ('.$this->transformToFieldList($newProduct).')
            VALUES '.implode(', ', $addrProduct).'
            ';
            DB::statement($sql);
            $addrProduct = [];
            $sql = '
            INSERT INTO `product_price` (product_id, price_list, price_sale, price_cart, discount_percent, price_list_original, price_sale_original, store)
            VALUES '.implode(', ', $addr).'
            ';
            DB::statement($sql);
            $addr = [];
            $slugs = [];
            //$this->resp[]='old '.count($oldproducts);
            $this->dispatchBrowserEvent('continue');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott '.count($resp ?? []);
        }
        $this->page += 1;
    }

    //meta field id: 1-Kiadó 2-Terjedelem 3-Év
    public function fixProducts()
    {
        $this->take = 100;
        $this->all = OldProduct::
            //where('product_stock', 0)
            where('product_status', 1)
                                ->where('product_stockType', 3)
                                ->count() / $this->take;

        $oldproducts = $this->page >= 0 ? OldProduct::where('product_status', 1)
                                                    ->where('product_stockType', 3)->skip(($this->page * $this->take))->take($this->take)->get() : null;

        if ($this->running ?? false) {
            foreach ($oldproducts as $old) {
                // $prod  = DB::table('product')->select('id', 'state', 'slug')->where('slug', $old->product_slug)->get();

                //if ($prod[0] ?? FALSE) {
                //DB::select(DB::raw("UPDATE product SET state = 1 WHERE slug = '". $old->product_slug."'"));
                DB::statement("UPDATE product SET state = 1 WHERE slug = '".$old->product_slug."'");
                // }
            }
            $this->dispatchBrowserEvent('continueXXX');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott '.count($resp ?? []);
        }
        $this->page += 1;
    }

    public function migrateProductImage()
    {
        $this->take = 200;
        $this->all = OldImage::count() / $this->take;

        $olditems = $this->page >= 0 ? OldImage::skip($this->page * $this->take)->take($this->take)->get() : null;

        if ($this->running ?? false) {
            foreach ($olditems as $old) {
                $product = Product::find($old->image_product_id);
                if ($product ?? false) {
                    if (Storage::exists('public/product/'.$old->image_filename.''.$old->image_ext) && ! Storage::exists('public/product/cover/'.$old->image_filename.'_'.$old->image_width.'-'.$old->image_height.$old->image_ext)) {
                        Storage::copy('public/product/'.$old->image_filename.''.$old->image_ext,
                            'public/product/cover/'.$old->image_filename.'_'.$old->image_width.'-'.$old->image_height.$old->image_ext);
                        $product->cover = 'product/cover/'.$old->image_filename.'_'.$old->image_width.'-'.$old->image_height.$old->image_ext;
                        $product->save();
                    }
                }
            }
            $this->dispatchBrowserEvent('continueImage');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    public function migrateProductImage2()
    {
        $this->take = 200;
        $this->all = OldImage::count() / $this->take;

        $olditems = $this->page >= 0 ? OldImage::skip($this->page * $this->take)->take($this->take)->get() : null;

        if ($this->running ?? false) {
            foreach ($olditems as $old) {
                $product = Product::find($old->image_product_id);
                if ($product ?? false) {
                    //if (Storage::exists('public/product/'.$old->image_filename.''.$old->image_ext) && !Storage::exists('public/product/cover/'.$old->image_filename.'_'.$old->image_width.'-'.$old->image_height.$old->image_ext)) {
                    //Storage::copy('public/product/'.$old->image_filename.''.$old->image_ext, 'public/product/cover/'.$old->image_filename.'_'.$old->image_width.'-'.$old->image_height.$old->image_ext);
                    $product->cover = 'product/cover/'.$old->image_filename.'_'.$old->image_width.'-'.$old->image_height.$old->image_ext;
                    $product->save();
                    //}
                }
            }
            $this->dispatchBrowserEvent('continueImage2');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }

        $this->page += 1;
    }

    public function migrateProductMeta()
    {
        $this->take = 1000;
        $olds = DB::select(DB::raw(' SELECT COUNT(*) as count FROM old_meta_value '));
        $this->all = $olds[0]->count / $this->take;
        $publishers = Publisher::all();
        foreach ($publishers as $pub) {
            $publisher[$pub->title] = $pub->id;
        }
        $olds = $this->page >= 0 ? DB::select(DB::raw(' SELECT * FROM old_meta_value ORDER BY meta_value_product_id ASC LIMIT '.($this->page * $this->take).', '.$this->take.' ')) : null;
        if ($this->running ?? false) {
            $productToChange = [];
            foreach ($olds as $old) {
                //$product = Product::find($old->meta_value_product_id);
                //if ($product ?? false){
                if ($old->meta_value_meta_field_id == 2) { //oldalszam
                    //$product->number_of_pages = intval($old->old_meta_value);
                    //$product->save();
                    $productToChange[$old->meta_value_product_id]['number_of_pages'] = intval($old->old_meta_value);
                }
                if ($old->meta_value_meta_field_id == 1) { //kiado
                    if (isset($publisher[$old->old_meta_value])) {
                        //$product->publisher_id = $publisher[$old->old_meta_value];
                        $productToChange[$old->meta_value_product_id]['publisher_id'] = $publisher[$old->old_meta_value];
                    } else {
                        $sql = "
                            INSERT INTO `publishers` (title, description, status)
                            VALUES ('".str_replace("'", "\'", $old->old_meta_value)."', '', 1)
                            ";
                        DB::statement($sql);
                        $publishers = Publisher::all();
                        foreach ($publishers as $pub) {
                            $publisher[$pub->title] = $pub->id;
                        }
                        $productToChange[$old->meta_value_product_id]['publisher_id'] = $publisher[$old->old_meta_value];
                        //$productToChange[$old->meta_value_product_id]['publisher_name'] = $old->old_meta_value;
                    }

                    //$product->save();
                }
                if ($old->meta_value_meta_field_id == 3) { //kiadas_eve
                    //$product->release_year = $old->old_meta_value;
                    $productToChange[$old->meta_value_product_id]['release_year'] = intval(substr($old->old_meta_value,
                        0, 4));
                    //$product->save();
                }
                //}
            }

            $publishers = Publisher::all();
            foreach ($publishers as $pub) {
                $publisher[$pub->title] = $pub->id;
            }
            foreach ($productToChange as $id => $p) {
                $product = Product::select('id')->find($id);
                if ($product ?? false) {
                    $product->number_of_pages = $p['number_of_pages'] ?? null;
                    if ($p['publisher_id'] ?? false) {
                        $product->publisher_id = $p['publisher_id'];
                    } else {
                        $product->publisher_id = $publisher[($p['publisher_name'] ?? '')] ?? null;
                    }
                    $product->release_year = $p['release_year'] ?? null;
                    $product->save();
                }
            }

            $this->dispatchBrowserEvent('continueMeta');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    //====USERS=====

    public function migrateUsers()
    {
        $map = [
            1 => 'lastname',
            2 => 'firstname',
            4 => 'phone',
            17 => 'author_follow_up',
        ];

        $mapAddress = [
            1 => 'last_name',
            2 => 'first_name',
            4 => 'address_phone',
            // type billing
            5 => 'address',
            6 => 'city',
            7 => 'zip_code',
            //            8 => 'billing-phone',
            // type shipping
            9 => 'address',
            10 => 'city',
            11 => 'zip_code',
            //            12 => 'delivery-msg',
            //            13 => 'delivery-company',
            // entity_type 2
            14 => 'business_name',
            15 => 'vat_number',
            18 => 'country_id',
            // type shipping
            19 => 'country_id',
        ];

        $countries = DB::table('countries')->select('id', 'code')->get();
        $this->take = 1000;
        $olds = DB::select(DB::raw(' SELECT COUNT(*) as count FROM old_olcso_user '));
        $this->all = $olds[0]->count / $this->take;

        $olds = $this->page >= 0 ? DB::select(DB::raw(' SELECT * FROM old_olcso_user LIMIT '.$this->page * $this->take.', '.$this->take.' ')) : null;
        if ($this->running ?? false) {
            foreach ($olds as $old) {
                if ($old->user_deleted !== 1) {
                    $new = [
                        'old_id' => $old->user_id,
                        'email' => 'QQQ_'.$old->user_email,
                        'password' => $old->user_password_hash,
                        'firstname' => '',
                        'lastname' => '',
                        'phone' => '',
                        'marketing_accepted' => 1,
                        'tac_accepted' => 1,
                        'author_follow_up' => 1,
                        'status' => $old->user_active,
                        'store' => 1,
                        'created_at' => $old->user_createdate,
                        'updated_at' => $old->user_modifydate,
                    ];

                    $address = [
                        'last_name' => '',
                        'first_name' => '',
                        'business_name' => '',
                        'vat_number' => '',
                        'city' => 'Üres',
                        'zip_code' => '',
                        'address' => '',
                        'address_phone' => '',
                        'address_email' => 'QQQ_'.$old->user_email,
                        'comment' => '',
                        'country_id' => $countries->where('code', 'HU')->first()->id,
                        'type' => 'billing',
                        'role' => 'customer',
                        'entity_type' => 1,
                        'role_id' => $old->user_id,
                    ];

                    $oldFields = DB::select(DB::raw('SELECT * FROM old_olcso_user_field_value WHERE user_field_value_user_id='.$old->user_id));
                    foreach ($oldFields as $oField) { //meta_value_meta_field_id
                        // $oField->user_field_value_field_id
                        if (isset($map[$oField->user_field_value_field_id])) {
                            $new[$map[$oField->user_field_value_field_id]] = $oField->user_field_value_value;
                        }
                        if (isset($mapAddress[$oField->user_field_value_field_id])) {
                            $address[$mapAddress[$oField->user_field_value_field_id]] = $oField->user_field_value_value;
                            if (in_array($oField->user_field_value_field_id, [5, 6, 7])) {
                                $address['type'] = 'billing';
                            }
                            if (in_array($oField->user_field_value_field_id, [8, 9, 10])) {
                                $address['type'] = 'shipping';
                            }
                            if (in_array($oField->user_field_value_field_id, [14, 15])) {
                                $address['entity_type'] = 2;
                            }
                            if (in_array($oField->user_field_value_field_id, [18, 19])) {
                                $address['country_id'] = $countries->where('code',
                                    $oField->user_field_value_value)->first()?->id ?? $countries->where('code',
                                        'HU')->first()->id;
                            }
                            $address['address'] = Str::limit($address['address'], 250);
                        }
                    }
                    $news[] = $this->transformToInsert($new);
                    $addresses[] = $this->transformToInsert($address);
                }
            }

            $sql = '
            INSERT INTO `customers` (
               '.$this->transformToFieldList($new).'
            )
            VALUES '.implode(', ', $news).'
            ';
            $sqlAddress = '
            INSERT INTO `addresses` (
               '.$this->transformToFieldList($address).'
            )
            VALUES '.implode(', ', $addresses).'
            ';
            //            DB::transaction(function () use ($sql, $sqlAddress) {
            //                DB::statement($sql);
            //                DB::statement($sqlAddress);
            //                $this->dispatchBrowserEvent('continueUser');
            //            });
            DB::beginTransaction();

            try {
                DB::statement($sql);
                DB::statement($sqlAddress);

                DB::commit();
                $this->dispatchBrowserEvent('continueUser');
            } catch (Exception $e) {
                DB::rollback();
            }
        }

        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    //=====ORDERS=====

    public function migrateOrders()
    {
        /*

===Régi Fizetési státusz===
1 várakozó kártyás => 0
2 Elutasított kártyás => 1
2 < Teljesített kártyás => 3


Régi order_payment
1 - Kártyás  => card
2 - Utánvét  => cash
3 - Előreutalás => transfer
4 - Fizetés átvételkor => cash_on_delivery


Régi order_status
1 - Várakozó =>1
2 - Összekészítve => 3
3 - Szállítás alatt => 4
4 - Átvehető  =>5
5 - Teljesítve  => 6
6 - Törölve   => 8

 store <= edit	1	BUDAPEST - INGYENES SZEMÉLYES ÁTVÉTEL	A budapesti álomgyár könyvesboltban (Blaha - 1072 Budapest, Rákóczi út 42.)	inperson1	3	0	1	0	personal
 store <= edit	2	DEBRECEN - INGYENES SZEMÉLYES ÁTVÉTEL	A debreceni álomgyár könyvesboltban (4025 Debrecen, Piac utca 49-51.)	inperson2	3	0	1	0	personal
 home <= edit	3	Futárszolgálat	Kiszállítás a megadott szállíási címre	post	1	890	1	0	post
 box < = edit	4	Csomagpont	Kiszállítás a kiválasztott csomagpontra	pickup	2	890	1	0	pickup
 store <= edit	5	SZEGED - INGYENES SZEMÉLYES ÁTVÉTEL	A szegedi álomgyár könyvesboltban (6720 Szeged, Jókai utca 7-9. (nagyáruház passzázs))	inperson3	4	0	0	0	personal
 store <= edit	6	ESZTERGOM - INGYENES SZEMÉLYES ÁTVÉTEL	Az esztergomi álomgyár könyvesboltban (2500 Esztergom, Széchenyi tér 21.)	inperson4	5	0	0	0	personal
 store <= edit	7	MISKOLC - INGYENES SZEMÉLYES ÁTVÉTEL	A miskolci álomgyár könyvesboltban (3520 Miskolc, Széchenyi István út 25.)	inperson5	6	0	0	0	personal
 store <= edit	8	SZEKSZÁRD - INGYENES SZEMÉLYES ÁTVÉTEL	A szekszárdi álomgyár könyvesboltban (7100 Szekszárd, Széchenyi utca 26.)	inperson6	7	0	0	0	personal
 store <= edit	9	KAZINCBARCIKA - INGYENES SZEMÉLYES ÁTVÉTEL	A kazincbarcikai álomgyár könyvesboltban (3700 Kazincbarcika, Egressy út 22.)	inperson7	8	0	0	0	personal
 store <= edit	10	BÉKÉSCSABA - INGYENES SZEMÉLYES ÁTVÉTEL	A bék


===The new Order===
    const STATUS_DRAFT                = 0;
    const STATUS_NEW                  = 1;
    const STATUS_PROCESSING           = 2;
    const STATUS_WAITING_FOR_SHIPPING = 3;
    const STATUS_SHIPPING             = 4;
    const STATUS_LANDED               = 5;
    const STATUS_COMPLETED            = 6;
    const STATUS_RETURNED             = 7;
    const STATUS_DELETED              = 8;

    const STATUS_PAYMENT_WAITING   = 0;
    const STATUS_PAYMENT_ERROR     = 1;
    const STATUS_PAYMENT_CANCELLED = 2;
    const STATUS_PAYMENT_PAID      = 3;', PackageNameComponent::class);

    //home, box, store
    //card, cash, transfer
*/
        $this->take = 100;
        $from = '2021-09-01 00:00:01';

        $order_status = [0 => 0, 1 => 1, 2 => 3, 3 => 4, 4 => 5, 5 => 6, 6 => 8];
        $payment_status = [0 => 0, 1 => 0, 2 => 1, 3 => 3, 4 => 3, 5 => 3];
        $payment_method = [0 => 'card', 1 => 'card', 2 => 'cash', 3 => 'transfer', 4 => 'cash_on_delivery'];
        $delivery_method = [
            1 => 'shop', 2 => 'shop', 3 => 'home', 4 => 'box', 5 => 'shop', 6 => 'shop', 7 => 'shop', 8 => 'shop',
            9 => 'shop', 10 => 'shop',
        ]; //frissíteni kell a régi álomgyár delivery táblából

        $this->all = DB::table('old_order')->where('order_createdate', '>', $from)->count() / $this->take;

        $country = Country::where('name', 'Magyarország')->first();

        $paymentmethods = paymentMethod::all();
        foreach ($paymentmethods as $sm) {
            $payments[$sm->method_id] = $sm->id;
        }
        $shippingmethods = ShippingMethod::all();
        foreach ($shippingmethods as $sm) {
            $shippings[$sm->method_id] = $sm->id;
        }
        $countries = Country::all();
        foreach ($countries as $c) {
            $country[$c->code] = $c->id;
        }

        $oldorders = DB::table('old_olcso_order')
                       ->select('old_cart.cart_user_id', 'old_order.*')
                       ->leftJoin('old_cart', function ($join) {
                           $join->on('old_order.order_cart_id', '=', 'old_cart.cart_id')->whereNotNull('old_cart.cart_user_id');
                       })
                       ->where('order_createdate', '>', $from)->skip($this->page * $this->take)->take($this->take)->get();

        if ($this->running ?? false) {
            foreach ($oldorders as $old) {
                if ($old->order_code == null) {
                    continue;
                }
                $this->newOrder = new Order(); // The New Order...

                //$this->newOrder->id = $old->order_id;
                $this->newOrder->status = $order_status[$old->order_status];
                $this->newOrder->payment_status = $payment_status[$old->order_payment_status];
                if ($this->newOrder->status == 6) {
                    $this->newOrder->payment_status = 3;
                }
                $this->newOrder->shipping_fee = $old->order_delivery_cost ?? 0;
                $this->newOrder->payment_fee = 0;
                $this->newOrder->store = 0; // Álomgyár
                $this->newOrder->order_number = $old->order_code;

                if (! isset($payment_method[$old->order_payment])) {
                    dd($old);
                }
                $this->newOrder->country_id = $country->id;
                $this->newOrder->payment_method_id = $payments[$payment_method[$old->order_payment]];

                $this->newOrder->shipping_method_id = $shippings[$delivery_method[$old->order_delivery] ?? 'shop'];
                $this->newOrder->shipping_data = $old->order_delivery_data;

                $this->newOrder->created_at = $old->order_createdate == '0000-00-00 00:00:00' ? null : $old->order_createdate;
                $this->newOrder->updated_at = $old->order_modifydate == '0000-00-00 00:00:00' ? null : $old->order_modifydate;
                $this->newOrder->email = '';
                $this->newOrder->total_amount = $old->order_total;

                $this->newOrder->total_quantity = 1;
                $this->newOrder->has_ebook = 0;
                $this->newOrder->old_customer_id = $old->cart_user_id;
                $this->newOrder->customer_id = null;

                if ($this->newOrder->save()) {
                    $orderitems = DB::table('old_product_to_cart')->where('product_to_cart_cart_id', $old->order_cart_id)->get();
                    $total_quantity = 0;
                    $has_ebook = $not_only_ebook = 0;
                    foreach ($orderitems as $item) {
                        $product = Product::where('id', $item->product_to_cart_product_id)->select('type')->first();
                        if ($product ?? false) {
                            if ($product->type ?? 0 == 1) {
                                $has_ebook = 1;
                            } else {
                                $not_only_ebook = true;
                            }
                            $total_quantity += $item->product_to_cart_amount;

                            $newOrderItem = [
                                'order_id' => $this->newOrder->id,
                                'product_id' => $item->product_to_cart_product_id,
                                'quantity' => $item->product_to_cart_amount,
                                'price' => $item->product_to_cart_price,
                                'original_price' => $item->product_to_cart_price,
                                'cart_price' => 0,
                                'total' => $item->product_to_cart_price * $item->product_to_cart_amount,
                            ];
                            DB::table('order_items')->insert($newOrderItem);
                        }
                    }
                    if ($not_only_ebook != true) {
                        $this->newOrder->status = 6;
                    }
                    $this->newOrder->total_amount = $old->order_total;
                    $this->newOrder->total_quantity = $total_quantity;
                    $this->newOrder->has_ebook = $has_ebook;
                    $this->newOrder->save();

                    $type = [1 => 'shipping', 2 => 'billing'];
                    $old_address = DB::select(DB::raw('SELECT old_address.* FROM old_address  WHERE old_address.address_order_id = '.$old->order_id.' '));
                    foreach ($old_address as $address) {
                        $addr[] = "('".$address->address_lastname."', '".$address->address_fistname."', '".str_replace("'",
                            "\'",
                            $address->address_city)."', '".$address->address_zipcode."', '".str_replace("'",
                                "\'", $address->address_line1)."', '".str_replace("'", '',
                                    $address->address_phone)."', '".$address->address_email."', '".str_replace([
                                        ':', "'",
                                    ], ['', "\'"],
                                        $address->address_msg)."', '".$country[$address->address_country_code]."', '".$type[$address->address_type]."', 'order', '".($address->address_forcompany == 1 ? 2 : 1)."', '".$this->newOrder->id."')";
                    }
                    $sql = '
                    INSERT INTO `addresses` (last_name, first_name, city, zip_code, address, address_phone, address_email, comment, country_id, type, role, entity_type, role_id)
                    VALUES '.implode(', ', $addr).' ';

                    try {
                        DB::statement($sql);
                    } catch (Exception $e) {
                    }

                    $addr = [];
                }
            }
            $this->dispatchBrowserEvent('continueOrder');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    public function migrateSocial()
    {
        $this->take = 100;
        $olds = DB::select(DB::raw(' SELECT COUNT(*) as count FROM old_olcso_social'));
        $this->all = $olds[0]->count / $this->take;

        DB::table('customers')->whereNull('email_verified_at')->update(['email_verified_at' => now()]);
        $olds = $this->page >= 0 ? DB::select(DB::raw(' SELECT * FROM old_olcso_social LIMIT '.$this->page * $this->take.', '.$this->take.' ')) : null;
        if ($this->running ?? false) {
            foreach ($olds as $old) {
                DB::table('customers')
                  ->where('old_id', $old->social_user_id)
                  ->where('store', 1)
                  ->update(['provider_id' => $old->social_code]);
            }
            $this->dispatchBrowserEvent('continueSocial');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    public function migrateOrdersCustomers()
    {
        $this->take = 100;
        $customersCount = DB::table('customers')
                            ->where('store', 0)
                            ->where('id', '<', 71500)
                            ->where('id', '>', 28237)
                            ->count();
        $this->all = $customersCount / $this->take;

        $customers = $this->page >= 0
            ? DB::select(DB::raw('SELECT * FROM customers WHERE store = 0 AND id > 28237 AND id < 71500 ORDER BY id ASC LIMIT '.$this->page * $this->take.', '.$this->take.' '))
            : null;
        if ($this->running ?? false) {
            foreach ($customers as $customer) {
                DB::select(DB::raw("UPDATE addresses SET role_id = $customer->id WHERE role_id = $customer->old_id AND id < 71500 "));
                //                DB::table('addresses')
                //                  ->where('role_id', $customer->old_id)
                //                  ->where('id', '>', 71501)
                //                  ->orderBy('id', 'ASC')
                //                  ->update(['role_id' => $customer->id]);

                //                DB::select(DB::raw("UPDATE orders WHERE old_customer_id = $customer->old_id AND store = 0 SET customer_id = $customer->id "));
                //                DB::table('orders')
                //                  ->where('old_customer_id', $customer->old_id)
                //                  ->where('store', 1)
                //                  ->update(['customer_id' => $customer->id]);
            }
            $this->dispatchBrowserEvent('continueOrdersCustomers');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    //=====EBOOK ORDERS=====

    public function migrateEbookOrders()
    {
        $this->take = 100;

        $order_status = [0 => 0, 1 => 1, 2 => 3, 3 => 4, 4 => 5, 5 => 6, 6 => 8];
        $payment_status = [0 => 0, 1 => 0, 2 => 1, 3 => 3, 4 => 3, 5 => 3];
        $payment_method = [0 => 'card', 1 => 'card', 2 => 'cash', 3 => 'transfer', 4 => 'cash_on_delivery'];
        $delivery_method = [
            1 => 'shop', 2 => 'shop', 3 => 'home', 4 => 'box', 5 => 'shop', 6 => 'shop', 7 => 'shop', 8 => 'shop',
            9 => 'shop', 10 => 'shop',
        ]; //frissíteni kell a régi álomgyár delivery táblából

        $this->all = OldOrder::whereNull('order_delivery_data')
                             ->where('order_payment_status', 3)
                             ->where('order_payment', 1)
                             ->where('order_delivery_cost', 0)
                             ->count() / $this->take;

        $country = Country::where('name', 'Magyarország')->first();
        $customers = DB::table('customers')->select('id', 'old_id')->get();

        $paymentmethods = paymentMethod::all();
        foreach ($paymentmethods as $sm) {
            $payments[$sm->method_id] = $sm->id;
        }
        $shippingmethods = ShippingMethod::all();
        foreach ($shippingmethods as $sm) {
            $shippings[$sm->method_id] = $sm->id;
        }
        $countries = Country::all();
        foreach ($countries as $c) {
            $country[$c->code] = $c->id;
        }

        $olditems = OldOrder::select('old_cart.cart_user_id', 'old_order.*')
                            ->whereNull('order_delivery_data')
                            ->where('order_payment_status', 3)
                            ->where('order_payment', 1)
                            ->where('order_delivery_cost', 0)
                            ->leftJoin('old_cart', function ($join) {
                                $join->on('old_order.order_cart_id', '=',
                                    'old_cart.cart_id')->whereNotNull('old_cart.cart_user_id');
                            })
                            ->skip(($this->page * $this->take) - $this->take)->take($this->take)->get();

        if ($this->running ?? false) {
            foreach ($olditems as $old) {
                if ($old->order_code == null) {
                    continue;
                }
                $this->newOrder = new Order(); // The New Order...

                //$this->newOrder->id = $old->order_id;
                $this->newOrder->status = $order_status[$old->order_status];
                $this->newOrder->payment_status = $payment_status[$old->order_payment_status];
                if ($this->newOrder->status == 6) {
                    $this->newOrder->payment_status = 3;
                }
                $this->newOrder->shipping_fee = $old->order_delivery_cost ?? 0;
                $this->newOrder->payment_fee = 0;
                $this->newOrder->store = 0;
                $this->newOrder->order_number = $old->order_code;

                if (! isset($payment_method[$old->order_payment])) {
                    // dd($old);
                }
                $this->newOrder->country_id = $country->id;
                $this->newOrder->payment_method_id = $payments[$payment_method[$old->order_payment]];

                $this->newOrder->shipping_method_id = $shippings[$delivery_method[$old->order_delivery] ?? 'shop'];
                $this->newOrder->shipping_data = $old->order_delivery_data;

                $this->newOrder->created_at = $old->order_createdate == '0000-00-00 00:00:00' ? null : $old->order_createdate;
                $this->newOrder->updated_at = $old->order_modifydate == '0000-00-00 00:00:00' ? null : $old->order_modifydate;
                $this->newOrder->email = '';
                $this->newOrder->total_amount = $old->order_total;

                $this->newOrder->total_quantity = 1;
                $this->newOrder->has_ebook = 0;

                $orderitems = OldOrderItem::where('product_to_cart_cart_id', $old->order_cart_id)->get();
                $total_quantity = 0;
                $has_ebook = $not_only_ebook = 0;
                foreach ($orderitems as $item) {
                    $product = Product::where('id', $item->product_to_cart_product_id)->select('type')->first();
                    if ($product ?? false) {
                        if ($product->type ?? 0 == 1) {
                            $has_ebook = 1;
                        } else {
                            $not_only_ebook = true;
                        }
                    }
                }
                if ($has_ebook) {
                    if ($this->newOrder->save()) {
                        //$orderitems = OldOrderItem::where('product_to_cart_cart_id', $old->order_cart_id)->get();
                        $total_quantity = 0;
                        $has_ebook = $not_only_ebook = 0;
                        foreach ($orderitems as $item) {
                            $product = Product::where('id', $item->product_to_cart_product_id)->select('type')->first();
                            if ($product ?? false) {
                                if ($product->type ?? 0 == 1) {
                                    $has_ebook = 1;
                                } else {
                                    $not_only_ebook = true;
                                }
                                $total_quantity += $item->product_to_cart_amount;

                                $newOrderItem = [
                                    'order_id' => $this->newOrder->id,
                                    'product_id' => $item->product_to_cart_product_id,
                                    'quantity' => $item->product_to_cart_amount,
                                    'price' => $item->product_to_cart_price,
                                    'original_price' => $item->product_to_cart_price,
                                    'cart_price' => 0,
                                    'total' => $item->product_to_cart_price * $item->product_to_cart_amount,
                                ];
                                DB::table('order_items')->create($newOrderItem);
                            }
                        }
                        if ($not_only_ebook != true) {
                            $this->newOrder->status = 6;
                        }
                        $this->newOrder->total_amount = $old->order_total;
                        $this->newOrder->total_quantity = $total_quantity;
                        $this->newOrder->has_ebook = $has_ebook;
                        $this->newOrder->customer_id = $customers->where('old_id', $old->cart_user_id)->first()?->id;
                        $this->newOrder->save();

                        $type = [1 => 'shipping', 2 => 'billing'];
                        $old_address = DB::select(DB::raw('SELECT old_address.* FROM old_address  WHERE old_address.address_order_id = '.$old->order_id.' '));
                        foreach ($old_address as $address) {
                            $addr[] = "('".$address->address_lastname."', '".$address->address_fistname."', '".str_replace("'",
                                "\'",
                                $address->address_city)."', '".$address->address_zipcode."', '".str_replace("'",
                                    "\'", $address->address_line1)."', '".str_replace("'", '',
                                        $address->address_phone)."', '".$address->address_email."', '".str_replace([
                                            ':', "'",
                                        ], ['', "\'"],
                                            $address->address_msg)."', '".$country[$address->address_country_code]."', '".$type[$address->address_type]."', 'order', '".($address->address_forcompany == 1 ? 2 : 1)."', '".$this->newOrder->id."')";
                        }
                        $sql = '
                        INSERT INTO `addresses` (last_name, first_name, city, zip_code, address, address_phone, address_email, comment, country_id, type, role, entity_type, role_id)
                        VALUES '.implode(', ', $addr).' ';
                        DB::statement($sql);
                        $addr = [];
                    }
                }
            }
            $this->dispatchBrowserEvent('continueEbookOrder');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    public function migrateAuthors()
    {
        $oldauthors = OldAuthor::get();
        foreach ($oldauthors as $old) {
            //try {
            $newAuthor = [
                'id' => $old->author_id,
                'title' => $old->author_name,
                'meta_title' => $old->author_name,
                'slug' => $old->author_slug,
                'description' => $old->author_bio,
                'meta_description' => $old->author_bio,
                'cover' => $old->author_image,
                'status' => 1,
                'created_at' => $old->author_createdate,
                'updated_at' => $old->author_modifydate == '0000-00-00 00:00:00' ? null : $old->author_modifydate,
            ];
            DB::table('author')->upsert($newAuthor);
            //} catch (\Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            //}

            //dd($newProduct);
        }
        $this->resp[] = 'old author '.count($oldauthors);
    }

    public function migratePublishers()
    {
        $olditems = OldPublisher::get();
        foreach ($olditems as $old) {
            $newItem = [
                'id' => $old->publisher_id,
                'title' => str_replace("'", "\'", $old->publisher_name),
                'status' => 1,
            ];
            DB::table('publishers')->insert($newItem);
        }
        $this->resp[] = 'Kiadók migrálva: '.count($olditems);
    }

    public function migrateOaddress()
    {
        $this->take = 3000;
        $olds = DB::select(DB::raw('
        SELECT COUNT(*) as count FROM old_address
        LEFT JOIN orders ON orders.id = address_order_id
        WHERE orders.id IS NOT NULL
        '));
        $this->all = $olds[0]->count / $this->take;

        if ($this->running ?? false) {
            $countries = Country::all();
            foreach ($countries as $c) {
                $country[$c->code] = $c->id;
            }

            $olds = $this->page >= 0 ? DB::select(DB::raw('
            SELECT old_address.* FROM old_address
            LEFT JOIN orders ON orders.order_number = address_order_id
            WHERE orders.id IS NOT NULL
            LIMIT '.($this->page * $this->take).', '.$this->take.'
            ')) : null;
            $type = [1 => 'shipping', 2 => 'billing'];
            foreach ($olds as $old) {
                $addr[] = "('".$old->address_lastname."', '".$old->address_fistname."', '".str_replace("'",
                    "\'", $old->address_city)."', '".$old->address_zipcode."', '".str_replace("'", "\'",
                        $old->address_line1)."', '".str_replace("'", '',
                            $old->address_phone)."', '".$old->address_email."', '".str_replace([':', "'"],
                                ['', "\'"],
                                $old->address_msg)."', '".$country[$old->address_country_code]."', '".$type[$old->address_type]."', 'order', '".($old->address_forcompany == 1 ? 2 : 1)."', '".$old->address_order_id."')";
            }
            $sql = '
            INSERT INTO `addresses` (last_name, first_name, city, zip_code, address, address_phone, address_email, comment, country_id, type, role, entity_type, role_id)
            VALUES '.implode(', ', $addr).'
            ';
            //$this->resp[]='asdasd '.$sql;
            DB::statement($sql);
            $addr = '';
            //$this->resp[]='old address '.count($olds);

            $this->dispatchBrowserEvent('continueOrderAddress');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }

        $this->page += 1;
    }

    public function migrateSubcatToProduct()
    {
        $this->take = 100;
        $olds = DB::select(DB::raw('
        SELECT COUNT(*) as count FROM old_category_to_product
        '));
        $this->all = $this->page >= 0 ? $olds[0]->count / $this->take : null;

        if ($this->running ?? false) {
            $olds = $this->page >= 0 ? DB::select(DB::raw('
            SELECT old_category_to_product.* FROM old_category_to_product
            LIMIT '.($this->page * $this->take).', '.$this->take.'
            ')) : null;

            foreach ($olds as $old) {
                $addr[] = "('".$old->category_to_product_id."', '".$old->category_to_product_product_id."', '".str_replace("'",
                    "\'", $old->category_to_product_category_id)."')";
            }
            $sql = '
            INSERT INTO `product_subcategory` (id, product_id, subcategory_id)
            VALUES '.implode(', ', $addr).'
            ';
            try {
                DB::statement($sql);
                $addr = '';
            } catch (Exception $e) {
                //echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
            $this->dispatchBrowserEvent('continueSubcatToProduct');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }

        $this->page += 1;
    }

    public function migrateAuthor()
    {
        $this->take = 3000;
        $olds = DB::select(DB::raw('
        SELECT COUNT(*) as count FROM old_author
        '));
        $this->all = $olds[0]->count / $this->take;

        if ($this->running ?? false) {
            $olds = DB::select(DB::raw('
            SELECT old_author.* FROM old_author
            LIMIT '.($this->page * $this->take) - $this->take.', '.$this->take.'
            '));

            foreach ($olds as $old) {
                $new = (object) [
                    'id' => $old->author_id,
                    'title' => $old->author_name,
                    'slug' => $old->author_slug,
                    'description' => $old->author_bio,
                    'cover' => $old->author_image,
                    'status' => 1,
                    'created_at' => $old->author_createdate,
                    'updated_at' => 'null',
                ];
                $addr[] = "('".$new->id."', '".str_replace("'", "\'",
                    $new->title)."', '".$new->slug."', '".str_replace("'", "\'",
                        $new->description)."', '".$new->cover."', '".$new->status."', '".$new->created_at."', ".$new->updated_at.')';
            }
            $sql = '
            INSERT INTO `author` (id, title, slug, description, cover, status, created_at, updated_at)
            VALUES '.implode(', ', $addr).'
            ';
            DB::statement($sql);
            $addr = '';

            $this->dispatchBrowserEvent('continueAuthor');
        }
        $this->running = true;

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    public function migrateCategory()
    {
        $this->take = 100;
        $olds = DB::select(DB::raw("
        SELECT COUNT(*) as count FROM old_category
        WHERE category_hidden = 0 AND category_deleted = 0 AND `category_name` NOT LIKE '%*%'
        "));
        $this->all = $olds[0]->count / $this->take;

        if ($this->running ?? false) {
            $olds = DB::select(DB::raw("
            SELECT old_category.* FROM old_category
            WHERE category_hidden = 0 AND category_deleted = 0 AND  `category_name` NOT LIKE '%*%'
            LIMIT ".($this->page * $this->take) - $this->take.', '.$this->take.'
            '));

            foreach ($olds as $old) {
                $new = (object) [
                    'id' => $old->category_id,
                    'title' => $old->category_name,
                    'slug' => $old->category_slug,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => 'null',
                    'deleted_at' => 'null',
                ];
                $addr[] = "('".$new->id."', '".str_replace("'", "\'",
                    $new->title)."', '".$new->slug."', '".$new->status."', '".$new->created_at."', ".$new->updated_at.', '.$new->deleted_at.')';
            }
            $sql = '
            INSERT INTO `subcategory` (id, title, slug, status, created_at, updated_at, deleted_at)
            VALUES '.implode(', ', $addr).'
            ';
            DB::statement($sql);
            $addr = '';

            $this->dispatchBrowserEvent('continueCategory');
        }

        if ($this->page > $this->all) {
            $this->running = false;
            $this->resp[] = 'Migráció sikeresen lefutott';
        }
        $this->page += 1;
    }

    protected function transformToInsert($array)
    {
        $resp = '(';
        foreach ($array as $field => $value) {
            if ($value != '') {
                $resp .= "'".str_replace(["'", "\'"], ["\'", ''], $value)."', ";
            } else {
                $resp .= 'null, ';
            }
        }
        $resp = str_replace(', ||', '', $resp.'||');

        return $resp.')';
    }

    protected function transformToFieldList($array)
    {
        $fields = [];
        foreach ($array as $field => $value) {
            array_push($fields, $field);
        }
        $resp = implode(', ', $fields);

        return $resp;
    }
}
