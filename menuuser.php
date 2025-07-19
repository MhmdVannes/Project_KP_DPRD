<?php
include 'session_check.php';
include 'koneksi.php';

// Get ID from URL or session
$id_user = isset($_GET['id_user']) ? intval($_GET['id_user']) : $_SESSION['id_user'];

// Check if user is logged in and has the correct role
if (!isset($_SESSION['id_user']) || !isset($_SESSION['peran']) || $_SESSION['peran'] !== 'user') {
    header("Location: login.php");
    exit();
}

// For security, verify the URL id_user matches the session id_user
if ($id_user != $_SESSION['id_user']) {
    // If not matching, redirect to the correct URL
    header("Location: menuuser.php?id_user=" . $_SESSION['id_user']);
    exit();
}

// Get user details from database
$query = "SELECT * FROM tb_user WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);
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
        </div>

        <div class="row">
          <!-- Status Card -->
          <div class="col-md-4">
            <div class="status-card">
              <h3>Status Analisis</h3>
              <p>Aktif</p>
            </div>
          </div>

          <?php
// Ambil NIP dan Jabatan dari database berdasarkan session atau kriteria yang relevan
$id_user = $_SESSION['id_user']; // Pastikan session 'user_id' ada untuk identifikasi user, jika diperlukan

// Query untuk mengambil jabatan dan nip berdasarkan user_id
$sql = "SELECT jabatan, nip FROM tb_user WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user); // Mengikat parameter user_id
$stmt->execute();
$result = $stmt->get_result();

// Menampilkan data jika ditemukan
if ($result->num_rows > 0) {
    // Ambil data dari hasil query
    $row = $result->fetch_assoc();
    $jabatan = $row['jabatan'];
    $nip = $row['nip'];
} else {
    $jabatan = 'Belum diatur'; // Jika tidak ditemukan data, tampilkan pesan default
    $nip = 'Belum diatur'; // Jika tidak ditemukan data, tampilkan pesan default
}

$stmt->close();
?>

<!-- Info Cards -->
<div class="col-md-8">
    <div class="row">
        <div class="col-md-6">
            <div class="info-card">
                <h5>Jabatan Saat Ini</h5>
                <p><?php echo $jabatan; ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-card">
                <h5>NIP</h5>
                <p><?php echo $nip; ?></p>
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

<?php
// Set zona waktu ke Sulawesi Tenggara (WITA)
date_default_timezone_set('Asia/Makassar'); // WITA (Waktu Indonesia Tengah)

// Cek apakah waktu login sudah diset di session
if (!isset($_SESSION['waktu_login'])) {
    // Jika belum diset, simpan waktu login saat pertama kali
    $_SESSION['waktu_login'] = date('H:i');
}

// Ambil waktu login dari session
$waktu_login = $_SESSION['waktu_login'];
?>

<div class="col-md-4">
    <div class="info-card">
        <h5>Aktivitas Terakhir</h5>
        <ul class="activity-list">
            <li class="activity-item">
                <div class="d-flex justify-content-between">
                    <span>Login ke sistem</span>
                    <span class="activity-time"><?php echo $waktu_login; ?></span>
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
