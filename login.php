<?php
session_start();
require_once "koneksi.php"; // pastikan file koneksi sudah dibuat



$error = '';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin/dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

// Ambil user dari database berdasarkan username
$query = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; // ← PENTING
    header("Location: admin/dashboard.php");
    exit;
} else {
    $error = "Username atau password salah!";
}


}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Tas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --dark-bg: #121212;
            --darker-bg: #0a0a0a;
            --dark-card: #1e1e1e;
            --primary-color: #6200ee;
            --primary-hover: #7c4dff;
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
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }
        
        .login-card {
            background-color: var(--dark-card);
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }
        
        .login-header {
            background-color: var(--darker-bg);
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-body {
            padding: 30px;
        }
        
        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            height: 45px;
            border-radius: 5px;
        }
        
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(98, 0, 238, 0.25);
        }
        
        .form-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 5px;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }
        
        .input-group-text {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-secondary);
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--text-secondary);
            margin: 20px 0;
            font-size: 0.8rem;
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .divider::before {
            margin-right: 10px;
        }
        
        .divider::after {
            margin-left: 10px;
        }
        
        .social-login .btn {
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .social-login .btn:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .social-login .btn i {
            margin-right: 8px;
            font-size: 1.1rem;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: #ff4444;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .brand-logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .brand-logo i {
            margin-right: 10px;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="brand-logo">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Dashboard</span>
                    </div>
                    <p class="mb-0">Silakan masuk ke akun Anda</p>
                </div>
                
                <div class="login-body">
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                            </div>
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk
                            </button>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            <div>
                                <a href="#" class="text-decoration-none" style="color: var(--primary-color);">Lupa password?</a>
                            </div>
                        </div>
                        
                        <!-- <div class="divider">ATAU</div>
                        
                        <div class="social-login mb-4">
                            <button type="button" class="btn btn-block">
                                <i class="fab fa-google text-danger"></i> Lanjutkan dengan Google
                            </button>
                            <button type="button" class="btn btn-block">
                                <i class="fab fa-facebook-f text-primary"></i> Lanjutkan dengan Facebook
                            </button>
                        </div> -->
                    </form>
                    <!-- <div class="login-footer">
                        Belum punya akun? <a href="register.php">Daftar sekarang</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi sederhana saat form muncul
        document.addEventListener('DOMContentLoaded', function() {
            const loginCard = document.querySelector('.login-card');
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                loginCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                loginCard.style.opacity = '1';
                loginCard.style.transform = 'translateY(0)';
            }, 100);
            
            // Toggle password visibility
            const passwordInput = document.getElementById('password');
            const togglePassword = document.createElement('span');
            togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
            togglePassword.style.position = 'absolute';
            togglePassword.style.right = '10px';
            togglePassword.style.top = '50%';
            togglePassword.style.transform = 'translateY(-50%)';
            togglePassword.style.cursor = 'pointer';
            togglePassword.style.color = 'var(--text-secondary)';
            
            const inputGroup = passwordInput.parentElement;
            inputGroup.style.position = 'relative';
            inputGroup.appendChild(togglePassword);
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        });
    </script>
</body>
</html>