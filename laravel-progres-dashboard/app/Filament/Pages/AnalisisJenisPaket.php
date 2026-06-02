<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\Progres\DashboardQueryService;
use Illuminate\Contracts\Support\Htmlable;

class AnalisisJenisPaket extends Page
{
    protected string $view = 'filament.pages.analisis-jenis-paket';

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-briefcase';
    }

    public static function getNavigationLabel(): string
    {
        return 'Analisis Jenis Paket';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Distribusi Jenis Paket';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Analisis Mendalam';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    protected function getViewData(): array
    {
        $service = app(DashboardQueryService::class);
        $data = $service->baseQuery()
            ->selectRaw('
                jenis_paket, 
                COUNT(*) as jumlah_paket,
                SUM(pagu) as total_pagu,
                SUM(realisasi) as total_realisasi,
                SUM(CASE WHEN status_risiko = "Kritis" THEN 1 ELSE 0 END) as jumlah_kritis
            ')
            ->groupBy('jenis_paket')
            ->orderByDesc('total_pagu')
            ->get();
            
        return ['jenis' => $data];
    }
}
