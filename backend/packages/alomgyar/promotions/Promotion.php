<?php

namespace Alomgyar\Promotions;

use Alomgyar\Products\Product;
use Alomgyar\Promotions\Scopes\NotShowFlashDealScope;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @method static whereSlug( mixed $slug )
 */
class Promotion extends Model implements HasMedia
{
    use LogsActivity;
    use InteractsWithMedia;
    use SoftDeletes;
    use HasFactory;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    const NOT_FLASH_DEAL = 0;

    const IS_FLASH_DEAL = 1;

    protected $table = 'promotion';

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'cover',
        'list_image_xl',
        'list_image_sm',
        'status',
        'store_0',
        'store_1',
        'store_2',
        'order',
        'active_from',
        'active_to',
        'is_flash_deal',
        'created_by_id',
    ];

    protected static $logAttributes = ['*'];

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

    public function getStoreLogoUrlAttribute()
    {
        $url = '';

        if ($this->store_0 == 1) {
            $url = '/logo-alomgyar.png';
        }
        if ($this->store_1 == 1) {
            $url = '/logo-olcsokonyvek.png';
        }
        if ($this->store_2 == 1) {
            $url = '/logo-nagyker.png';
        }

        return $url;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product', 'promotion_id', 'product_id');
    }

    public function price($id)
    {
        return $this->hasOne(PromotionPrice::class)->where('product_id', $id)->first();
    }

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

    public function scopeCurrent($query)
    {
        return $query->where('status', 1)->where('active_from', '<', Carbon::now())->where('active_to', '>', Carbon::now());
    }

    public function scopeFlashDeals($query)
    {
        return $query->where('is_flash_deal', self::IS_FLASH_DEAL);
    }

    public function scopeNotFlashDeals($query)
    {
        return $query->where('is_flash_deal', self::NOT_FLASH_DEAL);
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (! $model->created_by_id) {
                $model->created_by_id = auth()->id();
            }
        });

        static::addGlobalScope(new NotShowFlashDealScope());
        // static::addGlobalScope('show-not-flash-deals', function (Builder $builder) {
        //     $builder->notFlashDeals();
        // });
    }
}
