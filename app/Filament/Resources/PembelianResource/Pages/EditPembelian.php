<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Bahan_Baku;
use App\Models\Jurnal;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelian extends EditRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    $pembelian = $this->record;

                    // 1. KEMBALIKAN STOK BAHAN BAKU
                    foreach ($pembelian->details as $detail) {
                        $bahanBaku = Bahan_Baku::where('nama_bahan_baku', $detail->nama_barang)->first();
                        if ($bahanBaku) {
                            $bahanBaku->decrement('stok_bahan_baku', $detail->qty);
                        }
                    }

                    // 2. HAPUS DATA JURNAL & DETAILNYA
                    $jurnal = Jurnal::where('no_referensi', $pembelian->no_faktur)->first();
                    
                    if ($jurnal) {
                        // Menggunakan jurnaldetail() untuk mengosongkan item jurnal terkait sebelum dihapus
                        $jurnal->jurnaldetail()->delete(); 
                        $jurnal->delete();
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}