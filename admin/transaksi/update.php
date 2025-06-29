<?php
include '../../koneksi.php';

$id = $_GET['id'];
$status = $_GET['status'];

if(in_array($status, ['Selesai', 'Dibatalkan'])) {
  mysqli_query($conn, "UPDATE transaksi SET status='$status' WHERE id=$id");
}

if ($status === 'Selesai') {
    // Ambil transaksi
    $trx = mysqli_query($conn, "SELECT * FROM transaksi WHERE id = $id");
    $data = mysqli_fetch_assoc($trx);
    $produk_id = $data['produk_id'];
    $jumlah = $data['jumlah'];

    // Kurangi stok produk
    mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id = $produk_id");
}


header("Location: index.php");
exit;

