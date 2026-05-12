<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;
use App\Models\DetailPenggajian;

class Penggajian extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'tanggal_gaji',
        'total_gaji',
    ];

    // relasi ke karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    // relasi ke detail penggajian
    public function detailPenggajian()
{
    return $this->hasMany(DetailPenggajian::class, 'penggajian_id');
}
}