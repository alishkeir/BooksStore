<?php

namespace Alomgyar\RankedProducts\Repository;

use Alomgyar\Products\ApiProduct as Product;
use Alomgyar\RankedProducts\Entity\RankedProduct as RankedProductEntity;
use Alomgyar\RankedProducts\Model\RankedProduct;
use App\Http\Resources\ProductListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RankedProductRepository
{
    public function getBestSellersForApi(int $storeId): array
    {
        return [
            'products' => $this->getProductsForApi($storeId, RankedProductEntity::SOLD),
            'url_to_the_list' => '/sikerlista/eladasi-sikerlista',
        ];
    }

    public function getBestDiscountedForApi(int $storeId): array
    {
        return [
            'products' => $this->getProductsForApi($storeId, RankedProductEntity::DISCOUNT_SOLD),
            'url_to_the_list' => '/sikerlista/akcios-sikerlista',
        ];
    }

    public function getBestPreordersForApi(int $storeId): array
    {
        return [
            'products' => $this->getProductsForApi($storeId, RankedProductEntity::PRE),
            'url_to_the_list' => '/sikerlista/elojegyzes-sikerlista',
        ];
    }

    public function getEbookBestSellersForAPI(int $storeId): array
    {
        return [
            'products' => $this->getProductsForApi($storeId, RankedProductEntity::E_SOLD),
            'url_to_the_list' => '/sikerlista/e-konyv-sikerlista',
        ];
    }

    public function getProductsForApi(int $storeId, string $type): AnonymousResourceCollection
    {
        $productIds = RankedProduct::where('store_id', $storeId)
            ->where('type', $type)
            ->orderBy('rank', 'ASC')
            ->take(5)
            ->get()
            ->pluck('product_id')->toArray();

        $products = Product::join('ranked_products', 'product.id', '=', 'ranked_products.product_id')
            ->select('product.id', 'product.title', 'product.slug', 'product.status', 'product.cover', 'product.state', 'product.type', 'product.authors', 'ranked_products.rank', 'product.is_new', 'product.publisher_id')
            ->whereIn('product.id', $productIds)
            ->where('product.status', Product::STATUS_ACTIVE)
            ->where('ranked_products.type', $type)
            ->where('ranked_products.store_id', $storeId)
            ->with('ranked')
            ->orderBy('ranked_products.rank', 'ASC')
            ->get();

        return ProductListResource::collection($products);
    }

    public function getBestSellers(int $storeId, int $take = 20)
    {
        $discountOption = $this->getDiscountSlug($storeId);

        $storeSlug = sprintf('store_%d', $storeId);

        $sql = "
            SELECT count(oi.product_id) as cnt, oi.product_id as id
            FROM order_items oi
            INNER JOIN product p on oi.product_id = p.id
            INNER JOIN product_price as pp on oi.product_id = pp.product_id
            WHERE oi.created_at > ?
            AND p.{$storeSlug} = 1
            AND p.type = ?
            AND p.state = ?
            AND p.status = ?
            AND pp.store = ?
            AND pp.discount_percent <= ?
            GROUP BY pp.product_id, pp.discount_percent
            ORDER BY cnt DESC
            LIMIT ?
        ";

        return DB::select($sql, [
            Carbon::now()->subWeek(),
            Product::BOOK,
            Product::STATE_NORMAL,
            Product::STATUS_ACTIVE,
            $storeId,
            (int) $discountOption,
            $take,
        ]);
    }

    public function getBestDiscounted(int $storeId, int $take = 20)
    {
        $discountOption = $this->getDiscountSlug($storeId);

        $storeSlug = sprintf('store_%d', $storeId);

        $sql = "
            SELECT count(oi.product_id) as cnt, oi.product_id as id
            FROM order_items oi
            INNER JOIN product p on oi.product_id = p.id
            INNER JOIN product_price as pp on oi.product_id = pp.product_id
            WHERE oi.created_at > ?
            AND p.{$storeSlug} = 1
            AND p.type = ?
            AND p.state = ?
            AND p.status = ?
            AND pp.store = ?
            AND pp.discount_percent > ?
            GROUP BY pp.product_id, pp.discount_percent
            ORDER BY cnt DESC
            LIMIT ?
        ";

        return DB::select($sql, [
            Carbon::now()->subWeek(),
            Product::BOOK,
            Product::STATE_NORMAL,
            Product::STATUS_ACTIVE,
            $storeId,
            (int) $discountOption,
            $take,
        ]);
    }

    public function getBestPreorders(int $storeId, int $take = 20)
    {
        return Product::active()
            ->where(sprintf('store_%d', $storeId), 1)
            ->where('type', 0)
            ->whereStatus(Product::STATUS_ACTIVE)
            ->pre()
            ->orderBy(sprintf('product.preorders_count_%d', $storeId), 'DESC')
            ->orderBy('product.id', 'ASC')
            ->selectForList()
            ->take($take)
            ->get();
    }

    public function getEbookBestSellers(int $storeId, int $take = 20)
    {
        $storeSlug = sprintf('store_%d', $storeId);

        $sql = "
            SELECT count(oi.product_id) as cnt, oi.product_id as id
            FROM order_items oi
            INNER JOIN product p on oi.product_id = p.id
            INNER JOIN product_price as pp on oi.product_id = pp.product_id
            WHERE oi.created_at > ?
            AND p.{$storeSlug} = 1
            AND p.type = ?
            AND p.state = ?
            AND p.status = ?
            AND pp.store = ?
            GROUP BY pp.product_id, pp.discount_percent
            ORDER BY cnt DESC
            LIMIT ?
        ";

        return DB::select($sql, [
            Carbon::now()->subWeek(),
            Product::EBOOK,
            Product::STATE_NORMAL,
            Product::STATUS_ACTIVE,
            $storeId,
            $take,
        ]);
    }

    /**
     * @return false|mixed
     */
    public function getDiscountSlug(int $storeId): mixed
    {
        $discountSlug = match ($storeId) {
            0 => 'default_discount_rate_alomgyar',
            1 => 'default_discount_rate_olcsokonyvek',
            2 => 'default_discount_rate_nagyker',
        };

        return option($discountSlug, 0);
    }
}
