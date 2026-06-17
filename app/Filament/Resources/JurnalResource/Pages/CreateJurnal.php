<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJurnal extends CreateRecord
{
    protected static string $resource = JurnalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $totalDebit = collect($data['items'])->sum(function ($item) {
            return (float) ($item['debit'] ?? 0);
        });

        $totalCredit = collect($data['items'])->sum(function ($item) {
            return (float) ($item['credit'] ?? 0);
        });

        if ($totalDebit !== $totalCredit) {
            throw new \Exception(
                "Total Debit (Rp {$totalDebit}) harus sama dengan Total Credit (Rp {$totalCredit})"
            );
        }

        return $data;
    }
}