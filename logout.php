<?php
session_start();

// Hapus semua data session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Set header no-cache untuk memastikan halaman tidak di-cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Toko Tas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --dark-bg: #121212;
            --darker-bg: #0a0a0a;
            --dark-card: #1e1e1e;
            --primary-color: #6200ee;
            --text-primary: #e1e1e1;
            --text-secondary: #b0b0b0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logout-container {
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }
        
        .logout-card {
            background-color: var(--dark-card);
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
            padding: 40px;
        }
        
        .logout-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        
        .logout-message {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 25px;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 5px;
        }
        
        .btn-primary:hover {
            background-color: #7c4dff;
        }
        
        .brand-logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .brand-logo i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .redirect-message {
            color: var(--text-secondary);
            margin-top: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout-container">
            <div class="logout-card">
                <div class="brand-logo">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Toko Tas</span>
                </div>
                
                <div class="logout-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                
                <h2 class="logout-message">Anda telah logout</h2>
                
                <p>Terima kasih telah menggunakan sistem kami. Anda akan diarahkan ke halaman login dalam beberapa detik.</p>
                
                <div class="d-grid gap-2">
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Kembali Ke Homepage
                    </a>
                </div>
                
                <div class="redirect-message">
                    Jika tidak otomatis redirect, <a href="login.php" style="color: var(--primary-color);">klik di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Redirect otomatis setelah 5 detik
        setTimeout(function() {
            window.location.href = "index.php";
        }, 5000);
    </script>
</body>
</html>