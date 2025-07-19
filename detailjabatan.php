<?php

include 'session_check.php';// Start session at the beginning

// Database connection
include 'koneksi.php'; // Use your connection file instead of direct connection

// Check login status
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Get user ID - check both direct session and GET parameter
$id_user = isset($_GET['id']) ? $_GET['id'] : $_SESSION['id_user'];

// Handle form submission for updating kode jabatan
if ($_POST && isset($_POST['update_kode_jabatan'])) {
    $kode_jabatan_baru = $_POST['kode_jabatan'];
    
    // Update kode jabatan in database
    $update_query = "UPDATE tb_input_jabatan SET kode_jabatan = ? WHERE id_user = ?";
    $update_stmt = $conn->prepare($update_query);
    
    if ($update_stmt) {
        $update_stmt->bind_param("si", $kode_jabatan_baru, $id_user);
        if ($update_stmt->execute()) {
            $success_message = "Kode jabatan berhasil diperbarui!";
        } else {
            $error_message = "Gagal memperbarui kode jabatan: " . $conn->error;
        }
        $update_stmt->close();
    } else {
        $error_message = "Error preparing update query: " . $conn->error;
    }
}

// Main information query
$query = "SELECT 
            tb_input_jabatan.nama_jabatan, 
            tb_input_jabatan.unit_kerja, 
            tb_input_jabatan.ikhtisar_jabatan, 
            tb_input_jabatan.unit_kerja1, 
            tb_input_jabatan.kode_jabatan,
            tb_syarat_jabatan.pangkat, 
            tb_syarat_jabatan.pendidikan, 
            tb_syarat_jabatan.jenjang, 
            tb_syarat_jabatan.teknis, 
            tb_syarat_jabatan.fungsional, 
            tb_syarat_jabatan.pengalaman_kerja
          FROM tb_input_jabatan
          JOIN tb_syarat_jabatan ON tb_input_jabatan.id_user = tb_syarat_jabatan.id_user
          WHERE tb_input_jabatan.id_user = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query Error: " . $conn->error);
}
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

// Tasks query
$query_tugas = "SELECT uraian_tugas, hasil_krj, jumlah_beban, waktu_penyelesaian, waktu_efektif, kebutuhan_pegawai 
                FROM tb_tugas_pokok
                WHERE id_user = ?";
$stmt_tugas = $conn->prepare($query_tugas);
$stmt_tugas->bind_param("i", $id_user);
$stmt_tugas->execute();
$result_tugas = $stmt_tugas->get_result();

$query_hasil_kerja = "SELECT hasil_kerja FROM tb_hasil_kerja WHERE id_user = ?";
$stmt_hasil_kerja = $conn->prepare($query_hasil_kerja);
$stmt_hasil_kerja->bind_param("i", $id_user);
$stmt_hasil_kerja->execute();
$result_hasil_kerja = $stmt_hasil_kerja->get_result();

$query_bahan_kerja = "SELECT bahan_kerja, penggunaan_tugas1 FROM tb_bahan_kerja WHERE id_user = ?";
$stmt_bahan_kerja = $conn->prepare($query_bahan_kerja);
$stmt_bahan_kerja->bind_param("i", $id_user); // Pastikan $id_user telah didefinisikan
$stmt_bahan_kerja->execute();
$result_bahan_kerja = $stmt_bahan_kerja->get_result();

$query_perangkat_kerja = "SELECT perangkat_kerja, penggunaan_tugas2 FROM tb_perangkat_kerja WHERE id_user = ?";
$stmt_perangkat_kerja = $conn->prepare($query_perangkat_kerja);
$stmt_perangkat_kerja->bind_param("i", $id_user);
$stmt_perangkat_kerja->execute();
$result_perangkat_kerja = $stmt_perangkat_kerja->get_result();

$query_tanggung_jawab = "SELECT tanggung_jawab FROM tb_tanggung_jawab WHERE id_user = ?";
$stmt_tanggung_jawab = $conn->prepare($query_tanggung_jawab);
$stmt_tanggung_jawab->bind_param("i", $id_user);
$stmt_tanggung_jawab->execute();
$result_tanggung_jawab = $stmt_tanggung_jawab->get_result();

