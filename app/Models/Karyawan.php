<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'Karyawan'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getKodePegawai()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_pegawai), 'PG000') as kode_pegawai 
                FROM karyawans";

        $kodepegawai = DB::select($sql);

        // cacah hasilnya
        foreach ($kodepegawai as $kdpgw) {
            $kd = $kdpgw->kode_pegawai;
        }

        // Mengambil substring tiga digit akhir
        $noawal = substr($kd, -3);

        $noakhir = $noawal + 1;

        $noakhir = 'PG' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);

        return $noakhir;
    }
}