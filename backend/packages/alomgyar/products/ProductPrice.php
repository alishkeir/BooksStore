<?php

namespace Alomgyar\Products;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductPrice extends Model
{
    use LogsActivity;

    protected $table = 'product_price';

    protected $fillable = [
        'product_id',
        'store',
        'discount_percent',
        'price_list',
        'price_sale',
        'price_cart',
        'price_list_original',
        'price_sale_original',
    ];

    protected static $logAttributes = ['*'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getDiscountPercentage($model)
    {
        if (! isset($model->price_sale) || $model->price_sale == null || $model->price_sale == 0) {
            $discountPercentage = 0;
        } else {
            $discountPercentage = round(100 - (($model->price_sale / $model->price_list) * 100));
        }

        return $discountPercentage;
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->discount_percent = self::getDiscountPercentage($model);
        });

        static::updating(function ($model) {
            $model->discount_percent = self::getDiscountPercentage($model);
        });
    }
}
