<?php

namespace App\Imports;

use Alomgyar\Products\Product;
use Alomgyar\Warehouses\Inventory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InventoryImport implements ToModel, WithStartRow
{
    protected $warehouseID;

    public function __construct($warehouseID)
    {
        $this->warehouseID = $warehouseID;
    }

    public function model(array $row)
    {
        return new Inventory([
            'product_id' => Product::where('isbn', $row[0])->first()->id,
            'warehouse_id' => $this->warehouseID,
            'stock' => $row[5],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
