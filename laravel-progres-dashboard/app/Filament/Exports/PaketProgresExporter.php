<?php

namespace App\Filament\Exports;

use App\Models\PaketProgres;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PaketProgresExporter extends Exporter
{
    protected static ?string $model = PaketProgres::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('satker'),
            ExportColumn::make('kode'),
            ExportColumn::make('paket'),
            ExportColumn::make('lokasi'),
            ExportColumn::make('jenis_paket'),
            ExportColumn::make('metode_pemilihan'),
            ExportColumn::make('sumber_dana'),
            ExportColumn::make('pagu'),
            ExportColumn::make('realisasi'),
            ExportColumn::make('pagu_setelah_efisiensi'),
            ExportColumn::make('sisa_anggaran'),
            ExportColumn::make('keuangan_percent'),
            ExportColumn::make('fisik_percent'),
            ExportColumn::make('keuangan_setelah_efisiensi_percent'),
            ExportColumn::make('fisik_setelah_efisiensi_percent'),
            ExportColumn::make('gap_fisik_keuangan'),
            ExportColumn::make('status_risiko'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data Paket Progres Anda telah selesai dan siap diunduh.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
