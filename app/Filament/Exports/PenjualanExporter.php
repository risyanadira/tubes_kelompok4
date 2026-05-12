<?php

namespace App\Filament\Exports;

use App\Models\Penjualan;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class PenjualanExporter extends Exporter
{
    protected static ?string $model = Penjualan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('no_faktur'),
            ExportColumn::make('tgl'),
            ExportColumn::make('karyawan.nama_pegawai')->label('Kasir'),
            ExportColumn::make('status'),
            ExportColumn::make('total'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Export Penjualan berhasil dilakukan.';
    }
}