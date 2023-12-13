<?php

namespace Alomgyar\Posts;

use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        $model = Post::latest()->paginate(25);

        return view('posts::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('posts::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $data = request()->all();
        $data['body'] = html_entity_decode(request()->body);
        $post = Post::create($data);

        session()->flash('success', 'Bejegyzés sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $post, 'return_url' => route('posts.index')]);
//        return redirect()->route('posts.index')->with('success', 'Post sikeresen létrehozva!');
    }

    public function edit(Post $post)
    {
        return view('posts::edit', [
            'model' => $post,
        ]);
    }

    public function update(Post $post)
    {
        $data = $this->validateRequest();
        $data = request()->all();
        $data['body'] = html_entity_decode(request()->body);
        $post->update($data);

        session()->flash('success', 'Bejegyzés sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $post, 'return_url' => route('posts.index')]);
        //return redirect()->route('posts.edit', ['post' => $post->id]);
    }

    public function show(Post $post)
    {
        return view('posts::show', [
            'model' => $post,
        ]);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
        ]);
    }
}
