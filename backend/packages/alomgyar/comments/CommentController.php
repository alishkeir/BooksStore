<?php

namespace Alomgyar\Comments;

use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index()
    {
        $model = Comment::latest()->paginate(25);

        return view('comments::index', [
            'model' => $model,
        ]);
    }

    public function edit(Comment $comment)
    {
        return view('comments::edit', [
            'model' => $comment,
        ]);
    }

    public function update(Comment $comment)
    {
        $data = request()->all();
        $this->validateRequest();
        $comment->update($data);

        session()->flash('success', 'Hozzászólás sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $comment, 'return_url' => route('comments.index')]);
    }

    protected function validateRequest()
    {
        return request()->validate([
            'comment' => 'required',
        ]);
    }
}
