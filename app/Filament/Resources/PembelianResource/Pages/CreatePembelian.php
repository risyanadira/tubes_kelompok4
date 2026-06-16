<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use App\Models\Bahan_Baku;
use App\Models\Jurnal;       
use Filament\Resources\Pages\CreateRecord;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $pembelian = $this->record;

        // 1. TAMBAH STOK BAHAN BAKU
        foreach ($pembelian->details as $detail) {
            $bahanBaku = Bahan_Baku::where('nama_bahan_baku', $detail->nama_barang)->first();
            if ($bahanBaku) {
                $bahanBaku->increment('stok_bahan_baku', $detail->qty);
            }
        }

        // 2. INPUT HEADER JURNAL
        $jurnal = Jurnal::create([
            'tgl'          => $pembelian->tanggal,
            'no_referensi' => $pembelian->no_faktur,
            'deskripsi'    => 'Pembelian bahan baku via faktur ' . $pembelian->no_faktur,
        ]);

        // 3. INPUT DETAIL JURNAL (Menggunakan relasi jurnaldetail() sesuai database kamu)
        // Akun Debit: Persediaan Bahan Baku (COA ID: 1)
        $jurnal->jurnaldetail()->create([
            'coa_id'    => 1, 
            'deskripsi' => 'Persediaan Bahan Baku (Debit)',
            'debit'     => $pembelian->total_harga,
            'credit'    => 0,
        ]);

        // Akun Kredit: Kas/Bank (COA ID: 10)
        $jurnal->jurnaldetail()->create([
            'coa_id'    => 10, 
            'deskripsi' => 'Kas (Kredit)',
            'debit'     => 0,
            'credit'    => $pembelian->total_harga,
        ]);
    }
    
}