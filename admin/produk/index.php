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
<html>

<head>
    <title>Data Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2>Daftar Produk</h2>
    <div class="d-flex mb-3 gap-3">
        <a href="../dashboard.php" class="btn btn-primary">Kembali</a>
        <a href="tambah.php" class="btn btn-success">+ Tambah Produk</a>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <?= $row['nama'] ?>
            </td>
            <td>
                <?= $row['jenis'] ?>
            </td>
            <td>Rp
                <?= number_format($row['harga'], 0, ',', '.') ?>
            </td>
            <td>
                <?= $row['stok'] ?>
            </td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Hapus produk ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>