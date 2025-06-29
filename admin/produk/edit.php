<?php
require_once "../../koneksi.php";
session_start();

// Cek login dan role admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Ambil ID produk dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Ambil data produk dari database
$query = "SELECT * FROM produk WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = "UPDATE produk SET nama=?, jenis=?, harga=?, stok=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssdii", $nama, $jenis, $harga, $stok, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal mengupdate produk: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin Panel</title>
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
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .form-container {
            padding: 40px;
        }

        .alert {
            background: #fee2e2;
            color: #dc2626;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #dc2626;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
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

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
        }

        .input-icon .form-control {
            padding-left: 45px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
                border-radius: 0;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .header {
                padding: 25px 20px;
            }
            
            .form-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-edit"></i> Edit Produk</h2>
            <p>Perbarui informasi produk <?= htmlspecialchars($produk['nama']) ?></p>
        </div>
        
        <div class="form-container">
            <?php if (!empty($error)): ?>
                <div class="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="nama"><i class="fas fa-tag"></i> Nama Produk</label>
                    <div class="input-icon">
                        <i class="fas fa-box"></i>
                        <input type="text" id="nama" name="nama" class="form-control" 
                               value="<?= htmlspecialchars($produk['nama']) ?>" 
                               placeholder="Masukkan nama produk" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="jenis"><i class="fas fa-list"></i> Jenis Produk</label>
                    <div class="input-icon">
                        <i class="fas fa-layer-group"></i>
                        <input type="text" id="jenis" name="jenis" class="form-control" 
                               value="<?= htmlspecialchars($produk['jenis']) ?>" 
                               placeholder="Masukkan jenis produk">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="harga"><i class="fas fa-money-bill"></i> Harga</label>
                    <div class="input-icon">
                        <i class="fas fa-rupiah-sign"></i>
                        <input type="number" id="harga" name="harga" step="0.01" class="form-control" 
                               value="<?= $produk['harga'] ?>" 
                               placeholder="0.00" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="stok"><i class="fas fa-warehouse"></i> Stok</label>
                    <div class="input-icon">
                        <i class="fas fa-cubes"></i>
                        <input type="number" id="stok" name="stok" class="form-control" 
                               value="<?= $produk['stok'] ?>" 
                               placeholder="0" required>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>