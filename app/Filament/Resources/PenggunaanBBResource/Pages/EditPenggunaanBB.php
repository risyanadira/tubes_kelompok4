<?php

namespace App\Filament\Resources\PenggunaanBBResource\Pages;

use App\Filament\Resources\PenggunaanBBResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenggunaanBB extends EditRecord
{
    protected static string $resource = PenggunaanBBResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
