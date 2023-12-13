<?php

namespace Alomgyar\PackagePoints\Filter\RowFilter;

use Illuminate\Database\Eloquent\Builder;

class PartnerFilter
{
    public function filter(Builder $builder, $value)
    {
        return $builder->where('partner_id', '=', $value);
    }
}
