<?php

namespace App\Exports;

use Alomgyar\Warehouses\Inventory;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehouseExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $warehouseID;

    public function __construct($warehouseID)
    {
        $this->warehouseID = $warehouseID;
    }

    public function collection()
    {
        $collect = collect([]);
        Inventory::with('product')
                 ->where('warehouse_id', $this->warehouseID)->get()->each(function ($item) use ($collect) {
                     if ($item->product ?? false && $item ?? false) {
                         $collect->push(collect([
                             (string) $item->product->isbn ?? 'Hiányzó adat',
                             $item->product->title,
                             $item->stock,
                             $item->product->stock,
                             null,
                         ]));

                         return $collect;
                     }
                 });

        return $collect;
    }

    public function headings(): array
    {
        return [
            'Isbn',
            'Cím',
            'Készlet a raktárban',
            'Készlet összesen',
            'ÚJ KÉSZLET',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
