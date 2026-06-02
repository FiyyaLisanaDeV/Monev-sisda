<?php

namespace App\Jobs;

use App\Models\ImportBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

class ProcessProgresImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $importBatchId;

    public function __construct($importBatchId)
    {
        $this->importBatchId = $importBatchId;
    }

    public function handle(): void
    {
        $batch = ImportBatch::find($this->importBatchId);
        if (!$batch) return;

        try {
            $batch->update([
                'status' => 'processing',
                'started_at' => now(),
            ]);

            $filePath = storage_path('app/private/' . $batch->file_path);
            
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\ProgresExcelImport($batch->id), 
                $filePath
            );

            // Update total rows for audit
            $totalRaw = \App\Models\RawProgresRow::where('import_batch_id', $batch->id)->count();
            $batch->update([
                'total_raw_rows' => $totalRaw,
            ]);

            // Execute DataCleanerService
            app(\App\Services\Progres\DataCleanerService::class)->process($batch);
            $batch->update([
                'status' => 'completed',
                'finished_at' => now(),
            ]);

        } catch (Exception $e) {
            $batch->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'finished_at' => now(),
            ]);
        }
    }
}
