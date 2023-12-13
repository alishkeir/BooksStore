<?php

namespace Database\Seeders;

use Alomgyar\Carts\Cart;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            AuthorSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            ProductSeeder::class,
            ProductPriceSeeder::class,
            CategorySubcategoryPivotSeeder::class,
            ProductAuthorPivotSeeder::class,
            ProductSubcategoryPivotSeeder::class,
            PublisherSeeder::class,
            PromotionSeeder::class,
            PromotionProductPivotSeeder::class,
            PostSeeder::class,
            ShopSeeder::class,
            // BannerSeeder::class,
            /* Customers and Orders */
            CustomerSeeder::class,
            PaymentMethodSeeder::class,
            ShippingMethodSeeder::class,
            CountrySeeder::class,
            OrderSeeder::class,
            AddressSeeder::class,
            OrderItemSeeder::class,
            CustomerPreorderSeeder::class,
            CustomerWishlistSeeder::class,
            CustomerAuthorSeeder::class,
            ProductReviewSeeder::class,
            CommentSeeder::class,
            CouponSeeder::class,
            TemplateSeeder::class,
            /* Cart and Cart items */
            CartSeeder::class,
            CartItemsSeeder::class,
        ]);
    }
}
