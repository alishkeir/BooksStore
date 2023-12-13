<?php

namespace Alomgyar\Carts;

use Alomgyar\Customers\Customer;
use Alomgyar\Products\Product;
use App\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Cart extends Model
{
    use LogsActivity, SoftDeletes, HasFactory;

    protected $fillable = [
        'customer_id',
        'guest_token',
        'total_quantity',
        'total_amount',
        'order_id',
        'total_amount_full_price',
        'store',
        'reminded_at',
    ];

    protected static $logAttributes = ['*'];

    public static function generateGuestToken()
    {
        return hash('sha256', Str::random(40));
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getProductsAttribute()
    {
        return Product::whereIn('id', $this->items->pluck('product_id'))->get()->transform(function ($item
        ) {
            $cartItem = $this->items()->where('product_id', $item->id)->first();
            $item['quantity'] = $cartItem->quantity;
            $item['is_cart_price'] = $cartItem->is_cart_price;

            return $item;
        });
    }

    public function hasEbook()
    {
        foreach ($this->items as $item) {
            if ($item->product->type === Product::EBOOK) {
                return true;
            }
        }
    }

    public function onlyEbook()
    {
        $onlyEbook = true;
        foreach ($this->items as $item) {
            if ($item->product->type == Product::BOOK) {
                $onlyEbook = false;
            }
        }

        return $onlyEbook;
    }
}
