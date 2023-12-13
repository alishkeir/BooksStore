<?php

namespace Alomgyar\Authors;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorSelectResource;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $model = Author::latest()->paginate(25);

        return view('authors::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('authors::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $author = Author::create(request()->all());

        session()->flash('success', 'Szerző sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $author, 'return_url' => route('authors.index')]);
    }

    public function edit(Author $author)
    {
        return view('authors::edit', [
            'model' => $author,
        ]);
    }

    public function update(Author $author)
    {
        $data = request()->all();
        $this->validateRequest();
        if (! ($data['status'] ?? false)) {
            $data['status'] = 0;
        }
        $author->update($data);

        session()->flash('success', 'Szerző sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $author, 'return_url' => route('authors.edit', ['author' => $author->id])]);
    }

    public function show(Author $author)
    {
        return view('authors::show', [
            'model' => $author,
        ]);
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Szerző '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'slug' => 'required',
        ]);
    }

    public function search(Request $request)
    {
        $term = trim($request->q);

        $authors = Author::select('id', 'title')
                           ->search($term)
                           ->latest()
                           ->paginate(25);

        return response([
            'results' => AuthorSelectResource::collection($authors),
            'pagination' => [
                'more' => $authors->currentPage() !== $authors->lastPage(),
            ],
        ]);
    }
}
