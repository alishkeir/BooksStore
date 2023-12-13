<?php

namespace Alomgyar\Pages;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        $model = Page::latest()->paginate(25);

        return view('pages::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('pages::create');
    }

    public function store()
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $data['body'] = html_entity_decode(request()->body);
        $page = Page::create($data);

        session()->flash('success', 'Oldal sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $page, 'return_url' => route('pages.index')]);
    }

    public function edit(Page $page)
    {
        return view('pages::edit', [
            'model' => $page,
        ]);
    }

    public function update(Page $page)
    {
        $data = request()->all();
        $this->validateRequest();

        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $data['body'] = html_entity_decode(request()->body);
        $page->update($data);
        session()->flash('success', 'Oldal sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $page, 'return_url' => route('pages.edit', ['page' => $page->id])]);
    }

    public function show(Page $page)
    {
        return view('pages::show', [
            'model' => $page,
        ]);
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('pages.index')->with('success', 'Page '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'slug' => 'required',
        ]);
    }
}
