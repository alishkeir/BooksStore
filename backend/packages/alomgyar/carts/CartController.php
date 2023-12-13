<?php

namespace Alomgyar\Carts;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $model = Cart::latest()->paginate(25);

        return view('carts::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('carts::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $cart = Cart::create(request()->all());

        $cart->addMediaFromDisk('/carts/covers/'.$data['cover'], 'public')
            ->toMediaCollection('cover');

        foreach ($data['gallery'] as $gallery) {
            $cart->addMediaFromDisk('/carts/gallery/'.$gallery, 'public')
                ->toMediaCollection('gallery');
        }

        session()->flash('success', 'Cart sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $cart, 'return_url' => route('carts.index')]);
//        return redirect()->route('carts.index')->with('success', 'Cart sikeresen létrehozva!');
    }

    public function edit(Cart $cart)
    {
        return view('carts::edit', [
            'model' => $cart,
        ]);
    }

    public function update(Cart $cart)
    {
        $data = $this->validateRequest();
        $cart->update($data);

        if (! empty($data['cover'])) {
            $cart->clearMediaCollection('cover');
            $cart->addMediaFromDisk('/carts/covers/'.$data['cover'], 'public')
                ->toMediaCollection('cover');
        }

        if (! empty($data['gallery'])) {
            $cart->clearMediaCollection('gallery');
            foreach ($data['gallery'] as $gallery) {
                $cart->addMediaFromDisk('/carts/gallery/'.$gallery, 'public')
                    ->toMediaCollection('gallery');
            }
        }

        session()->flash('success', 'Cart sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $cart, 'return_url' => route('carts.index')]);
//        return redirect()->route('carts.index', ['cart' => $cart->id]);
    }

    public function show(Cart $cart)
    {
        return view('carts::show', [
            'model' => $cart,
        ]);
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return redirect()->route('carts.index')->with('success', 'Cart '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    }
}
