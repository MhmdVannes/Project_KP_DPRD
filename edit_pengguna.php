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

// Ambil data pengguna berdasarkan ID
$sql = "SELECT * FROM tb_user WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Jika pengguna tidak ditemukan
    header("Location: manajemenpengguna.php");
    exit();
}

$user = $result->fetch_assoc();

// Proses form jika ada pengiriman data POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $nip = $_POST['nip'];
    $jabatan = $_POST['jabatan'];
    $peran = $_POST['peran'];
    $password = $_POST['password'];
    
    // Jika password diisi, update dengan password baru
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE tb_user SET nama = ?, nip = ?, jabatan = ?, peran = ?, password = ? WHERE id_user = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssssi", $nama, $nip, $jabatan, $peran, $hashed_password, $id_user);
    } else {
        // Jika password tidak diisi, update tanpa password
        $update_sql = "UPDATE tb_user SET nama = ?, nip = ?, jabatan = ?, peran = ? WHERE id_user = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $nama, $nip, $jabatan, $peran, $id_user);
    }
    
    if ($stmt->execute()) {
        // Berhasil update
        header("Location: manajemenpengguna.php?status=updated");
        exit();
    } else {
        // Gagal update
        $error = "Gagal mengupdate data pengguna: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Edit Pengguna - Sekretariat DPRD Provinsi Sulawesi Tenggara</title>
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

        /* Form Styling */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1a237e, #283593);
        }

        .form-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .form-card h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #1a237e, #283593);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #1a237e;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
            color: #1a237e;
        }

        .form-control:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
            background: white;
        }

        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.8rem 1rem;
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

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1a237e, #283593);
            border: none;
            box-shadow: 0 4px 15px rgba(26, 35, 126, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #283593, #1a237e);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 35, 126, 0.3);
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

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
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
                    <div class="form-card position-relative">
                        <h2>Edit Pengguna</h2>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $user['nama']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" value="<?php echo $user['nip']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo $user['jabatan']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="peran" class="form-label">Peran</label>
                                <select class="form-select" id="peran" name="peran" required>
                                    <option value="admin" <?php echo ($user['peran'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="user" <?php echo ($user['peran'] == 'user') ? 'selected' : ''; ?>>User</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="password" class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="manajemenpengguna.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
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