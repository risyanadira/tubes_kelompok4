<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'tanggal',
        'supplier_id',
        'total_harga',
        'keterangan',
    ];

    /**
     * Relasi ke Tabel Supplier (Induk)
     */
    public function supplier(): BelongsTo
    {
        // Pastikan foreign key-nya sesuai dengan migration (supplier_id)
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id_supplier');
    }

    /**
     * Relasi ke Tabel DetailPembelian (Anak)
     * Inilah yang dicari oleh Filament Repeater
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailPembelian::class, 'pembelian_id', 'id');
    }
}