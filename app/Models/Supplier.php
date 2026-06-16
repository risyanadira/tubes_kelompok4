<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // 1. ISI INI: Paksa Laravel membaca nama tabel tunggal sesuai di phpMyAdmin kamu
    protected $table = 'supplier';

    // 2. ISI INI: Tegaskan bahwa primary key-nya adalah id_supplier
    protected $primaryKey = 'id_supplier';

    // 3. Karena id_supplier kamu berupa string/text (contoh: SUP001), tambahkan ini:
    protected $keyType = 'string';
    public $incrementing = false; 

    protected $fillable = [
        'id_supplier',
        'nama_supplier',
        'alamat',
        'no_telp',
    ];
}