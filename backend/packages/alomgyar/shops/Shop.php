<?php

namespace Alomgyar\Shops;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Shop extends Model
{
    use LogsActivity, SoftDeletes, HasFactory;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    protected $table = 'shops';

    protected $fillable = [
        'title',
        'description',
        'zip_code',
        'city',
        'address',
        'status',
        'show_shipping',
        'phone',
        'email',
        'facebook',
        'cover',
        'latitude',
        'longitude',
        'opening_hours',
        'store_0',
        'store_1',
        'store_2',
    ];

    protected static $logAttributes = ['*'];

    protected $casts = [
        'opening_hours' => AsCollection::class,
        'show_shipping' => 'boolean',
    ];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
        'show_shipping' => self::STATUS_ACTIVE
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

    public function scopeShowOnShipping($query)
    {
        return $query->where('show_shipping', 1);
    }

    public function scopeActiveOfflineShops($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)->where('email', '!=', 'webshop@alomgyar.hu');
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }
}
