<?php
require_once "../../koneksi.php";
session_start();

// Cek login dan role admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Pastikan ada parameter ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Hapus produk dari database
$query = "DELETE FROM produk WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus produk: " . mysqli_error($conn);
}
?>
