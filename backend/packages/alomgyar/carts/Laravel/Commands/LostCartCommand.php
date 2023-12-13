<?php

namespace Alomgyar\Carts\Laravel\Commands;

use Alomgyar\Carts\Laravel\Services\LostCartService;
use Illuminate\Console\Command;

class LostCartCommand extends Command
{
    protected $signature = 'cart:send-lost';

    protected $description = 'Hátrahagyott kosár emlékeztetők.';

    public function handle(LostCartService $service)
    {
        $service->sendReminder();
    }
}
