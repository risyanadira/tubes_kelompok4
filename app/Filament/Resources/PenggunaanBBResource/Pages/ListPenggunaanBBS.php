<?php

namespace App\Filament\Resources\PenggunaanBBResource\Pages;

use App\Filament\Resources\PenggunaanBBResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenggunaanBBS extends ListRecords
{
    protected static string $resource = PenggunaanBBResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
