<?php

namespace App\Http\Controllers;

use App\Http\Traits\ErrorMessages;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderApiController
{
    use ErrorMessages;

    protected array $validRefs = ['status'];

    private bool $guest = true;

    public function __invoke(Request $request)
    {
        if (! in_array(request('ref'), $this->validRefs)) {
            return $this->badRefMessage();
        }

        if (request('ref') === 'status') {
            return $this->getStatus($request);
        }
    }

    public function getStatus(Request $request)
    {
        $order = Order::where('order_number', request()->body['order_number'])->firstOrFail();

        return [
            'data' => [
                'order_number' => request()->body['order_number'],
                'last_update' => Carbon::parse($order->updated_at)->format('Y.m.d. H:i:s'),
                'steps' => [
                    [
                        'title' => 'Várakozó',
                        'description' => 'Rendelésed bekerült a rendszerünkbe.',
                        'active' => in_array($order->status, [Order::STATUS_DRAFT, Order::STATUS_NEW]),
                    ],
                    [
                        'title' => 'Feldolgozás alatt',
                        'description' => 'Munkatársunk éppen feldolgozza rendelését.',
                        'active' => in_array($order->status, [Order::STATUS_PROCESSING, Order::STATUS_WAITING_FOR_SHIPPING]),
                    ],
                    [
                        'title' => $this->checkShippingStatusString($order) ? 'Szállítás alatt' : 'Átvehető',
                        'description' => $this->checkShippingStatusString($order) ? 'Csomagja szállítás alatt.' : 'Csomagja átvehető.',
                        'active' => in_array($order->status, [Order::STATUS_SHIPPING, Order::STATUS_LANDED]) || ($order->status === Order::STATUS_COMPLETED && $order->shippingMethod->method_id === 'store'),

                    ],
                    [
                        'title' => 'Teljesítve',
                        'description' => '',
                        'active' => $order->status === Order::STATUS_COMPLETED && $order->shippingMethod->method_id !== 'store',
                    ],
                ],
            ],
        ];
    }

    private function checkShippingStatusString($order): bool
    {
        return $order->status !== Order::STATUS_LANDED && ! ($order->status === Order::STATUS_COMPLETED && $order->shippingMethod->method_id === 'store');
    }
}
