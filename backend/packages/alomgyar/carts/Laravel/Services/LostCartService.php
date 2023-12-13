<?php

namespace Alomgyar\Carts\Laravel\Services;

use Alomgyar\Carts\Laravel\Job\LostCartJob;
use Alomgyar\Carts\Repository\LostCartRepository;
use Illuminate\Support\Facades\Log;

class LostCartService
{
    public function sendReminder()
    {
        $repository = new LostCartRepository();
        $carts = $repository->findCartsForReminder();
        $i = 0;

        foreach ($carts as $cart) {
            if (! empty($cart->customer)) {
                LostCartJob::dispatch($cart);
                $i++;
            }
        }
        Log::info('Elhagyott kosarak száma: '.$i);
    }
}
