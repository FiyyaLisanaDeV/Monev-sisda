<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\Progres\DashboardQueryService;
use Illuminate\Contracts\Support\Htmlable;

class AnalisisLokasi extends Page
{
    protected string $view = 'filament.pages.analisis-lokasi';

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-map-pin';
    }

    public static function getNavigationLabel(): string
    {
        return 'Analisis Lokasi';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Analisis Berdasarkan Lokasi';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Analisis Mendalam';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    protected function getViewData(): array
    {
        $service = app(DashboardQueryService::class);
        $data = $service->baseQuery()
            ->selectRaw('
                lokasi, 
                COUNT(*) as jumlah_paket,
                SUM(pagu) as total_pagu,
                SUM(realisasi) as total_realisasi,
                SUM(CASE WHEN status_risiko = "Kritis" THEN 1 ELSE 0 END) as jumlah_kritis
            ')
            ->groupBy('lokasi')
            ->orderByDesc('total_pagu')
            ->limit(20) // Top 20 locations
            ->get();
            
        return ['lokasis' => $data];
    }
}
