<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Kasir - Baso Jafra</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        body{
            background:#f4f7fe;
            font-family:'Segoe UI',sans-serif;
        }

        /* SIDEBAR */

        .sidebar{
            width:250px;
            height:100vh;
            position:fixed;
            left:0;
            top:0;
            background:linear-gradient(180deg,#0d6efd,#084298);
            padding:25px 20px;
            display:flex;
            flex-direction:column;
            color:white;
        }
        .logo{
            color: white;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 40px;
            text-align: center;
        }

        .logo img{
            width: 80px;
            height: auto;
            border-radius: 12px;
        }

        .sidebar-menu a{
            display:block;
            color:white;
            text-decoration:none;
            padding:14px 16px;
            border-radius:12px;
            margin-bottom:12px;
            transition:0.3s;
            font-size:16px;
        }

        .sidebar-menu a:hover,
        .active-menu{
            background:rgba(255,255,255,0.2);
            transform:translateX(5px);
        }

        .sidebar-menu i{
            margin-right:10px;
        }

        /* LOGOUT */

        .logout{
            margin-top:auto;
        }

        .logout a{
            display:block;
            color:white;
            text-decoration:none;
            padding:14px 16px;
            border-radius:12px;
            background:rgba(255,255,255,0.15);
            transition:0.3s;
        }

        .logout a:hover{
            background:rgba(255,255,255,0.25);
        }

        /* MAIN */

        .main{
            margin-left:270px;
            padding:30px;
        }

        /* CARD */

        .card-box{
            border:none;
            border-radius:15px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
            overflow:hidden;
            background:white;
        }

        .menu-img{
            width:100%;
            height:140px;
            object-fit:cover;
        }

    </style>
</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div>

        <div class="logo text-center">

            <img src="{{ asset('/logo.png') }}"
                alt="Logo Baso Jafra"
                width="120"
                class="mb-2">

            <div class="fw-bold text-white">
                Baso Jafra
            </div>

        </div>

        <div class="sidebar-menu">

            <a href="/dashboard">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="/penjualan" class="active-menu">
                <i class="fa-solid fa-cart-shopping"></i>
                Transaksi Baru
            </a>

            <a href="/laporan">
                <i class="fa-solid fa-file-lines"></i>
                Laporan Penjualan
            </a>

        </div>

    </div>

    <div class="logout">

        <a href="/logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

</div>

<!-- MAIN -->

<div class="main">

    <h3 class="fw-bold mb-4">
        Transaksi Baru
    </h3>

    <div class="row">

        <!-- MENU -->

        <div class="col-md-8">

            <div class="row">

                @forelse($menu as $m)

                <div class="col-md-4 mb-3">

                    <div class="card card-box">

                        <!-- GAMBAR MENU -->

                        @if($m->foto)

                            <img src="{{ asset('storage/' . $m->foto) }}"
                                 class="menu-img">

                        @else

                            <div class="menu-img d-flex align-items-center justify-content-center bg-primary text-white">

                                <i class="fa-solid fa-bowl-food fa-2x"></i>

                            </div>

                        @endif

                        <div class="p-3 text-center">

                            <h6 class="fw-bold">
                                {{ $m->nama_menu }}
                            </h6>

                            <h5 class="text-primary">
                                Rp {{ number_format($m->harga) }}
                            </h5>

                            <form action="/penjualan/tambah/{{ $m->id }}" method="POST">

                                @csrf

                                <button class="btn btn-primary w-100">

                                    Tambah

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

                @empty

                    <p class="text-muted">
                        Menu belum tersedia
                    </p>

                @endforelse

            </div>

        </div>

        <!-- KERANJANG -->

        <div class="col-md-4">

            <div class="card card-box p-3">

                <h5 class="fw-bold mb-3">
                    Keranjang
                </h5>

                @php $total = 0; @endphp

                <table class="table table-borderless">

                    @if(session('cart'))

                        @foreach(session('cart') as $id => $item)

                        @php $total += $item['subtotal']; @endphp

                    <tr>

                        <td width="100%">

                            <div class="d-flex justify-content-between align-items-start">

                                <div>

                                    <b>{{ $item['nama_menu'] }}</b>

                                    <br>

                                    <small>
                                        {{ $item['qty'] }} x {{ number_format($item['harga']) }}
                                    </small>

                                </div>

                                <div class="d-flex align-items-center">

                                    <span class="me-3">
                                        Rp {{ number_format($item['subtotal']) }}
                                    </span>

                                    <a href="/penjualan/hapus/{{ $id }}"
                                    class="text-danger">

                                        <i class="fa fa-trash"></i>

                                    </a>

                                </div>

                            </div>

                        </td>

                    </tr>


                        </tr>

                        @endforeach

                    @else

                        <tr>

                            <td class="text-center text-muted">

                                Keranjang kosong

                            </td>

                        </tr>

                    @endif

                </table>

                <hr>

                <div class="d-flex justify-content-between">

                    <b>Total</b>

                    <b class="text-primary">
                        Rp {{ number_format($total) }}
                    </b>

                </div>

            </div>

            <!-- CHECKOUT -->
            @if(session('cart')) 
            <div class="card card-box p-3 mt-3">

                <h5 class="fw-bold mb-3">
                
                        <h5 class="fw-bold mb-3">
                🍜 Rekomendasi Menu
            </h5>

            @forelse($rekomendasi as $r)
            
            <div class="card mb-2">

                @if($r->foto)
                    <img src="{{ asset('storage/' . $r->foto) }}"
                        class="card-img-top"
                        style="height:120px; object-fit:cover;">
                @endif

                <div class="card-body text-center">

                    <b>{{ $r->nama_menu }}</b>

                    <br>

                    <span class="text-primary">
                        Rp {{ number_format($r->harga) }}
                    </span>

                    <form action="/penjualan/tambah/{{ $r->id }}" method="POST" class="mt-2">

                        @csrf

                        <button type="submit" class="btn btn-success btn-sm w-100">

                            <i class="fa fa-plus"></i>
                            Tambah

                        </button>

                    </form>

                </div>

            </div>

            @empty

            <p class="text-muted">
                Tidak ada rekomendasi
            </p>

            @endforelse

            </div>
            @endif

            <div class="card card-box p-3 mt-3">

                <form action="/penjualan/simpan" method="POST">

                    @csrf

                    <label class="form-label">
                        Kasir
                    </label>

                    <select name="karyawan_id"
                            class="form-control mb-3"
                            required>

                        <option value="">
                            Pilih Kasir
                        </option>

                        @forelse($karyawan as $k)

                            <option value="{{ $k->id }}">
                                {{ $k->nama_pegawai }}
                            </option>

                        @empty

                            <option disabled>
                                Tidak ada data kasir
                            </option>

                        @endforelse

                    </select>

                    <button class="btn btn-success w-100">

                        Simpan Transaksi

                    </button>

                </form>

            </div>

        </div>

    </div>

    <!-- RIWAYAT -->

    <div class="card card-box p-4 mt-4">

        <h4 class="fw-bold mb-3">

            Riwayat Transaksi

        </h4>

        <table class="table table-bordered">

            <thead class="table-primary">

                <tr>

                    <th>No Faktur</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($penjualan as $p)

                <tr>

                    <td>{{ $p->no_faktur }}</td>

                    <td>{{ $p->tgl }}</td>

                    <td>
                        Rp {{ number_format($p->total) }}
                    </td>

                    <td>

                        @if($p->status == 'pending')

                            <span class="badge bg-warning">
                                Pending
                            </span>

                        @else

                            <span class="badge bg-success">
                                Lunas
                            </span>

                        @endif

                    </td>

                    <td>

                        @if($p->status == 'pending')

                            <a href="/midtrans/{{ $p->id }}"
                               class="btn btn-success btn-sm">

                                Bayar

                            </a>

                        @else

                            <button class="btn btn-secondary btn-sm" disabled>

                                Sudah Dibayar

                            </button>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5"
                        class="text-center text-muted">

                        Belum ada transaksi

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>