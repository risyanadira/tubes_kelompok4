<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Penggajian;

class DetailPenggajian extends Model
{
    use HasFactory;

    protected $table = 'detail_penggajians';

    protected $fillable = [
        'penggajian_id',
        'komponen_gaji',
        'nominal',
    ];

    // relasi ke penggajian
    public function penggajian()
    {
        return $this->belongsTo(Penggajian::class, 'penggajian_id');
    }
}