<?php
/*
Author: Hódi
Date: 2021. 05. 06. 15:42
Project: alomgyar-webshop-be
*/

namespace Alomgyar\Authors;

use Alomgyar\Products\Product;
use App\Http\Resources\ProductListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ApiAuthor extends Author
{
    public static function authorBooks(Author $author, $productID): AnonymousResourceCollection
    {
        $products = DB::table('product_author')
            ->distinct()
            ->select('product.id', 'product.slug', 'product.title', 'product.state', 'product.type', 'product.authors',
                'product_price.price_list', 'product_price.price_sale', 'product_price.price_cart', 'product_price.discount_percent',
                'product.cover', 'product.published_at', 'product.is_new', 'product.publisher_id')
            ->join('product', function ($join) {
                $join->on('product_author.product_id', '=', 'product.id');
            })
            ->leftJoin('product_price', function ($join) {
                $join->on('product_price.product_id', '=', 'product.id')->where('product_price.store', request('store'));
            })
            ->where('product_author.author_id', $author->id)
            ->where('product.id', '!=', $productID)
            ->where('product.store_'.request('store'), 1)
            ->where('product.status', Product::STATUS_ACTIVE)
            ->limit(5)
            ->get();

        return ProductListResource::collection($products);
    }

    public static function headerSearch($term)
    {
        return DB::table('author')
                 ->select('id', 'title', 'slug')
                 ->where('status', 1)
                 ->whereNull('deleted_at')
                 ->where('title', 'like', '%'.$term.'%');
    }

    public function scopeFullSearch($query, $term)
    {
        return $query
                 ->select('id', 'title', 'slug')
                 ->where('status', 1)
                 ->where('title', 'like', '%'.$term.'%');
    }
}
