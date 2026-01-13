<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Perpustakaan Digital</title>
  <link rel="stylesheet" href="Assets/css/portal.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
 </head>
 <body class="h-full">
  <div class="portal-container"><!-- Decorative Elements -->
   <div class="decorative-element deco-1">
    📚
   </div>
   <div class="decorative-element deco-2">
    📖
   </div>
   <div class="decorative-element deco-3">
    📕
   </div>
   <div class="container"><!-- Portal Header -->
    <div class="portal-header">
     <div class="portal-logo"><i class="bi bi-book-half text-white" style="font-size: 56px;"></i>
     </div>
     <h1 class="portal-title" id="portalTitle">Portal Perpustakaan Digital</h1>
     <p class="portal-subtitle" id="portalSubtitle">Sistem Manajemen Perpustakaan</p>
    </div><!-- Role Cards -->
    <div class="role-cards-container"><!-- Admin Card -->
     <div class="role-card admin-card" onclick="selectRole('admin')">
      <div class="role-icon-wrapper"><i class="bi bi-shield-lock-fill role-icon"></i>
      </div>
      <h2 class="role-title" id="adminTitle">Admin Dashboard</h2>
      <p class="role-description" id="adminDescription">Kelola buku, user, dan sistem perpustakaan</p>
      <ul class="features-list">
       <li><i class="bi bi-check-circle-fill"></i> <span>Manajemen koleksi buku</span></li>
       <li><i class="bi bi-check-circle-fill"></i> <span>Kelola user &amp; peminjaman</span></li>
       <li><i class="bi bi-check-circle-fill"></i> <span>Laporan &amp; statistik</span></li>
       <li><i class="bi bi-check-circle-fill"></i> <span>Konfigurasi sistem</span></li>
      </ul><button onclick="window.location.href='Admin/Layouts/login.php'" class="role-btn"> <span>Masuk Sebagai Admin </span> </button>
     </div><!-- User Card -->
     <div class="role-card user-card" onclick="selectRole('user')">
      <div class="role-icon-wrapper"><i class="bi bi-person-circle role-icon"></i>
      </div>
      <h2 class="role-title" id="userTitle">Portal Pengguna</h2>
      <p class="role-description" id="userDescription">Pinjam dan kelola buku Anda dengan mudah</p>
      <ul class="features-list">
       <li><i class="bi bi-check-circle-fill"></i> <span>Katalog buku lengkap</span></li>
       <li><i class="bi bi-check-circle-fill"></i> <span>Peminjaman online</span></li>
       <li><i class="bi bi-check-circle-fill"></i> <span>Riwayat peminjaman</span></li>
      </ul><button class="role-btn"> <span>Masuk Sebagai User</span> </button>
     </div>
    </div><!-- Footer -->
    <div class="portal-footer">
     <p id="footerText">© 2026 Perpustakaan Digital. All rights reserved.</p>
    </div>
   </div>
  </div><!-- Toast Notification -->
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
   <div id="toast" class="toast align-items-center text-white border-0" role="alert" style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); border-radius: 12px;">
    <div class="d-flex">
     <div class="toast-body fw-medium" id="toastMessage">
      Portal siap digunakan!
     </div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
   </div>
  </div>
  <script src="Assets/scripts/portal.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>