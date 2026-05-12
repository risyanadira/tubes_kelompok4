<?php

namespace App\Filament\Resources\PenggunaanBBResource\Pages;

use App\Filament\Resources\PenggunaanBBResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Bahan_Baku; //

class CreatePenggunaanBB extends CreateRecord
{
    protected static string $resource = PenggunaanBBResource::class;

    protected function afterCreate(): void
    {
    $data = $this->record;

    // Pakai id_bahan_baku karena ini yang menampung string 'BB004'
    \App\Models\Bahan_Baku::where('id_bahan_baku', $data->id_bahan_baku)
        ->decrement('stok_bahan_baku', $data->jumlah_penggunaan);
        }
}