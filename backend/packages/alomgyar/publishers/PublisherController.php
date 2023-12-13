<?php

namespace Alomgyar\Publishers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductSelectResource;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        $model = Publisher::latest()->paginate(25);

        return view('publishers::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('publishers::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $publisher = Publisher::create(request()->all());

        session()->flash('success', 'Kiadó sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $publisher, 'return_url' => route('publishers.index')]);
//        return redirect()->route('publishers.index')->with('success', 'Publisher sikeresen létrehozva!');
    }

    public function edit(Publisher $publisher)
    {
        return view('publishers::edit', [
            'model' => $publisher,
        ]);
    }

    public function update(Publisher $publisher)
    {
        $data = $this->validateRequest();
        $publisher->update($data);

        session()->flash('success', 'Kiadó sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $publisher, 'return_url' => route('publishers.index')]);
//        return redirect()->route('publishers.index', ['publisher' => $publisher->id]);
    }

    public function show(Publisher $publisher)
    {
        return view('publishers::show', [
            'model' => $publisher,
        ]);
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Publisher '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
        ]);
    }

    public function search(Request $request)
    {
        $term = trim($request->q);
        $onlyBooks = $request->onlyBooks;

        $publishers = Publisher::select('id', 'title')
                           ->search($term)
                           ->latest();

        $publishers = $publishers->paginate(25);

        return response([
            'results' => ProductSelectResource::collection($publishers),
            'pagination' => [
                'more' => $publishers->currentPage() !== $publishers->lastPage(),
            ],
        ]);
    }
}
