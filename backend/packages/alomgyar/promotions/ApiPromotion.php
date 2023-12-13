<?php
/*
Author: Hódi
Date: 2021. 05. 18. 14:42
Project: alomgyar-webshop-be
*/

namespace Alomgyar\Promotions;

class ApiPromotion extends Promotion
{
    public function scopeActive($query)
    {
        return $query->whereStatus(1)
            ->where('active_from', '<', now())
            ->where('active_to', '>', now());
    }

    public function scopeByStore($query)
    {
        return $query->where('store_'.request('store'), 1);
    }
}
