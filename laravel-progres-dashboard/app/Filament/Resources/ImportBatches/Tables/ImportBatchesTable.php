<?php

namespace App\Filament\Resources\ImportBatches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImportBatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable(),
                TextColumn::make('periode')
                    ->label('Periode')
                    ->searchable(),
                TextColumn::make('tahun_anggaran')
                    ->label('TA')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'uploaded' => 'gray',
                        'processing' => 'warning',
                        'completed' => 'success',
                        'completed_with_warning' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('total_raw_rows')
                    ->label('Raw Rows')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_paket_detail')
                    ->label('Paket')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Waktu Upload')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
