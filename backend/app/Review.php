<?php

namespace App;

use Alomgyar\Customers\Customer;
use Alomgyar\Products\Product;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Review extends Model
{
    use LogsActivity;

    protected $table = 'product_review';

    protected $fillable = [
        'customer_id',
        'product_id',
        'review',
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
        : $query->where('product_review.id', 'like', '%'.$term.'%')
            ->orWhere('product_review.product_id', 'like', '%'.$term.'%')
            ->orWhere('product_review.customer_id', 'like', '%'.$term.'%');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

//    public function paymentMethod()
//    {
//        return $this->belongsTo(PaymentMethod::class);
//    }
}
