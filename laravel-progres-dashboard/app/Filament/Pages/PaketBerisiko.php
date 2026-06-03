<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use App\Models\PaketProgres;
use App\Services\Progres\DashboardQueryService;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Exports\PaketProgresExporter;
use Filament\Actions\ViewAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;

class PaketBerisiko extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.paket-berisiko';

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-exclamation-triangle';
    }

    public static function getNavigationLabel(): string
    {
        return 'Paket Berisiko';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Daftar Paket Berisiko (Kritis & Perhatian)';
    }

    public static function getNavigationGroup(): ?string { return 'Pantauan Eksekutif'; }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public function table(Table $table): Table
    {
        $service = app(DashboardQueryService::class);

        return $table
            ->query(
                $service->baseQuery()
                    ->whereIn('status_risiko', ['Kritis', 'Perlu Perhatian'])
                    ->orderByDesc('risk_score')
                    ->orderByDesc('sisa_anggaran')
                    ->orderByDesc('pagu_setelah_efisiensi')
            )
            ->columns([
                TextColumn::make('status_risiko')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Kritis' => 'danger',
                        'Perlu Perhatian' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('satker')->label('Satker')->searchable()->sortable()->toggleable(),
                TextColumn::make('lokasi')->label('Lokasi')->searchable()->sortable()->toggleable(),
                TextColumn::make('kode')->label('Kode')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('paket')->label('Nama Paket')->searchable()->wrap(),
                TextColumn::make('jenis_paket')->label('Jenis Paket')->searchable()->toggleable(),
                
                TextColumn::make('pagu_setelah_efisiensi')
                    ->label('Pagu Efisiensi')
                    ->numeric(locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('realisasi')
                    ->label('Realisasi')
                    ->numeric(locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('sisa_anggaran')
                    ->label('Sisa Anggaran')
                    ->numeric(locale: 'id')
                    ->sortable()
                    ->color('danger')
                    ->weight('bold'),
                    
                TextColumn::make('keuangan_setelah_efisiensi_percent')
                    ->label('Keu (%)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                    
                TextColumn::make('fisik_setelah_efisiensi_percent')
                    ->label('Fisik (%)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                    
                TextColumn::make('gap_fisik_keuangan')
                    ->label('Deviasi/Gap (%)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn ($state) => $state < -10 || $state > 15 ? 'danger' : 'warning'),

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->wrap()
                    ->state(function ($record): string {
                        $reasons = [];
                        $realisasi = $record->realisasi ?? 0;
                        $keu = $record->keuangan_setelah_efisiensi_percent ?? ($record->keuangan_percent ?? 0);
                        $fisik = $record->fisik_setelah_efisiensi_percent ?? ($record->fisik_percent ?? 0);
                        $gap = abs($record->gap_fisik_keuangan ?? 0);

                        if ($realisasi == 0) {
                            $reasons[] = '⛔ Realisasi Rp 0 (belum ada pencairan)';
                        }
                        if ($keu < 70) {
                            $reasons[] = '🔴 Serapan keuangan sangat rendah (' . number_format($keu, 1) . '%)';
                        } elseif ($keu < 90) {
                            $reasons[] = '🟡 Serapan keuangan di bawah target (' . number_format($keu, 1) . '%)';
                        }
                        if ($fisik < 70) {
                            $reasons[] = '🔴 Progres fisik sangat rendah (' . number_format($fisik, 1) . '%)';
                        } elseif ($fisik < 90) {
                            $reasons[] = '🟡 Progres fisik di bawah target (' . number_format($fisik, 1) . '%)';
                        }
                        if ($gap > 15) {
                            $reasons[] = '⚠️ Deviasi fisik-keuangan tinggi (' . number_format($gap, 1) . '%)';
                        }

                        return implode("\n", $reasons) ?: 'Tidak ada catatan';
                    })
                    ->color(fn ($record): string => ($record->status_risiko ?? '') === 'Kritis' ? 'danger' : 'warning'),
            ])
            ->filters([
                SelectFilter::make('satker')
                    ->label('Filter Satker')
                    ->options(fn () => PaketProgres::select('satker')->distinct()->pluck('satker', 'satker')->toArray()),
                SelectFilter::make('lokasi')
                    ->label('Filter Lokasi')
                    ->options(fn () => PaketProgres::select('lokasi')->whereNotNull('lokasi')->distinct()->pluck('lokasi', 'lokasi')->toArray()),
                SelectFilter::make('jenis_paket')
                    ->label('Filter Jenis Paket')
                    ->options(fn () => PaketProgres::select('jenis_paket')->whereNotNull('jenis_paket')->distinct()->pluck('jenis_paket', 'jenis_paket')->toArray()),
                SelectFilter::make('status_risiko')
                    ->label('Filter Status')
                    ->options([
                        'Kritis' => 'Kritis',
                        'Perlu Perhatian' => 'Perlu Perhatian',
                    ]),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Lihat Detail')
                    ->modalHeading('Detail Informasi Paket')
                    ->stickyModalHeader()
                    ->modalWidth('5xl')
                    ->modalContent(fn ($record) => view('filament.partials.paket-detail-modal', [
                        'record' => $record,
                    ])),
            ])
            ->recordAction(ViewAction::class)
            ->bulkActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(PaketProgresExporter::class)->label('Export Terpilih (Excel/CSV)'),
                ]),
            ])
            ->headerActions([
                ExportAction::make()->exporter(PaketProgresExporter::class)->label('Export Semua Data'),
            ]);
    }
}
