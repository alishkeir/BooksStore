<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'folder', 'fields', 'resource'];

    protected $casts = [
        'fields' => 'array',
    ];

    public function getResourceAttribute()
    {
        return $this->resource === 1 ? 'true' : 'false';
    }
}
