<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TrenBaksoChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected static ?string $heading = '🍜 Tren Varian Bakso';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Popularitas',
                    'data' => [86, 88, 82, 90, 70],
                ],
            ],
            'labels' => [
                'Bakso Mercon',
                'Bakso Mozzarella',
                'Bakso Lava',
                'Bakso Kriwil',
                'Bakso Urat Jumbo',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}