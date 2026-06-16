<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sudah benar menunjuk ke 'pembelian'
    protected $table = 'pembelian'; 

    // WAJIB daftarkan semua kolom yang bisa diisi dari form di sini:
    protected $fillable = [
    'no_faktur', // Selesai! Sekarang siap dipakai untuk otomatisasi jurnal
    'tanggal',
    'supplier_id',
    'total_harga',
    'keterangan',
    ];

    // Relasi ke detail_pembelian
    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'pembelian_id');
    }
}