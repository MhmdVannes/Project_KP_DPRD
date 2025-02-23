<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include koneksi database
include('koneksi.php');

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: manajemenpengguna.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data pengguna
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: manajemenpengguna.php");
    exit();
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);
    
    $sql_update = "UPDATE users SET nama = ?, nip = ?, jabatan = ?, peran = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $nama, $nip, $jabatan, $peran, $id);
    
    if ($stmt_update->execute()) {
        header("Location: manajemenpengguna.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Edit Pengguna - DPRD Sultra</title>
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
                <h1 class="page-title">Edit Pengguna</h1>
                <p class="page-subtitle">Edit data pengguna sistem</p>
            </div>

            <div class="form-card">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control" name="nip" value="<?php echo htmlspecialchars($user['nip']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" value="<?php echo htmlspecialchars($user['jabatan']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Peran</label>
                        <select class="form-control" name="peran" required>
                            <option value="admin" <?php echo $user['peran'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="user" <?php echo $user['peran'] == 'user' ? 'selected' : ''; ?>>User</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="manajemenpengguna.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
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
</body>
</html> 