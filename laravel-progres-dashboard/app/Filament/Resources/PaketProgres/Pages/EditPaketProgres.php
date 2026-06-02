<?php

namespace App\Filament\Resources\PaketProgres\Pages;

use App\Filament\Resources\PaketProgres\PaketProgresResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaketProgres extends EditRecord
{
    protected static string $resource = PaketProgresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
