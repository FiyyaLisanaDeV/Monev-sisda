<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProgresExcelImport implements WithMultipleSheets
{
    protected $importBatchId;

    public function __construct($importBatchId)
    {
        $this->importBatchId = $importBatchId;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheetNames = ['OPSDA', 'BENDUNGAN', 'BALAI', 'PJSA', 'PJPA'];

        foreach ($sheetNames as $sheetName) {
            $sheets[$sheetName] = new ProgresSheetImport($this->importBatchId, $sheetName);
        }

        return $sheets;
    }
}
