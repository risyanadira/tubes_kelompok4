<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\DetailPenjualan;

class TotalPenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Menu Terlaris';

    protected function getData(): array
    {
        $data = DetailPenjualan::query()
            ->join('menu', 'detail_penjualan.menu_id', '=', 'menu.id')
            ->selectRaw('
                menu.nama_menu,
                SUM(detail_penjualan.qty) as total_terjual
            ')
            ->groupBy('menu.nama_menu')
            ->orderByDesc('total_terjual')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total_terjual')->toArray(),

                    'backgroundColor' => [
                        '#2563EB', // biru
                        '#3B82F6',
                        '#60A5FA',
                        '#10B981', // hijau
                        '#34D399',
                        '#F59E0B', // orange
                        '#FBBF24',
                        '#EF4444', // merah
                        '#F87171',
                        '#8B5CF6', // ungu
                        '#A78BFA',
                    ],

                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                    'hoverOffset' => 10,
                ],
            ],

            'labels' => $data->pluck('nama_menu')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}