<?php

namespace App\Filament\Pages;

use App\Models\ImportBatch;
use App\Models\PaketProgres;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RiskStatusChart;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class ExecutiveDashboard extends BaseDashboard
{
    protected static string $routePath = '/';

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-presentation-chart-bar';
    }

    public static function getNavigationLabel(): string
    {
        return 'Ringkasan Eksekutif';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Ringkasan Eksekutif';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Pantauan Eksekutif';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            RiskStatusChart::class,
        ];
    }

    public function getHeader(): ?View
    {
        $latestBatch = ImportBatch::query()
            ->where('status', 'completed')
            ->latest('id')
            ->first();

        $totalPagu = (float) PaketProgres::query()->sum('pagu');
        $totalRealisasi = (float) PaketProgres::query()->sum('realisasi');
        $serapan = $totalPagu > 0 ? round(($totalRealisasi / $totalPagu) * 100, 2) : 0.0;
        $totalPaket = PaketProgres::query()->count();
        $paketKritis = PaketProgres::query()->where('status_risiko', 'Kritis')->count();
        $paketPerhatian = PaketProgres::query()->where('status_risiko', 'Perlu Perhatian')->count();

        return view('filament.pages.executive-dashboard-header', [
            'totalPagu' => $totalPagu,
            'totalRealisasi' => $totalRealisasi,
            'serapan' => $serapan,
            'totalPaket' => $totalPaket,
            'paketKritis' => $paketKritis,
            'paketPerhatian' => $paketPerhatian,
            'tahunAnggaran' => $latestBatch?->tahun_anggaran ?? now()->year,
        ]);
    }
}
