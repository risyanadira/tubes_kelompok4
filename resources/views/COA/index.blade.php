<h2>Data COA</h2>
<a href="/coa/create">Tambah Data</a>

<table border="1">
<tr>
    <th>Kode</th>
    <th>Nama</th>
    <th>Header</th>
    <th>Aksi</th>
</tr>


<tr>
    <td>{{ $c->kode_akun }}</td>
    <td>{{ $c->nama_akun }}</td>
    <td>{{ $c->header_akun }}</td>
    <td>
        <a href="/coa/{{ $c->id }}/edit">Edit</a>

        <form action="/coa/{{ $c->id }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Hapus</button>
        </form>
    </td>
</tr>

</table>