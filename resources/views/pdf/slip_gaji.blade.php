<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        .total { font-weight: bold; background: #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SLIP GAJI KARYAWAN</h2>
    </div>
    <p>Nama: {{ $record->karyawan->nama_pegawai }}<br>
    Tanggal: {{ $record->tanggal_gaji }}</p>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->detailPenggajian as $detail)
            <tr>
                <td>{{ $detail->komponen_gaji }}</td>
                <td>Rp {{ number_format($detail->nominal) }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td>TOTAL DITERIMA</td>
                <td>Rp {{ number_format($record->total_gaji) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>