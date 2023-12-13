<?php

namespace Alomgyar\Coupons;

use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index()
    {
        $model = Coupon::latest()->paginate(25);

        return view('coupons::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('coupons::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $coupon = Coupon::create(request()->all());

        session()->flash('success', 'Coupon sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $coupon, 'return_url' => route('coupons.index')]);
//        return redirect()->route('coupons.index')->with('success', 'Coupon sikeresen létrehozva!');
    }

    public function edit(Coupon $coupon)
    {
        return view('coupons::edit', [
            'model' => $coupon,
        ]);
    }

    public function update(Coupon $coupon)
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $coupon->update($data);

        session()->flash('success', 'Coupon sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $coupon, 'return_url' => route('coupons.index')]);
//        return redirect()->route('coupons.index', ['coupon' => $coupon->id]);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'Coupon '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'code' => 'required',
            'prefix' => 'required',
        ]);
    }
}
