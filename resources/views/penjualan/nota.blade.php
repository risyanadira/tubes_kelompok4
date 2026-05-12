<!DOCTYPE html>
<html>
<head>
    <title>Nota</title>

    <style>
        body{
            font-family: monospace;
            padding:20px;
        }

        .center{
            text-align:center;
        }

        hr{
            border:1px dashed #000;
        }
    </style>
</head>

<body>

<div class="center">
    <h3>BASO JAFRA</h3>
    <small>STRUK PEMBELIAN</small>
</div>

<hr>

<p>No Faktur: {{ $penjualan->no_faktur }}</p>
<p>Tanggal: {{ $penjualan->tgl }}</p>
<p>Kasir: {{ $penjualan->karyawan->nama_pegawai }}</p>

<hr>

@foreach($penjualan->detail as $d)
    <p>
        {{ $d->menu->nama_menu }}<br>
        {{ $d->qty }} x {{ number_format($d->harga) }}
        <span style="float:right">
            {{ number_format($d->subtotal) }}
        </span>
    </p>
@endforeach

<hr>

<h3>Total: Rp {{ number_format($penjualan->total) }}</h3>

<hr>

<div class="center">
    <small>Terima Kasih 🙏</small>
</div>

<script>
    window.onload = function () {
        window.print();

        window.onafterprint = function () {
            window.location.href = "/penjualan";
        }
    }
</script>

</body>
</html>