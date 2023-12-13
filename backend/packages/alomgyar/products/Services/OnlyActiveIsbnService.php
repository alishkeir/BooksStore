<?php

namespace Alomgyar\Products\Services;

use Alomgyar\Products\Jobs\Isbn\DuplicateIsbnModifyOldIdToNewForMovementsJob;

class OnlyActiveIsbnService
{
    // if there are only active ones, keep the newest version and merge the older versions' stock movements to the newest one
    public function handle($isbnGroup)
    {
        $data = (new IsbnService)->grabLatestFromCollection($isbnGroup);

        $newId = $data['newId'];
        $oldIds = $data['oldIds'];

        foreach ($oldIds as $key => $oldId) {
            DuplicateIsbnModifyOldIdToNewForMovementsJob::dispatch($oldId, $newId);
        }
    }
}
