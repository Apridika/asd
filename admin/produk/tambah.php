<?php
require_once "../../koneksi.php";
session_start();

// Cek jika bukan admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = "INSERT INTO produk (nama, jenis, harga, stok) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssdi", $nama, $jenis, $harga, $stok);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menambah produk: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Tambah Produk</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jenis</label>
            <input type="text" name="jenis" class="form-control">
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</body>
</html>
