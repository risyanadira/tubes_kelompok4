<?php

namespace App\Filament\Resources\PenggajianResource\Pages;

use App\Filament\Resources\PenggajianResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\DetailPenggajian;

class CreatePenggajian extends CreateRecord
{
    protected static string $resource = PenggajianResource::class;

    protected function afterCreate(): void
    {
        $total = DetailPenggajian::where(
            'penggajian_id',
            $this->record->id
        )->sum('nominal');

        $this->record->update([
            'total_gaji' => $total,
        ]);
    }
}