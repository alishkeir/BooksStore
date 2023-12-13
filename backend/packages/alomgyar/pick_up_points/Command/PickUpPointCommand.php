<?php

namespace Alomgyar\PickUpPoints\Command;

use Alomgyar\PickUpPoints\Services\PickUpPointsService;
use Illuminate\Console\Command;

class PickUpPointCommand extends Command
{
    protected $signature = 'pick_up_point:update';

    protected $description = 'Update PickUpPoints.';

    public function handle(PickUpPointsService $service)
    {
        $service->run();
    }
}
