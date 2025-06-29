<?php
include "koneksi.php";

$nama_pembeli = $_POST['nama_pembeli'];
$produk_id = $_POST['produk_id'];
$jumlah = $_POST['jumlah'];

// Ambil detail produk
$produk = mysqli_query($conn, "SELECT * FROM produk WHERE id = $produk_id");
$data = mysqli_fetch_assoc($produk);
$nama_produk = $data['nama'];
$harga = $data['harga'];
$total = $harga * $jumlah;

// simpan transaksi ke database
mysqli_query($conn, "INSERT INTO transaksi (nama_pembeli, produk_id, jumlah, total, status)
VALUES ('$nama_pembeli', '$produk_id', '$jumlah', '$total', 'Proses')");

// Format teks WA
$pesan = "Halo Admin, saya ingin memesan:
Nama: $nama_pembeli
Produk: $nama_produk
Jumlah: $jumlah
Total: Rp" . number_format($total, 0, ',', '.');

$link_wa = "https://wa.me/6285708080464?text=" . urlencode($pesan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Checkout</title>
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
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    h4 {
      color: #333;
      margin-bottom: 30px;
      text-align: center;
      font-size: 28px;
      font-weight: 600;
      color: #28a745;
    }
    
    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #e1e5e9;
    }
    
    .detail-item:last-of-type {
      border-bottom: 2px solid #667eea;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }
    
    .label {
      font-weight: 600;
      color: #555;
    }
    
    .value {
      color: #333;
      font-weight: 500;
    }
    
    .total {
      font-size: 20px;
      font-weight: bold;
      color: #dc3545;
    }
    
    .btn-container {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }
    
    .btn {
      flex: 1;
      padding: 15px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      text-align: center;
      display: inline-block;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
      color: white;
    }
    
    .btn-secondary {
      background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
      color: white;
    }
    
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    .btn:active {
      transform: translateY(-1px);
    }
    
    @media (max-width: 480px) {
      .btn-container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h4>✅ Struk Checkout</h4>
    
    <div class="detail-item">
      <span class="label">Nama Pembeli:</span>
      <span class="value"><?= htmlspecialchars($nama_pembeli) ?></span>
    </div>
    
    <div class="detail-item">
      <span class="label">Produk:</span>
      <span class="value"><?= $nama_produk ?></span>
    </div>
    
    <div class="detail-item">
      <span class="label">Jumlah:</span>
      <span class="value"><?= $jumlah ?></span>
    </div>
    
    <div class="detail-item">
      <span class="label">Harga Satuan:</span>
      <span class="value">Rp<?= number_format($harga, 0, ',', '.') ?></span>
    </div>
    
    <div class="detail-item">
      <span class="label">Total:</span>
      <span class="value total">Rp<?= number_format($total, 0, ',', '.') ?></span>
    </div>

    <div class="btn-container">
      <a href="<?= $link_wa ?>" target="_blank" class="btn btn-primary">📱 Kirim ke WhatsApp</a>
      <a href="checkout.php" class="btn btn-secondary">← Kembali</a>
    </div>
  </div>
</body>
</html>