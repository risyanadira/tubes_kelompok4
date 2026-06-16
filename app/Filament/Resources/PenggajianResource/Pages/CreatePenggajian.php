<?php

namespace App\Filament\Resources\PenggajianResource\Pages;

use App\Filament\Resources\PenggajianResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Jurnal;
use App\Models\Coa;
use Illuminate\Support\Facades\DB;

class CreatePenggajian extends CreateRecord
{
    protected static string $resource = PenggajianResource::class;

    // Kembali ke halaman daftar penggajian setelah berhasil disimpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Fungsi otomatisasi jurnal setelah data penggajian sukses terbuat
    protected function afterCreate(): void
    {
        $penggajian = $this->record;

        // Menggunakan DB Transaction agar aman dari data parsial/gagal di tengah jalan
        DB::transaction(function () use ($penggajian) {

            // 1. BUAT HEADER JURNAL UMUM
            $jurnal = Jurnal::create([
                // Mengambil tanggal dari kolom tabel penggajianmu (misal: 'tgl' atau 'tanggal')
                'tgl'          => $penggajian->tgl ?? now()->toDateString(), 
                // Mengambil nomor slip/faktur gaji sebagai referensi jurnal (misal: 'no_slip')
                'no_referensi' => $penggajian->no_slip ?? 'PAY-' . $penggajian->id, 
                'deskripsi'    => 'Pembayaran Gaji Karyawan - Periode: ' . ($penggajian->periode ?? now()->format('F Y')),
            ]);

            // Ambil nominal total gaji dari data yang baru disimpan
            // Sesuai dengan nama kolom total di tabel penggajianmu (misal: 'total_gaji' atau 'total')
            $totalGaji = $penggajian->total_gaji ?? 0; 

            // 2. AMBIL ID ASLI COA DARI DATABASE BERDASARKAN KODE AKUN
            $coaBebanGaji = Coa::where('kode_akun', '502')->first(); // Beban Gaji Karyawan
            $coaKas = Coa::where('kode_akun', '101')->first();       // Kas

            // 3. INPUT DETAIL JURNAL (DEBIT & CREDIT)
            if ($jurnal && $coaBebanGaji && $coaKas) {

                // Baris 1 (DEBIT): Beban Gaji Bertambah
                $jurnal->jurnaldetail()->create([
                    'coa_id'    => $coaBebanGaji->id, // Menggunakan ID auto-increment dari database
                    'deskripsi' => 'Beban Gaji Karyawan Ref: ' . ($penggajian->no_slip ?? $penggajian->id),
                    'debit'     => $totalGaji,
                    'credit'    => 0, // Menggunakan kolom 'credit' sesuai database kamu
                ]);

                // Baris 2 (KREDIT): Kas Berkurang
                $jurnal->jurnaldetail()->create([
                    'coa_id'    => $coaKas->id, // Menggunakan ID auto-increment dari database
                    'deskripsi' => 'Pembayaran Gaji Karyawan via Kas Ref: ' . ($penggajian->no_slip ?? $penggajian->id),
                    'debit'     => 0,
                    'credit'    => $totalGaji, // Menggunakan kolom 'credit' sesuai database kamu
                ]);
            }
        });
    }
}