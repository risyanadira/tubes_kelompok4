<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    // Menentukan tabel database yang digunakan
    protected $table = 'jurnal';

    // Menentukan kolom yang boleh diisi (mass-assignable)
    protected $fillable = [
        'tgl',
        'no_referensi',
        'deskripsi',
    ];

    /**
     * Relasi One-to-Many ke JurnalDetail
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jurnaldetail()
    {
        return $this->hasMany(JurnalDetail::class, 'jurnal_id', 'id');
    }
}