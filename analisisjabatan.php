<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include koneksi database
include('koneksi.php');

// Fungsi helper untuk menangani nilai null
function displayValue($value) {
    return htmlspecialchars($value ?? '-');
}

// Fungsi untuk mendapatkan data jabatan dan sub-jabatannya
function getJabatanHierarki($conn, $parent_id = null, $level = 0, $max_level = 3) {
    // Batasi level rekursi
    if ($level >= $max_level) {
        return array();
    }
    
    $result = array();
    
    try {
        // Gunakan prepared statement untuk menghindari SQL injection
        if ($parent_id === null) {
            $sql = "SELECT j.id, j.parent_id, j.nama_jabatan, j.level, j.unit_kerja, 
                    d.pangkat, d.pendidikan, d.diklat_jenjang, d.diklat_teknis, 
                    d.diklat_fungsional, d.pengalaman_kerja, d.hasil_kerja, 
                    d.temperamen_kerja, d.minat_kerja, d.fungsi_data, 
                    d.fungsi_orang, d.fungsi_benda, d.prestasi,
                    u.nama as input_by, u.nip as input_nip, u.jabatan as input_jabatan
                    FROM jabatan_hierarki j 
                    LEFT JOIN detail_analisis_jabatan d ON j.id = d.jabatan_id 
                    LEFT JOIN users u ON d.input_by = u.id
                    WHERE j.parent_id IS NULL 
                    ORDER BY j.nama_jabatan";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
        } else {
            $sql = "SELECT j.id, j.parent_id, j.nama_jabatan, j.level, j.unit_kerja, 
                    d.pangkat, d.pendidikan, d.diklat_jenjang, d.diklat_teknis, 
                    d.diklat_fungsional, d.pengalaman_kerja, d.hasil_kerja, 
                    d.temperamen_kerja, d.minat_kerja, d.fungsi_data, 
                    d.fungsi_orang, d.fungsi_benda, d.prestasi,
                    u.nama as input_by, u.nip as input_nip, u.jabatan as input_jabatan
                    FROM jabatan_hierarki j 
                    LEFT JOIN detail_analisis_jabatan d ON j.id = d.jabatan_id 
                    LEFT JOIN users u ON d.input_by = u.id
                    WHERE j.parent_id = ? 
                    ORDER BY j.nama_jabatan";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("i", $parent_id);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $query_result = $stmt->get_result();
        
        while ($row = $query_result->fetch_assoc()) {
            $row['level'] = $level;
            // Rekursi untuk mendapatkan children dengan level yang lebih dalam
            $row['children'] = getJabatanHierarki($conn, $row['id'], $level + 1, $max_level);
            $result[] = $row;
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        // Log error atau tampilkan pesan error yang sesuai
        error_log("Error in getJabatanHierarki: " . $e->getMessage());
        return array();
    }
    
    return $result;
}

// Ambil seluruh data jabatan dalam struktur hierarki dengan batas maksimal 3 level
$jabatan_hierarki = getJabatanHierarki($conn, null, 0, 3);

// Cek apakah ada data
$check_data = $conn->query("SELECT COUNT(*) as total FROM jabatan_hierarki");
$data_exists = $check_data->fetch_assoc()['total'] > 0;
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

            <div class="page-header">
                <h1 class="page-title">Analisis Jabatan</h1>
                <p class="page-subtitle">Daftar lengkap analisis jabatan DPRD Sulawesi Tenggara</p>
            </div>

            <!-- Alert jika tidak ada data -->
            <?php if (!$data_exists): ?>
            <div class="alert alert-info" role="alert" style="
                background-color: #e8f4fd;
                color: #1a237e;
                border: none;
                border-radius: 15px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                display: flex;
                align-items: center;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <i class="fas fa-info-circle me-3" style="font-size: 1.5rem;"></i>
                <div>
                    <h5 style="margin-bottom: 0.5rem; font-weight: 600;">Belum Ada Data</h5>
                    <p style="margin: 0;">Belum ada data analisis jabatan yang tersedia. Silakan input data terlebih dahulu melalui menu Input Data.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Jabatan List -->
            <?php if ($data_exists): ?>
            <div class="jabatan-list">
                <?php
                function renderJabatan($jabatan) {
                    $level_badge = "badge-level badge-level-" . $jabatan['level'];
                    $level_text = "";
                    switch($jabatan['level']) {
                        case 1:
                            $level_text = "Kepala Bagian";
                            break;
                        case 2:
                            $level_text = "Kepala Sub Bagian";
                            break;
                        case 3:
                            $level_text = "Staff";
                            break;
                    }
                    ?>
                    <div class="jabatan-card">
                        <div class="jabatan-header">
                            <div>
                                <h3 class="jabatan-title"><?php echo displayValue($jabatan['nama_jabatan']); ?></h3>
                                <div class="jabatan-subtitle">
                                    <span class="<?php echo $level_badge; ?>"><?php echo $level_text; ?></span>
                                    <span class="ms-2"><?php echo displayValue($jabatan['unit_kerja']); ?></span>
                                </div>
                                <div class="input-info mt-2" style="font-size: 0.85rem; color: #64748b;">
                                    <i class="fas fa-user-edit me-1"></i> Diinput oleh: <?php echo displayValue($jabatan['input_by']); ?> (<?php echo displayValue($jabatan['input_jabatan']); ?>)
                                </div>
                            </div>
                            <button class="btn-detail" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $jabatan['id']; ?>">
                                <i class="fas fa-info-circle me-1"></i>Detail
                            </button>
                        </div>
                        <div class="jabatan-body">
                            <div class="info-section">
                                <div class="info-title">Syarat Jabatan</div>
                                <div class="info-content">
                                    <div>Pangkat: <?php echo displayValue($jabatan['pangkat']); ?></div>
                                    <div>Pendidikan: <?php echo displayValue($jabatan['pendidikan']); ?></div>
                                </div>
                            </div>
                            <div class="info-section">
                                <div class="info-title">Hasil Kerja</div>
                                <div class="info-content"><?php echo nl2br(displayValue($jabatan['hasil_kerja'])); ?></div>
                            </div>
                        </div>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal<?php echo $jabatan['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Jabatan: <?php echo displayValue($jabatan['nama_jabatan']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="detail-section">
                                            <div class="detail-title">Informasi Umum</div>
                                            <div class="detail-content">
                                                <div>Unit Kerja: <?php echo displayValue($jabatan['unit_kerja']); ?></div>
                                                <div>Level: <?php echo $level_text; ?></div>
                                            </div>
                                        </div>
                                        <div class="detail-section">
                                            <div class="detail-title">Informasi Input Data</div>
                                            <div class="detail-content">
                                                <div>Nama: <?php echo displayValue($jabatan['input_by']); ?></div>
                                                <div>NIP: <?php echo displayValue($jabatan['input_nip']); ?></div>
                                                <div>Jabatan: <?php echo displayValue($jabatan['input_jabatan']); ?></div>
                                            </div>
                                        </div>
                                        <div class="detail-section">
                                            <div class="detail-title">Syarat Jabatan</div>
                                            <div class="detail-content">
                                                <div>Pangkat: <?php echo displayValue($jabatan['pangkat']); ?></div>
                                                <div>Pendidikan: <?php echo displayValue($jabatan['pendidikan']); ?></div>
                                                <div>Diklat Jenjang: <?php echo displayValue($jabatan['diklat_jenjang']); ?></div>
                                                <div>Diklat Teknis: <?php echo displayValue($jabatan['diklat_teknis']); ?></div>
                                                <div>Diklat Fungsional: <?php echo displayValue($jabatan['diklat_fungsional']); ?></div>
                                                <div>Pengalaman Kerja: <?php echo nl2br(displayValue($jabatan['pengalaman_kerja'])); ?></div>
                                            </div>
                                        </div>
                                        <div class="detail-section">
                                            <div class="detail-title">Karakteristik Pribadi</div>
                                            <div class="detail-content">
                                                <div>Temperamen Kerja: <?php echo displayValue($jabatan['temperamen_kerja']); ?></div>
                                                <div>Minat Kerja: <?php echo displayValue($jabatan['minat_kerja']); ?></div>
                                            </div>
                                        </div>
                                        <div class="detail-section">
                                            <div class="detail-title">Fungsi Pekerjaan</div>
                                            <div class="detail-content">
                                                <div>Fungsi Data: <?php echo displayValue($jabatan['fungsi_data']); ?></div>
                                                <div>Fungsi Orang: <?php echo displayValue($jabatan['fungsi_orang']); ?></div>
                                                <div>Fungsi Benda: <?php echo displayValue($jabatan['fungsi_benda']); ?></div>
                                            </div>
                                        </div>
                                        <div class="detail-section">
                                            <div class="detail-title">Prestasi yang Diharapkan</div>
                                            <div class="detail-content">
                                                <?php echo nl2br(displayValue($jabatan['prestasi'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($jabatan['children'])): ?>
                            <div class="sub-jabatan">
                                <?php foreach ($jabatan['children'] as $child): ?>
                                    <?php renderJabatan($child); ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }

                foreach ($jabatan_hierarki as $jabatan) {
                    renderJabatan($jabatan);
                }
                ?>
            </div>
            <?php endif; ?>
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
