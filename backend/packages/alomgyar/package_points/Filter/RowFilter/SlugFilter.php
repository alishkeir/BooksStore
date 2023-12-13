<?php

namespace Alomgyar\PackagePoints\Filter\RowFilter;

use Illuminate\Database\Eloquent\Builder;

class SlugFilter
{
    public function filter(Builder $builder, $value)
    {
        return $builder
            ->where('code', 'LIKE', '%'.$value.'%')
            ->orWhere('customer', 'LIKE', '%'.$value.'%');
    }
}
