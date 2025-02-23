<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
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
$sql = "SELECT * FROM users WHERE 1=1";

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
$totalAdmin = $conn->query("SELECT COUNT(*) as count FROM users WHERE peran = 'admin'")->fetch_assoc()['count'];
$totalStaff = $conn->query("SELECT COUNT(*) as count FROM users WHERE peran = 'user'")->fetch_assoc()['count'];
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
                    <ul class="navbar-nav ms-md-auto align-items-center">
                        <!-- Profile User Dropdown -->
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                <!-- Flexbox untuk menempatkan ikon avatar dan nama pengguna -->
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <i class="fas fa-user-circle fa-2x"></i>
                                    </div>
                                </div>
                            </a>
                            <!-- Dropdown menu untuk profile -->
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li class="dropdown-item">
                                        <div class="account-info">
                                            <p class="account-name fw-bold">
                                                <?php 
                                                if(isset($_SESSION['nama'])) {
                                                    echo $_SESSION['nama'];
                                                } else {
                                                    echo "User";
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </div>
                            </ul>
                        </li>
                    </ul>
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

        <!-- Search and Filter Section -->
        <div class="search-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Daftar Pengguna</h4>
                <a href="tambah_pengguna.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Pengguna
                </a>
            </div>
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" class="form-control search-input border-start-0" 
                               placeholder="Cari berdasarkan nama, NIP, atau jabatan..." 
                               value="<?php echo htmlspecialchars($searchTerm); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="all" <?php echo $roleFilter == 'all' ? 'selected' : ''; ?>>Semua Peran</option>
                        <option value="admin" <?php echo $roleFilter == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo $roleFilter == 'user' ? 'selected' : ''; ?>>User</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-filter w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-section">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th style="width: 5%">No.</th>
                            <th style="width: 25%">Nama</th>
                            <th style="width: 20%">NIP</th>
                            <th style="width: 20%">Jabatan</th>
                            <th style="width: 15%">Peran</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>
                                        <td>' . $no . '</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <i class="fas fa-user-circle fa-2x"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">' . htmlspecialchars($row['nama']) . '</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>' . htmlspecialchars($row['nip']) . '</td>
                                        <td>' . htmlspecialchars($row['jabatan']) . '</td>
                                        <td>
                                            <span class="badge ' . ($row['peran'] == 'admin' ? 'badge-admin' : 'badge-user') . '">
                                                ' . ucfirst(htmlspecialchars($row['peran'])) . '
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex space-x-3">
                                                <a href="edit_pengguna.php?id=' . $row['id'] . '" 
                                                   class="p-1.5 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="showDeleteConfirmation(' . $row['id'] . ')" 
                                                   class="p-1.5 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>';
                                $no++;
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center py-4">Tidak ada data pengguna yang ditemukan.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>

    <!-- Add Modal HTML before closing body tag -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="mb-3">Berhasil Hapus</h3>
                    <p class="mb-4">Data pengguna berhasil dihapus dari sistem</p>
                    <button type="button" class="btn btn-primary px-4" onclick="window.location.href='manajemenpengguna.php'">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-circle text-warning" style="font-size: 4rem; color: #f59e0b;"></i>
                    </div>
                    <h3 class="mb-3">Konfirmasi Hapus</h3>
                    <p class="mb-4">Apakah Anda yakin ingin menghapus pengguna ini?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger px-4" id="confirmDelete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this script before closing body tag -->
    <script>
        let deleteUrl = '';

        function showDeleteConfirmation(id) {
            deleteUrl = 'hapus_pengguna.php?id=' + id;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            fetch(deleteUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        deleteModal.hide();
                        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
                    }
                });
        });
    </script>

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