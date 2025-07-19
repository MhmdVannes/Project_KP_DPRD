<?php
include 'session_check.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Termasuk file koneksi ke database
include('koneksi.php');

// Inisialisasi variabel untuk pesan
$msg = '';
$msgClass = '';

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil dan membersihkan input
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Validasi input
    if (empty($nama) || empty($nip) || empty($jabatan) || empty($password)) {
        $msg = 'Semua field harus diisi';
        $msgClass = 'alert-danger';
    } else {
        // Cek apakah nama sudah ada
        $checkNama = $conn->query("SELECT * FROM tb_user WHERE nama = '$nama'");
        if ($checkNama->num_rows > 0) {
            $msg = 'Nama sudah digunakan';
            $msgClass = 'alert-danger';
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Menyimpan data ke database
            $sql = "INSERT INTO tb_user (nama, nip, jabatan, peran, password) 
                    VALUES ('$nama', '$nip', '$jabatan', '$peran', '$hashedPassword')";
            
            if ($conn->query($sql)) {
                $msg = 'Pengguna berhasil ditambahkan';
                $msgClass = 'alert-success';
                // Redirect setelah 2 detik
                header("refresh:2;url=manajemenpengguna.php");
            } else {
                $msg = 'Error: ' . $conn->error;
                $msgClass = 'alert-danger';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Tambah Pengguna - Sekretariat DPRD Provinsi Sulawesi Tenggara</title>
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
        google: { families: ["Public Sans:300,400,500,600,700", "Poppins:400,500,600,700"] },
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
        :root {
            --primary-color: #1a3c6e;
            --primary-light: #2c5b9e;
            --accent-color: #f9b000;
            --text-dark: #263238;
            --text-light: #546e7a;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --shadow-sm: 0 2px 10px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 15px rgba(0,0,0,0.08);
            --radius-sm: 8px;
            --radius-md: 16px;
        }

        body {
            font-family: 'Public Sans', 'Poppins', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
        }

        /* Main Panel Styling */
        .main-panel {
            margin-left: 260px;
            background: var(--bg-light);
            min-height: 100vh;
            padding: 2rem;
            transition: all 0.3s ease;
            width: calc(100% - 260px);
            position: relative;
            padding-top: 110px !important;
        }

        /* Header Styling */
        .main-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 260px;
            z-index: 999;
            background: var(--bg-white);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .navbar-header {
            padding: 0.75rem 1.5rem;
            background: var(--bg-white) !important;
            box-shadow: var(--shadow-sm);
        }

        .logo-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-header .logo img {
            height: 45px;
            width: auto;
        }

        .navbar-brand img {
            transition: all 0.3s ease;
            height: 55px !important;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .navbar-brand span {
            font-size: 1.1rem;
            margin-left: 1rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1.3;
            transition: all 0.3s ease;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 1.75rem;
            border-left: 5px solid var(--accent-color);
            padding-left: 15px;
        }

        /* Form Styling */
        .form-section {
            background: var(--bg-white);
            border-radius: var(--radius-md);
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            border-top: 5px solid var(--primary-color);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.6rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--bg-light);
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 60, 110, 0.1);
            background: var(--bg-white);
        }

        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--bg-light);
            color: var(--text-dark);
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 60, 110, 0.1);
            background: var(--bg-white);
        }

        /* Button Styling */
        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-light);
            border-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(26, 60, 110, 0.2);
        }

        .btn-secondary {
            background: #e2e8f0;
            border-color: #e2e8f0;
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background: #cbd5e1;
            border-color: #cbd5e1;
            color: var(--text-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Alert Styling */
        .alert {
            border-radius: var(--radius-sm);
            padding: 1rem 1.5rem;
            font-weight: 500;
            margin-bottom: 2rem;
            border: none;
        }

        .alert-success {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #166534;
        }

        .alert-danger {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }

        /* Input Group Styling */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            z-index: 10;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            transition: all 0.3s ease;
        }

        .input-with-icon {
            padding-left: 40px;
        }

        .input-with-icon:focus + .input-icon {
            color: var(--primary-color);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background-color: var(--primary-color);
            border-radius: var(--radius-sm) var(--radius-sm) 0 0 !important;
            color: white;
            font-weight: 600;
            border: none;
        }

        /* Form Group Hover Effect */
        .form-group {
            transition: all 0.3s ease;
        }

        .form-group:hover .form-label {
            color: var(--primary-light);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .main-panel {
                margin-left: 0;
                width: 100%;
                padding-top: 100px !important;
            }

            .main-header {
                left: 0;
            }
            
            .navbar-brand span {
                font-size: 0.9rem;
            }
            
            .page-title {
                font-size: 1.7rem;
            }
            
            .form-section {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand span {
                font-size: 0.8rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/sidebar.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  </head>
  <body>
    <div class="wrapper">
      <?php include('sidebar.php'); ?>

      <div class="main-panel">
        <!-- Main Header -->
        <div class="main-header">
            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg border-bottom">
                <div class="container-fluid px-3">
                    <!-- Logo dan Teks -->
                    <a class="navbar-brand d-flex align-items-center" href="index.php">
                        <img src="img/logosultra.png" alt="Logo Sultra" class="d-inline-block me-3">
                        <span>SEKRETARIAT DPRD PROVINSI<br>SULAWESI TENGGARA</span>
                    </a>
                    <!-- Navbar Item -->
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="profile.php">
                                    <i class="fas fa-user"></i> Profil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Page Content -->
        <div class="container-fluid">
            <h2 class="page-title">
                <i class="fas fa-user-plus me-2"></i> Tambah Pengguna Baru
            </h2>
            
            <!-- Alert Message -->
            <?php if($msg != ''): ?>
                <div class="alert <?php echo $msgClass; ?>">
                    <i class="fas <?php echo ($msgClass == 'alert-success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-section">
                <div class="mb-4">
                    <h4 class="text-primary fw-bold mb-3">
                        <i class="fas fa-id-card me-2"></i>
                        Informasi Pengguna
                    </h4>
                    <p class="text-muted">Lengkapi data pengguna baru dengan benar</p>
                </div>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nama" class="form-label">
                                    <i class="fas fa-user me-1"></i> Nama Lengkap
                                </label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap">
                                <small class="text-muted">Nama ini akan digunakan untuk login</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nip" class="form-label">
                                    <i class="fas fa-id-badge me-1"></i> NIP
                                </label>
                                <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="jabatan" class="form-label">
                                    <i class="fas fa-briefcase me-1"></i> Jabatan
                                </label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukkan jabatan">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="peran" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i> Peran
                                </label>
                                <select class="form-select" id="peran" name="peran">
                                    <option value="" selected disabled>Pilih peran pengguna</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> Konfirmasi Password
                                </label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Pengguna
                        </button>
                        <a href="manajemenpengguna.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                    <div id="customAlert" class="alert alert-danger" style="display: none;">
    <i class="fas fa-exclamation-circle me-2"></i>
    <span id="alertMessage"></span>
</div>
                </form>
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
    <script>
        // Form validation
        $(document).ready(function(){
            $('form').on('submit', function(e){
                let valid = true;
                
                // Check required fields
                $(this).find('input, select').each(function(){
                    if($(this).attr('id') !== 'confirm_password' && ($(this).val() === '' || $(this).val() === null)){
                        $(this).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    }
                });
                
                // Check password confirmation
                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();
                
                if(password !== confirmPassword) {
                    $('#confirm_password').addClass('is-invalid');
                    alert('Password dan konfirmasi password tidak cocok');
                    valid = false;
                }
                
                // Prevent form submission if validation fails
                if(!valid){
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang diperlukan');
                }
            });
            
            // Remove validation classes on input
            $('input, select').on('focus', function(){
                $(this).removeClass('is-invalid');
            });
        });
    </script>
  </body>
</html>