<?php

namespace App\Helpers\External;

use Alomgyar\Authors\Author;
use Alomgyar\Publishers\Publisher;
use Alomgyar\Subcategories\Subcategory;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Book24Helper
{
    public const NOT_AVAILABLE = 0;

    public const PREORDER_STRING = 'Preorder';

    public const STOCK_STRING = 'InStock';

    public static function getProductIsbn($productXml)
    {
        return isset($productXml->ISBN) && ! empty($productXml->ISBN) ? $productXml->ISBN : $productXml->EanCode;
    }

    public static function isPreorder($productXml)
    {
        return $productXml->Status == self::PREORDER_STRING and $productXml->Availability == self::NOT_AVAILABLE;
    }

    public static function isAvailable($productXml)
    {
        return $productXml->Status == self::STOCK_STRING;
    }

    public static function limitCheck($limited, $reachedLimit)
    {
        return ! $limited || ($limited && ! $reachedLimit);
    }

    public static function errorLog($errorString)
    {
        Log::channel('book24')->error($errorString);
    }

    public function handleAuthors($productId, $authors, $primaryAuthorName)
    {
        foreach ($authors as $author) {
            $id = $this->handleAuthor($author);
            $authorIds[] = $id;
            if ($author == $primaryAuthorName) {
                $primaryAuthorId = $id;
            }
        }
        foreach ($authorIds as $authorId) {
            try {
                $productAuthorExists = Cache::remember('sync-product-author-exists-'.$productId.'-'.$authorId, 60 * 60 * 2, function () use ($productId, $authorId) {
                    return DB::table('product_author')
                        ->where('product_id', $productId)
                        ->where('author_id', $authorId)
                        ->exists();
                });

                if (! $productAuthorExists) {
                    $productAuthor = DB::table('product_author')->insert([
                        'product_id' => $productId,
                        'author_id' => $authorId,
                        'primary' => $authorId == ($primaryAuthorId ?? false) ? 1 : 0,
                    ]);
                }
            } catch (QueryException $queryException) {
                continue;
            }
        }
    }

    public function handleAuthor($author)
    {
        if (empty($author) || $author == '-') {
            return null;
        }
        $authorSlug = Str::slug($author);
        $getAuthor = Cache::remember('sync-author-get-id-'.$authorSlug, 60 * 60 * 4, function () use ($author, $authorSlug) {
            return Author::query()
                ->select('id')
                ->where('title', $author)
                ->orWhere('slug', $authorSlug)
                ->first();
        });

        if (! $getAuthor) {
            $getAuthor = Author::create([
                'title' => $author,
                'slug' => $authorSlug,
                'status' => 1,
            ]);
        }

        return $getAuthor->id;
    }

    public function handleCategory($categories, $productId)
    {
        // THIS CATEGORY FORMAT IS EQUAL
        // <Category>Fantasy | Paranormális misztikus fantasy</Category>

        $transformedCategories = explode(' | ', $categories);

        foreach ($transformedCategories as $categoryName) {
            $getCategory = Cache::rememberForever('sync-category-get-id-model-'.$categoryName, function () use ($categoryName) {
                return Subcategory::query()
                    ->select('id')
                    ->where('title', $categoryName)
                    ->orWhere('slug', Str::slug($categoryName, '-'))
                    ->first();
            });

            if (empty($getCategory)) {
                try {
                    $getCategory = Subcategory::create([
                        'title' => $categoryName,
                        'slug' => Str::slug($categoryName, '-'),
                        'status' => 1,
                    ]);
                } catch (QueryException $queryException) {
                    continue;
                }
            }

            try {
                $productSubCategoryExists = Cache::remember('sync-product-subcategory-exists-'.$productId.'-'.$getCategory->id, 60 * 60 * 4, function () use ($productId, $getCategory) {
                    return DB::table('product_subcategory')
                    ->where('product_id', $productId)
                    ->where('subcategory_id', $getCategory->id)
                    ->exists();
                });

                if (! $productSubCategoryExists) {
                    DB::table('product_subcategory')->insert([
                        'product_id' => $productId,
                        'subcategory_id' => $getCategory->id,
                    ]);
                }
            } catch (QueryException $queryException) {
                $this->errorLog('category ['.$getCategory->id.']: '.$queryException);

                continue;
            }
        }
    }

    public function handlePublisher($product, $publisher)
    {
        if (empty($publisher) || $publisher == '-') {
            return null;
        }

        $getPublisher = Cache::rememberForever('sync-publisher-get-id-'.$publisher, function () use ($publisher) {
            return Publisher::query()
                ->select('id')
                ->where('title', $publisher)
                ->first();
        });

        if (empty($getPublisher)) {
            return; //don't save a new publisher if it doesn't already exist
        }
        try {
            $getPublisher->products()->save($product);
        } catch (QueryException $queryException) {
            $this->errorLog('publisher ['.$getPublisher->id.']: '.$queryException);

            return;
        }
    }
}
