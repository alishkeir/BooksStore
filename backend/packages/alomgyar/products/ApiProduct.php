<?php
/*
Author: Hódi
Date: 2021. 04. 27. 15:28
Project: alomgyar-webshop-be
*/

namespace Alomgyar\Products;

use Alomgyar\Categories\ApiCategory;
use Alomgyar\Subcategories\ApiSubcategory;
use App\Http\Resources\CategoriesSubcategoriesResource;
use App\Http\Resources\ProductListResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class ApiProduct extends Product
{
    const PER_PAGE = 20;

    /**
     * @var string[]
     */
    public static array $sortingOptions = [
        'most-popular' => 'Legnépszerűbb',
        'release-year' => 'Megjelenés szerint',
        'price-asc' => 'Ár szerint növekvő',
        'price-desc' => 'Ár szerint csökkenő',
        'biggest-discount' => 'Legmagasabb kedvezmény',
    ];

    public static array $publishingFilters = [
        'normal' => 'Csak rendelhetők',
        'csak-az-ideiek' => 'Csak az ideiek',
        'csak-az-akciosak' => 'Csak az akciósak',
    ];

    public static function getSortBy($sortBy): array
    {
        return array_map(function ($key, $value) use ($sortBy) {
            return [
                'slug' => $key,
                'title' => $value,
                'selected' => $key == $sortBy,
            ];
        }, array_keys(ApiProduct::$sortingOptions), ApiProduct::$sortingOptions);
    }

    public static function getByPublishing($byPublishing): array
    {
        $promotion = request()->all()['body']['section'] === 'promotion' && isset(request()->all()['body']['section_params']['slug']);

        $keys = $promotion ? array_filter(array_keys(ApiProduct::$publishingFilters), function ($value) {
            return $value !== 'csak-az-akciosak';
        }) : array_keys(ApiProduct::$publishingFilters);

        $values = $promotion ? array_filter(ApiProduct::$publishingFilters, function ($key) {
            return $key !== 'csak-az-akciosak';
        }, ARRAY_FILTER_USE_KEY) : ApiProduct::$publishingFilters;

        return [
            'title' => 'Megjelenés szerint',
            'id' => 'by_publishing',
            'type' => 'checkbox',
            'data' => array_map(function ($key, $value) use ($byPublishing) {
                return [
                    'slug' => $key,
                    'title' => $value,
                    'selected' => ! empty($byPublishing) && in_array($key, $byPublishing),
                ];
            }, $keys, $values),
        ];
    }

    public static function getCategory()
    {
        return [
            'title' => 'Kategóriák szerint',
            'id' => 'category',
            'type' => 'radio',
            'data' => ApiCategory::select('id', 'slug', 'title')->orderBy('title', 'asc')->get()->transform(function (
                $item
            ) {
                $item->selected = false;
                if ($item->slug === request()['body']['filters']['category']) {
                    $item->selected = true;
                }

                return $item;
            }),
        ];
    }

    public static function getSubcategory()
    {
        $category = request()['body']['filters']['category'];

        return [
            'title' => 'Alkategóriák szerint',
            'id' => 'subcategory',
            'type' => 'tag',
            'data' => isset($category) ?
                ApiSubcategory::select('id', 'slug', 'title')
                              ->when($category && ApiCategory::whereSlug(request()['body']['filters']['category'])->exists(), function ($query) {
                                  $subcategories = ApiCategory::whereSlug(request()['body']['filters']['category'])->first()->subcategories->pluck('id');

                                  return $query->whereIn('id', $subcategories);
                              })
                              ->orderBy('title', 'asc')
                              ->get()
                              ->transform(function ($item) {
                                  $item->selected = false;
                                  if ($item->slug === request()['body']['filters']['subcategory']) {
                                      $item->selected = true;
                                  }

                                  return $item;
                              })
                : null,
        ];
    }

    public static function getCategoriesBySubcategories(Collection $subcategories): AnonymousResourceCollection
    {
        $categories = $subcategories->map(function ($item, $key) {
            return $item->categories->keyBy('id');
        });

        request()->merge(compact('subcategories'));

        return CategoriesSubcategoriesResource::collection($categories->flatten()->unique('id')->sortByDesc('orders_count_'.request('store'))->values());
    }

    public static function similarBooks($subcategories, $type, $productID): ResourceCollection
    {
        $products = $subcategories->map(function ($item, $key) use ($type, $productID) {
            return $item->products->where('type', $type)
                                  ->where('store_'.request('store'), 1)
                                  ->where('status', Product::STATUS_ACTIVE)
                                  ->where('id', '!=', $productID)
                                  ->keyBy('id');
        });

        return ProductListResource::collection($products->flatten()->unique('id')->sortByDesc('orders_count_'.request('store'))->take(5)->values());
    }

    public function scopePromotionalSuccessList($query)
    {
        return $query->select('product.id', 'product.slug', 'product.title', 'product.state', 'product.type',
            'product.cover', 'product.is_new')
                     ->with([
                         'prices' => function ($query) {
                             $query->where('store', request('store'));
                         },
                     ])
                     ->join('product_price', 'product.id', '=', 'product_price.product_id')
                     ->where('product_price.store', request('store'))
                     ->where('product_price.discount_percent', '>', 0)
                     ->active()
                     ->thisStore()
                     ->orderBy('orders_count_'.request('store'), 'DESC')
                     ->limit(20)
                     ->get()
                     ->transform(function ($item, $index) {
                         $item->rank = $index + 1;

                         return $item;
                     });
    }

    public static function headerSearch($term, $type)
    {
        return DB::table('product')
                 ->select('product.id', 'product.title', 'product.slug', 'product.authors')
                 ->where([
                     ['product.store_'.request('store'), 1],
                     ['product.type', $type],
                     ['product.status', 1],
                 ])
                 ->where(function ($query) use ($term) {
                     $query->where('product.title', 'like', '%'.$term.'%')
                         //                      ->orWhere('product.description', 'like', '%' . $term . '%')
                           ->orWhere('product.isbn', 'like', '%'.$term.'%')
                           ->orWhere('product.authors', 'like', '%'.$term.'%');
                 });
    }

    public static function fullSearch($term, $type)
    {
        return DB::table('product')
                 ->select('product.id', 'product.slug', 'product.title', 'product.description',
                     'product.state',
                     'product.type',
                     'product.cover', 'product.published_at', 'product.authors',
                     'product_price.price_list', 'product_price.price_sale', 'product_price.price_cart',
                     'product_price.discount_percent', 'product.is_new', 'product.publisher_id')
                 ->join('product_price', function ($join) {
                     $join->on('product.id', '=', 'product_price.product_id')
                          ->where('product_price.store', '=', request('store'));
                 })
                 ->where([
                     ['product.store_'.request('store'), 1],
                     ['product.type', $type],
                     ['product.status', 1],
                 ])
                 ->where(function ($query) use ($term) {
                     $query->where('product.title', 'like', '%'.$term.'%')
                         //                           ->orWhere('product.description', 'like', '%' . $term . '%')
                           ->orWhere('product.authors', 'like', '%'.$term.'%')
                           ->orWhere('product.isbn', 'like', '%'.$term.'%');
                 });
    }

    private static function getPrices($productIDs)
    {
        return DB::table('product_price')->whereIn('product_id', $productIDs)->where('store', request('store'));
    }

    private static function getAuthors($productIDs)
    {
        return DB::table('author')
                 ->selectRaw('`author`.*, `product_author`.`product_id` as `pivot_product_id`, `product_author`.`author_id` as `pivot_author_id`,
          `product_author`.`primary` as `pivot_primary`')
                 ->join('product_author', function ($join) {
                     $join->on('author.id', '=', 'product_author.author_id');
                 })
                 ->whereIn('product_author.author_id', $productIDs)
                 ->whereNull('author.deleted_at')
                 ->orderBy('primary', 'DESC');
    }

    private static function getProducts($term, $type, $perPage, $page)
    {
        return DB::table('product')
                 ->select('product.id')
                 ->where([
                     ['product.store_'.request('store'), 1],
                     ['product.type', $type],
                     ['product.status', 1],
                 ])
                 ->where(function ($query) use ($term) {
                     $query->where('product.title', 'like', '%'.$term.'%')
                           ->orWhere('product.description', 'like', '%'.$term.'%');
                 })
                 ->whereNull('product.deleted_at')
                 ->paginate($perPage, ['*'], 'page', $page);
    }

    private static function productCount($term, $type)
    {
        return DB::table('product')
                 ->where([
                     ['product.store_'.request('store'), 1],
                     ['product.type', $type],
                     ['product.status', 1],
                 ])
                 ->where(function ($query) use ($term) {
                     $query->where('product.title', 'like', '%'.$term.'%')
                           ->orWhere('product.description', 'like', '%'.$term.'%');
                 })
                 ->whereNull('product.deleted_at')
                 ->count();
    }
}
