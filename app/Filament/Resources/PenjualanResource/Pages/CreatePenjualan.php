<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Jurnal;
use App\Models\Coa;
use Illuminate\Support\Facades\DB;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // 1. Ambil data transaksi penjualan yang baru saja disimpan
        $penjualan = $this->record;

        // Gunakan Database Transaction agar aman
        DB::transaction(function () use ($penjualan) {

            // 2. BUAT HEADER JURNAL UMUM
            $jurnal = Jurnal::create([
                'tgl'          => $penjualan->tgl ?? now()->toDateString(),
                'no_referensi' => $penjualan->no_faktur,
                'deskripsi'    => 'Penjualan POS - No Faktur: ' . $penjualan->no_faktur,
            ]);

            // Ambil nominal total penjualan
            $totalPenjualan = $penjualan->total ?? 0;

            // 3. CARI ID COA ASLI DARI DATABASE BERDASARKAN KODE AKUN
            // Kas (Kode Akun: 101) -> Mencari ID aslinya di tabel coas
            $coaKas = Coa::where('kode_akun', '101')->first();
            
            // Pendapatan Penjualan (Kode Akun: 401) -> Mencari ID aslinya di tabel coas
            $coaPendapatan = Coa::where('kode_akun', '401')->first();

            // 4. BUAT DETAIL JURNAL (Menggunakan relasi jurnaldetail() ke tabel jurnal_detail)
            if ($jurnal && $coaKas && $coaPendapatan) {

                // Baris 1 (DEBIT): Kas Bertambah
                $jurnal->jurnaldetail()->create([
                    'coa_id'    => $coaKas->id, // Mengambil ID dari database (biasanya 1)
                    'deskripsi' => 'Penerimaan Kas dari Penjualan POS ' . $penjualan->no_faktur,
                    'debit'     => $totalPenjualan,
                    'credit'    => 0, // Sudah fix menggunakan 'credit', bukan 'kredit'
                ]);

                // Baris 2 (KREDIT): Pendapatan Penjualan Bertambah
                $jurnal->jurnaldetail()->create([
                    'coa_id'    => $coaPendapatan->id, // Mengambil ID dari database (biasanya 9)
                    'deskripsi' => 'Pendapatan atas Penjualan POS ' . $penjualan->no_faktur,
                    'debit'     => 0,
                    'credit'    => $totalPenjualan, // Sudah fix menggunakan 'credit', bukan 'kredit'
                ]);
            }
        });
    }
}