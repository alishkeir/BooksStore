<?php

namespace App;

use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PublicPreOrder extends Model
{
    use LogsActivity;

    protected $table = 'public_preorders';

    protected $fillable = [
        'email',
        'product_id',
        'store',
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
        : $query->where('public_preorders.id', 'like', '%'.$term.'%')
            ->orWhere('public_preorders.product_id', 'like', '%'.$term.'%')
            ->orWhere('public_preorders.email', 'like', '%'.$term.'%');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
