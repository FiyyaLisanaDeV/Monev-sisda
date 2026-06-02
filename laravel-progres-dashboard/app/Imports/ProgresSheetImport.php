<?php

namespace App\Imports;

use App\Models\RawProgresRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProgresSheetImport implements ToCollection, WithChunkReading
{
    protected $importBatchId;
    protected $sheetName;
    protected $rowCounter = 1;

    public function __construct($importBatchId, $sheetName)
    {
        $this->importBatchId = $importBatchId;
        $this->sheetName = $sheetName;
    }

    public function collection(Collection $rows)
    {
        $insertData = [];
        foreach ($rows as $row) {
            $insertData[] = [
                'import_batch_id' => $this->importBatchId,
                'satker' => $this->sheetName,
                'sheet_name' => $this->sheetName,
                'row_number' => $this->rowCounter,
                'raw_data_json' => json_encode($row->toArray()),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $this->rowCounter++;
        }

        RawProgresRow::insert($insertData);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
