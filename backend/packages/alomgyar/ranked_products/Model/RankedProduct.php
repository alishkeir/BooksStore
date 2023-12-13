<?php

namespace Alomgyar\RankedProducts\Model;

use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Model;

class RankedProduct extends Model
{
    protected $table = 'ranked_products';

    protected $fillable = [
        'product_id',
        'rank',
        'type',
        'store_id',
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
