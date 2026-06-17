<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;

class PenjualanMenuChart extends ChartWidget
{
    protected static bool $isDiscovered = false;
    protected static ?string $heading = '📊 Menu Terlaris';

    protected function getData(): array
    {
        $data = DetailPenjualan::join(
                'menu',
                'detail_penjualan.menu_id',
                '=',
                'menu.id'
            )
            ->select(
                'menu.nama_menu',
                DB::raw('SUM(detail_penjualan.qty) as total')
            )
            ->groupBy('menu.nama_menu')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_menu')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}