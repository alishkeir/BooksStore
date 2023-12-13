<?php

namespace Alomgyar\Products\Listeners;

use Alomgyar\Products\Events\ProductAvailableEvent;
use Alomgyar\Products\Services\ProductHasNormalStateService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductAvailableListener implements ShouldQueue
{
    public function handle(ProductAvailableEvent $event)
    {
        (new ProductHasNormalStateService())->sendEmails($event->getProduct());
    }
}
