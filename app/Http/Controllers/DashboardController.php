<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Menu;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransaksi = Penjualan::whereDate('tgl', today())->count();

        $totalPenjualan = Penjualan::whereDate('tgl', today())->sum('total');

        $totalMenu = Menu::count();

        $transaksiTerbaru = Penjualan::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalTransaksi',
            'totalPenjualan',
            'totalMenu',
            'transaksiTerbaru'
        ));
    }
}