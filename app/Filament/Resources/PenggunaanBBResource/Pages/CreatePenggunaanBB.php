<?php

namespace App\Filament\Resources\PenggunaanBBResource\Pages;

use App\Filament\Resources\PenggunaanBBResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Bahan_Baku;
use App\Models\Jurnal;

class CreatePenggunaanBB extends CreateRecord
{
    protected static string $resource = PenggunaanBBResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $penggunaan = $this->record;

        // 1. KURANGI STOK BAHAN BAKU & HITUNG TOTAL BIAYA SECARA OTOMATIS
        $totalBiaya = 0;
        $bahanBaku = Bahan_Baku::where('id_bahan_baku', $penggunaan->id_bahan_baku)->first();
        
        if ($bahanBaku) {
            // Kurangi stok sesuai jumlah penggunaan
            $bahanBaku->decrement('stok_bahan_baku', $penggunaan->jumlah_penggunaan);
            
            // Hitung total biaya = harga satuan bahan baku * jumlah yang digunakan
            // Catatan: Pastikan nama kolom harga di tabel bahan_baku kamu adalah 'harga_bahan_baku' atau sesuaikan jika berbeda
            $totalBiaya = ($bahanBaku->harga_bahan_baku ?? 0) * $penggunaan->jumlah_penggunaan;
        }

        // 2. INPUT HEADER JURNAL
        $jurnal = Jurnal::create([
            'tgl'          => $penggunaan->tanggal_penggunaan,
            'no_referensi' => $penggunaan->kode_penggunaan,
            'deskripsi'    => 'Penggunaan bahan baku ' . $penggunaan->kode_penggunaan,
        ]);

        // 3. INPUT DETAIL JURNAL (Menggunakan ->create() satu per satu seperti pembelian)
        // Akun Debit: Beban Bahan Baku (COA ID: 10 sesuai tabel COA kamu)
        $jurnal->jurnaldetail()->create([
            'coa_id'    => 10, 
            'deskripsi' => 'Biaya Pemakaian Bahan Baku (Debit)',
            'debit'     => $totalBiaya,
            'credit'    => 0,
        ]);

        // Akun Kredit: Persediaan Bahan Baku (COA ID: 5 sesuai tabel COA kamu)
        $jurnal->jurnaldetail()->create([
            'coa_id'    => 5, 
            'deskripsi' => 'Pengurangan Persediaan Bahan Baku (Kredit)',
            'debit'     => 0,
            'credit'    => $totalBiaya,
        ]);
    }
}