<?php

namespace Alomgyar\Customers;

use Alomgyar\Countries\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Address extends Model
{
    use LogsActivity, HasFactory;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    const ENTITY_PRIVATE = 1;

    const ENTITY_BUSINESS = 2;

    protected $fillable = [
        'last_name',
        'first_name',
        'business_name',
        'vat_number',
        'city',
        'zip_code',
        'address',
        'country_id',
        'comment',
        'type',
        'role',
        'entity_type',
        'role_id',
    ];

    protected static $logAttributes = ['*'];

    public static $validationRules = [
        'last_name' => ['nullable', 'required_if:entity_type,private', 'min:2', 'max:60'],
        'first_name' => ['nullable', 'required_if:entity_type,private', 'min:2', 'max:60'],
        'business_name' => [
            'nullable', 'required_if:entity_type,business', 'required_with:vat_number', 'min:2', 'max:60',
        ],
        'vat_number' => [
            'nullable', 'required_if:entity_type,business', 'min:6', 'max:15',
        ],
        'city' => ['required', 'min:2', 'max:60'],
        'zip_code' => ['required', 'min:2', 'max:20'],
        'address' => ['required', 'min:2', 'max:60'],
        'comment' => ['nullable', 'max:300'],
        'country_id' => ['required'],
        'type' => ['required'],
        'entity_type' => ['sometimes', 'required_if:type,billing'],
    ];

    public static $validationMessages = [
        'last_name.required_if' => 'A vezetéknév kitöltése kötelező',
        'first_name.required_if' => 'A keresztnév kitöltése kötelező',
        'business_name.required_if' => 'A cég neve kitöltése kötelező',
        'vat_number.required_if' => 'Az adószám kitöltése kötelező',
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query->where('id', 'like', '%'.$term.'%')
                    ->orWhere('city', 'like', '%'.$term.'%')
                    ->orWhere('zip_code', 'like', '%'.$term.'%')
                    ->orWhere('address_name', 'like', '%'.$term.'%')
                    ->orWhere('type', 'like', '%'.$term.'%')
                    ->orWhere('role', 'like', '%'.$term.'%')
                    ->orWhere('entity', 'like', '%'.$term.'%')
                    ->orWhere('first_name', 'like', '%'.$term.'%')
                    ->orWhere('last_name', 'like', '%'.$term.'%');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status === self::STATUS_ACTIVE ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    public function getFullNameAttribute()
    {
        return (! empty($this->last_name) || ! empty($this->first_name)) && $this->entity_type === 1
            ? "{$this->last_name} {$this->first_name}"
            : "{$this->business_name}";
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getEntityAttribute()
    {
        return match ($this->entity_type) {
            self::ENTITY_BUSINESS => 'business',
            default => 'private',
        };
    }
}
