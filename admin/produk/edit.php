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
<html>
<head>
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Edit Produk</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($produk['nama']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Jenis</label>
            <input type="text" name="jenis" class="form-control" value="<?= htmlspecialchars($produk['jenis']) ?>">
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" step="0.01" class="form-control" value="<?= $produk['harga'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $produk['stok'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</body>
</html>
