<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    protected $fillable = [
        'penjualan_id',
        'menu_id',
        'qty',
        'harga',
        'subtotal'
    ];

    // RELASI KE PENJUALAN
    public function penjualan()
    {
        return $this->belongsTo(
            Penjualan::class,
            'penjualan_id'
        );
    }

    // RELASI KE MENU
    public function menu()
    {
        return $this->belongsTo(
            Menu::class,
            'menu_id'
        );
    }
}