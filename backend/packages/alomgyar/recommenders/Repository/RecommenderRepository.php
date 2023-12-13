<?php

namespace Alomgyar\Recommenders\Repository;

use Alomgyar\Customers\Customer;
use Illuminate\Support\Facades\DB;

class RecommenderRepository
{
    public function getCustomersByProductId(int $productId): \Illuminate\Support\Collection
    {
        $customers = DB::select('
            SELECT DISTINCT (c.email), c.firstname
            FROM orders o
            INNER JOIN customers c ON c.id = o.customer_id
            WHERE o.id in (
                SELECT oi.order_id
                FROM order_items oi
                WHERE product_id = ?
                )

        ', [$productId]);

        return Customer::hydrate($customers);
    }

    public function getCustomersByProductIdArchive(int $productId): \Illuminate\Support\Collection
    {
        $customers = DB::select('
            SELECT DISTINCT (c.email), c.firstname
            FROM archive_orders_for_recommenders ao
            INNER JOIN customers c ON c.id = ao.customer_id
            WHERE ao.product_id = ?
        ', [$productId]);
        //
        return Customer::hydrate($customers);
    }

    public function getCustomerNumByProductId(int $productId)
    {
        return $this->getCustomersByProductId($productId)->count() + $this->getCustomersByProductIdArchive($productId)->count();
    }
}
