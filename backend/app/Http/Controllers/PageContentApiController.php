<?php

namespace App\Http\Controllers;

use Alomgyar\Pages\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageContentApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $page = Page::where([
            'slug' => $request->get('body')['slug'] ?? null,
            'status' => 1,
            sprintf('store_%s', request('store')) => 1,
        ])->firstOrFail();

        return response()->json([
            'title' => $page->title,
            'body' => $page->body,
            //'cover' => $page->getCoverAsWebp(),
            'cover' => $page->cover,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
        ]);
    }
}
