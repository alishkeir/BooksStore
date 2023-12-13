<?php

namespace Alomgyar\Products\Services;

use Alomgyar\Products\Jobs\Isbn\DuplicateIsbnModifyOldIdToNewForMovementsJob;
use Alomgyar\Products\Product;

class OneActiveIsbnService
{
    // if there is one active and one inactive, merge the stock movements of the inactive one to the active one
    public function handle($isbnGroup)
    {
        $oldIds = [];
        $newId = null;

        // POPULATE GIVEN VARIABLES
        foreach ($isbnGroup as $key => $book) {
            if ($book->status == Product::STATUS_ACTIVE) {
                $newId = $book->id;
            } else {
                $oldIds[] = $book->id;
            }
        }

        foreach ($oldIds as $key => $oldId) {
            DuplicateIsbnModifyOldIdToNewForMovementsJob::dispatch($oldId, $newId);
        }
    }
}
