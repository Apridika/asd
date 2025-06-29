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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin Dashboard</title>
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

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            padding: 40px;
            width: 100%;
            max-width: 600px;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #ec4899, #f59e0b);
            border-radius: 24px 24px 0 0;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header h2 {
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #64748b;
            font-size: 1.1em;
        }

        .alert {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #f87171;
            color: #dc2626;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert::before {
            content: '⚠️';
            font-size: 1.2em;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        .form-select {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 50px;
        }

        .form-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1.2em;
            z-index: 1;
        }

        .input-with-icon {
            padding-left: 50px;
        }

        .currency-input {
            position: relative;
        }

        .currency-input::before {
            content: 'Rp';
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-weight: 600;
            z-index: 1;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 16px 32px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex: 1;
            min-width: 120px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(107, 114, 128, 0.4);
            color: white;
            text-decoration: none;
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .form-step {
            opacity: 0;
            animation: slideIn 0.6s ease forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .required-indicator {
            color: #ef4444;
            margin-left: 4px;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .form-container {
                padding: 30px 25px;
                border-radius: 20px;
            }

            .form-header h2 {
                font-size: 2em;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        .success-animation {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2em;
            color: white;
            animation: bounce 0.6s ease;
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: scale(1); }
            40%, 43% { transform: scale(1.1); }
            70% { transform: scale(1.05); }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="form-container">
        <div class="form-header">
            <h2>🛍️ Tambah Produk</h2>
            <p>Tambahkan produk baru ke dalam sistem inventory</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="" id="productForm">
            <div class="form-step">
                <div class="form-group">
                    <label class="form-label">
                        📦 Nama Produk
                        <span class="required-indicator">*</span>
                    </label>
                    <input type="text" 
                           name="nama" 
                           class="form-input" 
                           placeholder="Masukkan nama produk"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        🏷️ Jenis Produk
                        <span class="required-indicator">*</span>
                    </label>
                    <select name="jenis" class="form-select" required>
                        <option value="">-- Pilih Jenis Tas --</option>
                        <option value="Tas Selempang">Tas Selempang</option>
                        <option value="Tas Backpack">Tas Backpack</option>
                        <option value="Tas Laptop">Tas Laptop</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        💰 Harga
                        <span class="required-indicator">*</span>
                    </label>
                    <div class="currency-input">
                        <input type="number" 
                               name="harga" 
                               step="0.01" 
                               class="form-input input-with-icon" 
                               placeholder="0"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        📊 Stok
                        <span class="required-indicator">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-icon">📦</span>
                        <input type="number" 
                               name="stok" 
                               class="form-input input-with-icon" 
                               placeholder="Jumlah stok"
                               min="0"
                               required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        ✅ Simpan Produk
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        ❌ Batal
                    </a>
                </div>
            </div>
        </form>

        <div class="success-animation" id="successAnimation">
            <div class="checkmark">✓</div>
            <h3 style="color: #10b981; margin-bottom: 10px;">Berhasil!</h3>
            <p style="color: #6b7280;">Produk berhasil ditambahkan ke sistem</p>
        </div>
    </div>

    <script>
        // Form validation dan enhancement
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const nama = document.querySelector('input[name="nama"]').value.trim();
            const jenis = document.querySelector('select[name="jenis"]').value;
            const harga = document.querySelector('input[name="harga"]').value;
            const stok = document.querySelector('input[name="stok"]').value;

            // Validasi sederhana
            if (!nama || !jenis || !harga || !stok) {
                e.preventDefault();
                alert('Semua field harus diisi!');
                return;
            }

            if (parseFloat(harga) <= 0) {
                e.preventDefault();
                alert('Harga harus lebih dari 0!');
                return;
            }

            if (parseInt(stok) < 0) {
                e.preventDefault();
                alert('Stok tidak boleh negatif!');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '⏳ Menyimpan...';
            submitBtn.disabled = true;

            // Simulate processing time (remove this in production)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Auto-format currency input
        const hargaInput = document.querySelector('input[name="harga"]');
        hargaInput.addEventListener('input', function() {
            let value = this.value.replace(/[^\d.]/g, '');
            if (value) {
                // Format number with thousands separator for display purposes
                this.setAttribute('data-formatted', parseInt(value).toLocaleString('id-ID'));
            }
        });

        // Enhanced form animations
        const formGroups = document.querySelectorAll('.form-group');
        formGroups.forEach((group, index) => {
            group.style.animationDelay = `${index * 0.1}s`;
        });

        // Focus enhancement
        const inputs = document.querySelectorAll('.form-input, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>