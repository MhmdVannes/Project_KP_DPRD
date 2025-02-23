<?php
session_start(); // Mulai sesi

// Termasuk file koneksi ke database
include('koneksi.php');

// Inisialisasi variabel error message
$error_message = "";

// Menangani saat form login dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan input ada di $_POST sebelum mengaksesnya
    if (isset($_POST['nip']) && isset($_POST['password'])) {
        $nip = mysqli_real_escape_string($conn, $_POST['nip']); // NIP yang dimasukkan
        $enteredPassword = mysqli_real_escape_string($conn, $_POST['password']); // Password yang dimasukkan

        // Query untuk mengambil data pengguna berdasarkan NIP
        $sql = "SELECT * FROM users WHERE nip = '$nip'";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Cek apakah password menggunakan MD5 atau password_hash
            if (md5($enteredPassword) === $user['password'] || 
                (strlen($user['password']) > 32 && password_verify($enteredPassword, $user['password']))) {
                // Jika password cocok, simpan data pengguna di sesi
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['nip'] = $user['nip'];
                $_SESSION['peran'] = $user['peran'];

                // Mengarahkan pengguna ke halaman yang sesuai berdasarkan perannya
                if ($user['peran'] == 'admin') {
                    header("Location: index.php");
                } else {
                    header("Location: menuuser.php");
                }
                exit();
            } else {
                $error_message = "NIP atau password salah!";
            }
        } else {
            $error_message = "NIP atau password salah!";
        }
    } else {
        $error_message = "Harap isi semua form!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sekretariat Dewan Perwakilan Rakyat Daerah Provinsi Sulawesi Tenggara</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    <link rel="icon" href="img/logosultra.png" type="image/x-icon"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .page-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
            padding: 0 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px 30px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .logo {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
            border: 5px solid rgba(255, 255, 255, 0.9);
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo img {
            max-width: 60px;
            max-height: 60px;
        }

        .header-text {
            color: white;
        }

        .header-text h3 {
            font-size: 2.2rem;
            margin-bottom: 5px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-text p {
            font-size: 1.1rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .wrapper {
            width: 420px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 180px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .wrapper:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.3);
        }

        h2 {
            color: #1a365d;
            margin-bottom: 30px;
            font-size: 2.2rem;
            font-weight: 600;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, #667eea, #764ba2);
            border-radius: 2px;
        }

        .input-field {
            position: relative;
            margin: 25px 0;
        }

        .input-field i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: #718096;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .input-field input {
            width: 100%;
            height: 55px;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 0 45px;
            font-size: 16px;
            color: #2d3748;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .input-field label {
            position: absolute;
            top: 50%;
            left: 45px;
            transform: translateY(-50%);
            color: #718096;
            font-size: 15px;
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 5px;
        }

        .input-field input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }

        .input-field input:focus ~ label,
        .input-field input:not(:placeholder-shown) ~ label {
            top: -10px;
            left: 15px;
            font-size: 13px;
            color: #667eea;
            font-weight: 500;
        }

        .input-field input:focus ~ i {
            color: #667eea;
        }

        button {
            width: 100%;
            height: 55px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .error-message {
            color: #e53e3e;
            background-color: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .error-message::before {
            content: '\f071';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #e53e3e;
        }

        @media (max-width: 768px) {
            .header {
                position: relative;
                top: 0;
                margin-bottom: 30px;
            }

            .wrapper {
                width: 90%;
                max-width: 420px;
                margin-top: 0;
                padding: 35px 25px;
            }

            .logo-container {
                padding: 10px 20px;
            }

            .logo {
                width: 80px;
                height: 80px;
            }

            .logo img {
                max-width: 45px;
                max-height: 45px;
            }

            .header-text h3 {
                font-size: 1.8rem;
            }

            .header-text p {
                font-size: 1rem;
            }

            h2 {
                font-size: 2rem;
            }

            .input-field input {
                height: 50px;
            }

            button {
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="header">
            <div class="logo-container">
                <div class="logo">
                    <img src="img/logosultra.png" alt="Logo Sulawesi Tenggara">
                </div>
                <div class="header-text">
                    <h3>Sekretariat Daerah</h3>
                    <p>Provinsi Sulawesi Tenggara</p>
                </div>
            </div>
        </div>
        
        <div class="wrapper">
            <form action="login.php" method="POST">
                <h2>Login</h2>

                <!-- Menampilkan pesan error jika ada -->
                <?php if (!empty($error_message)) { ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php } ?>

                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" id="nip" name="nip" required placeholder=" ">
                    <label>Enter Username (NIP)</label>
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required placeholder=" ">
                    <label>Enter Password</label>
                </div>
                <button type="submit">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>