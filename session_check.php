<?php
session_start();

// Set timeout dalam detik (15 menit = 900 detik)
$timeout = 900;

// Jangan lakukan redirect jika halaman saat ini adalah login.php
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page == 'login.php') {
    // Jika ini adalah halaman login, tidak perlu cek session
    // Cukup perbarui waktu aktivitas jika session sudah ada
    if (isset($_SESSION['id_user'])) {
        $_SESSION['LAST_ACTIVITY'] = time();
    }
} else {
    // Jika user belum login, arahkan ke login.php
    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit();
    }

    // Cek apakah sesi sudah kedaluwarsa
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout) {
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=true"); // Tambahkan pesan timeout
        exit();
    }

    // Perbarui waktu aktivitas terakhir
    $_SESSION['LAST_ACTIVITY'] = time();
}
?>