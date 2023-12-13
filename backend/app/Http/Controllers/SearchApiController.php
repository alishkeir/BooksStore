<?php

namespace App\Http\Controllers;

use Alomgyar\Authors\ApiAuthor as Author;
use Alomgyar\Products\ApiProduct as Product;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\SearchAuthorResource;
use App\Http\Resources\SearchProductResource;
use App\Http\Traits\ErrorMessages;
use Illuminate\Support\Str;

class SearchApiController extends Controller
{
    use ErrorMessages;

    private int $limit;

    private int $perPage;

    private int $bookPage;

    private int $ebookPage;

    private string $term;

    private bool $is_searchEbook = true;

    public function __invoke()
    {
        if (! isset(request()->body['term']) || Str::length(request()->body['term']) < 3) {
            return response(['data' => null]);
        }

        $this->perPage = 10;
        $this->term = trim(request()->body['term']);
        $this->bookPage = request()->body['book_page'] ?? 1;
        $this->ebookPage = request()->body['ebook_page'] ?? 1;
        $this->is_searchEbook = in_array(request('store'), [0, 1]);

        if (request()->body['in_header']) {
            return $this->headerSearch();
        }

        return $this->fullSearch();
    }

    private function headerSearch()
    {
        $books = $this->findBooksHeader();
        $ebooks = $this->is_searchEbook ? $this->findeBooksHeader() : [];
        $authors = $this->findAuthorsHeader();

        return response([
            'data' => [
                'books' => SearchProductResource::collection($books),
                'ebooks' => SearchProductResource::collection($ebooks),
                'authors' => SearchAuthorResource::collection($authors),
            ],
        ]);
    }

    private function fullSearch()
    {
        $books = $this->findBooksFull();
        $ebooks = $this->is_searchEbook ? $this->findeBooksFull() : [];
        $authors = $this->findAuthorsFull();

        return response([
            'data' => [
                'books' => [
                    'products' => ProductListResource::collection($books),
                    'pagination' => [
                        'current_page' => $this->bookPage,
                        'per_page' => $this->perPage,
                        'total' => $books->total(),
                        'last_page' => $books->currentPage() === $books->lastPage(),
                    ],
                ],
                'ebooks' => [
                    'products' => ProductListResource::collection($ebooks),
                    'pagination' => $this->is_searchEbook ? [
                        'current_page' => $this->ebookPage,
                        'per_page' => $this->perPage,
                        'total' => $ebooks->total(),
                        'last_page' => $ebooks->currentPage() === $ebooks->lastPage(),
                    ] : [],
                ],
                'authors' => AuthorResource::collection($authors),
                'total_results' => $books->total() + ($this->is_searchEbook ? $ebooks->total() : 0) + count($authors),
            ],
        ]);
    }

    private function findBooksHeader()
    {
        return Product::headerSearch($this->term, Product::BOOK)->limit(3)->get();
    }

    private function findeBooksHeader()
    {
        return Product::headerSearch($this->term, Product::EBOOK)->limit(3)->get();
    }

    private function findAuthorsHeader()
    {
        return Author::headerSearch($this->term)->limit(3)->get();
    }

    private function findBooksFull()
    {
        return Product::fullSearch($this->term, Product::BOOK)->paginate($this->perPage, ['*'], 'page', $this->bookPage);
    }

    private function findeBooksFull()
    {
        return Product::fullSearch($this->term, Product::EBOOK)->paginate($this->perPage, ['*'], 'page', $this->ebookPage);
    }

    private function findAuthorsFull()
    {
        return Author::fullSearch($this->term)->limit(25)->get();
    }
}
