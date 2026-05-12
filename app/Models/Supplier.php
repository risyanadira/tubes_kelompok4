<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    public $incrementing = false; // Karena pakai string (SUP001)
=======
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    public $incrementing = false;
>>>>>>> 1e493812c591d84e8c40eacbdad9e4eaa7474380
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_supplier',
        'alamat',
        'no_telp',
    ];
}