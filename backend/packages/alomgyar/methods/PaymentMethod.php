<?php

namespace Alomgyar\Methods;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentMethod extends Model
{
    use LogsActivity, HasFactory;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    protected $fillable = [
        'name',
        'description',
        'payment_method',
        'method_id',
        'fee_0',
        'fee_1',
        'fee_2',
        'status_0',
        'status_1',
        'status_2',
    ];

    protected static $logAttributes = ['*'];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        'status_0' => self::STATUS_ACTIVE,
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
        : $query->where('id', 'like', '%'.$term.'%')
            ->orWhere('name', 'like', '%'.$term.'%');
    }

    public function scopeActive($query)
    {
        return $query->where('status_0', self::STATUS_ACTIVE);
    }

    public function fee($store)
    {
        return $this->{'fee_'.$store};
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status_0 === self::STATUS_ACTIVE ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }
}
