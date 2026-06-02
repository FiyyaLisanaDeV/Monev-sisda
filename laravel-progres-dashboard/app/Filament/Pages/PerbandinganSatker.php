<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\Progres\DashboardQueryService;
use Illuminate\Contracts\Support\Htmlable;

class PerbandinganSatker extends Page
{
    protected string $view = 'filament.pages.perbandingan-satker';

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-building-office-2';
    }

    public static function getNavigationLabel(): string
    {
        return 'Perbandingan Satker';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Perbandingan Kinerja Satker';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Analisis Mendalam';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    protected function getViewData(): array
    {
        $service = app(DashboardQueryService::class);
        $data = $service->baseQuery()
            ->selectRaw('
                satker, 
                COUNT(*) as jumlah_paket,
                SUM(pagu) as total_pagu,
                SUM(realisasi) as total_realisasi,
                SUM(sisa_anggaran) as total_sisa_anggaran,
                AVG(fisik_percent) as rata_rata_fisik,
                SUM(CASE WHEN status_risiko = "Kritis" THEN 1 ELSE 0 END) as jumlah_kritis
            ')
            ->groupBy('satker')
            ->orderByDesc('total_pagu')
            ->get();
            
        return ['satkers' => $data];
    }
}
