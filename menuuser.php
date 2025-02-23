<?php
ob_start();
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika belum login
    header("Location: login.php");
    exit();
}

// Cek role user
if ($_SESSION['peran'] !== 'user') {
    header("Location: index.php");
    exit();
}

// Include koneksi database
include('koneksi.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard User - DPRD Sultra</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="img/logosultra.png"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/sidebar.css" />
    
    <style>
      .main-panel {
        background: #f4f6f9;
        min-height: 100vh;
        padding: 2rem;
        margin-left: 260px;
        width: calc(100% - 260px);
      }
      
      .page-header {
        background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      }

      .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        color: white;
      }

      .page-subtitle {
        opacity: 0.9;
        margin-top: 0.5rem;
      }

      .info-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      }

      .info-card h5 {
        color: #1a237e;
        font-weight: 600;
        margin-bottom: 1rem;
      }

      .info-card p {
        color: #64748b;
        margin-bottom: 0;
      }

      .status-card {
        background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(30,136,229,0.2);
      }

      .status-card h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
      }

      .status-card p {
        opacity: 0.9;
        margin: 0;
      }

      .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
      }

      .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
      }

      .activity-item:last-child {
        border-bottom: none;
      }

      .activity-time {
        font-size: 0.875rem;
        color: #64748b;
      }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <?php include('sidebar.php'); ?>
      
      <div class="main-panel">
        <!-- Page Header -->
        <div class="page-header">
          <h1 class="page-title">Selamat Datang, <?php echo $_SESSION['nama']; ?></h1>
          <p class="page-subtitle">Dashboard Analisis Jabatan DPRD Sulawesi Tenggara</p>
        </div>

        <div class="row">
          <!-- Status Card -->
          <div class="col-md-4">
            <div class="status-card">
              <h3>Status Analisis</h3>
              <p>Aktif</p>
            </div>
          </div>

          <!-- Info Cards -->
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <div class="info-card">
                  <h5>Jabatan Saat Ini</h5>
                  <p><?php echo $_SESSION['jabatan'] ?? 'Belum diatur'; ?></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-card">
                  <h5>NIP</h5>
                  <p><?php echo $_SESSION['nip'] ?? 'Belum diatur'; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content Section -->
        <div class="row mt-4">
          <div class="col-md-8">
            <div class="info-card">
              <h5>Panduan Penggunaan</h5>
              <div class="mb-4">
                <h6 class="fw-bold">1. Dashboard</h6>
                <p>Halaman ini menampilkan ringkasan informasi dan status Anda.</p>
              </div>
              <div class="mb-4">
                <h6 class="fw-bold">2. Input Data</h6>
                <p>Gunakan menu Input Data untuk mengisi formulir analisis jabatan Anda.</p>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-card">
              <h5>Aktivitas Terakhir</h5>
              <ul class="activity-list">
                <li class="activity-item">
                  <div class="d-flex justify-content-between">
                    <span>Login ke sistem</span>
                    <span class="activity-time"><?php echo date('H:i'); ?></span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Core JS Files -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>
  </body>
</html>
<?php ob_end_flush(); ?>
