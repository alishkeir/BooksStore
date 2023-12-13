<?php

namespace App\Exports;

use Alomgyar\Products\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BooksWithoutPublisherExport implements FromCollection, WithMapping, WithHeadings
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
        $publishers = Product::query()
            ->select('id', 'title', 'isbn', 'type', 'status')
            ->whereNull('publisher_id')
            ->orderBy('title', 'asc')
            ->get();

        return $publishers;
    }

    public function map($publishers): array
    {
        return [
            $publishers->id,
            $publishers->title,
            $publishers->isbn,
            $publishers->type ? 'eKönyv' : 'könyv',
            $publishers->status ? 'Aktív' : 'Inaktív',

        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cím',
            'ISBN',
            'könyv / eKönyv',
            'Státusz',
        ];
    }
}
