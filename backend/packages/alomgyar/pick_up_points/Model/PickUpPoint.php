<?php

namespace Alomgyar\PickUpPoints\Model;

use Illuminate\Database\Eloquent\Model;

class PickUpPoint extends Model
{
    public const STATUS_ACTIVE = 1;

    public const STATUS_INACTIVE = 0;

    public const PROVIDER_EASYBOX = 'easybox';

    protected $fillable = [
        'provider',
        'provider_name',
        'provider_id',
        'provider_type',
        'name',
        'city',
        'address',
        'zip',
        'long',
        'lat',
        'open',
        'description',
        'status',
        'type',
    ];
}
