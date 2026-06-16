<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Pakai nama tabel 'supplier' sesuai migration kita di Step 1
    protected $table = 'supplier';

    // Primary key custom sesuai Step 2
    protected $primaryKey = 'id_supplier';

    // Wajib false karena kita pakai string 'SUP001', bukan angka otomatis
    public $incrementing = false;

    // Tipe datanya string
    protected $keyType = 'string';

    protected $fillable = [
        'id_supplier',
        'nama_supplier',
        'alamat',
        'no_telp',
    ];
}