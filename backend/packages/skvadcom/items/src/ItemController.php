<?php

namespace Skvadcom\Items;

use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function index()
    {
        $model = Item::latest()->paginate(25);

        return view('items::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('items::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $item = Item::create(request()->all());

        $item->addMediaFromDisk('/items/covers/'.$data['cover'], 'public')
            ->toMediaCollection('cover');

        foreach ($data['gallery'] as $gallery) {
            $item->addMediaFromDisk('/items/gallery/'.$gallery, 'public')
                ->toMediaCollection('gallery');
        }

        session()->flash('success', 'Item sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $item, 'return_url' => route('items.index')]);
//        return redirect()->route('items.index')->with('success', 'Item sikeresen létrehozva!');
    }

    public function edit(Item $item)
    {
        return view('items::edit', [
            'model' => $item,
        ]);
    }

    public function update(Item $item)
    {
        $data = $this->validateRequest();
        $item->update($data);

        if (! empty($data['cover'])) {
            $item->clearMediaCollection('cover');
            $item->addMediaFromDisk('/items/covers/'.$data['cover'], 'public')
                ->toMediaCollection('cover');
        }

        if (! empty($data['gallery'])) {
            $item->clearMediaCollection('gallery');
            foreach ($data['gallery'] as $gallery) {
                $item->addMediaFromDisk('/items/gallery/'.$gallery, 'public')
                    ->toMediaCollection('gallery');
            }
        }

        session()->flash('success', 'Item sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $item, 'return_url' => route('items.index')]);
//        return redirect()->route('items.index', ['item' => $item->id]);
    }

    public function show(Item $item)
    {
        return view('items::show', [
            'model' => $item,
        ]);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    }
}
