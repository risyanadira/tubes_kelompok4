<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;

use App\Models\Penjualan;

class MidtransController extends Controller
{
    public function bayar($id)
    {
        $penjualan = Penjualan::findOrFail($id);

        // CONFIG MIDTRANS
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // DATA TRANSAKSI
        $params = [

            'transaction_details' => [

                'order_id' => 'ORDER-' . $penjualan->id . '-' . time(),

                'gross_amount' => $penjualan->total,

            ],

            'customer_details' => [

                'first_name' => 'Customer',

            ]

        ];

        // SNAP TOKEN
        $snapToken = Snap::getSnapToken($params);

        return view('midtrans.bayar', compact(
            'snapToken',
            'penjualan'
        ));
    }

    public function success($id)
{
    $penjualan = Penjualan::findOrFail($id);

    // ubah status jadi lunas
    $penjualan->status = 'lunas';

    $penjualan->save();

    return redirect('/penjualan')
        ->with('success', 'Pembayaran berhasil');
}
}