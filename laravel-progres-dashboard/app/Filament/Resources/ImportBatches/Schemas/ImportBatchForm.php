<?php

namespace App\Filament\Resources\ImportBatches\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ImportBatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('file_path')
                    ->label('File Excel')
                    ->directory('progres-imports')
                    ->storeFileNamesIn('file_name')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('periode')
                    ->label('Periode Laporan')
                    ->required()
                    ->placeholder('Contoh: Desember 2025'),
                TextInput::make('tahun_anggaran')
                    ->label('Tahun Anggaran')
                    ->required()
                    ->numeric()
                    ->default(date('Y')),
            ]);
    }
}
