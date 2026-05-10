<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Karyawan;
use App\Models\MetodePembayaran;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $menu = Menu::all();

        $karyawan = Karyawan::all();

        $metode = MetodePembayaran::all();

        return view('penjualan.index', compact(
            'menu',
            'karyawan',
            'metode'
        ));
    }

    public function tambah($id)
    {
        $menu = Menu::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {

            $cart[$id]['qty']++;

            $cart[$id]['subtotal'] =
                $cart[$id]['qty'] *
                $cart[$id]['harga'];

        } else {

            $cart[$id] = [
                'nama_menu' => $menu->nama_menu,
                'harga' => $menu->harga,
                'qty' => 1,
                'subtotal' => $menu->harga
            ];
        }

        session()->put('cart', $cart);

        return redirect('/penjualan');
    }

    public function simpan(Request $request)
    {
        $cart = session()->get('cart');

        if(!$cart) {
            return redirect('/penjualan');
        }

        $total = 0;

        foreach($cart as $item) {
            $total += $item['subtotal'];
        }

        // SIMPAN PENJUALAN
        $penjualan = Penjualan::create([

            'karyawan_id' => $request->karyawan_id,

            'no_faktur' => 'TRX-' . time(),

            'tgl' => now(),

            'kode_metode' => $request->kode_metode,

            'total' => $total
        ]);

        // SIMPAN DETAIL
        foreach($cart as $id => $item) {

            DetailPenjualan::create([

                'penjualan_id' => $penjualan->id,

                'menu_id' => $id,

                'qty' => $item['qty'],

                'harga' => $item['harga'],

                'subtotal' => $item['subtotal']
            ]);
        }

        // HAPUS CART
        session()->forget('cart');

        return redirect('/penjualan/nota/' . $penjualan->id);
    }

    public function nota($id)
    {
        $penjualan = Penjualan::with([
            'detail.menu',
            'karyawan',
            'metodePembayaran'
        ])->findOrFail($id);

        return view('penjualan.nota', compact('penjualan'));
    }

    public function dashboard()
{
    // TOTAL TRANSAKSI HARI INI
    $totalTransaksi = Penjualan::whereDate(
        'tgl',
        today()
    )->count();

    // TOTAL PENJUALAN HARI INI
    $totalPenjualan = Penjualan::whereDate(
        'tgl',
        today()
    )->sum('total');

    // TOTAL MENU
    $totalMenu = Menu::count();

    // TRANSAKSI TERBARU
    $transaksiTerbaru = Penjualan::latest()
                            ->take(5)
                            ->get();

    return view('dashboard', compact(
        'totalTransaksi',
        'totalPenjualan',
        'totalMenu',
        'transaksiTerbaru'
    ));
}
}