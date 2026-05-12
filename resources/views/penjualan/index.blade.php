<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Kasir - Baso Jafra</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body{
            background:#f4f7fe;
            font-family:Segoe UI;
        }

        /* SIDEBAR */
        .sidebar{
            width:250px;
            height:100vh;
            position:fixed;
            left:0;
            top:0;
            background:linear-gradient(180deg,#0d6efd,#084298);
            padding:25px;
            color:white;
        }

        .sidebar a{
            color:white;
            text-decoration:none;
            display:block;
            margin-bottom:10px;
        }

        /* MAIN */
        .main{
            margin-left:270px;
            padding:30px;
        }

        .card-box{
            border:none;
            border-radius:15px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
            overflow:hidden;
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

    <h4>Baso Jafra</h4>
    <hr>

    <a href="/dashboard"><i class="fa fa-home"></i> Dashboard</a>
    <a href="/penjualan"><i class="fa fa-cart-shopping"></i> Transaksi</a>
    <a href="/logout"><i class="fa fa-right-from-bracket"></i> Logout</a>

</div>

<!-- MAIN -->
<div class="main">

    <h3 class="fw-bold mb-4">Transaksi Baru</h3>

    <div class="row">

        <!-- MENU -->
        <div class="col-md-8">

            <div class="row">

                @forelse($menu as $m)

                <div class="col-md-4 mb-3">

                    <div class="card card-box">

                        <!-- GAMBAR MENU -->
                        @if($m->foto)
                            <img src="{{ asset('storage/' . $m->foto) }}" class="menu-img">
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
                    <p class="text-muted">Menu belum tersedia</p>
                @endforelse

            </div>

        </div>

        <!-- CART -->
        <div class="col-md-4">

            <div class="card card-box p-3">

                <h5>Keranjang</h5>

                @php $total = 0; @endphp

                <table class="table table-borderless">

                    @if(session('cart'))

                        @foreach(session('cart') as $item)

                        @php $total += $item['subtotal']; @endphp

                        <tr>
                            <td>
                                <b>{{ $item['nama_menu'] }}</b><br>
                                <small>{{ $item['qty'] }} x {{ number_format($item['harga']) }}</small>
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($item['subtotal']) }}
                            </td>
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
                    <b class="text-primary">Rp {{ number_format($total) }}</b>
                </div>

            </div>

            <!-- CHECKOUT -->
            <div class="card card-box p-3 mt-3">

                <form action="/penjualan/simpan" method="POST">
                    @csrf

                    <label class="form-label">Kasir</label>

                    <select name="karyawan_id" class="form-control mb-3" required>

                        <option value="">Pilih Kasir</option>

                        @forelse($karyawan as $k)
                            <option value="{{ $k->id }}">
                                {{ $k->nama_pegawai }}
                            </option>
                        @empty
                            <option disabled>Tidak ada data kasir</option>
                        @endforelse

                    </select>

                    <button class="btn btn-success w-100">
                        Simpan Transaksi
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>