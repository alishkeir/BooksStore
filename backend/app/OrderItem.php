<?php

namespace App;

use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderItem extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'original_price',
        'cart_price',
        'quantity',
        'total',
    ];

    protected static $logAttributes = ['*'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
        : $query->where('order_items.id', 'like', '%'.$term.'%');
    }
}
