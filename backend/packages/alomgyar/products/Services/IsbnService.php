<?php

namespace Alomgyar\Products\Services;

use Alomgyar\Product_movements\ProductMovementItems;
use Alomgyar\Products\Jobs\Isbn\HandleGroupJob;
use Alomgyar\Products\Jobs\Isbn\ModifyOldIdToNewForMovementItemsJob;
use Alomgyar\Products\Jobs\Isbn\MultipleActiveIsbnGroupJob;
use Alomgyar\Products\Jobs\Isbn\OneIsActiveIsbnGroupJob;
use Alomgyar\Products\Jobs\Isbn\OnlyInactiveIsbnGroupJob;
use Alomgyar\Products\Product;
use Illuminate\Support\Facades\DB;

class IsbnService
{
    public function isIsbnAlreadyExists($isbn)
    {
        $isbnExists = DB::table('product')->where('isbn', $isbn)->exists();

        return $isbnExists;
    }

    public function isIsbnAlreadyExistsWithSameType($isbn, $type)
    {
        $isbnExists = DB::table('product')->where('isbn', $isbn)->where('type', $type)->exists();

        return $isbnExists;
    }

    // IT QUERY FIRST ONLY ON EBOOKS
    // TYPE = 1 = EBOOK
    public function getDuplicateEbookIsbnQuery()
    {
        $duplicateIsbns = DB::select(DB::raw(
            'SELECT isbn, COUNT(*) as total
            FROM
                product
            WHERE
                type = 1
            GROUP BY
                isbn
            HAVING
                total > 1
            '
        ));

        return $duplicateIsbns;
    }

    // THIS IS GENERAL
    public function getDuplicateBookIsbnQuery()
    {
        $duplicateIsbns = DB::select(DB::raw(
            'SELECT isbn, COUNT(*) as total
            FROM
                product

            GROUP BY
                isbn
            HAVING
                total > 1
            '
        ));

        return $duplicateIsbns;
    }

    public function handleDuplicationsWithSameIsbn()
    {
        $duplicateIsbns = $this->getDuplicateEbookIsbnQuery();
        $ebookIsbnGroups = Product::query()
            ->withoutGlobalScopes()
            ->select('id', 'title', 'isbn', 'status', 'type', 'created_at')
            ->whereIn('isbn', collect($duplicateIsbns)->pluck('isbn'))
            ->orderBy('isbn')
            ->orderBy('created_at')
            ->without(['author', 'prices'])
            ->get()
            ->groupBy('isbn');

        foreach ($ebookIsbnGroups as $key => $isbnGroup) {
            // RUN WITH JOB, SO IT CAN WORK IN BACKGROUND
            HandleGroupJob::dispatch($isbnGroup);
        }

        return 'YAAY';
    }

    public function isbnGroupHandle($isbnGroup)
    {
        // CHECK OF ACTIVE RECORDS NUMBER
        $activeCount = $isbnGroup->where('status', Product::STATUS_ACTIVE)->count();

        $todo = match ($activeCount) {
            // if there are only inactive ones, keep the newest version and merge the older versions' stock movements to the newest one
            0 => OnlyInactiveIsbnGroupJob::dispatch($isbnGroup),
            // if there is one active and one inactive, merge the stock movements of the inactive one to the active one
            1 => OneIsActiveIsbnGroupJob::dispatch($isbnGroup),
            // if there are only active ones, keep the newest version and merge the older versions' stock movements to the newest one
            default => MultipleActiveIsbnGroupJob::dispatch($isbnGroup),
        };
    }

    // GET PRODUCT

    public function modify($oldId, $newId)
    {
        $productMovementItems = ProductMovementItems::query()
            ->withoutGlobalScopes()
            ->where('product_id', $oldId)
            ->get();

        foreach ($productMovementItems as $key => $productMovementItem) {
            ModifyOldIdToNewForMovementItemsJob::dispatch($productMovementItem, $newId, $oldId);
        }
    }

    public function modifyByDictionary()
    {
        $dictionary = (new DuplicateBookDictionary)->dictionary();

        foreach ($dictionary as $oldId => $newId) {
            $this->modify($oldId, $newId);
        }
    }

    public function modifyByDictionaryTwo()
    {
        $dictionary = (new DuplicateBookDictionary)->dictionaryTwo();

        foreach ($dictionary as $oldId => $newId) {
            $this->modify($oldId, $newId);
        }
    }

    public function deleteOldBooks()
    {
        $deleteIds = (new DuplicateBookDictionary)->deletableIdFromDictionaries();

        $products = Product::whereIntegerInRaw('id', $deleteIds)->delete();
    }

    public function grabLatestFromCollection($isbnGroup)
    {
        $oldIds = [];
        $newId = null;

        // GRABBING THE CREATED AT DATE
        // WE COULD USE ID
        // BUT ERROR COMES WHEN SYNC FROM EXTERNAL SITE HAS
        // THE SAME ISBN
        // SAME CREATED
        $latestCreatedAtById = $isbnGroup->max('id');

        // POPULATE GIVEN VARIABLES
        foreach ($isbnGroup as $key => $book) {
            if ($book->id == $latestCreatedAtById) {
                $newId = $book->id;
            } else {
                $oldIds[] = $book->id;
            }
        }

        $data = [
            'newId' => $newId,
            'oldIds' => $oldIds,
        ];

        return $data;
    }
}
