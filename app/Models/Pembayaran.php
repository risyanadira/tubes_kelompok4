<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// TAMBAHAN
use App\Models\Penjualan;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $guarded = [];

    // relasi ke penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}