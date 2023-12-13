<?php

namespace App\Http\Livewire;

use Alomgyar\Orders\EloquentOrdersRepository;
use App\Order;
use Carbon\Carbon;
use Livewire\Component;

class WebShopOrdersChart extends Component
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function render()
    {
        $repository = new EloquentOrdersRepository(new Order());

        $previousMonthWebShopOrdersRaw = $repository->getOrdersFromStoreBetweenFromAndToDate([0, 1, 2], Carbon::today()->subMonth(), Carbon::today())->whereIn('status', [1, 2, 3, 4]);
        $shopOrdersNewCounted = $previousMonthWebShopOrdersRaw->whereIn('status', 1)->count();
        $shopOrdersProcessingCounted = $previousMonthWebShopOrdersRaw->whereIn('status', 2)->count();
        $shopOrdersWaitingForShippingAndShipping = $previousMonthWebShopOrdersRaw->whereIn('status', [3, 4])->count();

        return view('livewire.web-shop-orders-chart', [
            'total' => $previousMonthWebShopOrdersRaw->count(),
            'new' => $shopOrdersNewCounted,
            'processing' => $shopOrdersProcessingCounted,
            'waitingForShippingAndShipping' => $shopOrdersWaitingForShippingAndShipping,
        ]);
    }
}
