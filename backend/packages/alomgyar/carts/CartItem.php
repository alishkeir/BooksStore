<?php

namespace Alomgyar\Carts;

use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CartItem extends Model
{
    use LogsActivity, SoftDeletes, HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'is_cart_price',
        'quantity',
    ];

    protected static $logAttributes = ['*'];

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
