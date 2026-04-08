<h2>Edit COA</h2>

<form action="/coa/{{ $coa->id }}" method="POST">


<input type="text" name="kode_akun" value="{{ $coa->kode_akun }}"><br>
<input type="text" name="nama_akun" value="{{ $coa->nama_akun }}"><br>
<input type="text" name="header_akun" value="{{ $coa->header_akun }}"><br>

<button type="submit">Update</button>
</form>