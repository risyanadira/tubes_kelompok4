<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PengembanganMenuChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected static ?string $heading = '💡 Potensi Pengembangan Menu';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Potensi',
                    'data' => [95, 75, 88, 80, 60],
                ],
            ],
            'labels' => [
                'Bakso Mozzarella',
                'Bakso Sambal Matah',
                'Bakso Lava Level',
                'Paket Hemat',
                'Bakso BBQ',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}