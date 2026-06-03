<?php

namespace App\Filament\Widgets;

use App\Models\PaketProgres;
use Filament\Widgets\ChartWidget;

class RiskStatusChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Risiko Paket';
    protected ?string $description = 'Status aman, perhatian, review, dan kritis';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $aman = PaketProgres::where('status_risiko', 'Aman')->count();
        $perhatian = PaketProgres::where('status_risiko', 'Perlu Perhatian')->count();
        $review = PaketProgres::where('status_risiko', 'Perlu Review')->count();
        $kritis = PaketProgres::where('status_risiko', 'Kritis')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Paket',
                    'data' => [$aman, $perhatian, $review, $kritis],
                    'backgroundColor' => [
                        '#10b981', // Aman: Green
                        '#f59e0b', // Perhatian: Orange
                        '#3b82f6', // Perlu Review: Blue
                        '#ef4444', // Kritis: Red
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Aman', 'Perhatian', 'Perlu Review', 'Kritis'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '65%',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
