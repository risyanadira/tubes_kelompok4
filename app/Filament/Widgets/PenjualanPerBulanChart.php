<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

use App\Models\Pembayaran;
use Carbon\Carbon;

class PenjualanPerBulanChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function getHeading(): string
    {
        return 'Pendapatan Per Bulan ' . date('Y');
    }

    protected function getData(): array
    {
        $year = now()->year;

        $payments = Pembayaran::query()
            ->where('status', 'lunas')
            ->whereYear('tgl_bayar', $year)
            ->selectRaw('MONTH(tgl_bayar) as month, SUM(gross_amount) as total_pendapatan')
            ->groupBy('month')
            ->pluck('total_pendapatan', 'month');

        $allMonths = collect(range(1, 12));

        $data = $allMonths->map(function ($month) use ($payments) {
            return $payments->get($month, 0);
        });

        $labels = $allMonths->map(function ($month) {
            return Carbon::create()
                ->month($month)
                ->locale('id')
                ->translatedFormat('F');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $data->toArray(),
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}