$query_wewenang = "SELECT wewenang FROM tb_wewenang WHERE id_user = ?";
$stmt_wewenang = $conn->prepare($query_wewenang);
$stmt_wewenang->bind_param("i", $id_user);
$stmt_wewenang->execute();
$result_wewenang = $stmt_wewenang->get_result();

$query_korelasi = "SELECT jabatan, instansi, dalam_hal FROM tb_kolerasi_jabatan WHERE id_user = ?";
$stmt_korelasi = $conn->prepare($query_korelasi);
$stmt_korelasi->bind_param("i", $id_user);
$stmt_korelasi->execute();
$result_korelasi = $stmt_korelasi->get_result();

$query_lingkungan = "SELECT tempat_kerja, suhu, udara, keadaan_ruangan, letak, penerangan, suara, keadaan_tempat_kerja, getaran FROM tb_kondisi_lingkungan_kerja WHERE id_user = ?";
$stmt_lingkungan = $conn->prepare($query_lingkungan);
$stmt_lingkungan->bind_param("i", $id_user);
$stmt_lingkungan->execute();
$result_lingkungan = $stmt_lingkungan->get_result();

$query_risiko = "SELECT fisik_mental, penyebab FROM tb_risiko_bahaya WHERE id_user = ?";
$stmt_risiko = $conn->prepare($query_risiko);
$stmt_risiko->bind_param("i", $id_user);
$stmt_risiko->execute();
$result_risiko = $stmt_risiko->get_result();

$query_prestasi = "SELECT prestasi FROM tb_prestasi WHERE id_user = ?";
$stmt_prestasi = $conn->prepare($query_prestasi);
$stmt_prestasi->bind_param("i", $id_user);
$stmt_prestasi->execute();
$result_prestasi = $stmt_prestasi->get_result();

$query_kelas_jabatan = "SELECT kelas_jabatan FROM tb_kelas_jabatan WHERE id_user = ?";
$stmt_kelas_jabatan = $conn->prepare($query_kelas_jabatan);
$stmt_kelas_jabatan->bind_param("i", $id_user);
$stmt_kelas_jabatan->execute();
$result_kelas_jabatan = $stmt_kelas_jabatan->get_result();

$query_syarat_jabatan_lain = "SELECT keterampilan_kerja, bakat_kerja, temperamen_kerja, minat_kerja, aktivitas FROM tb_syarat_jabatan_lain WHERE id_user = ?";
$stmt_syarat_jabatan_lain = $conn->prepare($query_syarat_jabatan_lain);
$stmt_syarat_jabatan_lain->bind_param("i", $id_user);
$stmt_syarat_jabatan_lain->execute();
$result_syarat_jabatan_lain = $stmt_syarat_jabatan_lain->get_result();

$query_kondisi_fisik = "SELECT jenis_kelamin, umur_maksimal, tinggi_badan, berat_badan, postur_badan, penampilan, keadaan_fisik FROM tb_kondisi_fisik WHERE id_user = ?";
$stmt_kondisi_fisik = $conn->prepare($query_kondisi_fisik);
$stmt_kondisi_fisik->bind_param("i", $id_user);
$stmt_kondisi_fisik->execute();
$result_kondisi_fisik = $stmt_kondisi_fisik->get_result();

$query_fungsi_pekerjaan = "SELECT berhubungan_data, berhubungan_orang, berhubungan_benda FROM tb_fungsi_pekerjaan WHERE id_user = ?";
$stmt_fungsi_pekerjaan = $conn->prepare($query_fungsi_pekerjaan);
$stmt_fungsi_pekerjaan->bind_param("i", $id_user);
$stmt_fungsi_pekerjaan->execute();
$result_fungsi_pekerjaan = $stmt_fungsi_pekerjaan->get_result();

// Check if data exists
if (!$row = $result->fetch_assoc()) {
    echo "Data tidak ditemukan.";
    exit();
}

