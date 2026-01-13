<?php
include '../../config/koneksi.php'; // Menghubungkan ke database menggunakan PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Query untuk validasi login admin menggunakan PDO
        $query = "SELECT id_petugas, nama_petugas FROM petugas WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Login berhasil
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            session_start();
            $_SESSION['id_petugas'] = $data['id_petugas']; // Simpan ID petugas di sesi
            $_SESSION['nama_petugas'] = $data['nama_petugas']; // Simpan nama petugas di sesi
            header('Location: ../Dashboard/dashboard.php'); // Redirect ke dashboard admin
            exit;
        } else {
            // Login gagal
            $error = "Username atau password salah.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Perpustakaan Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            background: radial-gradient(circle, rgba(220, 38, 38, 0.15) 0%, transparent 70%);
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
            max-width: 1000px;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: grid;
            grid-template-columns: 1fr 1fr;
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

        .login-form {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .graphic-side {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .graphic-side::before {
            content: '📚';
            position: absolute;
            font-size: 150px;
            opacity: 0.1;
            top: 20%;
            right: 10%;
            animation: float 4s ease-in-out infinite;
        }

        .graphic-side::after {
            content: '📖';
            position: absolute;
            font-size: 120px;
            opacity: 0.08;
            bottom: 15%;
            left: 5%;
            animation: float 5s ease-in-out infinite 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .logo-style {
            max-width: 100%;
            height: auto;
            filter: brightness(0) invert(1);
            animation: pulse 3s ease-in-out infinite;
            position: relative;
            z-index: 1;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            color: #DC2626;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .login-form h2 {
            font-size: 32px;
            font-weight: 800;
            color: #1E293B;
            margin-bottom: 12px;
        }

        .login-form > p {
            color: #64748B;
            font-size: 15px;
            margin-bottom: 0;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            font-size: 14px;
            margin-top: 24px;
            animation: shake 0.5s ease;
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
            position: relative;
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
            border-color: #DC2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
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

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #64748B;
            font-size: 18px;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #DC2626;
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
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
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
            box-shadow: 0 12px 24px rgba(220, 38, 38, 0.3);
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

        .back-link {
            text-align: center;
            margin-top: 24px;
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
            color: #DC2626;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
            }

            .graphic-side {
                display: none;
            }

            .login-form {
                padding: 40px 30px;
            }

            .login-form h2 {
                font-size: 28px;
            }

            body::before,
            body::after {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .login-form {
                padding: 30px 20px;
            }

            .login-form h2 {
                font-size: 24px;
            }

            .form-control {
                padding: 12px 16px;
                font-size: 14px;
            }

            .btn-primary {
                padding: 14px 24px;
                font-size: 15px;
            }
        }
    </style>
 </head>
 <body>
  <div class="login-container">
   <div class="login-form">
    <div class="login-header"><span class="admin-badge"> <i class="bi bi-shield-lock-fill"></i> Admin Access </span>
    </div>
    <h2 class="fw-bold text-center">Login Admin</h2>
    <p class="text-center mb-4">Masukkan username dan password Anda untuk masuk.</p><?php if (isset($error)): ?>
    <div class="alert alert-danger text-center"><i class="bi bi-exclamation-circle me-2"></i><?= $error; ?>
    </div><?php endif; ?>
    <form method="POST" action="" id="loginForm" novalidate>
     <div class="input-wrapper"><label for="username" class="form-label">Username</label> <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username Anda" required>
      <div class="validation-message error" id="usernameError"><i class="bi bi-exclamation-circle"></i> <span>Username wajib diisi</span>
      </div>
     </div>
     <div class="input-wrapper"><label for="password" class="form-label">Password</label>
      <div class="password-wrapper"><input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password Anda" required> <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
      </div>
      <div class="validation-message error" id="passwordError"><i class="bi bi-exclamation-circle"></i> <span>Password wajib diisi</span>
      </div>
     </div><button type="submit" class="btn btn-primary w-100" id="loginBtn"> <span> <i class="bi bi-box-arrow-in-right me-2"></i>Login </span> <span class="loading-spinner"></span> </button>
    </form>
    <div class="back-link"><a href="../../index.php"> <i class="bi bi-arrow-left"></i> Kembali ke Portal </a>
    </div>
   </div>
   <div class="graphic-side"><img src="../../Assets/img/logorev.svg" alt="Logo Perpustakaan Digital" class="img-fluid logo-style">
   </div>
  </div>
  <script src="../../Assets/scripts/login_Admin.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script></body>
</html>