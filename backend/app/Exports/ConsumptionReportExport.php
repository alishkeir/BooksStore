<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ConsumptionReportExport implements FromView
{
    protected $items;

    protected $details;

    public function __construct($items, $details)
    {
        $this->items = $items;
        $this->details = $details;
    }

    public function view(): View
    {
        return view('exports.consumption-report', [
            'model' => $this->items,
            'details' => $this->details,
        ]);
    }
}
