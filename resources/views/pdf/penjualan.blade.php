<h2>LAPORAN PENJUALAN</h2>
<hr>

@foreach ($penjualan as $p)
    <h4>{{ $p->no_faktur }}</h4>
    <p>Tanggal: {{ $p->tgl }}</p>
    <p>Kasir: {{ $p->karyawan->nama_pegawai ?? '-' }}</p>

    <table width="100%" border="1" cellpadding="5">
        <tr>
            <th>Menu</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>

        @foreach ($p->detail as $d)
            <tr>
                <td>{{ $d->menu->nama_menu ?? '-' }}</td>
                <td>{{ $d->qty }}</td>
                <td>{{ number_format($d->harga) }}</td>
                <td>{{ number_format($d->subtotal) }}</td>
            </tr>
        @endforeach
    </table>

    <p><b>Total: Rp {{ number_format($p->total) }}</b></p>

    <hr>
@endforeach