<?php
include 'session_check.php'; // Start session at the beginning

// Database connection
include 'koneksi.php'; // Use your connection file instead of direct connection

// Check login status
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Get user ID - check both direct session and GET parameter
$id_user = isset($_GET['id']) ? $_GET['id'] : $_SESSION['id_user'];

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

// Check if main data exists
if (!$row = $result->fetch_assoc()) {
    $no_data = true;
} else {
    $no_data = false;
    
    // If data exists, proceed with other queries
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
    $stmt_bahan_kerja->bind_param("i", $id_user);
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
    $row_lingkungan = $result_lingkungan->fetch_assoc();

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
    $row_syarat_jabatan_lain = $result_syarat_jabatan_lain->fetch_assoc();

    $query_kondisi_fisik = "SELECT jenis_kelamin, umur_maksimal, tinggi_badan, berat_badan, postur_badan, penampilan, keadaan_fisik FROM tb_kondisi_fisik WHERE id_user = ?";
    $stmt_kondisi_fisik = $conn->prepare($query_kondisi_fisik);
    $stmt_kondisi_fisik->bind_param("i", $id_user);
    $stmt_kondisi_fisik->execute();
    $result_kondisi_fisik = $stmt_kondisi_fisik->get_result();
    $row_kondisi_fisik = $result_kondisi_fisik->fetch_assoc();

    $query_fungsi_pekerjaan = "SELECT berhubungan_data, berhubungan_orang, berhubungan_benda FROM tb_fungsi_pekerjaan WHERE id_user = ?";
    $stmt_fungsi_pekerjaan = $conn->prepare($query_fungsi_pekerjaan);
    $stmt_fungsi_pekerjaan->bind_param("i", $id_user);
    $stmt_fungsi_pekerjaan->execute();
    $result_fungsi_pekerjaan = $stmt_fungsi_pekerjaan->get_result();
}
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
        
        .no-data-message {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 2rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .no-data-icon {
            font-size: 5rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include('sidebar.php'); ?>
        
        <div class="main-panel">
            <div class="container mt-4">
                <div class="row mb-4">
                    <div class="col-md-12">
                    </div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-header">
                        <h2 class="text-uppercase fw-bold">PEMERINTAH DPRD PROVINSI SULAWESI TENGGARA</h2>
                        <h3 class="fw-semibold">INFORMASI JABATAN PEGAWAI DPRD PROVINSI SULAWESI TENGGARA</h3>
                    </div>
                </div>

                <div class="container">
                    <?php if ($no_data): ?>
                        <div class="no-data-message">
                            <div class="no-data-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <h3>Data Belum Tersedia</h3>
                            <p class="lead">Maaf, Anda belum menginput data analisis jabatan.</p>
                            <p>Silakan lakukan pengisian data terlebih dahulu untuk melihat informasi jabatan.</p>
                            <a href="input_data.php" class="btn btn-primary mt-3">
                                <i class="fas fa-plus-circle"></i> Input Data Jabatan
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Tabel Informasi Jabatan -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="table1">
                                <tbody>
                                    <tr>
                                        <th colspan="2">A. Nama Jabatan</th>
                                        <td><?php echo htmlspecialchars($row['nama_jabatan']); ?></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">B. Kode Jabatan</th>
                                        <td><?php echo htmlspecialchars($row['kode_jabatan']); ?></td>
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
                                    while ($row_hasil_kerja = $result_hasil_kerja->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='width: 5%; text-align: center;'>" . $no . ".</td>";
                                        echo "<td>" . htmlspecialchars($row_hasil_kerja['hasil_kerja']) . "</td>";
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
                                    while ($row_bahan_kerja = $result_bahan_kerja->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no . ".</td>";
                                        echo "<td>" . htmlspecialchars($row_bahan_kerja['bahan_kerja']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_bahan_kerja['penggunaan_tugas1']) . "</td>";
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
                                    while ($row_perangkat_kerja = $result_perangkat_kerja->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no . ".</td>";
                                        echo "<td>" . htmlspecialchars($row_perangkat_kerja['perangkat_kerja']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_perangkat_kerja['penggunaan_tugas2']) . "</td>";
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
                                    while ($row_tanggung_jawab = $result_tanggung_jawab->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='width: 5%; text-align: center;'>" . $no . ".</td>";
                                        echo "<td>" . htmlspecialchars($row_tanggung_jawab['tanggung_jawab']) . "</td>";
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
                                    while ($row_wewenang = $result_wewenang->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='width: 5%; text-align: center;'>" . $no . ".</td>";
                                        echo "<td>" . htmlspecialchars($row_wewenang['wewenang']) . "</td>";
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
                                    while ($row_korelasi = $result_korelasi->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='text-align: center;'>" . $no . ".</td>";
                                        echo "<td>" . htmlspecialchars($row_korelasi['jabatan']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_korelasi['instansi']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_korelasi['dalam_hal']) . "</td>";
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
                                    while ($row_risiko = $result_risiko->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>{$no}</td>";
                                        echo "<td>" . htmlspecialchars($row_risiko['fisik_mental']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row_risiko['penyebab']) . "</td>";
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
                                    <?php if ($row_syarat_jabatan_lain) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row_syarat_jabatan_lain['keterampilan_kerja'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row_syarat_jabatan_lain['bakat_kerja'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row_syarat_jabatan_lain['temperamen_kerja'] ?? '-') ?></td>
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
                                        <?php if ($row_syarat_jabatan_lain) { ?>
                                            <td><?= htmlspecialchars($row_syarat_jabatan_lain['minat_kerja'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row_syarat_jabatan_lain['aktivitas'] ?? '-') ?></td>
                                        <?php } ?>
                                        <?php if ($row_kondisi_fisik) { ?>
                                            <td><?= htmlspecialchars($row_kondisi_fisik['jenis_kelamin'] ?? '-') ?></td>
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
                                        <td><?= htmlspecialchars($row_kondisi_fisik['umur_maksimal'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row_kondisi_fisik['tinggi_badan'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row_kondisi_fisik['berat_badan'] ?? '-') ?></td>
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
                                        <td><?= htmlspecialchars($row_kondisi_fisik['postur_badan'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row_kondisi_fisik['penampilan'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row_kondisi_fisik['keadaan_fisik'] ?? '-') ?></td>
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
                                    <?php while ($row_fungsi_pekerjaan = $result_fungsi_pekerjaan->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row_fungsi_pekerjaan['berhubungan_data'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row_fungsi_pekerjaan['berhubungan_orang'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($row_fungsi_pekerjaan['berhubungan_benda'] ?? '-') ?></td>
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
                                    while ($row_prestasi = $result_prestasi->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='width: 5%; text-align: center;'>{$no}</td>";
                                        echo "<td>" . htmlspecialchars($row_prestasi['prestasi']) . "</td>";
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
                                    while ($row_kelas_jabatan = $result_kelas_jabatan->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='width: 5%; text-align: center;'>{$no}</td>";
                                        echo "<td>" . htmlspecialchars($row_kelas_jabatan['kelas_jabatan']) . "</td>";
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
                        </div>
                        <!-- Tabel Kelas Jabatan End -->
                    <?php endif; ?>
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
    <!-- Font Awesome for the icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>