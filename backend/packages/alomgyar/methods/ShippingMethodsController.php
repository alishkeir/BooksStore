<?php

namespace Alomgyar\Methods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingMethodsController extends Controller
{
    public function edit(ShippingMethod $shippingMethod)
    {
        return view('methods::edit', [
            'model' => $shippingMethod,
            'route' => route('shipping-method.update', $shippingMethod),
        ]);
    }

    public function update(ShippingMethod $shippingMethod, Request $request)
    {
        $data = request()->all();

        $checks = ['status', 'status_0', 'status_1', 'status_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $shippingMethod->update($data);

        session()->flash('success', 'Szállítási mód sikeresen frissítve!');

        return response()
            ->json([
                'success' => true,
                'model' => $shippingMethod,
                'return_url' => route('methods.index'),
            ]);
    }
}
