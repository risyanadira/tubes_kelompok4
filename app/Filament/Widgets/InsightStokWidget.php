<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\AiInsight;

class InsightStokWidget extends Widget
{
    protected static bool $isDiscovered = false;
    protected static string $view = 'filament.widgets.insight-stok-widget';

    protected int|string|array $columnSpan = 1;

    public function getViewData(): array
    {
        return [
            'insight' => AiInsight::latest()->first(),
        ];
    }
}