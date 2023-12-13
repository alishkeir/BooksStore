<?php

namespace App\Http\Livewire;

use Alomgyar\Orders\EloquentOrdersRepository;
use App\Order;
use Carbon\Carbon;
use Livewire\Component;

class WebShopLastWeekOrderChart extends Component
{
    public function render()
    {
        $repository = new EloquentOrdersRepository(new Order());
        $result = $repository->getOrdersForWeekReport([1, 2, 3, 4, 5, 6, 7], [0, 1, 2], Carbon::today()->subWeek(), Carbon::today());

        return view('livewire.web-shop-last-week-order-chart', [
            'total' => $result['total'],
        ]);
    }
}
