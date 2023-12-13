<?php

namespace Alomgyar\PackagePoints\Filter\RowFilter;

use Illuminate\Database\Eloquent\Builder;

class StatusFilter
{
    public function filter(Builder $builder, $value)
    {
        return $builder->where('status', '=', $value);
    }
}
