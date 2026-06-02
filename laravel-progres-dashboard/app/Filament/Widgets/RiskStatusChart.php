<?php

namespace App\Filament\Widgets;

use App\Models\PaketProgres;
use Filament\Widgets\ChartWidget;

class RiskStatusChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Risiko Paket';
    protected ?string $description = 'Status aman, perhatian, review, dan kritis';
    protected static ?int $sort = 2;

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
                        '#5f5e5f',
                        '#fbb717',
                        '#e5e2e3',
                        '#ba1a1a',
                    ],
                    'borderColor' => '#fbf8ff',
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
            'cutout' => '68%',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
