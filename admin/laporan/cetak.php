<?php
include '../../koneksi.php';
$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';
$filter = '';

if ($start && $end) {
    $filter = "AND tanggal BETWEEN '$start' AND '$end'";
}

// ambil data transaksi selesai
$data = mysqli_query($conn, "SELECT * FROM transaksi WHERE status = 'Selesai' $filter");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Cetak Laporan</title>
  <style>
    body { font-family: Arial; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    h2 { text-align: center; }
  </style>
</head>
<body>
  <h2>Laporan Transaksi</h2>
  <p>Periode: <?= $start ?> s.d <?= $end ?></p>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Pembeli</th>
        <th>Produk</th>
        <th>Jumlah</th>
        <th>Total</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $no = 1;
      while($row = mysqli_fetch_assoc($data)):
        $produk_id = $row['produk_id'];
        $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM produk WHERE id = '$produk_id'"));
      ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_pembeli'] ?></td>
        <td><?= $produk['nama'] ?></td>
        <td><?= $row['jumlah'] ?></td>
        <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
        <td><?= $row['tanggal'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>


  <script>
    window.print();
  </script>
</body>
</html>
