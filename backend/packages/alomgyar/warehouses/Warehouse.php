<?php

namespace Alomgyar\Warehouses;

use Alomgyar\Shops\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Warehouse extends Model implements HasMedia
{
    use LogsActivity;
    use InteractsWithMedia;
    use SoftDeletes;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    public const WEBSHOP_ID = 18;

    public const FAIR_EVENT_ID = 59;

    protected $table = 'warehouse';

    protected $fillable = [
        'title',
        'description',
        'type',
        'shop_id',
        'city',
        'zip_code',
        'address',
        'phone',
        'email',
        'status',
        'invoice_prefix',
        'billing_business_name',
        'billing_vat_number',
        'billing_city',
        'billing_zip_code',
        'billing_address',
        'is_merchant',
    ];

    protected static $logAttributes = ['*'];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
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

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query->where('id', 'like', '%'.$term.'%')
                    ->orWhere('title', 'like', '%'.$term.'%');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeMain()
    {
        return $this->whereType(1)->first();
    }

    public function scopeMerchant($query)
    {
        return $query->where('is_merchant', 1);
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    public function getWarehouseTypeAttribute()
    {
        return $this->type
            ? 'Központi'
            : ($this->is_merchant ? 'Kereskedői'
            : 'Normál');
    }

    public function getTotalInventoryAttribute()
    {
        return Inventory::active()->where('warehouse_id', $this->id)->sum('stock');
    }

    public function getFullAddressAttribute()
    {
        return $this->zip_code.' '.$this->city.', '.$this->address;
    }

    public function productInventory($productId)
    {
        return Inventory::active()->where(['warehouse_id' => $this->id, 'product_id' => $productId])->first()?->stock ?? 0;
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
