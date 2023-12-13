<?php

namespace App\Exports;

use Alomgyar\Products\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DuplicateISBNExport implements FromCollection, WithMapping, WithHeadings
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
            WHERE
                type = 1
            GROUP BY
                isbn
            HAVING
                total > 1
            '
        ));

        $books = Product::query()
            //->withoutGlobalScopes()
            ->select('id', 'title', 'isbn', 'status', 'type', 'created_at')
            ->whereIn('isbn', collect($duplicateIsbns)->pluck('isbn'))
            ->orderBy('isbn')
            ->orderBy('created_at')
            ->get();

        return $books;
    }

    public function map($books): array
    {
        $type = 'book';
        if ($books->type == Product::EBOOK) {
            $type = 'ebook';
        }

        return [
            $books->id,
            $books->title,
            $books->isbn,
            $books->status ? 'Aktív' : 'Inaktív',
            $type,
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
            'Könyv/Ekönyv',
            'Létrehozás dátuma',
        ];
    }
}
