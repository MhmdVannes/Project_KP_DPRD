<?php
include 'session_check.php';
include 'koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['id_user']) && isset($_SESSION['peran'])) {
    if ($_SESSION['peran'] == 'admin') {
        header("Location: index.php");
    } else {
        header("Location: menuuser.php");
    }
    exit();
}

// Inisialisasi variabel
$error = '';

// Proses login ketika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan nip dan password dikirim
    if (!empty($_POST['nip']) && !empty($_POST['password'])) {
        $nip = mysqli_real_escape_string($conn, $_POST['nip']);
        $password = $_POST['password'];

        // Query untuk mengambil data user berdasarkan NIP
        $query = "SELECT id_user, nama, peran, password FROM tb_user WHERE nip = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $nip);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Cek apakah user ditemukan
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Jika password di database masih dalam teks biasa (belum di-hash), gunakan langsung perbandingan sederhana
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                // Set session untuk user yang berhasil login
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['peran'] = $user['peran'];

                // Redirect berdasarkan peran
                if ($user['peran'] === 'admin') {
                    header("Location: index.php");
                } else {
                    header("Location: menuuser.php");
                }
                exit();
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "NIP tidak ditemukan!";
        }
    } else {
        $error = "Harap masukkan NIP dan password!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sekretariat DPRD Provinsi Sulawesi Tenggara</title>
    <link rel="icon" href="img/logosultra.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 900px;
            max-width: 90%;
            transition: all 0.3s ease;
        }
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .login-row {
            display: flex;
            min-height: 500px;
        }
        .login-image {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('img/dprd.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            flex: 1;
            color: white;
            text-align: center;
        }
        .login-form {
            padding: 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-logo {
            width: 120px;
            margin-bottom: 20px;
        }
        .login-title {
            font-weight: 700;
            margin-bottom: 10px;
            color: #1a237e;
        }
        .login-subtitle {
            margin-bottom: 40px;
            color: #64748b;
        }
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 20px;
            transition: all 0.3s;
            width: 100%; /* Ensure full width */
        }
        .form-control:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
        }
        .login-btn {
            background: #1a237e;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%; /* Ensure full width */
            margin: 0 auto; /* Center the button */
            display: block; /* Make it block to respect width */
        }
        .login-btn:hover {
            background: #283593;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(26, 35, 126, 0.2);
        }
        .error-message {
            color: #dc2626;
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
        }
        /* Fix for input groups */
        .input-group {
            width: 100%;
            display: flex;
            align-items: stretch;
        }
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 12px 0 0 12px;
            border: 2px solid #e2e8f0;
            border-right: none;
        }
        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            margin-bottom: 0;
        }
        .form-label {
            margin-bottom: 0.5rem;
            display: block;
        }
        .mb-3, .mb-4 {
            margin-bottom: 1.5rem !important;
            width: 100%;
        }
        form {
            width: 100%;
        }
        @media (max-width: 767px) {
            .login-image {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
        <div class="login-row">
            <div class="login-image">
                <img src="img/logosultra.png" alt="Logo" class="login-logo">
                <h3>Sekretariat DPRD Provinsi Sulawesi Tenggara</h3>
                <p>Sistem Analisis Jabatan (Anjab) </p>
            </div>
            <div class="login-form">
                <div class="text-center mb-4">
                    <img src="img/logosultra.png" alt="Logo" width="80">
                    <h4 class="login-title mt-3">Selamat Datang</h4>
                    <p class="login-subtitle">Silakan login untuk melanjutkan</p>
                </div>
                
                <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Nip</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="nip" name="nip" placeholder="Masukkan nip" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="Masukkan password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn login-btn text-white">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
