<?php

namespace Alomgyar\Promotions;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PromotionPrice extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'promotion_product';

    protected $fillable = [
        'product_id',
        'promotion_id',
        'price_sale_0',
        'price_sale_1',
        'price_sale_2',
    ];

    protected static $logAttributes = ['*'];

    public static $messages = [
        'required' => 'A :attribute kitöltése kötelező!',
        'email.required' => 'Az :attribute kitöltése kötelező!',
        'max' => 'Nem lehet hosszabb, mint :max karakter!',
        'alpha_num' => 'Csak az ABC betűit és számokat használhatod!',
        'alpha' => 'Csak az ABC betűit használhatod!',
        'string' => 'Csak az ABC betűit használhatod!',
        'min' => 'Nem lehet kevesebb, mint :min karakter!',
        'date' => 'Nem megfelelő dátum formátum!',
        'numeric' => 'Csak szám lehet!',
        'integer' => 'Csak szám lehet!',
        'email' => 'Nem megfelelő formátum!',
        'unique' => 'Ezzel az email címmel már regisztráltak!',
        'phone.regex' => 'Nem megfelelő formátum!',
    ];

    public function product()
    {
        return $this->belongsTo(\Alomgyar\Products\Product::class);
    }
}
