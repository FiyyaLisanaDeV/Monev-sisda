<?php

namespace App\Filament\Widgets;

use App\Models\PaketProgres;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPagu = PaketProgres::sum('pagu');
        $totalRealisasi = PaketProgres::sum('realisasi');
        $serapan = $totalPagu > 0 ? ($totalRealisasi / $totalPagu * 100) : 0;

        return [
            Stat::make('Total Pagu', 'Rp ' . number_format($totalPagu, 0, ',', '.'))
                ->description('Dana dialokasikan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
            Stat::make('Total Realisasi', 'Rp ' . number_format($totalRealisasi, 0, ',', '.'))
                ->description('Sudah disalurkan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Persentase Serapan', number_format($serapan, 2) . '%')
                ->description('Serapan keseluruhan')
                ->descriptionIcon('heroicon-m-chart-bar-square')
                ->color('primary'),
        ];
    }
}
