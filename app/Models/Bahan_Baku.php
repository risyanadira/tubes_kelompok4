<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// tambahan
use Illuminate\Support\Facades\DB;

class Bahan_Baku extends Model
{
    use HasFactory;
    
protected $table = 'Bahan_Baku'; // Nama tabel eksplisit

    protected $guarded = [];

    public static function getIdBahanBaku()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(id_bahan_baku), 'BB000') as id_bahan_baku
                FROM bahan_baku ";
        $IdBahanBaku = DB::select($sql);

        // cacah hasilnya
        foreach ($IdBahanBaku as $IdBB) {
            $id = $IdBB->id_bahan_baku;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($id,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'BB'.str_pad($noakhir,3,"0",STR_PAD_LEFT); //menyambung dengan string PR-001
        return $noakhir;

    }

    // Dengan mutator ini, setiap kali data harga_barang dikirim ke database, koma akan otomatis dihapus.
    public function setHargaBahanBakuAttribute($value)
    {
        // Hapus koma (,) dari nilai sebelum menyimpannya ke database
        $this->attributes['harga_bahan_baku'] = str_replace('.', '', $value);
    }
}
