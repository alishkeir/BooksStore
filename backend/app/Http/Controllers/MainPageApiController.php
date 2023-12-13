<?php

namespace App\Http\Controllers;

use Alomgyar\Banners\Models\Banner;
use Alomgyar\Carousels\Carousel;
use Alomgyar\Categories\Category;
use Alomgyar\Products\ApiProduct as Product;
use Alomgyar\Promotions\ApiPromotion as Promotion;
use Alomgyar\RankedProducts\Repository\RankedProductRepository;
use Alomgyar\Subcategories\Subcategory;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CarouselResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\PromotionListResource;
use App\Http\Resources\SubcategoryResource;
use App\Http\Traits\ErrorMessages;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class MainPageApiController extends Controller
{
    use ErrorMessages;

    private string $title;

    public function __invoke(RankedProductRepository $repository)
    {
        $this->title = 'Főoldal';

        return response([
            'data' => [
                'banner' => $this->getBanner(),
                'bestsellers' => $repository->getBestSellersForApi(request('store')),
                'best_discounted' => $repository->getBestDiscountedForApi(request('store')),
                'best_preorders' => $repository->getBestPreordersForApi(request('store')),
                'ebook_bestsellers' => $repository->getEbookBestSellersForApi(request('store')),
                'promotions' => $this->getPromotions(),
                'new_arrivals' => $this->getNewArrivals(),
                'home_promotion' => $this->getHomePromotion(),
                'home_category' => $this->getHomeCategory(),
                'all_categories' => $this->getCategories(),
                'carousels' => $this->getCarousels(),
                'shop_info' => [
                    'discount_rate' => option($this->getDiscountStoreSlug()),
                    'free_shopping_limit' => option(sprintf('free_shipping_limit_%s', $this->getStoreSlug())),
                ],
            ],
        ]);
    }

    private function getBanner()
    {
        $banner = Banner::where('shop_id', request('store'))->first();

        return new BannerResource($banner);
    }

    private function getPromotions()
    {
        $promotions = Promotion::active()->byStore()->orderBy('order')->take(3)->get();

        return [
            'promotions' => PromotionListResource::collection($promotions),
            'url_to_the_list' => '/akciok',
        ];
    }

    private function getNewArrivals()
    {
        $products = Product::thisStore()->active()->orderBy('published_at', 'DESC')->orderBy('orders_count_'.request('store'), 'DESC')->orderBy('product.id', 'ASC')->selectForList()->take(5)->get();

        return [
            'products' => ProductListResource::collection($products),
            'url_to_the_list' => '/ujdonsagok',
        ];
    }

    private function getHomePromotion()
    {
        $promotion = Promotion::active()->byStore()->orderBy('order')->first();

        return [
            'promotion' => new PromotionListResource($promotion),
        ];
    }

    private function getHomeCategory()
    {
        $subcategoryID = match ((int) request('store')) {
            0 => option('home_category_id_alomgyar'),
            1 => option('home_category_id_olcsokonyvek'),
            2 => option('home_category_id_nagyker'),
        };

        if ($subcategoryID) {
            $subcategory = Subcategory::find($subcategoryID);
            $productIDs = DB::table('product_subcategory')->where('subcategory_id',
                $subcategoryID)->take(5)->get()->pluck('product_id');
            $products = Product::select('id', 'slug', 'title', 'state', 'type', 'cover', 'is_new', 'authors',
                'published_at', 'publisher_id')
                                  ->thisStore()
                                  ->whereIn('id', $productIDs)
                                  ->active()
                                  ->orderBy('published_at', 'DESC')
                                  ->orderBy('orders_count_'.request('store'), 'DESC')
                                  ->take(5)
                                  ->get();

            return [
                'category' => new SubcategoryResource($subcategory),
                'products' => ProductListResource::collection($products),
                'url_to_the_list' => '/konyvlista/'.$subcategory->slug ?? '',
            ];
        }

        return [
            'category' => [],
            'products' => [],
            'url_to_the_list' => '',
        ];
    }

    private function getCategories()
    {
        $categories = Category::active()->orderBy('title', 'ASC')->get();

        return [
            'categories' => SubcategoryResource::collection($categories),
        ];
    }

    private function getCarousels(): array|AnonymousResourceCollection
    {
        $collection = Carousel::where([
            'status' => 1,
            'shop_id' => request('store'),
        ])->orderBy('order', 'ASC')->get();

        if (empty($collection)) {
            return [];
        }

        return CarouselResource::collection($collection);
    }

    private function getStoreSlug(): string
    {
        $storeId = (int) request('store');

        return match ($storeId) {
            0 => 'alomgyar',
            1 => 'olcsokonyvek',
            2 => 'nagyker',
        };
    }

    private function getDiscountStoreSlug(): string
    {
        $storeId = (int) request('store');

        return match ($storeId) {
            0 => 'new_product_discount_alomgyar',
            1 => 'default_discount_rate_olcsokonyvek',
            2 => 'default_discount_rate_nagyker',
        };
    }
}
