<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    private $myArray;

    public function __construct($myArray, $myHeading)
    {
        $this->myArray = $myArray;
        $this->myHeading = $myHeading;
    }

    public function array(): array
    {
        return $this->myArray;
    }

    public function headings(): array
    {
        return $this->myHeading;
        //return [
        //    'Terméknév', 'Rendelésszám', 'Tétel ár', 'Tétel mennyiség', 'Hol', 'Mikor', 'Kiadó', 'Beszállító'
        //];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            //'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
            //'F'  => ['font' => ['color' => array('rgb' => 'e62934')]],
            //'I'  => ['font' => ['color' => array('rgb' => 'fbc72e')]],
            //'L'  => ['font' => ['color' => array('rgb' => '4971ff')]],
        ];
    }
}
