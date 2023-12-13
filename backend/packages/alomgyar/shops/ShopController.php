<?php

namespace Alomgyar\Shops;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShopController extends Controller
{
    public function index()
    {
        $model = Shop::latest()->paginate(25);

        return view('shops::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('shops::create');
    }

    public function store()
    {
        $data = request()->all();
        $this->validateRequest();

        $checks = ['status','show_shipping' , 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $shop = Shop::create($data);

        session()->flash('success', 'Könyvesbolt sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $shop, 'return_url' => route('shops.index')]);
        //        return redirect()->route('shops.index')->with('success', 'Könyvesbolt sikeresen létrehozva!');
    }

    public function edit(Shop $shop)
    {
        return view('shops::edit', [
            'model' => $shop,
        ]);
    }

    public function update(Shop $shop)
    {
        $data = request()->all();
        $this->validateRequest();

        $checks = ['status', 'show_shipping' ,'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }

        $shop->update($data);

        session()->flash('success', 'Könyvesbolt sikeresen frissítve!');

        //return response()->json(['success' => true, 'model' => $shop, 'return_url' => route('shops.index')]);
        return response()->json(['success' => true, 'model' => $shop, 'return_url' => route('shops.edit', ['shop' => $shop->id])]);
    }

    public function show(Shop $shop)
    {
        return view('shops::show', [
            'model' => $shop,
        ]);
    }

    public function shop()
    {
        return view('shops::shop');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();

        return redirect()->route('shops.index')->with('success', 'Könyvesbolt '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'latitude' => 'required',
        ]);
    }

    public function search(Request $request)
    {
        $term = trim($request->q);
        $shops = Shop::select('id', 'title')->search($term)->latest()->paginate(25);

        return response([
            'results' => ShopSelectResource::collection($shops),
            'pagination' => [
                'more' => $shops->currentPage() !== $shops->lastPage(),
            ],
        ]);
    }
}
