<?php

namespace Alomgyar\Subcategories;

use Alomgyar\Categories\Category;
use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Subcategory extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia, SoftDeletes, HasFactory;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    protected $table = 'subcategory';

    protected $fillable = [
        'title',
        'slug',
        'status',
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

    public function getStatusHtmlAttribute()
    {
        return $this->status ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subcategory', 'subcategory_id', 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_subcategory', 'subcategory_id', 'product_id');
    }
}
