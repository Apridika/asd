<?php
include '../../koneksi.php';

// Ambil input filter tanggal dari URL
$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';

// Siapkan kondisi tambahan untuk filter SQL
$filter = '';
if ($start && $end) {
    $filter = "AND tanggal BETWEEN '$start' AND '$end'";
}

// Total pendapatan dari transaksi selesai
$q = mysqli_query($conn, "SELECT SUM(total) as pendapatan FROM transaksi WHERE status = 'Selesai' $filter");
$data = mysqli_fetch_assoc($q);
$pendapatan = $data['pendapatan'] ?? 0;

// Jumlah transaksi selesai
$q = mysqli_query($conn, "SELECT COUNT(*) as jumlah_transaksi FROM transaksi WHERE status = 'Selesai' $filter");
$data = mysqli_fetch_assoc($q);
$jumlah_transaksi = $data['jumlah_transaksi'];

// Total produk terjual dari transaksi selesai
$q = mysqli_query($conn, "SELECT SUM(jumlah) as produk_terjual FROM transaksi WHERE status = 'Selesai' $filter");
$data = mysqli_fetch_assoc($q);
$produk_terjual = $data['produk_terjual'] ?? 0;

// Grafik pendapatan bulanan
$bulanan = [];
$q = mysqli_query($conn, "
    SELECT DATE_FORMAT(tanggal, '%M') AS bulan, SUM(total) AS pendapatan
    FROM transaksi
    WHERE status = 'Selesai' $filter
    GROUP BY MONTH(tanggal)
    ORDER BY MONTH(tanggal)
");
while ($row = mysqli_fetch_assoc($q)) {
    $bulanan['label'][] = $row['bulan'];
    $bulanan['data'][] = $row['pendapatan'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Dashboard Analytics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .header h1 {
            color: #1a202c;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 20px;
        }

        .icon-revenue { background: linear-gradient(135deg, #10b981, #059669); }
        .icon-transaction { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .icon-product { background: linear-gradient(135deg, #f59e0b, #d97706); }

        .stat-label {
            color: #9ca3af;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            color: #1f2937;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .filter-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        .filter-title {
            color: #1f2937;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 20px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            color: #4b5563;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-input {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-group {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        .chart-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        .chart-title {
            color: #1f2937;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-button {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-form {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .back-button {
                bottom: 20px;
                left: 20px;
                right: 20px;
                text-align: center;
            }
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 20px;
        }

        canvas {
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1><i class="fas fa-chart-line"></i> Laporan Penjualan</h1>
            <p>Dashboard Analytics & Reporting System</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-revenue">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value">Rp<?= number_format($pendapatan, 0, ',', '.') ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-transaction">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-label">Transaksi Selesai</div>
                <div class="stat-value"><?= number_format($jumlah_transaksi) ?></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-product">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-label">Produk Terjual</div>
                <div class="stat-value"><?= number_format($produk_terjual) ?> <span style="font-size: 1rem; font-weight: 500;">pcs</span></div>
            </div>
        </div>

        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i> Filter Periode
            </div>
            <form method="get" class="filter-form">
                <div class="form-group">
                    <label for="start" class="form-label">Dari Tanggal</label>
                    <input type="date" id="start" name="start" class="form-input" value="<?= $_GET['start'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label for="end" class="form-label">Sampai Tanggal</label>
                    <input type="date" id="end" name="end" class="form-input" value="<?= $_GET['end'] ?? '' ?>">
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Terapkan
                    </button>
                    <a href="cetak.php?start=<?= $_GET['start'] ?? '' ?>&end=<?= $_GET['end'] ?? '' ?>" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
            </form>
        </div>

        <div class="chart-section">
            <div class="chart-title">
                <i class="fas fa-chart-area"></i> Grafik Pendapatan Bulanan
            </div>
            <div class="chart-container">
                <canvas id="grafikBulanan"></canvas>
            </div>
        </div>
    </div>

    <a href="../dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>

    <script>
        const ctx = document.getElementById('grafikBulanan').getContext('2d');
        
        // Create gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        gradient.addColorStop(1, 'rgba(118, 75, 162, 0.1)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($bulanan['label'] ?? []) ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($bulanan['data'] ?? []) ?>,
                    borderColor: '#667eea',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                return 'Pendapatan: Rp' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280',
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
</body>
</html>