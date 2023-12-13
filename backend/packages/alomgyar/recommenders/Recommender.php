<?php

namespace Alomgyar\Recommenders;

use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Recommender extends Model implements HasMedia
{
    use LogsActivity;
    use InteractsWithMedia;
    use SoftDeletes;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    protected $table = 'recommenders';

    protected $fillable = [
        'promoted_product_id',
        'original_product_id',
        'message_body',
        'release_date',
        'store',
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

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
        : $query->where('id', 'like', '%'.$term.'%');
        /** @todo megoldani a kapcsolt tábla keresést */
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    public function promotedProduct()
    {
        return $this->hasOne(Product::class, 'id', 'promoted_product_id');
    }

    public function originalProduct()
    {
        return $this->hasOne(Product::class, 'id', 'original_product_id');
    }
}
