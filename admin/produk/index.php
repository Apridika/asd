<?php
require_once "../../koneksi.php";
session_start();

// Cek jika bukan admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            padding: 2rem;
            max-width: 1200px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .page-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-custom:hover::before {
            left: 100%;
        }

        .btn-back {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-back:hover {
            background: linear-gradient(45deg, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
            color: white;
        }

        .btn-add {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }

        .btn-add:hover {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
            color: white;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(45deg, #34495e, #2c3e50);
            color: white;
        }

        .table thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(45deg, rgba(52, 152, 219, 0.1), rgba(155, 89, 182, 0.1));
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: middle;
            text-align: center;
        }

        .product-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .product-type {
            background: linear-gradient(45deg, #9b59b6, #8e44ad);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
        }

        .product-price {
            color: #27ae60;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .product-stock {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            color: white;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            min-width: 60px;
        }

        .action-buttons-table {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-sm-custom {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(45deg, #e67e22, #f39c12);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.4);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(45deg, #c0392b, #e74c3c);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                padding: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .action-buttons {
                justify-content: stretch;
            }

            .btn-custom {
                flex: 1;
                min-width: 120px;
            }

            .table-container {
                overflow-x: auto;
            }

            .action-buttons-table {
                flex-direction: column;
            }
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header Section -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-boxes"></i>
                Data Produk
            </h1>
            <p class="page-subtitle">Kelola produk dalam sistem inventory</p>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="../dashboard.php" class="btn btn-custom btn-back">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
            <a href="tambah.php" class="btn btn-custom btn-add">
                <i class="fas fa-plus"></i>
                Tambah Produk Baru
            </a>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-tag"></i> Nama Produk</th>
                        <th><i class="fas fa-layer-group"></i> Jenis</th>
                        <th><i class="fas fa-money-bill-wave"></i> Harga</th>
                        <th><i class="fas fa-warehouse"></i> Stok</th>
                        <th><i class="fas fa-cogs"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <span class="product-name"><?= htmlspecialchars($row['nama']) ?></span>
                        </td>
                        <td>
                            <span class="product-type"><?= htmlspecialchars($row['jenis']) ?></span>
                        </td>
                        <td>
                            <span class="product-price">
                                Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                            </span>
                        </td>
                        <td>
                            <span class="product-stock"><?= $row['stok'] ?></span>
                        </td>
                        <td>
                            <div class="action-buttons-table">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm-custom btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm-custom btn-delete"
                                    onclick="return confirm('⚠️ Apakah Anda yakin ingin menghapus produk ini?\n\nProduk: <?= htmlspecialchars($row['nama']) ?>\nAksi ini tidak dapat dibatalkan!')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h4>Belum Ada Produk</h4>
                <p>Mulai tambahkan produk pertama Anda untuk mengelola inventory</p>
                <a href="tambah.php" class="btn btn-custom btn-add mt-3">
                    <i class="fas fa-plus"></i>
                    Tambah Produk Pertama
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add loading effect to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!this.classList.contains('btn-delete')) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="loading"></span> Loading...';
                    this.disabled = true;
                    
                    // Re-enable after a short delay (in case of same-page actions)
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }
            });
        });

        // Add smooth scrolling
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add fade-in animation on page load
        window.addEventListener('load', function() {
            document.querySelector('.main-container').style.opacity = '0';
            document.querySelector('.main-container').style.transform = 'translateY(20px)';
            document.querySelector('.main-container').style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                document.querySelector('.main-container').style.opacity = '1';
                document.querySelector('.main-container').style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>

</html>