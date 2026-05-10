<!DOCTYPE html>
<html>
<head>
    <title>Nota Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h3>Nota Penjualan</h3>
        </div>

        <div class="card-body">

            <p>
                <b>No Faktur:</b>
                {{ $penjualan->no_faktur }}
            </p>

            <p>
                <b>Tanggal:</b>
                {{ $penjualan->tgl }}
            </p>

            <p>
                <b>Kasir:</b>
                {{ $penjualan->karyawan->nama_pegawai }}
            </p>

            <p>
                <b>Pembayaran:</b>
                {{ $penjualan->metodePembayaran->nama_metode }}
            </p>

            <table class="table table-bordered">

                <tr>
                    <th>Menu</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>

                @foreach($penjualan->detail as $d)

                <tr>

                    <td>
                        {{ $d->menu->nama_menu }}
                    </td>

                    <td>
                        {{ $d->qty }}
                    </td>

                    <td>
                        Rp {{ number_format($d->harga) }}
                    </td>

                    <td>
                        Rp {{ number_format($d->subtotal) }}
                    </td>

                </tr>

                @endforeach

                <tr>

                    <th colspan="3">
                        Total
                    </th>

                    <th>
                        Rp {{ number_format($penjualan->total) }}
                    </th>

                </tr>

            </table>

        </div>

    </div>

</div>

</body>
</html>