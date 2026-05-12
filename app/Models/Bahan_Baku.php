<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bahan_Baku extends Model
{
    use HasFactory;
    
    protected $table = 'bahan_baku'; 

    protected $guarded = [];

    protected $primaryKey = 'id_bahan_baku'; // Beritahu Laravel PK nya bukan 'id'
    public $incrementing = false;            // Karena PK nya string, bukan angka auto-increment
    protected $keyType = 'string';

    public static function getIdBahanBaku()
    {
        $sql = "SELECT IFNULL(MAX(id_bahan_baku), 'BB000') as id_bahan_baku
                FROM bahan_baku";
        
        $IdBahanBaku = DB::select($sql);

        // Ambil hasil pertama tanpa foreach agar lebih aman dari error
        $id = $IdBahanBaku[0]->id_bahan_baku ?? 'BB000';
        
        $noawal = substr($id, -3);
        $noakhir = (int)$noawal + 1;
        return 'BB' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);
    }

    public function setHargaBahanBakuAttribute($value)
    {
        $this->attributes['harga_bahan_baku'] = str_replace('.', '', $value);
    }

}