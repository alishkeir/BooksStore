<?php

namespace App\Console\Commands;

use Alomgyar\Products\Product;
use Alomgyar\Promotions\Promotion;
use Alomgyar\Promotions\Scopes\NotShowFlashDealScope;
use App\Helpers\StoreHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReCalculatePrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to recalculate the current prices for every product';

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
        $prices = [];
        $runForStores = [
            StoreHelper::ALOMGYAR,
            StoreHelper::OLCSOKONYVEK,
            StoreHelper::NAGYKER,
        ];  //0, 1, 2

        // UPDATE STATES, WHICH ARE NEW AND WHICH ARE NOT
        DB::statement('UPDATE product SET is_new = 1 WHERE published_at IS NULL AND state = '.Product::STATE_PRE.' AND published_before = 0;');
        DB::statement('UPDATE product SET is_new = 0 WHERE published_at IS NULL AND state = '.Product::STATE_PRE.' AND published_before = 1;');
        DB::statement("UPDATE product SET is_new = 1 WHERE published_at >= '".now()->subDays(4)."';");
        DB::statement("UPDATE product SET is_new = 0 WHERE published_at < '".now()->subDays(4)."';");

        // NEED TO INCLUDE FLASH DEALS ALSO
        $active_promotions = Promotion::withoutGlobalScope(NotShowFlashDealScope::class)->current()->pluck('id');

        $products = $this->queryProducts($active_promotions);

        // RUN FOR EACH PRODUCTS
        foreach ($products as $product) {
            // SKIP PRODUCTS if needed
            if ($product->do_not_update_price) {
                continue;
            }

            // RUN FOR STORES
            foreach ($runForStores as $store) {
                // INIT INFORMATIONS
                $price_list = $product->{'price_list_'.$store};

                // HANDLE IF THERE IS NO PROPER SHOP PRICE LIST
                // GET THE MAIN SHOPS PRICE
                if ($price_list == 0) {
                    $price_list = $product->price_list_0;
                }

                // IF THERE IS NO PRICE, SKIP THIS STEP AND DONT UPDATE PRICE
                if ($price_list == 0) {
                    continue;
                }

                $price_sale = $product->{'price_sale_'.$store};
                $new_price = $product->{'price_sale_'.$store};
                $defaultDiscountPrice = $this->getDefaultDiscountPrice($store, $price_list);

                // CHECK IF PRODUCT IS NEW

                if ($price_list == $price_sale || $price_sale == null || $price_sale == 0) {
                    $statement = "UPDATE product_price SET discount_percent = 0 where discount_percent > 0 AND product_id = {$product->id} AND store = {$store}";
                    DB::statement($statement);

                    continue;
                }

                // ha a product.published_at 4 napnál nem régebbi
                // összehasonlítani, hogy is_new false az else ágban meg true-e
                // 25%-kal csökkentett ár kisebb-e. ha igen, akkor ez a new_price
                // AND `product`.`title` NOT LIKE '%vászontáska%'
                if($product->discount_type === Product::DISCOUNT_TYPE_NEW_RATE){
                    $isNewDiscount = true;
                }else if($product->discount_type === Product::DISCOUNT_TYPE_DEFAULT_RATE){
                    $isNewDiscount = false;
                }else{
                    $isNewDiscount = strtotime($product->published_at) > time() - 345600;
                }

                if ($isNewDiscount and ! str_contains($product->title, Product::CANVAS_BAG_STRING)) {
                    $new_price = $this->caseProductIsNew($store, $price_list);
                } else {
                    // IF NOT NEW GET THE DEFAULT DISCOUNT RATE
                    $new_price = $this->caseProductIsNotNew($price_list, $price_sale, $defaultDiscountPrice, $product, $store, $active_promotions);
                }

                // CHECK FOR PRICES

                $differenceIsMoreThan1 = (abs($new_price - $price_sale) > 1);
                //do you feel the need to change?
                //if ($price_sale != $new_price) {
                if ($differenceIsMoreThan1) {
                    $prices[$store][] = [
                        'id' => $product->{'id_'.$store},
                        'product_id' => $product->id,
                        'price_list' => $price_list,
                        'price_sale' => $new_price,
                        //'price_sale_original'       => $new_price,
                        'discount_percent' => round(100 - (($new_price / $price_list) * 100)),
                    ];
                }
            }
        }

        // UNSET PRODUCTS FROM MEMORY
        unset($products);

        // RUN PRICE CHANGES
        $this->changePrices($prices);
    }

    public function getDefaultDiscountPrice($store, $price_list)
    {
        // GET DEFAULT DISCOUNT PRICE
        match ($store) {
            0 => $defaultProductDiscount = 1 - ((int) option('default_discount_rate_alomgyar', 0, true) / 100),
            1 => $defaultProductDiscount = 1 - ((int) option('default_discount_rate_olcsokonyvek', 0, true) / 100),
            default => $defaultProductDiscount = 1 - ((int) option('default_discount_rate_nagyker', 0, true) / 100),
        };
        $defaultDiscountPrice = (int) ($price_list * $defaultProductDiscount);

        return $defaultDiscountPrice;
    }

    public function queryProducts($active_promotions)
    {
        $select = '';
        $left_join = '';
        foreach ($active_promotions as $promotion) {
            $select .= '`prom_'.$promotion.'`.`price_sale_0` AS `prom_'.$promotion.'_0`, ';
            $select .= '`prom_'.$promotion.'`.`price_sale_1` AS `prom_'.$promotion.'_1`, ';
            $select .= '`prom_'.$promotion.'`.`price_sale_2` AS `prom_'.$promotion.'_2`, ';
            $left_join .= 'LEFT JOIN `promotion_product` AS `prom_'.$promotion.'` ON `product`.`id` = `prom_'.$promotion.'`.`product_id` AND `prom_'.$promotion.'`.`promotion_id` = '.$promotion.' ';
        }

        $products = DB::select(DB::raw('
        SELECT
            `product`.`id`,
            `product`.`is_new`,
            `product`.`title`,
            `product`.`do_not_update_price`,
            `product`.`discount_type`,
            `product`.`published_at`,
            '.$select.'
            `alom`.`price_list` AS `price_list_0`,
            `alom`.`price_sale` AS `price_sale_0`,
            `alom`.`id` AS `id_0`,
            `olcso`.`price_list` AS `price_list_1`,
            `olcso`.`price_sale` AS `price_sale_1`,
            `olcso`.`id` AS `id_1`,
            `nagyker`.`price_list` AS `price_list_2`,
            `nagyker`.`price_sale` AS `price_sale_2`,
            `nagyker`.`id` AS `id_2`
        FROM `product`
        '.$left_join.'
        LEFT JOIN `product_price` AS `olcso` ON `product`.`id` = `olcso`.`product_id` AND `olcso`.`store` = 1
        LEFT JOIN `product_price` AS `alom` ON `product`.`id` = `alom`.`product_id` AND `alom`.`store` = 0
        LEFT JOIN `product_price` AS `nagyker` ON `product`.`id` = `nagyker`.`product_id` AND `nagyker`.`store` = 2
        WHERE `product`.`deleted_at` is null
        AND `product`.`type` = 0
        '));

        return $products;
    }

    public function caseProductIsNew($store, $price_list)
    {
        match ($store) {
            0 => $newProductDiscount = 1 - ((int) option('new_product_discount_alomgyar', 0, true) / 100),
            1 => $newProductDiscount = 1 - ((int) option('new_product_discount_olcsokonyvek', 0, true) / 100),
            default => $newProductDiscount = 1 - ((int) option('new_product_discount_nagyker', 0, true) / 100),
        };

        //if ($new_price > $price_list * $newProductDiscount) {
        $new_price = $price_list * $newProductDiscount;

        return $new_price;
    }

    public function caseProductIsNotNew($price_list, $price_sale, $defaultDiscountPrice, $product, $store, $active_promotions)
    {
        //if sale is really a discounted price
        if ($price_sale == 0 || $price_sale > $price_list) {
            $new_price = $price_list;
        }

        // CHECK FOR PROMOTIONS
        // RUN ONCE, BUT GET BOTH DATA
        $promotionData = $this->checkForPromotions($active_promotions, $product, $store);
        $promotionPrice = $promotionData['promotionPrice'];
        $inPromotion = $promotionData['inPromotion'];

        // IF CANVAS, ONLY PROMOTION, OR DEFAULT PRICE LIST
        // IF NOT CANVAS, PROMOTION, OR DEFAULT DISCOUNT
        $givenPrice = $defaultDiscountPrice;
        if (str_contains($product->title, Product::CANVAS_BAG_STRING)) {
            $givenPrice = $price_list;
        }
        $new_price = $this->promotionOrGivenPrice($promotionPrice, $givenPrice, $inPromotion);

        return $new_price;
    }

    public function checkForPromotions($active_promotions, $product, $store)
    {
        $inPromotion = false;
        $promotionPrice = null;
        $lowestPromotionPrice = null;
        //have current promotion with lowest price?
        foreach ($active_promotions as $promotion) {
            // HAVE PROMOTION

            if ($product->{'prom_'.$promotion.'_'.$store} !== 0 && $product->{'prom_'.$promotion.'_'.$store} !== null) {
                // IN PROMOTION?
                $inPromotion = true;
                // GET PROMOTION PRICE
                $promotionPrice = $product->{'prom_'.$promotion.'_'.$store};
                if (! $lowestPromotionPrice) {
                    $lowestPromotionPrice = $promotionPrice;
                } elseif ($promotionPrice < $lowestPromotionPrice) {
                    $lowestPromotionPrice = $promotionPrice;
                }
            }

            // // OLD CHECK
            // if ($product->{"prom_" . $promotion . "_" . $store} != 0 && ($product->{"prom_" . $promotion . "_" . $store} ?? false) && $new_price > $product->{"prom_" . $promotion . "_" . $store}) {
            //     $new_price = $product->{"prom_" . $promotion . "_" . $store};
            // }
        }
        $promotionData = [
            'inPromotion' => $inPromotion,
            'promotionPrice' => $lowestPromotionPrice,
        ];

        return $promotionData;
    }

    public function promotionOrGivenPrice($promotionPrice, $givenPrice, $inPromotion)
    {
        // IF IN PROMOTION && HAS PROMOTION PRICE
        if ($promotionPrice !== 0 && $inPromotion) {
            // SET PROMOTION PRICE
            $new_price = $promotionPrice;
        } else {
            // SET THE DESIRED PRICE BACK
            // THIS CAN BE DEFAULT DISCOUNT, OR PRICE LIST
            // IT IS GIVEN AS A PARAMETER
            $new_price = $givenPrice;
        }

        return $new_price;
    }

    public function changePrices($prices)
    {
        $count = 0;
        //make a difference!
        foreach ($prices ?? [] as $store => $newPrices) {
            $update = [];
            foreach ($newPrices as $newPrice) {
                $count++;
                $update[] = '('.$newPrice['id'].', '.$newPrice['product_id'].', '.$newPrice['price_list'].', '.$newPrice['price_sale'].', '.$newPrice['discount_percent'].')';
            }

            $sql = '
            INSERT INTO `product_price` (id, product_id, price_list, price_sale, discount_percent)
            VALUES '.implode(', ', $update).'
            ON DUPLICATE KEY UPDATE price_list = VALUES(price_list), product_id = VALUES(product_id), price_sale = VALUES(price_sale), discount_percent = VALUES(discount_percent)
            ';
            DB::statement($sql);
        }

        echo date('m.d H:i:s').' '.$count.' products price is changed'.PHP_EOL;
        Log::channel('price-script')->info(date('m.d H:i:s').' '.$count.' products price is changed');
    }
}
