<?php
include 'session_check.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Termasuk file koneksi ke database
include('koneksi.php');

// Menangani pencarian dan filter jika ada
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$roleFilter = isset($_GET['role']) ? $_GET['role'] : 'all';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Query untuk mengambil data pengguna sesuai filter
$sql = "SELECT * FROM tb_user WHERE 1=1";

if (!empty($searchTerm)) {
    $sql .= " AND (nama LIKE '%$searchTerm%' OR nip LIKE '%$searchTerm%' OR jabatan LIKE '%$searchTerm%')";
}

if ($roleFilter != 'all') {
    $sql .= " AND peran = '$roleFilter'";
}

// Menjalankan query
$result = $conn->query($sql);

// Hitung total pengguna
$totalUsers = $result->num_rows;
$totalAdmin = $conn->query("SELECT COUNT(*) as count FROM tb_user WHERE peran = 'admin'")->fetch_assoc()['count'];
$totalStaff = $conn->query("SELECT COUNT(*) as count FROM tb_user WHERE peran = 'user'")->fetch_assoc()['count'];
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
    <style>
        /* Main Panel Styling */
        .main-panel {
            margin-left: 260px;
            background: #f8fafc;
            min-height: 100vh;
            padding: 2rem;
            transition: all 0.3s ease;
            width: calc(100% - 260px);
            position: relative;
            padding-top: 100px !important;
        }

        /* Header Styling */
        .main-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 260px;
            z-index: 999;
            background: white;
            transition: all 0.3s ease;
        }

        .navbar-header {
            padding: 0.5rem 1rem;
            background: white !important;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        .logo-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-header .logo img {
            height: 40px;
            width: auto;
        }

        .navbar-brand img {
            transition: all 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .navbar-brand span {
            font-size: 1.2rem;
            margin-left: 1rem;
            transition: all 0.3s ease;
        }

        .page-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            letter-spacing: 1px;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1a237e, #283593);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            box-shadow: 0 4px 15px rgba(26, 35, 126, 0.2);
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stats-info h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stats-info p {
            color: #64748b;
            font-size: 1.1rem;
            margin: 0;
            font-weight: 500;
        }

        /* Search Section */
        .search-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .search-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
            color: #1a237e;
        }

        .search-input::placeholder {
            color: #94a3b8;
        }

        .search-input:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
            background: white;
        }

        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
            cursor: pointer;
            color: #1a237e;
        }

        .form-select:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
            background: white;
        }

        /* Table Section */
        .table-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .custom-table th {
            background: #f8fafc;
            padding: 1.2rem 1rem;
            font-weight: 600;
            color: #1a237e;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .custom-table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #1a237e;
            vertical-align: middle;
            font-weight: 500;
        }

        .custom-table tr:hover {
            background: #f8fafc;
        }

        .avatar-sm {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: #1a237e;
        }

        tr:hover .avatar-sm {
            transform: scale(1.1);
            background: #1a237e;
            color: white;
        }

        /* Badge Styling */
        .badge {
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .badge-admin {
            background: #1a237e;
            color: white;
        }

        .badge-user {
            background: #00796b;
            color: white;
        }

        tr:hover .badge {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Action Buttons */
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            font-size: 14px;
            border: none;
            border-radius: 10px;
        }

        .btn-info {
            background-color: rgba(67, 24, 255, 0.1);
            color: #4318FF;
        }

        .btn-danger {
            background-color: rgba(255, 0, 0, 0.1);
            color: #FF0000;
        }

        .btn-info:hover {
            background-color: rgba(67, 24, 255, 0.2);
            color: #4318FF;
        }

        .btn-danger:hover {
            background-color: rgba(255, 0, 0, 0.2);
            color: #FF0000;
        }

        .btn-group {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-start;
            padding: 0;
            background: transparent;
            box-shadow: none;
        }

        .text-dark {
            color: #1a237e !important;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-panel {
                margin-left: 0;
                padding-top: 80px !important;
                width: 100%;
            }

            .main-header {
                left: 0;
            }
            
            .navbar-brand span {
                font-size: 1rem;
            }
        }

        /* Action Buttons */
        .flex.space-x-3 {
            display: flex;
            gap: 0.75rem;
        }

        .p-1\.5 {
            padding: 0.375rem;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .text-red-600 {
            color: #dc2626;
        }

        .hover\:text-blue-900:hover {
            color: #1e3a8a;
        }

        .hover\:text-red-900:hover {
            color: #7f1d1d;
        }

        .hover\:bg-blue-50:hover {
            background-color: #eff6ff;
        }

        .hover\:bg-red-50:hover {
            background-color: #fef2f2;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .transition-colors {
            transition-property: color, background-color;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
    </style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/sidebar.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body class="bg-light">
    <div class="wrapper">
      <?php include('sidebar.php'); ?>

      <div class="main-panel">
        <!-- Main Header -->
        <div class="main-header">
            <div class="main-header-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="index.php" class="logo">
                        <img src="img/logosultra.png" alt="navbar brand" class="navbar-brand" height="20" />
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
                        <span style="font-weight: 1000; color:rgb(3, 36, 71);">SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH PROVINSI SULAWESI TENGGARA</span>
                    </a>
                    <!-- Navbar Item -->
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $totalUsers; ?></h3>
                        <p>Total Pengguna</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $totalAdmin; ?></h3>
                        <p>Admin</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?php echo $totalStaff; ?></h3>
                        <p>Staff</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Example code for user listing table with action buttons -->
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nama</th>
            <th>NIP</th>
            <th>Jabatan</th>
            <th>Peran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Query untuk mendapatkan semua pengguna
        $sql = "SELECT * FROM tb_user";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['nip']; ?></td>
                <td><?php echo $row['jabatan']; ?></td>
                <td><?php echo $row['peran']; ?></td>
                <td>
                    <a href="edit_pengguna.php?id=<?php echo $row['id_user']; ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="hapus_pengguna.php?id=<?php echo $row['id_user']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>Tidak ada data pengguna</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Button to add new user -->
<!-- Button to add new user -->
<div class="mb-4">
    <a href="tambahpengguna.php" class="btn btn-success">
        <i class="fas fa-user-plus me-1"></i> Tambah Pengguna Baru
    </a>
</div>

    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>
  </body>
</html>
<?php
// Menutup koneksi database
$conn->close();
?>