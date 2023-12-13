<?php

namespace App;

use Alomgyar\Customers\Customer;
use Alomgyar\Products\Product;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class WishItem extends Model
{
    use LogsActivity;

    protected $table = 'customer_wishlist';

    protected $fillable = [
        'customer_id',
        'product_id',
        'notified_at',
    ];

    protected static $logAttributes = ['*'];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        // 'status' => self::STATUS_DRAFT,
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
        : $query->where('customer_wishlist.id', 'like', '%'.$term.'%')
            ->orWhere('customer_wishlist.product_id', 'like', '%'.$term.'%')
            ->orWhere('customer_wishlist.customer_id', 'like', '%'.$term.'%');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
