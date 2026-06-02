<?php

namespace App\Filament\Resources\ImportBatches\Pages;

use App\Filament\Resources\ImportBatches\ImportBatchResource;
use App\Jobs\ProcessProgresImportJob;
use Filament\Resources\Pages\CreateRecord;

class CreateImportBatch extends CreateRecord
{
    protected static string $resource = ImportBatchResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id() ?? 'admin';
        return $data;
    }

    protected function afterCreate(): void
    {
        ProcessProgresImportJob::dispatch($this->record->id);
    }
}
