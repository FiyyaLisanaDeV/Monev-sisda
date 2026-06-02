<?php

namespace App\Filament\Resources\PaketProgres\Pages;

use App\Filament\Resources\PaketProgres\PaketProgresResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaketProgres extends ListRecords
{
    protected static string $resource = PaketProgresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
