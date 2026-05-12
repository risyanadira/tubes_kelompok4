<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Menu;

class DashboardController extends Controller
{
    public function index()
    {
        // total transaksi hari ini
        $totalTransaksi = Penjualan::whereDate('tgl', today())->count();

        // total penjualan hari ini
        $totalPenjualan = Penjualan::whereDate('tgl', today())->sum('total');

        // total menu
        $totalMenu = Menu::count();

        // transaksi terbaru
        $transaksiTerbaru = Penjualan::latest()->take(5)->get();

        // total pending
        $totalPending = Penjualan::where('status', 'pending')->count();

        // total lunas
        $totalLunas = Penjualan::where('status', 'lunas')->count();

        return view('dashboard', compact(
            'totalTransaksi',
            'totalPenjualan',
            'totalMenu',
            'transaksiTerbaru',
            'totalPending',
            'totalLunas'
        ));
    }
}