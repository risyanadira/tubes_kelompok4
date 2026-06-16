<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TrenMinumanChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected static ?string $heading = '🥤 Tren Minuman Pendamping';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Popularitas',
                    'data' => [92, 85, 71, 82, 94],
                ],
            ],
            'labels' => [
                'Es Teh Jumbo',
                'Thai Tea',
                'Es Kopi Susu',
                'Lemon Tea',
                'Matcha',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}