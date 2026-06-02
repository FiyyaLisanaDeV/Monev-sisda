<?php

namespace App\Filament\Resources\PaketProgres\Tables;

use App\Models\PaketProgres;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaketProgresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')->label('Kode')->searchable(),
                TextColumn::make('paket')->label('Paket')->searchable()->wrap(),
                TextColumn::make('satker')->label('Satker')->searchable(),
                TextColumn::make('pagu')->label('Pagu')
                    ->numeric(locale: 'id')
                    ->sortable(),
                TextColumn::make('realisasi')->label('Realisasi')
                    ->numeric(locale: 'id')
                    ->sortable(),
                TextColumn::make('keuangan_percent')->label('Keuangan (%)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fisik_percent')->label('Fisik (%)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status_risiko')->label('Status Risiko')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aman' => 'success',
                        'Perlu Perhatian' => 'warning',
                        'Kritis' => 'danger',
                        'Perlu Review' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('satker')
                    ->options(fn () => PaketProgres::select('satker')->distinct()->pluck('satker', 'satker')->toArray()),
                SelectFilter::make('status_risiko')
                    ->options([
                        'Aman' => 'Aman',
                        'Perlu Perhatian' => 'Perlu Perhatian',
                        'Kritis' => 'Kritis',
                        'Perlu Review' => 'Perlu Review',
                    ]),
            ])
            ->defaultSort('risk_score', 'desc');
    }
}
