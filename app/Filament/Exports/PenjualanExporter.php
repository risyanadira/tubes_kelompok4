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
            ExportColumn::make('no_faktur')
                ->label('No Faktur'),

            ExportColumn::make('tgl')
                ->label('Tanggal'),

            ExportColumn::make('karyawan.nama_pegawai')
                ->label('Kasir'),

            ExportColumn::make('status'),

            ExportColumn::make('detail_penjualan')
                ->label('Detail Penjualan')
                ->state(function ($record) {

                    return $record->detail
                        ->map(function ($item) {

                            $namaMenu = $item->menu->nama_menu ?? '-';
                            $qty = $item->qty ?? 0;

                            return "{$namaMenu} ({$qty})";
                        })
                        ->implode(', ');
                }),

            ExportColumn::make('total')
                ->label('Total'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Export Penjualan berhasil dilakukan.';
    }
}