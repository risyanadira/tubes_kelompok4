<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PenggunaanBB extends Model
{
    use HasFactory;
    protected $table = 'penggunaan_bb'; // Sesuaikan dengan nama tabel di migration
    protected $guarded = []; // Atau gunakan $fillable untuk keamanan lebih ketat

    // Relasi ke tabel Bahan Baku
    public function BahanBaku()
    {
        return $this->belongsTo(Bahan_Baku::class, 'id_bahan_baku');
    }

    // Fungsi untuk generate kode penggunaan otomatis
    public static function getKodePenggunaan()
    {
        $sql = "SELECT IFNULL(MAX(kode_penggunaan), 'USE-00000') as kode_penggunaan FROM penggunaan_bb";
        $data = DB::select($sql);
        
        $kd = $data[0]->kode_penggunaan;
        $noawal = substr($kd, -5);
        $noakhir = (int)$noawal + 1;
        return 'USE-' . str_pad($noakhir, 5, "0", STR_PAD_LEFT);
    }
}
