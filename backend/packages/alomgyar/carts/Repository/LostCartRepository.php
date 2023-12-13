<?php

namespace Alomgyar\Carts\Repository;

use Alomgyar\Carts\Cart;
use Carbon\Carbon;

class LostCartRepository
{
    public function findCartsForReminder()
    {
        return Cart::with('customer')->where('updated_at', '<', Carbon::now()->subDays()->endOfDay())
        ->where('updated_at', '>', Carbon::now()->subDays()->startOfDay())
        ->whereNull('reminded_at')
        ->whereNull('deleted_at')
        ->where('total_quantity', '>', 0)
        ->get();
    }
}
