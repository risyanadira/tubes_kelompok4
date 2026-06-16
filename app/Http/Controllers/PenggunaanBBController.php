<?php

namespace App\Http\Controllers; // Pastikan namespace ini ada di baris paling atas

use App\Models\PenggunaanBB;
use App\Models\Bahan_Baku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Kamu harus membungkus fungsi store di dalam Class ini
class PenggunaanBBController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'id_bahan_baku' => 'required',
            'jumlah_penggunaan' => 'required|numeric|min:0.01',
        ]);

        // Menggunakan Transaction agar data tetap sinkron
        DB::transaction(function () use ($request) {
            
            // 2. Simpan data ke tabel penggunaan_bb
            PenggunaanBB::create([
                'id_bahan_baku' => $request->id_bahan_baku,
                'kode_penggunaan' => PenggunaanBB::getKodePenggunaan(),
                'nama_produk_jadi' => $request->nama_produk_jadi,
                'jumlah_penggunaan' => $request->jumlah_penggunaan,
                'satuan' => $request->satuan,
                'tanggal_penggunaan' => $request->tanggal_penggunaan,
                'keterangan' => $request->keterangan,
            ]);

            // 3. Logika pengurangan stok otomatis pada masterdata Bahan_Baku
            Bahan_Baku::where('id_bahan_baku', $request->id_bahan_baku)
                ->decrement('stok_bahan_baku', $request->jumlah_penggunaan);
        });

        return redirect()->back()->with('success', 'Data penggunaan berhasil dicatat dan stok berkurang!');
    }
}