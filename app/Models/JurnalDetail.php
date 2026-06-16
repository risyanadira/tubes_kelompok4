<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalDetail extends Model
{
    use HasFactory;

    protected $table = 'jurnal_detail'; 

    protected $guarded = [];

    // Paksa konversi string database menjadi angka desimal/float agar sum() bekerja
    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
    ];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class, 'jurnal_id');
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class);
    }
}