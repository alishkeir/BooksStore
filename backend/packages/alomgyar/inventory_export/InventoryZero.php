<?php

namespace Alomgyar\InventoryExport;

use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryZero extends Model
{
    use SoftDeletes;

    const STATE_ARCHIVE = 0;

    const STATE_ACTIVE = 1;

    protected $fillable = [
        'product_id', 'warehouse_id', 'stock', 'created_by_id', 'state',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('state', self::STATE_ACTIVE);
    }
}
