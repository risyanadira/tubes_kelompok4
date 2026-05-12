<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Karyawan;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    /*
    |-----------------------------------------
    | HALAMAN KASIR
    |-----------------------------------------
    */
    public function index()
    {
        $menu = Menu::all();
        $karyawan = Karyawan::all();

        return view('penjualan.index', compact('menu', 'karyawan'));
    }

    /*
    |-----------------------------------------
    | TAMBAH KE KERANJANG
    |-----------------------------------------
    */
    public function tambah($id)
    {
        $menu = Menu::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
            $cart[$id]['subtotal'] = $cart[$id]['qty'] * $cart[$id]['harga'];
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

    /*
    |-----------------------------------------
    | SIMPAN TRANSAKSI
    |-----------------------------------------
    */
    public function simpan(Request $request)
    {
        $cart = session()->get('cart');

        if (!$cart) {
            return redirect('/penjualan');
        }

        // HITUNG TOTAL
        $total = collect($cart)->sum('subtotal');

        // SIMPAN PENJUALAN
        $penjualan = Penjualan::create([
            'karyawan_id' => $request->karyawan_id,
            'no_faktur' => 'TRX-' . time(),
            'tgl' => now(),
            'total' => $total,
            'status' => 'pending'
        ]);

        // SIMPAN DETAIL
        foreach ($cart as $id => $item) {
            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'menu_id' => $id,
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['subtotal']
            ]);
        }

        // CLEAR CART
        session()->forget('cart');

        // REDIRECT KE NOTA
        return redirect('/penjualan/nota/' . $penjualan->id);
    }

    /*
    |-----------------------------------------
    | HALAMAN NOTA
    |-----------------------------------------
    */
    public function nota($id)
    {
        $penjualan = Penjualan::with(['detail.menu', 'karyawan'])
            ->findOrFail($id);

        return view('penjualan.nota', compact('penjualan'));
    }
}