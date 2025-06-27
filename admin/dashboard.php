<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}

?>




<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjualan - Toko Tas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #000000;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #000000;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            text-decoration: none;
        }

        body {
            font-family: "Nunito", sans-serif;
            background-color: #f8f9fc;
        }


        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #343434 100%);
            min-height: 100vh;
        }

        .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            letter-spacing: 0.05rem;
            z-index: 1;
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .metric-card {
            border-left: 0.25rem solid;
        }

        .metric-card.total-pendapatan {
            border-left-color: var(--primary-color);
        }

        .metric-card.pendapatan-bersih {
            border-left-color: var(--success-color);
        }

        .metric-card.total-pengeluaran {
            border-left-color: var(--danger-color);
        }

        .metric-card.produk-terjual {
            border-left-color: var(--info-color);
        }

        .metric-icon {
            font-size: 2rem;
            opacity: 0.3;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
    <!-- feather icons -->
    <script src="https://unpkg.com/feather-icons"></script>


</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->

            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse text-white">
                <div class="position-sticky pt-4"> <!-- pt-4 untuk padding top -->
                    <div class="sidebar-brand d-flex align-items-center justify-content-center text-white mb-4 pb-1"
                        href="#">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Yudhistira Handmade</span>
                    </div>

                    <ul class="nav flex-column px-2"> <!-- px-2 untuk padding horizontal -->
                        <li class="nav-item mb-2">
                            <a href="../index.html" class="nav-link active text-white py-2"> <!-- py-2 -->
                                <i data-feather='home'></i>
                                <span class="px-2">
                                    Homepage
                                </span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href='produk/index.php' class="nav-link text-white py-2">
                                <i class="fas fa-fw fa-boxes me-2"></i>
                                <span class="px-1">
                                    Produk
                                </span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link text-white py-2">
                                <i class="fas fa-fw fa-receipt me-2"></i>
                                <span class="px-1">
                                    Transaksi
                                </span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link text-white py-2">
                                <i class="fas fa-fw fa-chart-pie me-2"></i>
                                <span class="px-1">
                                    Laporan
                                </span>
                            </a>
                        </li>
                        <li class="nav-item "> <!-- mt-3 untuk margin top khusus logout -->
                            <a href="../logout.php" class="nav-link text-white py-2 ">
                                <i data-feather='log-out'></i>
                                <span class="px-2">
                                    Logout
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="maincontent">
                    <h1 class="h2 mb-4">Dashboard Penjualan</h1>
                </div>
                <!-- Metrics Row -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card metric-card total-pendapatan h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Pendapatan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp12.450.000</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill-wave metric-icon text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card metric-card pendapatan-bersih h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Pendapatan Bersih</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp8.120.000</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-wallet metric-icon text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card metric-card total-pengeluaran h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Total Pengeluaran</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp4.330.000</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-invoice-dollar metric-icon text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card metric-card produk-terjual h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Tas Terjual</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">142</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shopping-bag metric-icon text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row">
                    <!-- Pendapatan vs Pengeluaran Chart -->
                    <div class="col-xl-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Pendapatan vs Pengeluaran Bulan Ini</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li><a class="dropdown-item" href="#">Harian</a></li>
                                        <li><a class="dropdown-item" href="#">Mingguan</a></li>
                                        <li><a class="dropdown-item" href="#">Bulanan</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#">Ekspor Data</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="incomeExpenseChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Tas Terlaris -->
                    <div class="col-xl-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Kategori Terlaris</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="bestCategoryChart"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="me-2">
                                        <i class="fas fa-circle text-primary"></i> Tas Ransel
                                    </span>
                                    <span class="me-2">
                                        <i class="fas fa-circle text-success"></i> Tas Selempang
                                    </span>
                                    <span class="me-2">
                                        <i class="fas fa-circle text-info"></i> Tas Tote
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Transaksi Terakhir</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID Transaksi</th>
                                                <th>Produk</th>
                                                <th>Pelanggan</th>
                                                <th>Tanggal</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#TRX-001</td>
                                                <td>Tas Ransel Premium</td>
                                                <td>Andi Wijaya</td>
                                                <td>2023-05-15</td>
                                                <td>Rp450.000</td>
                                                <td><span class="badge bg-success">Selesai</span></td>
                                            </tr>
                                            <tr>
                                                <td>#TRX-002</td>
                                                <td>Tas Selempang Minimalis</td>
                                                <td>Budi Santoso</td>
                                                <td>2023-05-14</td>
                                                <td>Rp320.000</td>
                                                <td><span class="badge bg-success">Selesai</span></td>
                                            </tr>
                                            <tr>
                                                <td>#TRX-003</td>
                                                <td>Tas Tote Kanvas</td>
                                                <td>Citra Dewi</td>
                                                <td>2023-05-14</td>
                                                <td>Rp275.000</td>
                                                <td><span class="badge bg-warning">Proses</span></td>
                                            </tr>
                                            <tr>
                                                <td>#TRX-004</td>
                                                <td>Tas Ransel Sekolah</td>
                                                <td>Dian Pratama</td>
                                                <td>2023-05-13</td>
                                                <td>Rp380.000</td>
                                                <td><span class="badge bg-success">Selesai</span></td>
                                            </tr>
                                            <tr>
                                                <td>#TRX-005</td>
                                                <td>Tas Laptop</td>
                                                <td>Eka Putri</td>
                                                <td>2023-05-12</td>
                                                <td>Rp420.000</td>
                                                <td><span class="badge bg-danger">Dibatalkan</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        feather.replace();
    </script>

    <!-- java script -->
    <script src="script.js"></script>