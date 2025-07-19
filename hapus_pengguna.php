<?php
include 'session_check.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Termasuk file koneksi ke database
include('koneksi.php');

// Cek apakah ada ID pengguna yang diberikan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manajemenpengguna.php");
    exit();
}

$id_user = $_GET['id'];

// Cek apakah user mencoba menghapus dirinya sendiri
if ($_SESSION['id_user'] == $id_user) {
    header("Location: manajemenpengguna.php?error=self_delete");
    exit();
}

// Ambil data pengguna terlebih dahulu (sebelum konfirmasi)
$sql = "SELECT * FROM tb_user WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Jika pengguna tidak ditemukan
    header("Location: manajemenpengguna.php?error=user_not_found");
    exit();
}

$user = $result->fetch_assoc();

// Konfirmasi penghapusan
if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
    // Mulai transaksi
    $conn->begin_transaction();
    
    try {
        // Daftar tabel yang akan dihapus datanya berdasarkan id_user
        $tables_to_delete = [
            'tb_bahan_kerja',
            'tb_fungsi_pekerjaan',
            'tb_hasil_kerja',
            'tb_input_jabatan',
            'tb_kelas_jabatan',
            'tb_kolerasi_jabatan',
            'tb_kondisi_fisik',
            'tb_kondisi_lingkungan_kerja',
            'tb_perangkat_kerja',
            'tb_prestasi',
            'tb_risiko_bahaya',
            'tb_syarat_jabatan',
            'tb_syarat_jabatan_lain',
            'tb_tanggung_jawab',
            'tb_tugas_pokok',
            'tb_wewenang'
        ];
        
        $deleted_records = [];
        
        // Hapus data dari semua tabel terkait terlebih dahulu
        foreach ($tables_to_delete as $table) {
            $delete_related_sql = "DELETE FROM $table WHERE id_user = ?";
            $delete_related_stmt = $conn->prepare($delete_related_sql);
            $delete_related_stmt->bind_param("i", $id_user);
            
            if ($delete_related_stmt->execute()) {
                $affected_rows = $delete_related_stmt->affected_rows;
                if ($affected_rows > 0) {
                    $deleted_records[] = "$table ($affected_rows record)";
                }
            } else {
                throw new Exception("Gagal menghapus data dari $table: " . $conn->error);
            }
        }
        
        // Setelah semua data terkait dihapus, hapus pengguna
        $delete_sql = "DELETE FROM tb_user WHERE id_user = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id_user);
        
        if (!$delete_stmt->execute()) {
            throw new Exception("Gagal menghapus pengguna: " . $conn->error);
        }
        
        // Commit transaksi
        $conn->commit();
        
        // Berhasil hapus - redirect dengan informasi data yang dihapus
        $deleted_info = !empty($deleted_records) ? "&deleted=" . urlencode(implode(', ', $deleted_records)) : "";
        header("Location: manajemenpengguna.php?status=deleted" . $deleted_info);
        exit();
        
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi error
        $conn->rollback();
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Hapus Pengguna - Sekretariat DPRD Provinsi Sulawesi Tenggara</title>
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

        /* Delete Card Styling */
        .delete-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            max-width: 500px;
            margin: 0 auto;
        }

        .delete-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .delete-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #b91c1c;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .delete-card h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: #b91c1c;
            border-radius: 2px;
        }

        .user-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid #e2e8f0;
        }

        .user-info p {
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .user-info strong {
            font-weight: 600;
            color: #1a237e;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-danger {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            border: none;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.2);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
        }

        .btn-secondary {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            color: #64748b;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #1a237e;
        }

        .warning-text {
            color: #b91c1c;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .user-role {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .user-role.admin {
            background: #e0f2fe;
            color: #0369a1;
        }

        .user-role.staff {
            background: #dcfce7;
            color: #15803d;
        }

        /* Alert Styling */
        .alert {
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: none;
            font-weight: 500;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .alert-warning {
            background-color: #fef3c7;
            color: #d97706;
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
    </style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/sidebar.css" />
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

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="delete-card">
                        <h2>Konfirmasi Penghapusan</h2>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($user) && $user): ?>
                            <div class="user-info">
                                <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['nama']); ?></p>
                                <p><strong>NIP:</strong> <?php echo htmlspecialchars($user['nip']); ?></p>
                                <p><strong>Jabatan:</strong> <?php echo htmlspecialchars($user['jabatan']); ?></p>
                                <p><strong>Peran:</strong> 
                                    <span class="user-role <?php echo ($user['peran'] == 'admin') ? 'admin' : 'staff'; ?>">
                                        <?php echo ($user['peran'] == 'admin') ? 'Admin' : 'Staff'; ?>
                                    </span>
                                </p>
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Perhatian:</strong> Menghapus pengguna ini akan menghapus semua data terkait pengguna dari semua tabel sistem (bahan kerja, fungsi pekerjaan, hasil kerja, jabatan, prestasi, dll).
                            </div>
                            
                            <p class="warning-text">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Anda yakin ingin menghapus pengguna ini beserta SEMUA data terkaitnya? Tindakan ini tidak dapat dibatalkan.
                            </p>
                            
                            <form method="POST" action="">
                                <input type="hidden" name="confirm_delete" value="yes">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt me-2"></i> Hapus Pengguna
                                    </button>
                                    <a href="manajemenpengguna.php" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i> Batal
                                    </a>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Data pengguna tidak ditemukan.
                            </div>
                            <a href="manajemenpengguna.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
      </div>
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