<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include koneksi database
include('koneksi.php');

// Inisialisasi variabel pesan
$success_message = "";
$error_message = "";

// Proses form jika ada POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    
    // Cek apakah NIP sudah ada
    $check_nip = $conn->prepare("SELECT id FROM users WHERE nip = ?");
    $check_nip->bind_param("s", $nip);
    $check_nip->execute();
    $result = $check_nip->get_result();
    
    if ($result->num_rows > 0) {
        $error_message = "NIP sudah terdaftar!";
    } else {
        // Insert data pengguna baru
        $sql = "INSERT INTO users (nama, nip, jabatan, peran, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nama, $nip, $jabatan, $peran, $password);
        
        if ($stmt->execute()) {
            $success_message = "Pengguna berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan pengguna: " . $conn->error;
        }
        $stmt->close();
    }
    $check_nip->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Tambah Pengguna - DPRD Sultra</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="img/logosultra.png" type="image/x-icon"/>

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/atlantis.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/sidebar.css" />

    <style>
        .main-panel {
            background: #f4f6f9;
            min-height: 100vh;
            padding: 2rem;
            margin-left: 260px;
            width: calc(100% - 260px);
            padding-top: 100px !important;
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

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .form-label {
            color: #1a237e;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(26, 35, 126, 0.2);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #1a237e;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .main-panel {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include('sidebar.php'); ?>
        
        <div class="main-panel">
            <div class="page-header">
                <h1 class="page-title">Tambah Pengguna</h1>
                <p class="page-subtitle">Tambah pengguna baru ke sistem</p>
            </div>

            <div class="form-card">
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control" name="nip" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Peran</label>
                        <select class="form-control" name="peran" required>
                            <option value="">-- Pilih Peran --</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="manajemenpengguna.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="mb-3">Berhasil Tambah</h3>
                    <p class="mb-4">Data pengguna berhasil ditambahkan ke sistem</p>
                    <button type="button" class="btn btn-primary px-4" onclick="window.location.href='manajemenpengguna.php'">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>

    <?php if (!empty($success_message)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
    <?php endif; ?>
</body>
</html> 