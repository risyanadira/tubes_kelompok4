<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PenggunaanBB;
use Illuminate\Support\Facades\DB;

class PenggunaanBahanBakuChart extends ChartWidget
{
    protected static bool $isDiscovered = false;
    protected static ?string $heading = '📊 Penggunaan Bahan Baku';

    protected function getData(): array
    {
        $data = PenggunaanBB::join(
                'bahan_baku',
                'penggunaan_bb.id_bahan_baku',
                '=',
                'bahan_baku.id'
            )
            ->select(
                'bahan_baku.nama_bahan_baku',
                DB::raw('SUM(jumlah_penggunaan) as total')
            )
            ->groupBy('bahan_baku.nama_bahan_baku')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Penggunaan',
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_bahan_baku')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}