<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'karyawan_id',
        'no_faktur',
        'tgl',
        'kode_metode',
        'total'
    ];

    // RELASI KE KARYAWAN
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    // RELASI KE METODE PEMBAYARAN
    public function metodePembayaran()
    {
        return $this->belongsTo(
            MetodePembayaran::class,
            'kode_metode',
            'kode_metode'
        );
    }

    // RELASI KE DETAIL PENJUALAN
    public function detail()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }
}