<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function afterCreate(): void
    {
        $penjualan = $this->record->penjualan;

        if ($penjualan) {

            // ubah status penjualan jadi lunas
            $penjualan->status = 'lunas';

            $penjualan->save();
        }
    }
}