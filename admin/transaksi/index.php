<?php
include '../../koneksi.php';
$result = mysqli_query($conn, "
  SELECT t.*, p.nama AS nama_produk 
  FROM transaksi t 
  JOIN produk p ON t.produk_id = p.id
  ORDER BY t.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        .container {
            max-width: 1400px;
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

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .table-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            padding: 10px 40px 10px 15px;
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.9rem;
            width: 250px;
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }

        .search-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
            width: 300px;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.8);
        }

        .table-wrapper {
            overflow-x: auto;
            max-height: 70vh;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .modern-table th {
            background: #f8fafc;
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .modern-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-selesai {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .status-proses {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .status-dibatalkan {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .no-action {
            color: #9ca3af;
            font-style: italic;
            font-size: 0.9rem;
        }

        .currency {
            font-weight: 600;
            color: #059669;
        }

        .id-badge {
            background: #e5e7eb;
            color: #374151;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .quantity-badge {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .date-text {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .stats-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            border-radius: 12px;
            background: #f8fafc;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 500;
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
            z-index: 1000;
        }

        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .search-input {
                width: 100%;
            }
            
            .search-input:focus {
                width: 100%;
            }
            
            .stats-bar {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .action-buttons {
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-exchange-alt"></i> Data Transaksi</h1>
            <p>Sistem Manajemen Transaksi & Monitoring</p>
        </div>

        <?php
        // Hitung statistik
        $total_transaksi = mysqli_num_rows($result);
        mysqli_data_seek($result, 0); // Reset pointer
        
        $selesai = $proses = $dibatalkan = 0;
        $total_pendapatan = 0;
        
        $temp_result = mysqli_query($conn, "
            SELECT status, COUNT(*) as count, SUM(CASE WHEN status = 'Selesai' THEN total ELSE 0 END) as pendapatan
            FROM transaksi 
            GROUP BY status
        ");
        
        while($stat = mysqli_fetch_assoc($temp_result)) {
            if($stat['status'] == 'Selesai') $selesai = $stat['count'];
            elseif($stat['status'] == 'Proses') $proses = $stat['count'];
            elseif($stat['status'] == 'Dibatalkan') $dibatalkan = $stat['count'];
            $total_pendapatan += $stat['pendapatan'];
        }
        ?>

        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number"><?= $total_transaksi ?></div>
                <div class="stat-label">Total Transaksi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $selesai ?></div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $proses ?></div>
                <div class="stat-label">Proses</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $dibatalkan ?></div>
                <div class="stat-label">Dibatalkan</div>
            </div>
            <!-- <div class="stat-item">
                <div class="stat-number">Rp<?= number_format($total_pendapatan, 0, ',', '.') ?></div>
                <div class="stat-label">Total Pendapatan</div>
            </div> -->
        </div>

        <div class="table-container">
            <div class="table-header">
                <div class="table-title">
                    <i class="fas fa-table"></i>
                    Daftar Transaksi
                </div>
                <div class="search-container">
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="Cari transaksi..." id="searchInput">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
            </div>
            
            <div class="table-wrapper">
                <table class="modern-table" id="transactionTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Nama Pembeli</th>
                            <th><i class="fas fa-box"></i> Produk</th>
                            <th><i class="fas fa-sort-numeric-up"></i> Jumlah</th>
                            <th><i class="fas fa-money-bill"></i> Total</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="fas fa-calendar"></i> Tanggal</th>
                            <th><i class="fas fa-cogs"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        mysqli_data_seek($result, 0); // Reset pointer
                        if(mysqli_num_rows($result) > 0):
                            while($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><span class="id-badge">#<?= $row['id'] ?></span></td>
                            <td>
                                <strong><?= htmlspecialchars($row['nama_pembeli']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                            <td><span class="quantity-badge"><?= $row['jumlah'] ?> pcs</span></td>
                            <td><span class="currency">Rp<?= number_format($row['total'], 0, ',', '.') ?></span></td>
                            <td>
                                <?php
                                $status_class = match($row['status']) {
                                    'Selesai' => 'status-selesai',
                                    'Dibatalkan' => 'status-dibatalkan',
                                    default => 'status-proses'
                                };
                                $status_icon = match($row['status']) {
                                    'Selesai' => 'fas fa-check-circle',
                                    'Dibatalkan' => 'fas fa-times-circle',
                                    default => 'fas fa-clock'
                                };
                                ?>
                                <span class="status-badge <?= $status_class ?>">
                                    <i class="<?= $status_icon ?>"></i>
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td><span class="date-text"><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></span></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($row['status'] === 'Proses'): ?>
                                    <a href="update.php?id=<?= $row['id'] ?>&status=Selesai" class="btn btn-success">
                                        <i class="fas fa-check"></i> Selesai
                                    </a>
                                    <a href="update.php?id=<?= $row['id'] ?>&status=Dibatalkan" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Batalkan
                                    </a>
                                    <?php else: ?>
                                    <span class="no-action">
                                        <i class="fas fa-ban"></i> Tidak ada aksi
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h3>Tidak ada data transaksi</h3>
                                    <p>Belum ada transaksi yang tercatat dalam sistem</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="../dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('transactionTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        });

        // Confirmation for action buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const action = this.textContent.trim();
                if (action.includes('Selesai') || action.includes('Batalkan')) {
                    if (!confirm(`Apakah Anda yakin ingin ${action.toLowerCase()} transaksi ini?`)) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
</body>
</html>