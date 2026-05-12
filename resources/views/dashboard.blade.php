<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir - Baso Jafra</title>

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

        /* LOGOUT */

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
            border-radius: 18px;
            padding: 20px 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .welcome-box{
            background: linear-gradient(90deg, #0d6efd, #3d8bfd);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 6px 18px rgba(13,110,253,0.3);
        }

        .card-dashboard{
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
        }

        .card-dashboard:hover{
            transform: translateY(-5px);
        }

        .icon-box{
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .bg-blue{
            background: #0d6efd;
        }

        .bg-green{
            background: #198754;
        }

        .bg-orange{
            background: #fd7e14;
        }

        .table-card{
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
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

                <a href="/dashboard" class="active-menu">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>

                <a href="/penjualan">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Transaksi Baru
                </a>

                <a href="/laporan">
                    <i class="fa-solid fa-file-lines"></i>
                    Laporan Penjualan
                </a>

            </div>

        </div>

        <!-- LOGOUT -->

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
                    Dashboard Kasir
                </h4>

                <small class="text-muted">
                    Sistem POS Baso Jafra Solo
                </small>

            </div>

            <span class="badge bg-primary p-3">
                <i class="fa-solid fa-user"></i>
                Kasir Aktif
            </span>

        </div>

        <!-- WELCOME -->

        <div class="welcome-box">

            <h3 class="fw-bold mb-3">
                Selamat Datang 👋
            </h3>

            <p class="mb-0">
                Kelola transaksi penjualan Baso Jafra dengan cepat,
                mudah, dan modern.
            </p>

        </div>

        <!-- CARD -->

        <div class="row">

            <!-- TOTAL TRANSAKSI -->

            <div class="col-md-4 mb-4">

                <div class="card card-dashboard">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-muted">
                                Total Transaksi Hari Ini
                            </h6>

                            <h2 class="fw-bold text-primary">
                                {{ $totalTransaksi }}
                            </h2>

                        </div>

                        <div class="icon-box bg-blue">

                            <i class="fa-solid fa-cart-shopping"></i>

                        </div>

                    </div>

                </div>

            </div>

            <!-- TOTAL PENJUALAN -->

            <div class="col-md-4 mb-4">

                <div class="card card-dashboard">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-muted">
                                Total Penjualan Hari Ini
                            </h6>

                            <h2 class="fw-bold text-success">
                                Rp {{ number_format($totalPenjualan) }}
                            </h2>

                        </div>

                        <div class="icon-box bg-green">

                            <i class="fa-solid fa-money-bill-wave"></i>

                        </div>

                    </div>

                </div>

            </div>

            <!-- TOTAL MENU -->

            <div class="col-md-4 mb-4">

                <div class="card card-dashboard">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-muted">
                                Total Menu
                            </h6>

                            <h2 class="fw-bold text-warning">
                                {{ $totalMenu }}
                            </h2>

                        </div>

                        <div class="icon-box bg-orange">

                            <i class="fa-solid fa-utensils"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- TRANSAKSI TERBARU -->

        <div class="table-card">

            <h5 class="fw-bold text-primary mb-4">
                Transaksi Terbaru
            </h5>

            <table class="table table-hover align-middle">

                <thead class="table-primary">

                    <tr>
                        <th>No Faktur</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($transaksiTerbaru as $t)

                    <tr>

                        <td>
                            {{ $t->no_faktur }}
                        </td>

                        <td>
                            {{ $t->tgl }}
                        </td>

                        <td>
                            Rp {{ number_format($t->total) }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</body>
</html>