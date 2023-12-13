<?php

namespace Alomgyar\Methods;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ShippingMethod extends Model
{
    use LogsActivity, HasFactory;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    public const DPD = 'dpd';

    public const SAMEDAY = 'sameday';

    public const HOME = 'home';

    public const BOX = 'box';

    public const SHOP = 'shop';

    public const CASH_ON_DELIVERY = 'cash_on_delivery';

    protected $fillable = [
        'name',
        'description',
        'fee_0',
        'fee_1',
        'fee_2',
        'discounted_fee_0',
        'discounted_fee_1',
        'discounted_fee_2',
        'method_id',
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

    public function discountedFee($store)
    {
        return $this->{'discounted_fee_'.$store};
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status_0 === self::STATUS_ACTIVE ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }
}
