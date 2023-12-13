<?php

namespace Alomgyar\PackagePoints\Filter\RowFilter;

use Illuminate\Database\Eloquent\Builder;

class ShopFilter
{
    public function filter(Builder $builder, $value)
    {
        return $builder->where('shop_id', '=', $value);
    }
}
