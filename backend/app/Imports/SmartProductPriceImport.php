<?php

namespace App\Imports;

use App\Jobs\FindAndSetProductPriceJob;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SmartProductPriceImport implements ToCollection, WithHeadingRow
{
    /**
     * @param  array  $row
     * @return Parameter|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if (! empty($row['isbn']) && ! empty($row['uj_ar'])) {
                FindAndSetProductPriceJob::dispatch($row['isbn'], $row['uj_ar']);
            }
        }
    }
}
