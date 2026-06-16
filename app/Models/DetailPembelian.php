<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian'; // Nama tabel di phpMyAdmin

    protected $fillable = [
        'pembelian_id',
        'nama_barang',
        'qty',
        'harga',
        'subtotal',
    ];

    // Relasi balik: Detail ini milik satu transaksi Pembelian
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}