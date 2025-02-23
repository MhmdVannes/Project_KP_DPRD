<?php
ob_start();
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cek role user
if ($_SESSION['peran'] !== 'user') {
    header("Location: index.php");
    exit();
}

// Include koneksi database
include('koneksi.php');

// Inisialisasi variabel pesan
$success_message = "";
$error_message = "";

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $bagian = mysqli_real_escape_string($conn, $_POST['bagian']);
    $sub_bagian = mysqli_real_escape_string($conn, $_POST['sub_bagian']);
    
    // Tentukan nama jabatan dan parent berdasarkan pilihan
    $bagian_names = [
        '1' => 'Kepala Bagian Umum dan Keuangan',
        '2' => 'Kepala Bagian Persidangan dan Perundang-Undangan',
        '3' => 'Kepala Bagian Fasilitasi Penganggaran dan Pengawasan'
    ];
    
    // Simpan data bagian (parent) jika belum ada
    $check_parent = $conn->query("SELECT id FROM jabatan_hierarki WHERE nama_jabatan = '{$bagian_names[$bagian]}'");
    if ($check_parent->num_rows == 0) {
        $sql_parent = "INSERT INTO jabatan_hierarki (nama_jabatan, level, unit_kerja) VALUES (?, 1, 'DPRD Sultra')";
        $stmt_parent = $conn->prepare($sql_parent);
        $stmt_parent->bind_param("s", $bagian_names[$bagian]);
        $stmt_parent->execute();
        $parent_id = $stmt_parent->insert_id;
        $stmt_parent->close();
    } else {
        $parent_id = $check_parent->fetch_assoc()['id'];
    }
    
    // Simpan data sub bagian
    $sql = "INSERT INTO jabatan_hierarki (parent_id, nama_jabatan, level, unit_kerja) 
            VALUES (?, ?, 2, ?)";
    
    $stmt = $conn->prepare($sql);
    $nama_sub_bagian = $_POST['sub_bagian_text']; // Ambil dari hidden input yang akan kita tambahkan
    $unit_kerja = $bagian_names[$bagian];
    $stmt->bind_param("iss", $parent_id, $nama_sub_bagian, $unit_kerja);
    
    if ($stmt->execute()) {
        $jabatan_id = $stmt->insert_id;
        
        // Simpan detail analisis jabatan
        $sql_detail = "INSERT INTO detail_analisis_jabatan (
            jabatan_id, ikhtisar, pangkat, pendidikan, 
            diklat_jenjang, diklat_teknis, diklat_fungsional, 
            pengalaman_kerja, hasil_kerja, temperamen_kerja, 
            minat_kerja, upaya_fisik, jenis_kelamin, 
            fungsi_data, fungsi_orang, fungsi_benda, prestasi,
            input_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_detail = $conn->prepare($sql_detail);
        $stmt_detail->bind_param(
            "issssssssssssssssi",
            $jabatan_id,
            $_POST['ikhtisar'],
            $_POST['pangkat'],
            $_POST['pendidikan'],
            $_POST['diklat_jenjang'],
            $_POST['diklat_teknis'],
            $_POST['diklat_fungsional'],
            $_POST['pengalaman_kerja'],
            $_POST['hasil_kerja'],
            $_POST['temperamen_kerja'],
            $_POST['minat_kerja'],
            $_POST['upaya_fisik'],
            $_POST['jenis_kelamin'],
            $_POST['fungsi_data'],
            $_POST['fungsi_orang'],
            $_POST['fungsi_benda'],
            $_POST['prestasi'],
            $_SESSION['user_id']
        );
        
        if ($stmt_detail->execute()) {
            $success_message = "Data berhasil disimpan!";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                });
            </script>";
        } else {
            $error_message = "Terjadi kesalahan saat menyimpan detail: " . $conn->error;
        }
        $stmt_detail->close();
    } else {
        $error_message = "Terjadi kesalahan: " . $conn->error;
    }
    $stmt->close();
}

