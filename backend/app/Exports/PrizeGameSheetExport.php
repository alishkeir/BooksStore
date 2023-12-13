<?php

namespace App\Exports;

use App\PrizeGamingForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrizeGameSheetExport implements FromCollection, WithMapping, WithHeadings
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
        $participants = PrizeGamingForm::all();

        return $participants;
    }

    public function map($projectTasks): array
    {
        return [

            $projectTasks->name,
            $projectTasks->email,
            $projectTasks->phone,
            $projectTasks->address,
            $projectTasks->order_number,
            $projectTasks->prize_game_form,

        ];
    }

    public function headings(): array
    {
        return [
            'Név',
            'Email cím',
            'Telefonszám',
            'Bolt cím',
            'Nyugta/blokk',
            'Játék',
        ];
    }
}
