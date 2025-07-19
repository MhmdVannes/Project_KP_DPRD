<?php
include 'koneksi.php';
include 'session_check.php';

// Get ID from URL or session
$id_user = isset($_GET['id_user']) ? intval($_GET['id_user']) : $_SESSION['id_user'];

// Check if user is logged in and has the correct role
if (!isset($_SESSION['id_user']) || !isset($_SESSION['peran']) || $_SESSION['peran'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// For security, verify the URL id_user matches the session id_user
if ($id_user != $_SESSION['id_user']) {
    // If not matching, redirect to the correct URL
    header("Location: index.php?id_user=" . $_SESSION['id_user']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Sekretariat Dewan Perwakilan Rakyat Daerah Provinsi Sulawesi Tenggara</title>
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
    <link rel="stylesheet" href="assets/css/demo.css" />
    <!-- Sidebar CSS should be loaded last -->
    <link rel="stylesheet" href="assets/css/sidebar.css" />

    <style>
        /* Main Panel Styling */
        .main-panel {
            margin-left: 250px;
            padding-top: 100px;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 20px;
            background: #f4f6f9;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Header Styling */
        .main-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 999;
            background: white;
            transition: all 0.3s ease;
        }

        .navbar-header {
            padding: 0.5rem 1rem;
            background: white !important;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        /* Stats Cards Styling */
        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-panel {
                margin-left: 0;
                padding-top: 80px;
            }

            .main-header {
                left: 0;
            }
        }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <?php include('sidebar.php'); ?>

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.php" class="logo">
                <img
                  src="img/logosultra.png"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
  <div class="container-fluid">
    <!-- Logo dan Teks -->
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="img/logosultra.png" alt="SIASN Logo" class="d-inline-block me-2" style="height: 50px; max-height: 50px;">
      <span style="font-weight: 1000; color:rgb(3, 36, 71);">SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH  SULAWESI TENGGARA</span>
    </a>
  </div>
</nav>
          <!-- End Navbar -->
        </div>

        <div class="container-fluid">
          <!-- Page Header -->
          <div class="page-header">
            <h1 class="page-title">Dashboard</h1>
          </div>

<!-- Stats Cards --> 
<div class="row">
    <a href="manajemenpengguna.php" class="col-sm-6 col-md-6">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-users" style="color: black;"></i>
            </div>
            <div class="stats-info">
                <h3 style="color: black;">
                    <?php
                    // Query untuk menghitung jumlah user dengan peran 'user'
                    $sql = "SELECT COUNT(*) AS total_user FROM tb_user WHERE peran = 'user'";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $totalUser = $row['total_user'];
                    } else {
                        $totalUser = 0;
                    }
                    
                    echo number_format($totalUser);
                    ?>
                </h3>
                <p style="color: black;">Total Pengguna</p>
            </div>
        </div>
    </a>
    
    <a href="hasilanalisisjabatan.php" class="col-sm-6 col-md-6">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-file-alt" style="color: black;"></i>
            </div>
            <div class="stats-info">
                <h3 style="color: black;">
                    <?php
                    // Query untuk menghitung jumlah nama_jabatan yang sudah diinput
                    $sql = "SELECT COUNT(nama_jabatan) AS total_jabatan FROM tb_input_jabatan WHERE nama_jabatan IS NOT NULL";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $totalJabatan = $row['total_jabatan'];
                    } else {
                        $totalJabatan = 0;
                    }
                    
                    echo number_format($totalJabatan);
                    ?>
                </h3>
                <p style="color: black;">Analisis Jabatan</p>
            </div>
        </div>
    </a>
</div>


          <!-- Charts Section -->
          <!-- <div class="row">
            <div class="col-md-8">
              <div class="chart-card">
                <div class="card-header">
                  <h4 class="card-title">Statistik Pegawai</h4>
                  <p class="card-category">Data 6 bulan terakhir</p>
                </div>
                <div class="card-body">
                  <div class="chart-container" style="min-height: 375px">
                    <canvas id="statisticsChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="chart-card">
                <div class="card-header">
                  <h4 class="card-title">Distribusi Jabatan</h4>
                  <p class="card-category">Berdasarkan tingkat jabatan</p>
                </div>
                <div class="card-body">
                  <div class="chart-container" style="min-height: 375px">
                    <canvas id="distributionChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div> -->

          <!-- Recent Activities -->
          <!-- <div class="row">
            <div class="col-md-12">
              <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title">Aktivitas Terbaru</h4>
                  <button class="btn btn-primary btn-action">
                    <i class="fas fa-download me-2"></i>Unduh Laporan
                  </button>
                </div>
                <div class="table-responsive">
                  <table class="table custom-table">
                    <thead>
                      <tr>
                        <th>Pegawai</th>
                        <th>Aktivitas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3">
                              <i class="fas fa-user-circle fa-2x text-gray-400"></i>
                            </div>
                            <div>
                              <div class="fw-bold">ganti</div>
                              <small class="text-muted">Kepala Bagian</small>
                            </div>
                          </div>
                        </td>
                        <td>Mengajukan analisis jabatan baru</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                        <td>2024-01-20</td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3">
                              <i class="fas fa-user-circle fa-2x text-gray-400"></i>
                            </div>
                            <div>
                              <div class="fw-bold">Siti Aminah</div>
                              <small class="text-muted">Staff</small>
                            </div>
                          </div>
                        </td>
                        <td>Update data kepegawaian</td>
                        <td><span class="badge bg-warning">Proses</span></td>
                        <td>2024-01-19</td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3">
                              <i class="fas fa-user-circle fa-2x text-gray-400"></i>
                            </div>
                            <div>
                              <div class="fw-bold">Budi Santoso</div>
                              <small class="text-muted">Supervisor</small>
                            </div>
                          </div>
                        </td>
                        <td>Review dokumen analisis</td>
                        <td><span class="badge bg-info">Review</span></td>
                        <td>2024-01-18</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      </div>
      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>
    <!-- <script>
      // Statistics Chart
      var ctx = document.getElementById('statisticsChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [{
            label: 'Pegawai Aktif',
            data: [1200, 1250, 1300, 1280, 1290, 1294],
            borderColor: '#4299e1',
            backgroundColor: 'rgba(66, 153, 225, 0.1)',
            borderWidth: 2,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      // Distribution Chart
      var ctx2 = document.getElementById('distributionChart').getContext('2d');
      new Chart(ctx2, {
        type: 'doughnut',
        data: {
          labels: ['Pimpinan', 'Supervisor', 'Staff', 'Kontrak'],
          datasets: [{
            data: [15, 30, 45, 10],
            backgroundColor: [
              '#4299e1',
              '#48bb78',
              '#ed64a6',
              '#ecc94b'
            ]
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    </script> -->
  </body>
</html>
