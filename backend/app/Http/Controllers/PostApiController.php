<?php

namespace App\Http\Controllers;

use Alomgyar\Posts\ApiPost as Post;
use App\Http\Resources\PostListResource;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    public function lastPosts(Request $request)
    {
        $takeAmount = $request->get('amount', 4);

        $posts = Post::active()
                    ->byStore()
                    ->orderBy('published_at', 'DESC')
                    ->take($takeAmount)
                    ->get();

        return [
            'data' => [
                'posts' => PostListResource::collection($posts),
            ],
        ];
    }
}
