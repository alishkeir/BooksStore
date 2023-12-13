<?php

namespace Alomgyar\Comments;

use Alomgyar\Customers\Customer;
use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Comment extends Model
{
    use LogsActivity;
    use SoftDeletes;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    const ENTITY_PRODUCT = 0;

    const ENTITY_MAGAZINE = 1;

    protected $table = 'comments';

    protected $fillable = [
        'comment',
        'original_comment',
        'product_id',
        'post_id',
        'entity_type',
        'customer_id',
        'status',
        'store',
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
            ->orWhere('comment', 'like', '%'.$term.'%');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusHtmlAttribute()
    {
        if ($this->status == 0) {
            return '<a href="javascript:;"><span class="badge d-block badge-danger" title="Aktív"> Letiltott </span></a>';
        } elseif ($this->status == 1) {
            return '<a href="javascript:;"><span class="badge d-block badge-info" title="Aktív"> Új </span></a>';
        } elseif ($this->status == 2) {
            return '<a href="javascript:;"><span class="badge d-block badge-success" title="Aktív"> Aktív </span></a>';
        }
    }

    public function getEntityStringName(int $entityType)
    {
        if ($entityType === self::ENTITY_PRODUCT) {
            return 'product';
        }

        if ($entityType === self::ENTITY_MAGAZINE) {
            return 'magazine';
        }
    }

    public function getEntityID($comment)
    {
        if ($comment->entity_type == self::ENTITY_PRODUCT) {
            return $comment->product_id;
        }

        if ($comment->entity_type == self::ENTITY_MAGAZINE) {
            return $comment->post_id;
        }
    }
}
