<?php

namespace Alomgyar\Product_movements;

use Alomgyar\Products\Events\ProductStateChangedEvent;
use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductMovementItems extends Model
{
    use LogsActivity;
    use SoftDeletes;

    const STATUS_NEW = 1;

    const STATUS_CURRENT = 2;

    const STATUS_RUNOUT = 3;

    protected $table = 'product_movements_items';

    protected $fillable = [
        'product_movements_id',
        'product_id',
        'stock_in',
        'status',
        'stock_out',
        'purchase_price',
        'remaining_quantity_from_report',
    ];

    protected static $logAttributes = ['*'];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        'status' => self::STATUS_NEW,
    ];

    protected $dispatchesEvents = [
        'saved' => ProductStateChangedEvent::class,
    ];

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

    public function getStatusHtmlAttribute()
    {
        return match ($this->status) {
            1 => '<a href="javascript:;"><span class="badge badge-flat text-success-600" title="Új bevételezett">Új bevételezett</span></a>',
            2 => '<a href="javascript:;"><span class="badge badge-flat text-primary-600" title="Ebből fogy">Ebből fogy</span></a>',
            3 => '<a href="javascript:;"><span class="badge badge-flat text-danger-600" title="Elfogyott">Elfogyott</span></a>',
        };
    }

    public function productMovement()
    {
        return $this->belongsTo(ProductMovement::class, 'product_movements_id', 'id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function getQuantityAttribute(): string
    {
        if ($this->stock_in > 0) {
            return '<strong class="text-success">+ '.$this->stock_in.' db</strong>';
        } else {
            return '<strong class="text-danger">- '.$this->stock_out.' db</strong>';
        }
    }
}
