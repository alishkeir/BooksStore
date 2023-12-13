<?php

namespace App\Traits;


trait ActiveScopeTrait
{
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
}