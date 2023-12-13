<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class MerchantConsumptionReportImport extends DefaultValueBinder implements ToCollection, WithStartRow, WithCustomValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        if ($cell->isFormula()) {
            $cell->setValueExplicit($value, DataType::TYPE_FORMULA);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function collection(Collection $rows)
    {
        return $rows;
    }

    public function startRow(): int
    {
        return 2;
    }
}
