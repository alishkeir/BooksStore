<?php

namespace Alomgyar\RankedProducts\Services;

use Alomgyar\RankedProducts\Entity\RankedProduct;

class RankedProductService
{
    protected $labelMap = [
        RankedProduct::SOLD => 'Eladási sikerlista',
        RankedProduct::E_SOLD => 'E-könyv sikerlista',
        RankedProduct::PRE => 'Előjegyzés sikerlista',
        RankedProduct::DISCOUNT_SOLD => 'Akciós sikerlista',
    ];

    protected $urlMap = [
        RankedProduct::SOLD => '/sikerlista/eladasi-sikerlista',
        RankedProduct::E_SOLD => '/sikerlista/e-konyv-sikerlista',
        RankedProduct::PRE => '/sikerlista/elojegyzes-sikerlista',
        RankedProduct::DISCOUNT_SOLD => '/sikerlista/akcios-sikerlista',
    ];

    public static function create()
    {
        return new self();
    }

    public function getLabel(string $type)
    {
        return $this->labelMap[$type];
    }

    public function getListUrl(string $type)
    {
        return $this->urlMap[$type];
    }
}
