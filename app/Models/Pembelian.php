<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian'; // Nama tabel di phpMyAdmin

    protected $fillable = [
        'tanggal',
        'supplier_id',
        'total_harga',
        'keterangan',
    ];

    // Relasi: Pembelian ini milik satu Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id_supplier');
    }

    // Relasi: Satu Pembelian punya banyak Detail (barang)
    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'pembelian_id');
    }
}