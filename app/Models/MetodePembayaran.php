<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MetodePembayaran;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';

    protected $primaryKey = 'kode_metode';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kode_metode',
        'nama_metode',
        'nomor_rekening',
        'atas_nama',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::orderBy('kode_metode', 'desc')->first();

            if ($last) {
                $number = (int) substr($last->kode_metode, 2) + 1;
            } else {
                $number = 1;
            }

            $model->kode_metode = 'MP' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }
}