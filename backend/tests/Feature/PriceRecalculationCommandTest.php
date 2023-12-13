<?php

namespace Tests\Feature;

use Alomgyar\Products\Product;
use Alomgyar\Products\ProductPrice;
use Alomgyar\Promotions\Promotion;
use Alomgyar\Settings\Settings;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PriceRecalculationCommandTest extends TestCase
{
    use DatabaseTransactions;

    public $newProduct;

    public $basicProduct;

    public $promotionProduct;

    public $promotionProductSamePrice;

    public $promotion;

    public function createNewProduct()
    {
        $newProductData = [
            'title' => 'test new product',
            'description' => 'test desc',
            'slug' => 'test-new-product',
            'state' => Product::STATE_PRE, //normál, előjegyezhető, manuális(készlet)
            'status' => Product::STATUS_ACTIVE, //látható, nem látható
            'type' => Product::BOOK, //ebook, book, audio
            'store_0' => 1,
            'store_1' => 1,
            'store_2' => 1,
            'cover' => rand(1, 9999),
            'authors' => 'Fekete István',
            'published_before' => 0,
            'is_new' => 1,
        ];
        $this->newProduct = Product::create($newProductData);

        for ($i = 0; $i < 3; $i++) {
            $productPriceData = [
                'store' => $i,
                'product_id' => $this->newProduct->id,
                'discount_percent' => 10,
                'price_list' => 5000,
                'price_sale' => 4500,
                'price_cart' => 5000,
                'price_list_original' => 5000,
                'price_sale_original' => 4500,
            ];

            $productPrice = ProductPrice::create($productPriceData);
        }
    }

    public function createBasicProduct()
    {
        $basicProductData = [
            'title' => 'test basic product',
            'description' => 'test desc',
            'slug' => 'test-basic-product',
            'state' => Product::STATE_NORMAL, //normál, előjegyezhető, manuális(készlet)
            'status' => Product::STATUS_ACTIVE, //látható, nem látható
            'type' => Product::BOOK, //ebook, book, audio
            'store_0' => 1,
            'store_1' => 1,
            'store_2' => 1,
            'cover' => rand(1, 9999),
            'authors' => 'Fekete István',
            'published_before' => 0,
            'published_at' => '2022-12-12 10:10:10',
            'is_new' => 0,
        ];
        $this->basicProduct = Product::create($basicProductData);

        for ($i = 0; $i < 3; $i++) {
            $productPriceData = [
                'store' => $i,
                'product_id' => $this->basicProduct->id,
                'discount_percent' => 10,
                'price_list' => 5000,
                'price_sale' => 4500,
                'price_cart' => 5000,
                'price_list_original' => 5000,
                'price_sale_original' => 4500,
            ];

            $productPrice = ProductPrice::create($productPriceData);
        }
    }

    public function createPromotionalProduct()
    {
        $promotionProductData = [
            'title' => 'test promotion product',
            'description' => 'test desc',
            'slug' => 'test-promotion-product',
            'state' => Product::STATE_NORMAL, //normál, előjegyezhető, manuális(készlet)
            'status' => Product::STATUS_ACTIVE, //látható, nem látható
            'type' => Product::BOOK, //ebook, book, audio
            'store_0' => 1,
            'store_1' => 1,
            'store_2' => 1,
            'cover' => rand(1, 9999),
            'authors' => 'Fekete István',
            'published_before' => 0,
            'published_at' => '2022-12-12 10:10:10',
            'is_new' => 0,
        ];
        $this->promotionProduct = Product::create($promotionProductData);

        for ($i = 0; $i < 3; $i++) {
            $productPriceData = [
                'store' => $i,
                'product_id' => $this->promotionProduct->id,
                'discount_percent' => 10,
                'price_list' => 5000,
                'price_sale' => 4500,
                'price_cart' => 5000,
                'price_list_original' => 5000,
                'price_sale_original' => 4500,
            ];

            $productPrice = ProductPrice::create($productPriceData);
        }
    }

    public function createPromotionalProductSamePrice()
    {
        $promotionProductSamePriceData = [
            'title' => 'test promotion product same price',
            'description' => 'test desc',
            'slug' => 'test-promotion-product-same-price',
            'state' => Product::STATE_NORMAL, //normál, előjegyezhető, manuális(készlet)
            'status' => Product::STATUS_ACTIVE, //látható, nem látható
            'type' => Product::BOOK, //ebook, book, audio
            'store_0' => 1,
            'store_1' => 1,
            'store_2' => 1,
            'cover' => rand(1, 9999),
            'authors' => 'Fekete István',
            'published_before' => 0,
            'published_at' => '2022-12-12 10:10:10',
            'is_new' => 0,
        ];
        $this->promotionProductSamePrice = Product::create($promotionProductSamePriceData);

        for ($i = 0; $i < 3; $i++) {
            $productPriceData = [
                'store' => $i,
                'product_id' => $this->promotionProductSamePrice->id,
                'discount_percent' => 15,
                'price_list' => 999,
                'price_sale' => 849,
                'price_cart' => 999,
                'price_list_original' => 999,
                'price_sale_original' => 999,
            ];

            $productPrice = ProductPrice::create($productPriceData);
        }
    }

    public function createProducts()
    {
        $this->createNewProduct();
        $this->createBasicProduct();
        $this->createPromotionalProduct();
        $this->createPromotionalProductSamePrice();
    }

    public function createPromotion()
    {
        $this->createProducts();

        $promotionData = [
            'title' => 'TEST Promotion',
            'slug' => 'test-romotion',
            'meta_title' => 'TEST Promotion',
            'meta_description' => 'TEST Promotion',
            'cover' => 'TEST Promotion',
            'list_image_xl' => 'TEST Promotion',
            'list_image_sm' => 'TEST Promotion',
            'status' => Promotion::STATUS_ACTIVE,
            'store_0' => 1,
            'store_1' => 1,
            'store_2' => 1,
            //'order' => 'TEST Promotion',
            'active_from' => Carbon::now()->subMonth(),
            'active_to' => Carbon::now()->addMonth(),
        ];

        $this->promotion = Promotion::create($promotionData);

        $promotionProduct = [
            'promotion_id' => $this->promotion->id,
            'product_id' => $this->promotionProduct->id,
            'price_sale_0' => 1000,
            'price_sale_1' => 1000,
            'price_sale_2' => 1000,
        ];

        DB::table('promotion_product')->insert($promotionProduct);

        $promotionProductSamePrice = [
            'promotion_id' => $this->promotion->id,
            'product_id' => $this->promotionProductSamePrice->id,
            'price_sale_0' => 999,
            'price_sale_1' => 999,
            'price_sale_2' => 999,
        ];

        DB::table('promotion_product')->insert($promotionProductSamePrice);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPriceRecalculationCommandCases()
    {
        $this->createPromotion();

        $newAlomgyarDiscountRate = Settings::where('key', 'new_product_discount_alomgyar')->first()->primary;
        $defaultAlomgyarDiscountRate = Settings::where('key', 'default_discount_rate_alomgyar')->first()->primary;

        // ORIGINAL SETTED NEW PRODUCT PRICE
        $this->assertDatabaseHas('product_price', [
            'store' => 0,
            'product_id' => $this->newProduct->id,
            'discount_percent' => 10,
            'price_list' => 5000,
            'price_sale' => 4500,
            'price_cart' => 5000,
            'price_list_original' => 5000,
            'price_sale_original' => 4500,
        ]);

        // ORIGINAL SETTED BASIC PRODUCT PRICE
        $this->assertDatabaseHas('product_price', [
            'store' => 0,
            'product_id' => $this->basicProduct->id,
            'discount_percent' => 10,
            'price_list' => 5000,
            'price_sale' => 4500,
            'price_cart' => 5000,
            'price_list_original' => 5000,
            'price_sale_original' => 4500,
        ]);

        // ORIGINAL SETTED PROMOTION PRODUCT PRICE
        $this->assertDatabaseHas('product_price', [
            'store' => 0,
            'product_id' => $this->promotionProduct->id,
            'discount_percent' => 10,
            'price_list' => 5000,
            'price_sale' => 4500,
            'price_cart' => 5000,
            'price_list_original' => 5000,
            'price_sale_original' => 4500,
        ]);

        $this->assertDatabaseHas('promotion_product', [
            'promotion_id' => $this->promotion->id,
            'product_id' => $this->promotionProduct->id,
            'price_sale_0' => 1000,
            'price_sale_1' => 1000,
            'price_sale_2' => 1000,
        ]);

        $this->assertDatabaseHas('promotion_product', [
            'promotion_id' => $this->promotion->id,
            'product_id' => $this->promotionProductSamePrice->id,
            'price_sale_0' => 999,
            'price_sale_1' => 999,
            'price_sale_2' => 999,
        ]);

        // CALL COMMAND
        // CALL COMMAND
        // CALL COMMAND
        Artisan::call('calculate:prices');

        // CHECK NEW PRODUCT PRICE
        $this->assertDatabaseHas('product_price', [
            'store' => 0,
            'product_id' => $this->newProduct->id,
            'discount_percent' => (int) $newAlomgyarDiscountRate,
            'price_list' => 5000,
            'price_sale' => 5000 * (1 - ($newAlomgyarDiscountRate / 100)),
            //    'price_cart' => 5000,
            //    'price_list_original' => 5000,
            //    'price_sale_original' => 4500,
        ]);

        // CHECK BASIC PRODUCT PRICE
        $this->assertDatabaseHas('product_price', [
            'store' => 0,
            'product_id' => $this->basicProduct->id,
            'discount_percent' => (int) $defaultAlomgyarDiscountRate,
            'price_list' => 5000,
            'price_sale' => 5000 * (1 - ($defaultAlomgyarDiscountRate / 100)),
            // 'price_cart' => 5000,
            // 'price_list_original' => 5000,
            // 'price_sale_original' => 4500,
        ]);

        // CHECK PROMOTION PRODUCT PRICE
        $this->assertDatabaseHas('product_price', [
            'store' => 0,
            'product_id' => $this->promotionProduct->id,
            'discount_percent' => 80,
            'price_list' => 5000,
            'price_sale' => 1000,
            // 'price_cart' => 5000,
            // 'price_list_original' => 5000,
            // 'price_sale_original' => 4500,
        ]);

        // CHECK PROMOTION PRODUCT SAME PRICE
        $this->assertDatabaseHas('product_price', [
            'store' => 0,
            'product_id' => $this->promotionProductSamePrice->id,
            'discount_percent' => 0,
            'price_list' => 999,
            'price_sale' => 999,
            // 'price_cart' => 5000,
            // 'price_list_original' => 5000,
            // 'price_sale_original' => 4500,
        ]);
    }
}
