<?php

namespace App\Exports;

use Alomgyar\Publishers\Publisher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PublishersExport implements FromCollection, WithMapping, WithHeadings
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
        $publishers = Publisher::orderBy('title', 'asc')->get();

        return $publishers;
    }

    public function map($publishers): array
    {
        return [
            $publishers->id,
            $publishers->title,
            $publishers->status ? 'Aktív' : 'Inaktív',

        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Név',
            'Státusz',

        ];
    }
}
