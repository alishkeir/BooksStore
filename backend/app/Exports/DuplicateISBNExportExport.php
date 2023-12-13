<?php

namespace App\Exports;

use Alomgyar\Products\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DuplicateISBNExportExport implements FromCollection, WithMapping, WithHeadings
{
    // public $startDate;

    // public $endDate;

    // public function __construct($startDate, $endDate)
    // {
    //     $this->startDate = $startDate;
    //     $this->endDate = $endDate;
    // }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
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

        $books = Product::query()
            ->select('id', 'title', 'isbn', 'status', 'created_at')
            ->whereIn('isbn', collect($duplicateIsbns)->pluck('isbn'))
            ->orderBy('isbn')
            ->orderBy('created_at')
            ->get();

        return $books;
    }

    public function map($books): array
    {
        return [

            $books->id,
            $books->title,
            $books->isbn,
            $books->status ? 'Aktív' : 'Inaktív',
            $books->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cím',
            'ISBN',
            'Státusz',
            'Létrehozás dátuma',
        ];
    }
}
