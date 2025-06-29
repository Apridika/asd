<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout Produk</title>
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
      max-width: 480px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    h4 {
      color: #333;
      margin-bottom: 30px;
      text-align: center;
      font-size: 28px;
      font-weight: 600;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #555;
      font-size: 14px;
    }
    
    input, select {
      width: 100%;
      padding: 15px;
      border: 2px solid #e1e5e9;
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: white;
    }
    
    input:focus, select:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }
    
    .btn {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }
    
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    .btn:active {
      transform: translateY(-1px);
    }
  </style>
</head>
<body>
  <div class="container">
    <h4>Form Checkout</h4>
    
    <form action="preview_checkout.php" method="post">
      <div class="form-group">
        <label for="nama_pembeli">Nama Pembeli</label>
        <input type="text" name="nama_pembeli" required>
      </div>
      
      <div class="form-group">
        <label for="produk_id">Pilih Produk</label>
        <select name="produk_id" required>
          <option value="">-- Pilih Produk --</option>
          <?php
          $result = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0");
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id']}'>{$row['nama']} (Stok: {$row['stok']})</option>";
          }
          ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="jumlah">Jumlah</label>
        <input type="number" name="jumlah" min="1" required>
      </div>
      
      <button type="submit" class="btn">Buat Struk</button>
    </form>
  </div>
</body>
</html>