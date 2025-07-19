<?php
include 'koneksi.php';
include 'session_check.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user']; // Ambil ID user dari session

// Cek apakah user adalah admin
$is_admin = false;
if (isset($_SESSION['peran']) && $_SESSION['peran'] == 'admin') {
    $is_admin = true;
}

// Jika admin, ambil semua data dari semua user
if ($is_admin) {
    $query_check = "SELECT * FROM tb_input_jabatan";
    $data_inputed = true; // Asumsi ada data
} else {
    // Jika bukan admin, hanya ambil data user yang login
    $query_check = "SELECT * FROM tb_input_jabatan WHERE id_user = '$id_user'";
    // Jalankan query
    $result_check = mysqli_query($conn, $query_check);
    // Jika data ditemukan, berarti pengguna sudah menginput data
    if ($result_check && mysqli_num_rows($result_check) > 0) {
        $data_inputed = true;
    } else {
        $data_inputed = false;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Analisis Jabatan - DPRD Sultra</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="img/logosultra.png" type="image/x-icon"/>

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
        /* Main Header Styling */
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

        @media (max-width: 768px) {
            .main-header {
                left: 0;
            }
            
            .navbar-brand span {
                font-size: 1rem;
            }
        }

        /* Adjust main panel to account for fixed header */
        .main-panel {
            padding-top: 100px !important;
            margin-left: 260px;
            background: #f4f6f9;
            min-height: 100vh;
            padding: 2rem;
            width: calc(100% - 260px);
        }

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

        .jabatan-card {
            background: white;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .jabatan-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .jabatan-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a237e;
            margin: 0;
        }

        .jabatan-subtitle {
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .jabatan-body {
            padding: 1.5rem;
        }

        .info-section {
            margin-bottom: 1.5rem;
        }

        .info-title {
            font-weight: 600;
            color: #1a237e;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .info-content {
            color: #64748b;
            font-size: 0.95rem;
        }

        .sub-jabatan {
            margin-left: 2rem;
            padding-left: 1.5rem;
            border-left: 2px solid #e2e8f0;
        }

        .badge-level {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-level-1 {
            background: rgba(26, 35, 126, 0.1);
            color: #1a237e;
        }

        .badge-level-2 {
            background: rgba(0, 150, 136, 0.1);
            color: #009688;
        }

        .badge-level-3 {
            background: rgba(103, 58, 183, 0.1);
            color: #673ab7;
        }

        .btn-detail {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            background: rgba(26, 35, 126, 0.1);
            color: #1a237e;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            background: rgba(26, 35, 126, 0.2);
            transform: translateY(-2px);
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .detail-title {
            font-weight: 600;
            color: #1a237e;
            margin-bottom: 0.5rem;
        }

        .detail-content {
            color: #64748b;
        }

        @media (max-width: 768px) {
            .main-panel {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
                padding-top: 80px !important;
            }

            .sub-jabatan {
                margin-left: 1rem;
                padding-left: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include('sidebar.php'); ?>
        
        <div class="main-panel">
            <!-- Page Header -->
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
            <div class="container mt-4">
                <div class="page-header">
                    <h4 class="page-title">Daftar Hasil Analisis Jabatan</h4>
                    <p class="page-subtitle">Data hasil analisis jabatan yang telah diinputkan</p>
                </div>

                <?php if ($data_inputed): ?>
                    <!-- Jika data sudah diinput -->
                    <?php if (!$is_admin): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-2"></i> Data Anda sudah berhasil diinputkan!
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i> Menampilkan semua data analisis jabatan
                        </div>
                    <?php endif; ?>

                    <!-- Menampilkan Data Input -->
                    <div class="row">
                        <?php
                        // Tampilkan data yang telah diinput
                        if ($is_admin) {
                            // Untuk admin, tampilkan semua data
                            $query_data = "SELECT * FROM tb_input_jabatan";
                        } else {
                            // Untuk user biasa, tampilkan data miliknya saja
                            $query_data = "SELECT * FROM tb_input_jabatan WHERE id_user = '$id_user'";
                        }
                        
                        $result_data = mysqli_query($conn, $query_data);
                        
                        // Check if query was successful
                        if ($result_data) {
                            while ($row = mysqli_fetch_assoc($result_data)) {
                                // Ambil nama user dan NIP dari tabel users
                                $user_id = $row['id_user'];
                                $query_user = "SELECT nama, nip FROM tb_user WHERE id_user = '$user_id'";
                                $result_user = mysqli_query($conn, $query_user);
                                
                                // Check if this query was successful
                                if ($result_user) {
                                    $user_data = mysqli_fetch_assoc($result_user);
                                    $username = isset($user_data['nama']) ? $user_data['nama'] : 'Unknown User';
                                    $nip = isset($user_data['nip']) ? $user_data['nip'] : 'N/A';
                                } else {
                                    // Handle error in user query
                                    $username = 'Unknown User';
                                    $nip = 'N/A';
                                    // Uncomment for debugging:
                                    // echo "Error in user query: " . mysqli_error($conn);
                                }
                                
                                // Now you can use both $username and $nip variables
                                
                                echo '
                                <div class="col-md-6 mb-4">
                                    <div class="jabatan-card">
                                        <div class="jabatan-header">
                                            <div>
                                                <h5 class="jabatan-title">' . htmlspecialchars($row['nama_jabatan']) . '</h5>
                                                <p class="jabatan-subtitle">' . htmlspecialchars($row['unit_kerja']) . '</p>';
                                
                                // Tampilkan info penginput hanya jika admin
                                if ($is_admin) {
                                    echo '<p class="jabatan-subtitle"><small>Diinput oleh: ' . htmlspecialchars($username) . ' (NIP: ' . htmlspecialchars($nip) . ')</small></p>';
                                }
                                
                                echo '</div>
                                           
                                        </div>
                                        <div class="jabatan-body">
                                            <div class="info-section">
                                                <div class="info-title">Ikhtisar Jabatan</div>
                                                <div class="info-content">' . htmlspecialchars(substr($row['ikhtisar_jabatan'], 0, 150)) . '...</div>
                                            </div>
                                            <div class="text-end">
                                                <a href="detailjabatan.php?id=' . htmlspecialchars($row['id_user']) . '" class="btn btn-primary btn-detail">
                                                    <i class="fas fa-eye mr-1"></i> Lihat Hasil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                        } else {
                            // Handle query error gracefully
                            echo '<div class="col-12"><div class="alert alert-danger">
                                   <i class="fas fa-exclamation-circle mr-2"></i> Error: ' . mysqli_error($conn) . '
                                   </div></div>';
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <!-- Jika data belum diinput -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Anda belum menginput data. Silakan lakukan input data terlebih dahulu.
                        <div class="mt-3">
                            <a href="input_data.php" class="btn btn-sm btn-warning">Input Data</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>     
        
    <!-- Modal Detail Jabatan -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Analisis Jabatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detailContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
    
    <script>
    // Function to load detail content
    function loadDetail(id) {
        $.ajax({
            url: 'get_detail_jabatan.php',
            type: 'POST',
            data: {id: id},
            success: function(response) {
                $('#detailContent').html(response);
                $('#detailModal').modal('show');
            }
        });
    }
    </script>
</body>
</html>