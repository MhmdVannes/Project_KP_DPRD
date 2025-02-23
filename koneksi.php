<?php
// Konfigurasi untuk koneksi ke database
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "dprdproject";

// Matikan pelaporan error strict untuk mysqli
mysqli_report(MYSQLI_REPORT_OFF);

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set karakter encoding
mysqli_set_charset($conn, "utf8");

// Jika berhasil, maka akan muncul pesan ini
// echo "Koneksi berhasil";

// Menutup koneksi setelah digunakan (boleh digunakan di bagian lain dari aplikasi)
?>
