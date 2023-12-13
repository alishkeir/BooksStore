<?php

namespace Skvadcom\General;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $table = 'general';

    protected $fillable = [
        'source',
        'key',
        'name',
        'value',
        'type',
        'extra',
    ];

    protected $casts = [
        'source' => 'string',
        'key' => 'string',
        'name' => 'string',
        'value' => 'string',
        'type' => 'string',
        'extra' => 'array',
    ];
}
