<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

use App\Models\Pembayaran;

class TotalPembayaranChart extends ChartWidget
{
    protected static ?string $heading = 'Total Pembayaran per Metode';

    protected function getData(): array
    {
        $data = Pembayaran::query()
            ->join(
                'metode_pembayaran',
                'pembayaran.kode_metode',
                '=',
                'metode_pembayaran.kode_metode'
            )
            ->where('status', 'lunas')
            ->selectRaw('
                metode_pembayaran.nama_metode,
                SUM(gross_amount) as total_pembayaran
            ')
            ->groupBy('metode_pembayaran.nama_metode')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pembayaran',
                    'data' => $data->pluck('total_pembayaran')->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_metode')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}