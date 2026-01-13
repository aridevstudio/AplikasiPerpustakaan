<?php
session_start();
include '../Config/koneksi.php'; // Pastikan path file config benar

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = $_POST['nis'];
    $password = $_POST['password'];

    try {
        // Query untuk memeriksa data di tabel anggota
        $stmt = $conn->prepare("SELECT nis, password, nama FROM anggota WHERE nis = :nis");
        $stmt->bindParam(':nis', $nis, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifikasi password
            if ($password === $user['password']) { // Ganti dengan password_verify jika menggunakan hash
                $_SESSION['nis'] = $user['nis'];
                $_SESSION['nama'] = $user['nama'];

                header('Location: home.php'); // Ganti dengan halaman utama setelah login
                exit;
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'NIS tidak ditemukan!';
        }
    } catch (PDOException $e) {
        $error = 'Terjadi kesalahan: ' . $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login User - Perpustakaan Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
  <style>
     * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(5, 150, 105, 0.15) 0%, transparent 70%);
            top: -250px;
            right: -250px;
            border-radius: 50%;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, transparent 70%);
            bottom: -200px;
            left: -200px;
            border-radius: 50%;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
            background: white;
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-wrapper {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 32px rgba(5, 150, 105, 0.3);
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .login-logo img {
            max-width: 80px;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
            color: #059669;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .login-header {
            font-size: 32px;
            font-weight: 800;
            color: #1E293B;
            margin-bottom: 12px;
        }

        .login-subtitle {
            color: #64748B;
            font-size: 15px;
            margin-bottom: 32px;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            font-size: 14px;
            margin-bottom: 24px;
            animation: shake 0.5s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .alert-danger {
            background: #FEE2E2;
            color: #991B1B;
            border-left: 4px solid #DC2626;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .input-wrapper {
            margin-bottom: 24px;
        }

        .form-control {
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #F8FAFC;
        }

        .form-control:focus {
            border-color: #059669;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
            background: white;
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #EF4444;
            background: #FEF2F2;
        }

        .form-control.is-valid {
            border-color: #10B981;
            background: #F0FDF4;
        }

        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding-right: 50px;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #64748B;
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .password-toggle-btn:hover {
            color: #059669;
        }

        .validation-message {
            font-size: 13px;
            margin-top: 6px;
            display: none;
            align-items: center;
            gap: 6px;
        }

        .validation-message.show {
            display: flex;
        }

        .validation-message.error {
            color: #EF4444;
        }

        .validation-message.success {
            color: #10B981;
        }

        .btn-primary {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 8px;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(5, 150, 105, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            background: #CBD5E1;
            cursor: not-allowed;
            transform: none;
        }

        .btn-primary span {
            position: relative;
            z-index: 1;
        }

        .loading-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 8px;
        }

        .btn-primary.loading .loading-spinner {
            display: inline-block;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .signup-link {
            color: #059669;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link:hover {
            color: #047857;
            text-decoration: underline;
        }

        .text-center p {
            color: #64748B;
            font-size: 14px;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E2E8F0;
        }

        .divider span {
            color: #94A3B8;
            font-size: 13px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #64748B;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover {
            color: #059669;
        }

        .feature-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #F0FDF4;
            color: #047857;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-container {
                padding: 36px 28px;
            }

            .login-header {
                font-size: 28px;
            }

            .login-subtitle {
                font-size: 14px;
            }

            .logo-wrapper {
                width: 80px;
                height: 80px;
            }

            .login-logo img {
                max-width: 60px;
            }

            .form-control {
                padding: 12px 16px;
                font-size: 14px;
            }

            .btn-primary {
                padding: 14px 24px;
                font-size: 15px;
            }

            body::before,
            body::after {
                display: none;
            }
        }
  </style>
 </head>
 <body>
  <div class="login-container">
   <div class="login-logo">
<span class="user-badge"> <i class="bi bi-person-circle"></i> User Portal </span>
   </div>
   <h2 class="login-header text-center">Welcome Back!</h2>
   <p class="login-subtitle text-center">Please login to your account</p><!-- Tampilkan pesan error --> <?php if ($error): ?>
   <div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-circle"></i> <span><?php echo htmlspecialchars($error); ?></span>
   </div><?php endif; ?>
   <form method="POST" action="" id="loginForm" novalidate>
    <div class="input-wrapper"><label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label> <input type="text" name="nis" class="form-control" id="nis" placeholder="Masukkan NIS Anda" required>
     <div class="validation-message error" id="nisError"><i class="bi bi-exclamation-circle"></i> <span>NIS wajib diisi</span>
     </div>
    </div>
    <div class="input-wrapper"><label for="password" class="form-label">Password</label>
     <div class="input-group"><input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password Anda" required> <button class="password-toggle-btn" type="button" id="showPassword"> <i class="bi bi-eye-slash"></i> </button>
     </div>
     <div class="validation-message error" id="passwordError"><i class="bi bi-exclamation-circle"></i> <span>Password wajib diisi</span>
     </div>
    </div><button type="submit" class="btn btn-primary w-100" id="loginBtn"> <span> <i class="bi bi-box-arrow-in-right me-2"></i>Login </span> <span class="loading-spinner"></span> </button>
   </form>
   <div class="divider"><span>atau</span>
   </div>
   <div class="back-link"><a href="../index.php"> <i class="bi bi-arrow-left"></i> Kembali ke Portal </a>
   </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
        // Password Toggle
        const showPassword = document.getElementById("showPassword");
        const passwordInput = document.getElementById("password");

        showPassword.addEventListener("click", () => {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                showPassword.innerHTML = '<i class="bi bi-eye"></i>';
            } else {
                passwordInput.type = "password";
                showPassword.innerHTML = '<i class="bi bi-eye-slash"></i>';
            }
        });

        // Form Validation
        const loginForm = document.getElementById('loginForm');
        const nisInput = document.getElementById('nis');
        const nisError = document.getElementById('nisError');
        const passwordError = document.getElementById('passwordError');
        const loginBtn = document.getElementById('loginBtn');

        // Real-time validation for NIS
        nisInput.addEventListener('input', function() {
            validateNIS();
        });

        nisInput.addEventListener('blur', function() {
            validateNIS();
        });

        // Real-time validation for Password
        passwordInput.addEventListener('input', function() {
            validatePassword();
        });

        passwordInput.addEventListener('blur', function() {
            validatePassword();
        });

        function validateNIS() {
            const value = nisInput.value.trim();
            
            if (value === '') {
                nisInput.classList.add('is-invalid');
                nisInput.classList.remove('is-valid');
                nisError.classList.add('show');
                nisError.querySelector('span').textContent = 'NIS wajib diisi';
                return false;
            } else if (!/^\d+$/.test(value)) {
                nisInput.classList.add('is-invalid');
                nisInput.classList.remove('is-valid');
                nisError.classList.add('show');
                nisError.querySelector('span').textContent = 'NIS hanya boleh berisi angka';
                return false;
            } else if (value.length < 5) {
                nisInput.classList.add('is-invalid');
                nisInput.classList.remove('is-valid');
                nisError.classList.add('show');
                nisError.querySelector('span').textContent = 'NIS minimal 5 digit';
                return false;
            } else {
                nisInput.classList.remove('is-invalid');
                nisInput.classList.add('is-valid');
                nisError.classList.remove('show');
                return true;
            }
        }

        function validatePassword() {
            const value = passwordInput.value.trim();
            
            if (value === '') {
                passwordInput.classList.add('is-invalid');
                passwordInput.classList.remove('is-valid');
                passwordError.classList.add('show');
                passwordError.querySelector('span').textContent = 'Password wajib diisi';
                return false;
            } else if (value.length < 6) {
                passwordInput.classList.add('is-invalid');
                passwordInput.classList.remove('is-valid');
                passwordError.classList.add('show');
                passwordError.querySelector('span').textContent = 'Password minimal 6 karakter';
                return false;
            } else {
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.add('is-valid');
                passwordError.classList.remove('show');
                return true;
            }
        }

        // Form Submit
        loginForm.addEventListener('submit', function(e) {
            const isNISValid = validateNIS();
            const isPasswordValid = validatePassword();

            if (!isNISValid || !isPasswordValid) {
                e.preventDefault();
                
                // Focus on first invalid field
                if (!isNISValid) {
                    nisInput.focus();
                } else if (!isPasswordValid) {
                    passwordInput.focus();
                }
            } else {
                // Add loading state
                loginBtn.classList.add('loading');
                loginBtn.disabled = true;
            }
        });

        // Prevent multiple submissions
        let isSubmitting = false;
        loginForm.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            
            if (validateNIS() && validatePassword()) {
                isSubmitting = true;
            }
        });

        // Auto-format NIS (remove non-numeric characters)
        nisInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9bd5ef9bf24cb5e4',t:'MTc2ODMxODIzOC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>