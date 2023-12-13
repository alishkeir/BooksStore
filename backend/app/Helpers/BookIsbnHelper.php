<?php

namespace App\Helpers;

use Alomgyar\Products\Product;
use Illuminate\Support\Facades\Cache;

class BookIsbnHelper
{
    public function isbnCollection()
    {
        $isbnCollection = Cache::rememberForever('isbn-collections', function () {
            return Product::withoutGlobalScopes()->without(['author', 'prices'])->select('id', 'isbn')->get()->unique('isbn');
        });

        return $isbnCollection;
    }

    public function forgetIsbnCollection()
    {
        Cache::forget('isbn-collections');
    }

    public function regenerateIsbnCollection()
    {
        $this->forgetIsbnCollection();
        $this->isbnCollection();
    }

    public function isbnExists($searchedIsbn)
    {
        $isbns = $this->isbnCollection();

        $isbnExists = $isbns->contains('isbn', $searchedIsbn);

        return $isbnExists;
    }
}
