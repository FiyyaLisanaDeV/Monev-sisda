<?php

namespace App\Filament\Resources\PaketProgres\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaketProgresForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->disabled(),
                Textarea::make('paket')
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('satker')
                    ->disabled(),
                TextInput::make('pagu')
                    ->numeric()
                    ->disabled(),
                TextInput::make('realisasi')
                    ->numeric()
                    ->disabled(),
                TextInput::make('keuangan_percent')
                    ->numeric()
                    ->disabled(),
                TextInput::make('fisik_percent')
                    ->numeric()
                    ->disabled(),
            ]);
    }
}
