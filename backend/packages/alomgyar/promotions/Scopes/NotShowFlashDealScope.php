<?php

namespace Alomgyar\Promotions\Scopes;

use Alomgyar\Promotions\Promotion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class NotShowFlashDealScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('is_flash_deal', Promotion::NOT_FLASH_DEAL);
    }
}
