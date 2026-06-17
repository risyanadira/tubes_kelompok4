<?php

namespace App\Filament\Resources\BukuBesarResource\Pages;

use App\Filament\Resources\BukuBesarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBukuBesars extends ListRecords
{
    protected static string $resource = BukuBesarResource::class;

    /**
     * Mengatur action di header halaman
     */
    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    /**
     * Memanggil custom widget agar tampil di halaman utama Buku Besar
     */
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\BukuBesarResource\Widgets\BukuBesar::class,
        ];
    }

    /**
     * Mengatur jumlah kolom widget (1 kolom penuh agar rapi)
     */
    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}