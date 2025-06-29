<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}
require_once "../koneksi.php";

// Jumlah produk
$q_produk = mysqli_query($conn, "SELECT COUNT(*) AS total_produk FROM produk");
$produk = mysqli_fetch_assoc($q_produk)['total_produk'];

// Total stok
$q_stok = mysqli_query($conn, "SELECT SUM(stok) AS total_stok FROM produk");
$stok = mysqli_fetch_assoc($q_stok)['total_stok'];

// Total nilai persediaan
$q_nilai = mysqli_query($conn, "SELECT SUM(harga * stok) AS total_nilai FROM produk");
$nilai = mysqli_fetch_assoc($q_nilai)['total_nilai'];

// Jenis produk (count per jenis)
$q_jenis = mysqli_query($conn, "
    SELECT jenis, COUNT(*) AS jumlah 
    FROM produk 
    GROUP BY jenis
");
$jenis_data = [];
while ($row = mysqli_fetch_assoc($q_jenis)) {
    $jenis_data[] = $row;
}


// Total produk terjual hanya dari transaksi yang SELESAI
$query_terjual = mysqli_query($conn, "
  SELECT SUM(jumlah) AS total_terjual 
  FROM transaksi 
  WHERE status = 'Selesai'
");
$data_terjual = mysqli_fetch_assoc($query_terjual);
$total_terjual = $data_terjual['total_terjual'] ?? 0;

// Produk terjual per jenis hanya dari transaksi SELESAI
$query = mysqli_query($conn, "
  SELECT p.jenis, SUM(t.jumlah) AS total_terjual
  FROM transaksi t
  JOIN produk p ON t.produk_id = p.id
  WHERE t.status = 'Selesai'
  GROUP BY p.jenis
");

$jenis = [];
$jumlah = [];

while ($row = mysqli_fetch_assoc($query)) {
    $jenis[] = $row['jenis'];
    $jumlah[] = $row['total_terjual'];
}

?>




<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Toko Tas</title>
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
                            <a href="../index.php" class="nav-link active text-white py-2"> <!-- py-2 -->
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
                            <a href='transaksi/index.php' class="nav-link text-white py-2">
                                <i class="fas fa-fw fa-receipt me-2"></i>
                                <span class="px-1">
                                Transaksi
                                </span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href='laporan/index.php' class="nav-link text-white py-2">
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
                    <h1 class="h2 mb-4">Dashboard Inventory</h1>
                </div>
                <!-- Metrics Row -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card metric-card total-pendapatan h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Produk</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $produk ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <!-- <i class="fas fa-money-bill-wave metric-icon text-primary"></i> -->
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
                                            Total Stok</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $stok ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <!-- <i class="fas fa-wallet metric-icon text-success"></i> -->
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
                                            Total Nilai persediaan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                            <?= number_format($nilai, 0, ',', '.') ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <!-- <i class="fas fa-file-invoice-dollar metric-icon text-danger"></i> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card metric-card produk-terjual h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Tas Terjual</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $total_terjual ?> pcs
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shopping-bag metric-icon text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

                <!-- Charts Row -->
                <div class="row">
                    <!-- Pendapatan vs Pengeluaran Chart
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
                    </div> -->

                    <!-- Kategori Tas Terlaris -->
                    <div class="col-xl-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Kategori Terlaris</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="bestCategoryChart"></canvas>
                                </div>

                                <div class="mt-4 text-center small">
                                    <span class="me-2">
                                        <i class="fas fa-circle text-primary"></i> Tas Backpack
                                    </span>
                                    <span class="me-2">
                                        <i class="fas fa-circle text-success"></i> Tas Selempang
                                    </span>
                                    <span class="me-2">
                                        <i class="fas fa-circle text-info"></i> Tas Laptop
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <script>
        feather.replace();
    </script>

    <!-- java script -->
    <script src="script.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('bestCategoryChart').getContext('2d');
        const bestCategoryChart = new Chart(ctx, {
            type: 'doughnut', // atau 'bar'
            data: {
                labels: <?= json_encode($jenis) ?>,
                datasets: [{
                    label: 'Produk Terjual',
                    data: <?= json_encode($jumlah) ?>,
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>