<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\AiInsight;

class InsightPenjualanWidget extends Widget
{
    protected static bool $isDiscovered = false;
    protected static string $view =
        'filament.widgets.insight-penjualan-widget';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'insight' => AiInsight::where(
                'tipe',
                'penjualan'
            )->latest()->first(),
        ];
    }
}