<?php

namespace App\Imports;

use Alomgyar\Products\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductPriceImport implements ToModel, WithStartRow
{
    /**
     * @return Parameter|null
     */
    public function model(array $row)
    {
        return new Product([
            'id' => $row[0],
            'title' => $row[1],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