// Tambahkan hidden input untuk nama sub bagian yang dipilih
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Input Data - DPRD Sultra</title>
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
        .main-panel {
            background: #f4f6f9;
            min-height: 100vh;
            padding: 2rem;
            margin-left: 260px;
            width: calc(100% - 260px);
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
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            color: #1a237e;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
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

        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-select:focus {
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

        .jabatan-section {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .jabatan-title {
            font-weight: 600;
            color: #1a237e;
            margin-bottom: 0.5rem;
        }

        .sub-jabatan {
            margin-left: 1.5rem;
            padding-left: 1rem;
            border-left: 2px solid #e2e8f0;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
        }

        .modal-body {
            padding: 2rem;
        }

        .fa-check-circle {
            color: #28a745;
        }

        .modal-body h3 {
            color: #333;
            font-weight: 600;
        }

        .modal-body p {
            color: #666;
        }

        .btn-primary {
            padding: 0.5rem 2rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include('sidebar.php'); ?>
        
        <div class="main-panel">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Input Data Analisis Jabatan</h1>
                <p class="page-subtitle">Silakan lengkapi form berikut dengan data yang sesuai</p>
            </div>

            <!-- Form Section -->
            <form method="POST" action="">
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Informasi Jabatan -->
                <div class="form-card">
                    <h4 class="section-title">Informasi Jabatan</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Bagian</label>
                            <select name="bagian" class="form-select" id="bagian" required>
                                <option value="">-- Pilih Bagian --</option>
                                <option value="1">Kepala Bagian Umum dan Keuangan</option>
                                <option value="2">Kepala Bagian Persidangan dan Perundang-Undangan</option>
                                <option value="3">Kepala Bagian Fasilitasi Penganggaran dan Pengawasan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Sub Bagian</label>
                            <select name="sub_bagian" class="form-select" id="sub_bagian">
                                <option value="">-- Pilih Sub Bagian --</option>
                                <!-- Sub Bagian Umum dan Keuangan -->
                                <optgroup label="Bagian Umum dan Keuangan" data-parent="1">
                                    <option value="1.1">Kepala Sub Bagian Umum</option>
                                    <option value="1.2">Perencana (Ahli Muda)</option>
                                    <option value="1.3">Analis Perencanaan</option>
                                    <option value="1.4">Analis Laporan Akuntabilitas Kinerja</option>
                                    <option value="1.5">Analis Laporan Keuangan</option>
                                    <option value="1.6">Penata Laporan Keuangan</option>
                                    <option value="1.7">Penyusun Rencana Kegiatan dan Anggaran</option>
                                    <option value="1.8">Bendahara</option>
                                    <option value="1.9">Penyusun Laporan Keuangan</option>
                                    <option value="1.10">Pengelola Bahan Perencanaan</option>
                                    <option value="1.11">Verifikator Keuangan</option>
                                    <option value="1.12">Pengelola Gaji</option>
                                    <option value="1.13">Pengolah Data Laporan Pertanggungjawaban Bendahara</option>
                                    <option value="1.14">Pengolah Data Sistem Informasi Perbendaharaan</option>
                                    <option value="1.15">Pengadministrasi Keuangan</option>
                                </optgroup>

                                <!-- Sub Bagian Persidangan dan Perundang-Undangan -->
                                <optgroup label="Bagian Persidangan dan Perundang-Undangan" data-parent="2">
                                    <option value="2.1">Perisalah Legislatif Ahli Muda (Ahli Muda)</option>
                                    <option value="2.2">Analis Protokol</option>
                                    <option value="2.3">Penyusun Risalah</option>
                                    <option value="2.4">Analis Produk Hukum</option>
                                    <option value="2.5">Penyusun Rancangan Perundang-Undangan</option>
                                    <option value="2.6">Analis Peraturan Perundang-Undangan dan Rancangan Perundang-Undangan</option>
                                    <option value="2.7">Penelaah Jaringan Dokumentasi dan Informasi Hukum</option>
                                    <option value="2.8">Pengelola Persidangan</option>
                                    <option value="2.9">Pengelola Dokumentasi</option>
                                    <option value="2.10">Petugas Protokol</option>
                                    <option value="2.11">Notulis Rapat</option>
                                    <option value="2.12">Pengelola Data</option>
                                    <option value="2.13">Pengadministrasi Rapat</option>
                                    <option value="2.14">Pengadministrasi Data Peraturan Perundang-Undangan</option>
                                    <option value="2.15">Pengadministrasi Risalah</option>
                                </optgroup>

                                <!-- Sub Bagian Fasilitasi Penganggaran dan Pengawasan -->
                                <optgroup label="Bagian Fasilitasi Penganggaran dan Pengawasan" data-parent="3">
                                    <option value="3.1">Analis Kebijakan (Ahli Madya)</option>
                                    <option value="3.2">Analis Keuangan Pusat dan Daerah (Ahli Muda)</option>
                                    <option value="3.3">Analis Hasil Pengawasan dan Pengaduan Masyarakat</option>
                                    <option value="3.4">Penyusun Rencana Tindak Lanjut dan Hasil Pengawasan</option>
                                    <option value="3.5">Analis Laporan Hasil Pengawasan</option>
                                    <option value="3.6">Pengelola Pelaporan dan Evaluasi Pelaksanaan Kegiatan APBD</option>
                                    <option value="3.7">Analis Pelaksanaan Anggaran</option>
                                    <option value="3.8">Analis Penganggaran</option>
                                    <option value="3.9">Notulis Rapat</option>
                                    <option value="3.10">Pengelola Data</option>
                                    <option value="3.11">Pengadministrasi Rapat</option>
                                    <option value="3.12">Penyusun Naskah Rapat Pimpinan</option>
                                    <option value="3.13">Pengelola Surat</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Syarat Jabatan -->
                <div class="form-card">
                    <h4 class="section-title">Syarat Jabatan</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pangkat/Golongan</label>
                            <input type="text" name="pangkat" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pendidikan</label>
                            <input type="text" name="pendidikan" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Diklat Jenjang</label>
                            <input type="text" name="diklat_jenjang" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Diklat Teknis</label>
                            <input type="text" name="diklat_teknis" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Diklat Fungsional</label>
                            <input type="text" name="diklat_fungsional" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pengalaman Kerja</label>
                        <textarea name="pengalaman_kerja" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <!-- Hasil Kerja -->
                <div class="form-card">
                    <h4 class="section-title">Hasil Kerja</h4>
                    <div class="mb-3">
                        <label class="form-label">Uraian Hasil Kerja</label>
                        <textarea name="hasil_kerja" class="form-control" rows="3" required></textarea>
                    </div>
                </div>

                <!-- Karakteristik Pribadi -->
                <div class="form-card">
                    <h4 class="section-title">Karakteristik Pribadi</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Temperamen Kerja</label>
                            <select name="temperamen_kerja" class="form-select">
                                <option value="">-- Pilih Temperamen --</option>
                                <option value="DCP">Directing Control Planning (DCP)</option>
                                <option value="FIF">Feeling Idea Fact (FIF)</option>
                                <option value="INFLU">Influencing (INFLU)</option>
                                <option value="STS">Set of Limits (STS)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minat Kerja</label>
                            <select name="minat_kerja" class="form-select">
                                <option value="">-- Pilih Minat --</option>
                                <option value="Investigatif">Investigatif</option>
                                <option value="Artistik">Artistik</option>
                                <option value="Sosial">Sosial</option>
                                <option value="Enterprising">Enterprising</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Fungsi Pekerjaan -->
                <div class="form-card">
                    <h4 class="section-title">Fungsi Pekerjaan</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fungsi Data</label>
                            <select name="fungsi_data" class="form-select">
                                <option value="">-- Pilih Fungsi Data --</option>
                                <option value="Menganalisis">Menganalisis</option>
                                <option value="Mengkoordinasi">Mengkoordinasi</option>
                                <option value="Menyusun">Menyusun</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fungsi Orang</label>
                            <select name="fungsi_orang" class="form-select">
                                <option value="">-- Pilih Fungsi Orang --</option>
                                <option value="Menasehati">Menasehati</option>
                                <option value="Mengajar">Mengajar</option>
                                <option value="Memimpin">Memimpin</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fungsi Benda</label>
                            <select name="fungsi_benda" class="form-select">
                                <option value="">-- Pilih Fungsi Benda --</option>
                                <option value="Mengendalikan">Mengendalikan</option>
                                <option value="Mengoperasikan">Mengoperasikan</option>
                                <option value="Memasang">Memasang</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Prestasi -->
                <div class="form-card">
                    <h4 class="section-title">Prestasi yang Diharapkan</h4>
                    <div class="mb-3">
                        <label class="form-label">Uraian Prestasi</label>
                        <textarea name="prestasi" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Tambahkan script untuk mengatur tampilan sub bagian -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const bagianSelect = document.getElementById('bagian');
        const subBagianSelect = document.getElementById('sub_bagian');
        
        bagianSelect.addEventListener('change', function() {
            const selectedBagian = this.value;
            
            // Sembunyikan semua optgroup
            Array.from(subBagianSelect.getElementsByTagName('optgroup')).forEach(optgroup => {
                if(selectedBagian === optgroup.dataset.parent) {
                    optgroup.style.display = '';
                } else {
                    optgroup.style.display = 'none';
                }
            });
            
            // Reset sub bagian selection
            subBagianSelect.value = '';
        });
        
        // Trigger change event pada load untuk menyembunyikan optgroup yang tidak relevan
        bagianSelect.dispatchEvent(new Event('change'));
    });
    </script>

    <!-- Tambahkan script untuk menambahkan hidden input -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const subBagianSelect = document.getElementById('sub_bagian');
        const form = document.querySelector('form');
        
        // Tambahkan hidden input untuk menyimpan teks sub bagian
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'sub_bagian_text';
        form.appendChild(hiddenInput);
        
        subBagianSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            hiddenInput.value = selectedOption.text;
        });
    });
    </script>

    <!-- Add this modal HTML before closing body tag -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="mb-3">Berhasil Simpan</h3>
                    <p class="mb-4">Data berhasil disimpan ke sistem</p>
                    <button type="button" class="btn btn-primary px-4" onclick="window.location.href='user.php'">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this script before closing body tag -->
    <script>
    <?php if (!empty($success_message)): ?>
        // Show modal when success message exists
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    <?php endif; ?>
    </script>
</body>
</html>
<?php ob_end_flush(); ?>