// Fetch additional data
$row_syarat_jabatan_lain = $result_syarat_jabatan_lain->fetch_assoc();
$row_kondisi_fisik = $result_kondisi_fisik->fetch_assoc();
$row_lingkungan = $result_lingkungan->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Detail Analisis Jabatan - DPRD Sultra</title>
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
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .detail-card {
            background: white;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .detail-header {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            padding: 1rem;
            border-radius: 15px 15px 0 0;
            color: white;
            text-align: center;
        }
        
        .back-button {
            margin-bottom: 1rem;
        }
        
        .kode-jabatan-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .kode-jabatan-form input {
            border: 1px solid #ddd;
            padding: 5px 10px;
            border-radius: 4px;
            width: auto;
            min-width: 200px;
        }
        
        .btn-save-kode {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-save-kode:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include('sidebar.php'); ?>
        
        <div class="main-panel">
            <div class="container mt-4">
                <?php if (isset($success_message)): ?>
                    <script>
                        Swal.fire({
                            title: 'Berhasil!',
                            text: '<?php echo $success_message; ?>',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <script>
                        Swal.fire({
                            title: 'Error!',
                            text: '<?php echo $error_message; ?>',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php endif; ?>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <a href="hasilanalisisjabatan.php" class="btn btn-sm btn-secondary back-button">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-header">
                        <h2 class="text-uppercase fw-bold">PEMERINTAH DPRD PROVINSI SULAWESI TENGGARA</h2>
                        <h3 class="fw-semibold">INFORMASI JABATAN PEGAWAI DPRD PROVINSI SULAWESI TENGGARA</h3>
                    </div>
                </div>

                <div class="container">
                            <!-- Tabel Informasi Jabatan -->
                            <div class="table-responsive">
                                <form method="POST" action="">
                                    <table class="table table-bordered table-hover" id="table1">
                                        <tbody>
                                            <tr>
                                                <th colspan="2">A. Nama Jabatan</th>
                                                <td><?php echo htmlspecialchars($row['nama_jabatan']); ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">B. Kode Jabatan</th>
                                                <td>
                                                    <div class="kode-jabatan-form">
                                                        <input type="text" name="kode_jabatan" value="<?php echo htmlspecialchars($row['kode_jabatan']); ?>" required>
                                                        <button type="submit" name="update_kode_jabatan" class="btn-save-kode">
                                                            <i class="fas fa-save"></i> Simpan
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">C. Unit Kerja</th>
                                                <td><?php echo htmlspecialchars($row['unit_kerja']); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Unit</td>
                                                <td><?php echo htmlspecialchars($row['unit_kerja1']); ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">D. Ikhtisar Jabatan</th>
                                                <td><?php echo nl2br(htmlspecialchars($row['ikhtisar_jabatan'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="3">E. Syarat Jabatan</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">a. Pangkat/Gol. Ruang</td>
                                                <td><?php echo htmlspecialchars($row['pangkat']); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">b. Pendidikan Formal</td>
                                                <td><?php echo htmlspecialchars($row['pendidikan']); ?></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="3" style="width: 10%;">c. Diklat</td>
                                                <td style="width: 10%;">Penjenjangan</td>
                                                <td><?php echo htmlspecialchars($row['jenjang']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Teknis</td>
                                                <td><?php echo htmlspecialchars($row['teknis']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Fungsional</td>
                                                <td><?php echo htmlspecialchars($row['fungsional']); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">d. Pengalaman Kerja</td>
                                                <td><?php echo nl2br(htmlspecialchars($row['pengalaman_kerja'])); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <!-- Tabel Informasi Jabatan End -->

                            <!-- Tabel Tugas Pokok -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="table2">
                                    <thead>
                                        <tr>
                                            <th colspan="7">F. Tugas Pokok</th>
                                        </tr>
                                        <tr>
                                            <th style="width: 5%; text-align: center;">No.</th>
                                            <th>Uraian Tugas</th>
                                            <th>Hasil Kerja</th>
                                            <th>Jumlah Beban Kerja 1 Tahun</th>
                                            <th>Waktu Penyelesaian (Jam)</th>
                                            <th>Waktu Efektif Penyelesaian</th>
                                            <th>Kebutuhan Pegawai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($row_tugas = $result_tugas->fetch_assoc()) { ?>
                                            <tr>
                                                <td style="text-align: center;"><?= $no ?></td>
                                                <td><?= htmlspecialchars($row_tugas['uraian_tugas']) ?></td>
                                                <td><?= htmlspecialchars($row_tugas['hasil_krj']) ?></td>
                                                <td><?= htmlspecialchars($row_tugas['jumlah_beban']) ?></td>
                                                <td><?= htmlspecialchars($row_tugas['waktu_penyelesaian']) ?></td>
                                                <td><?= htmlspecialchars($row_tugas['waktu_efektif']) ?></td>
                                                <td><?= htmlspecialchars($row_tugas['kebutuhan_pegawai']) ?></td>
                                            </tr>
                                        <?php
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Tugas Pokok End -->

                            <!-- Tabel Hasil Kerja -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">G. Hasil Kerja</th>
                                        </tr>
                                        <?php
                                        $no = 1;
                                        while ($row_hasil = $result_hasil_kerja->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td style='width: 5%; text-align: center;'>" . $no . ".</td>";
                                            echo "<td>" . htmlspecialchars($row_hasil['hasil_kerja']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Hasil Kerja End -->

                            <!-- Tabel Bahan Kerja -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">H. BAHAN KERJA :</th>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <th style="width: 5%;">No.</th>
                                            <th style="width: 45%;">BAHAN KERJA</th>
                                            <th style="width: 50%;">PENGGUNAAN DALAM TUGAS</th>
                                        </tr>
                                        <?php
                                        $no = 1;

                                        while ($row_bahan = $result_bahan_kerja->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>" . $no . ".</td>";
                                            echo "<td>" . htmlspecialchars($row_bahan['bahan_kerja']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row_bahan['penggunaan_tugas1']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Bahan Kerja End-->

                            <!-- Tabel Perangkat Kerja -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">I. PERANGKAT KERJA :</th>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <th style="width: 5%;">No.</th>
                                            <th style="width: 45%;">PERANGKAT KERJA</th>
                                            <th style="width: 50%;">PENGGUNAAN DALAM TUGAS</th>
                                        </tr>
                                        <?php
                                        $no = 1;

                                        while ($row_perangkat = $result_perangkat_kerja->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>" . $no . ".</td>";
                                            echo "<td>" . htmlspecialchars($row_perangkat['perangkat_kerja']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row_perangkat['penggunaan_tugas2']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Perangkat Kerja End-->

                            <!-- Tabel Tanggung Jawab -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">J. TANGGUNG JAWAB</th>
                                        </tr>
                                        <?php
                                        $no = 1;

                                        while ($row_tanggung = $result_tanggung_jawab->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td style='width: 5%; text-align: center;'>" . $no . ".</td>";
                                            echo "<td>" . htmlspecialchars($row_tanggung['tanggung_jawab']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Tanggung Jawab End -->

                            <!-- Tabel Wewenang -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">K. WEWENANG</th>
                                        </tr>
                                        <?php
                                        $no = 1;

                                        while ($row_wewenang_data = $result_wewenang->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td style='width: 5%; text-align: center;'>" . $no . ".</td>";
                                            echo "<td>" . htmlspecialchars($row_wewenang_data['wewenang']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Wewenang End -->

                            <!-- Tabel Korelasi Jabatan -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="4">L. KORELASI JABATAN :</th>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <th style="width: 5%;">No.</th>
                                            <th style="width: 30%;">JABATAN</th>
                                            <th style="width: 35%;">UNIT KERJA/ INSTANSI</th>
                                            <th style="width: 30%;">DALAM HAL</th>
                                        </tr>
                                        <?php
                                        $no = 1;

                                        while ($row_korelasi_data = $result_korelasi->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td style='text-align: center;'>" . $no . ".</td>";
                                            echo "<td>" . htmlspecialchars($row_korelasi_data['jabatan']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row_korelasi_data['instansi']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row_korelasi_data['dalam_hal']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div> <br>
                            <!-- Tabel Korelasi Jabatan End -->

                            <!-- Tabel Kondisi Lingkungan Kerja -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">M. KONDISI LINGKUNGAN KERJA :</th>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <th style="width: 5%;">No.</th>
                                            <th style="width: 45%;">ASPEK</th>
                                            <th style="width: 50%;">FAKTOR</th>
                                        </tr>

                                        <?php
                                        $aspek = ["Tempat kerja", "Suhu", "Udara", "Keadaan Ruangan", "Letak", "Penerangan", "Suara", "Keadaan tempat kerja", "Getaran"];
                                        $faktor_keys = ["tempat_kerja", "suhu", "udara", "keadaan_ruangan", "letak", "penerangan", "suara", "keadaan_tempat_kerja", "getaran"];

                                        if ($row_lingkungan) {
                                            for ($i = 0; $i < count($aspek); $i++) {
                                                echo "<tr>";
                                                echo "<td style='text-align: center;'>" . ($i + 1) . "</td>";
                                                echo "<td>" . htmlspecialchars($aspek[$i]) . "</td>";
                                                echo "<td>" . htmlspecialchars($row_lingkungan[$faktor_keys[$i]] ?? '') . "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div> <br>
                            <!-- Tabel Kondisi Lingkungan Kerja -->

                            <!-- Tabel Risiko Bahaya -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="3">N. RISIKO BAHAYA :</th>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <th style="width: 5%;">No.</th>
                                            <th style="width: 45%;">Fisik/Mental</th>
                                            <th style="width: 50%;">Penyebab</th>
                                        </tr>
                                        <?php
                                        $no = 1;
                                        while ($row = $result_risiko->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['fisik_mental']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['penyebab']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Risiko Bahaya End-->

                            <!-- Tabel Syarat Jabatan Lain -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="3">O. SYARAT JABATAN LAIN</th>
                                        </tr>
                                        <tr>
                                            <th style='width: 33%;'>Keterampilan Kerja</th>
                                            <th style='width: 33%;'>Bakat Kerja</th>
                                            <th>Temperamen Kerja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($aha = $result_syarat_jabatan_lain->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($aha['keterampilan_kerja'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($aha['bakat_kerja'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($aha['temperamen_kerja'] ?? '-') ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style='width: 33%;'>Minat Kerja</th>
                                            <th style='width: 33%;'>Upaya Fisik</th>
                                            <th>Jenis Kelamin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php if ($aha = $result_syarat_jabatan_lain->fetch_assoc()) { ?>
                                                <td><?= htmlspecialchars($aha['minat_kerja'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($aha['aktivitas'] ?? '-') ?></td>
                                            <?php } ?>
                                            <?php if ($ahk = $result_kondisi_fisik->fetch_assoc()) { ?>
                                                <td><?= htmlspecialchars($ahk['jenis_kelamin'] ?? '-') ?></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style='width: 33%;'>Umur Maksimal</th>
                                            <th style='width: 33%;'>Tinggi Badan (cm)</th>
                                            <th>Berat Badan (kg)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= htmlspecialchars($ahk['umur_maksimal'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($ahk['tinggi_badan'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($ahk['berat_badan'] ?? '-') ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style='width: 33%;'>Postur Badan</th>
                                            <th style='width: 33%;'>Penampilan</th>
                                            <th>Keadaan Fisik</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= htmlspecialchars($ahk['postur_badan'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($ahk['penampilan'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($ahk['keadaan_fisik'] ?? '-') ?></td>
                                        <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style='width: 33%;'>Fungsi Pekerjaan yang berhubungan dengan Data</th>
                                            <th style='width: 33%;'>Fungsi Pekerjaan yang berhubungan dengan Orang</th>
                                            <th>Fungsi Pekerjaan yang berhubungan dengan Benda</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($yami = $result_fungsi_pekerjaan->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($yami['berhubungan_data'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($yami['berhubungan_orang'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($yami['berhubungan_benda'] ?? '-') ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Syarat Jabatan Lain End -->

                            <!-- Tabel Prestasi yang Diharapkan -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">P. PRESTASI YANG DIHARAPKAN</th>
                                        </tr>
                                        <?php
                                        $no = 1;
                                        while ($row = $result_prestasi->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td style='width: 5%; text-align: center;'>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['prestasi']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }

                                        // Jika tidak ada data, tampilkan pesan
                                        if ($no === 1) {
                                            echo "<tr><td colspan='2' align='center'>Tidak ada data prestasi.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tabel Prestasi yang Diharapkan End -->

                            <!-- Tabel Kelas Jabatan -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">Q. KELAS JABATAN</th>
                                        </tr>
                                        <?php
                                        $no = 1;
                                        while ($row = $result_kelas_jabatan->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td style='width: 5%; text-align: center;'>{$no}</td>";
                                            echo "<td>" . htmlspecialchars($row['kelas_jabatan']) . "</td>";
                                            echo "</tr>";
                                            $no++;
                                        }

                                        // Jika tidak ada data, tampilkan pesan
                                        if ($no === 1) {
                                            echo "<tr><td colspan='2' align='center'>Tidak ada data kelas jabatan.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <a href="generate_pdf.php?id_user=<?= $id_user ?>" class="btn btn-primary">Download PDF</a>

                            </div>
                            <!-- Tabel Kelas Jabatan End -->

                        </div>
                </div>
            </div>
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