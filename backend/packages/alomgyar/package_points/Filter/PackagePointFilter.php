<?php

namespace Alomgyar\PackagePoints\Filter;

use Alomgyar\PackagePoints\Filter\RowFilter\PartnerFilter;
use Alomgyar\PackagePoints\Filter\RowFilter\ShopFilter;
use Alomgyar\PackagePoints\Filter\RowFilter\SlugFilter;
use Alomgyar\PackagePoints\Filter\RowFilter\StatusFilter;

class PackagePointFilter extends AbstractFilter
{
    protected $filters = [
        'status' => StatusFilter::class,
        'slug' => SlugFilter::class,
        'shop_id' => ShopFilter::class,
        'partner_id' => PartnerFilter::class,
    ];
}
