<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Pembayaran;
use App\Models\MetodePembayaran;

class DashboardStatCards extends BaseWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make(
                'Total Pembayaran',
                Pembayaran::count()
            )
            ->description('Jumlah transaksi pembayaran'),

            Stat::make(
                'Pembayaran Lunas',
                Pembayaran::where('status', 'lunas')->count()
            )
            ->description('Pembayaran berhasil'),

            Stat::make(
                'Total Pendapatan',
                'Rp ' . number_format(
                    Pembayaran::where('status', 'lunas')
                        ->sum('gross_amount'),
                    0,
                    ',',
                    '.'
                )
            )
            ->description('Total pembayaran diterima'),

            Stat::make(
                'Metode Pembayaran',
                MetodePembayaran::count()
            )
            ->description('Metode pembayaran tersedia'),

        ];
    }
}