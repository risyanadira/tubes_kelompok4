<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penggunaan Bahan Baku</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h2 { text-align: center; margin-bottom: 20px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Penggunaan Bahan Baku</h2>
    <table>
        <thead>
            <tr>
                <th>Kode Penggunaan</th>
                <th>Bahan Baku</th>
                <th>Produk Jadi</th>
                <th class="text-center">Jumlah</th>
                <th>Satuan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $p)
            <tr>
                <td>{{ $p->kode_penggunaan }}</td>
                <td>{{ optional($p->BahanBaku)->nama_bahan_baku ?? $p->id_bahan_baku }}</td>
                <td>{{ $p->nama_produk_jadi }}</td>
                <td class="text-center">{{ $p->jumlah_penggunaan }}</td>
                <td>{{ $p->satuan }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_penggunaan)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>