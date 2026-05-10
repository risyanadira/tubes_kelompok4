<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir - Baso Jafra</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        body{
            background: #f4f7fe;
            font-family: 'Segoe UI', sans-serif;
        }

        /* SIDEBAR */

        .sidebar{
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #0d6efd, #084298);
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
        }

        .logo{
            color: white;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .sidebar-menu a{
            display: block;
            color: white;
            text-decoration: none;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: 0.3s;
            font-size: 16px;
        }

        .sidebar-menu a:hover{
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }

        .active-menu{
            background: rgba(255,255,255,0.25);
            font-weight: 600;
        }

        .sidebar-menu i{
            margin-right: 10px;
        }

        .logout{
            margin-top: auto;
        }

        .logout a{
            display: block;
            color: white;
            text-decoration: none;
            padding: 14px 16px;
            border-radius: 12px;
            background: rgba(255,255,255,0.15);
            transition: 0.3s;
        }

        .logout a:hover{
            background: rgba(255,255,255,0.25);
        }

        /* MAIN */

        .main-content{
            margin-left: 270px;
            padding: 30px;
        }

        .topbar{
            background: white;
            border-radius: 20px;
            padding: 20px 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .menu-card{
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
            overflow: hidden;
        }

        .menu-card:hover{
            transform: translateY(-5px);
        }

        .menu-header{
            background: linear-gradient(90deg, #0d6efd, #3d8bfd);
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 45px;
        }

        .cart-box{
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .btn-primary{
            border-radius: 12px;
            padding: 10px;
        }

        .table{
            vertical-align: middle;
        }

        .payment-box{
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-top: 25px;
        }

    </style>

</head>
<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div>

            <div class="logo">
                <i class="fa-solid fa-bowl-food"></i>
                Baso Jafra
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

    <!-- MAIN CONTENT -->

    <div class="main-content">

        <!-- TOPBAR -->

        <div class="topbar d-flex justify-content-between align-items-center">

            <div>

                <h4 class="fw-bold text-primary mb-1">
                    Transaksi Baru
                </h4>

                <small class="text-muted">
                    Sistem POS Baso Jafra Solo
                </small>

            </div>

            <span class="badge bg-primary p-3">
                <i class="fa-solid fa-cash-register"></i>
                Kasir Aktif
            </span>

        </div>

        <div class="row">

            <!-- MENU -->

            <div class="col-md-8">

                <div class="row">

                    @foreach($menu as $m)

                    <div class="col-md-4 mb-4">

                        <div class="card menu-card">

                            <div class="menu-header p-0">

                                <img
                                    src="{{ asset('menu/' . $m->foto) }}"
                                    class="w-100"
                                    style="
                                        height:120px;
                                        object-fit:cover;
                                    "
                                >

                            </div>
                            <div class="card-body text-center">

                                <h5 class="fw-bold">
                                    {{ $m->nama_menu }}
                                </h5>

                                <h4 class="text-primary fw-bold mb-4">
                                    Rp {{ number_format($m->harga) }}
                                </h4>

                                <form action="/penjualan/tambah/{{ $m->id }}" method="POST">

                                    @csrf

                                    <button class="btn btn-primary w-100">

                                        <i class="fa-solid fa-plus"></i>

                                        Tambah

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            <!-- KERANJANG -->

            <div class="col-md-4">

                <div class="cart-box">

                    <h4 class="fw-bold text-primary mb-4">

                        <i class="fa-solid fa-cart-shopping"></i>

                        Keranjang

                    </h4>

                    <table class="table table-borderless">

                        @php
                            $total = 0;
                        @endphp

                        @if(session('cart'))

                            @foreach(session('cart') as $id => $item)

                            @php
                                $total += $item['subtotal'];
                            @endphp

                            <tr>

                                <td>

                                    <b>{{ $item['nama_menu'] }}</b>

                                    <br>

                                    <small class="text-muted">
                                        {{ $item['qty'] }} x
                                        Rp {{ number_format($item['harga']) }}
                                    </small>

                                </td>

                                <td class="text-end fw-bold">

                                    Rp {{ number_format($item['subtotal']) }}

                                </td>

                            </tr>

                            @endforeach

                        @endif

                    </table>

                    <hr>

                    <div class="d-flex justify-content-between">

                        <h5 class="fw-bold">
                            Total
                        </h5>

                        <h5 class="fw-bold text-primary">

                            Rp {{ number_format($total) }}

                        </h5>

                    </div>

                </div>

                <!-- PAYMENT -->

                <div class="payment-box">

                    <form action="/penjualan/simpan" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">
                                Kasir
                            </label>

                            <select name="karyawan_id" class="form-control" required>

                                <option value="">
                                    -- Pilih Kasir --
                                </option>

                                @foreach($karyawan as $k)

                                <option value="{{ $k->id }}">
                                    {{ $k->nama_pegawai }}
                                </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Metode Pembayaran
                            </label>

                            <select name="kode_metode" class="form-control" required>

                                <option value="">
                                    -- Pilih Metode --
                                </option>

                                @foreach($metode as $m)

                                <option value="{{ $m->kode_metode }}">
                                    {{ $m->nama_metode }}
                                </option>

                                @endforeach

                            </select>

                        </div>

                        <button class="btn btn-success w-100 p-3 fw-bold">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Simpan Transaksi

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</body>
</html>