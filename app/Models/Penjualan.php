<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'karyawan_id',
        'no_faktur',
        'tgl',
        'total',
        'status',
    ];

    // RELASI ke detail (kalau ada)
    public function detail()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    // RELASI ke pembayaran (punya temanmu)
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'penjualan_id');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'kode_metode', 'kode_metode');
    }

    // RELASI karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function nota($id)
{
    $penjualan = Penjualan::with(['detail.menu', 'karyawan'])
        ->findOrFail($id);

    return view('penjualan.nota', compact('penjualan'));
}

    public function bayar($id)
{
    $penjualan = Penjualan::findOrFail($id);

    // ubah status jadi lunas
    $penjualan->status = 'lunas';

    $penjualan->save();

    return redirect('/penjualan')
        ->with('success', 'Pembayaran berhasil');
}
}