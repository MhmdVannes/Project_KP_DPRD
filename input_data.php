<?php
include 'session_check.php';
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['peran'])) {
    header("Location: login.php");
    exit();
}

// Pastikan user memiliki peran yang sesuai
if ($_SESSION['peran'] !== 'user') {
    header("Location: unauthorized.php"); // Redirect ke halaman tidak berizin
    exit();
}

// Ambil ID user dari URL atau session
$id_user = isset($_GET['id_user']) ? intval($_GET['id_user']) : $_SESSION['id_user'];

// Pastikan `id_user` di URL cocok dengan session untuk keamanan
if ($id_user !== $_SESSION['id_user']) {
    header("Location: inputdata.php?id_user=" . $_SESSION['id_user']);
    exit();
}
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
            </div>
      
            <div class="container mt-4">
                        <div class="card">
                            <div class="card-body">
                                <form action="proseskirimdata.php" method="POST">
                                    <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">

                                    <!-- FORM DATA JABATAN -->
                                    <div class="card-header">
                                        <h4>Form Input Data</h4>
                                    </div> <br>
                                    <div class="form-group">
                                        <label for="nama_jabatan">Nama Jabatan:</label>
                                        <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" placeholder="Masukan nama jabatan" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_jabatan">Kode Jabatan:</label>
                                        <input type="text" class="form-control" id="kode_jabatan" name="kode_jabatan" placeholder="Hanya dapat diisi oleh Admin" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit_kerja1">Unit Kerja:</label>
                                        <input type="text" class="form-control" id="unit_kerja1" name="unit_kerja1" value="Sekretariat Dewan Perwakilan Rakyat Daerah" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="unit_kerja">Unit Kerja:</label>
                                        <select class="form-control" id="unit_kerja" name="unit_kerja" required onchange="toggleInput()">
                                            <option value="">Pilih Unit Kerja</option>
                                            <option value="JPT Madya">JPT Madya</option>
                                            <option value="JPT Pratama">JPT Pratama</option>
                                            <option value="Administrator">Administrator</option>
                                            <option value="Pengawas">Pengawas</option>
                                            <option value="Jabatan">Jabatan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="ikhtisar_jabatan">Ikhtisar Jabatan:</label>
                                        <textarea class="form-control" id="ikhtisar_jabatan" name="ikhtisar_jabatan" rows="3" placeholder="Masukan teks"></textarea>
                                    </div>
                                    <!-- FORM DATA JABATAN END -->

                                    <!-- SYARAT JABATAN -->
                                    <div class="card-header">
                                        <h4>Syarat Jabatan</h4>
                                    </div> <br>
                                    <div class="form-group">
                                        <label for="pangkat">Pangkat/Gol. Ruang:</label>
                                        <input type="text" class="form-control" id="pangkat" name="pangkat" placeholder="Minimal Pembina Tk. I, Gol. IV/b" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pendidikan">Pendidikan Formal:</label>
                                        <input type="text" class="form-control" id="pendidikan" name="pendidikan" placeholder="Minimal S-1 bidang yang relevan" required>
                                    </div>
                                    <div class="form-group">
                                        <strong><label>Diklat:</label></strong> <br>
                                        <label for="jenjang">Penjenjangan:</label>
                                        <select class="form-control" id="jenjang" name="jenjang" required>
                                            <option value="">Pilih Jenjang</option>
                                            <option value="Diklatpim Tingkat II">Diklatpim Tingkat II</option>
                                            <option value="Diklatpim Tingkat III">Diklatpim Tingkat III</option>
                                            <option value="Diklatpim Tingkat IV">Diklatpim Tingkat IV</option>
                                        </select> <br>
                                        <label for="teknis">Teknis:</label>
                                        <select class="form-control" id="teknis" name="teknis" required>
                                            <option value="">Pilih Teknis</option>
                                            <option value="Diklat Teknis Administrasi Keuangan">Diklat Teknis Administrasi Keuangan</option>
                                            <option value="Diklat Teknis Kepemimpinan Organisasi">Diklat Teknis Kepemimpinan Organisasi</option>
                                            <option value="Diklat pengadaan barang dan jasa">Diklat pengadaan barang dan jasa</option>
                                            <option value="Diklat Teknis Bidang Umum Atau Administrasi Dan Manajemen">Diklat Teknis Bidang Umum Atau Administrasi Dan Manajemen</option>
                                        </select> <br>
                                        <label for="fungsional">Fungsional:</label>
                                        <input type="text" class="form-control" id="fungsional" name="fungsional" placeholder="Masukan fungsional" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pengalaman_kerja">Pengalaman Kerja:</label>
                                        <textarea class="form-control" id="pengalaman_kerja" name="pengalaman_kerja" rows="3" placeholder="Masukan pengalaman kerja"></textarea>
                                        <small><strong>*Catatan:</strong></small> <br>
                                        <small>1. Minimal berpengalaman dalam 2 Jabatan Administrator atau telah 5 tahun dalam satu Jabatan Administrator.</small> <br>
                                        <small>2. Sedang menduduki Jabatan Administrator atau Jabatan Fungsional jenjang Ahli Madya paling singkat 2 tahun.</small>
                                    </div>
                                    <!-- SYARAT JABATAN END -->

                                    <!-- TUGAS POKOK -->
                                    <div class="card-header">
                                        <h4>Tugas Pokok</h4>
                                    </div> <br>
                                    <div class="form-group">
                                        <label for="uraian_tugas">Uraian Tugas:</label>
                                        <textarea class="form-control" id="uraian_tugas" name="uraian_tugas[]" rows="3" placeholder="Masukan uraian tugas"></textarea>
                                    </div>
                                    <div id="form-container1">
                                        <div class="form-group">
                                            <label for="hasil_krj">Hasil Kerja:</label>
                                            <select class="form-control" id="hasil_krj" name="hasil_krj[]" required onchange="toggleInput()">
                                                <option value="">Pilih Hasil Kerja</option>
                                                <option value="Dokumen">Dokumen</option>
                                                <option value="Kegiatan">Kegiatan</option>
                                            </select>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="jumlah_beban">Jumlah beban kerja 1 tahun:</label>
                                                <input type="number" class="form-control" id="jumlah_beban" name="jumlah_beban[]" placeholder="Masukan jumlah beban kerja 1 tahun" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="waktu_penyelesaian">Waktu penyelesaian:</label>
                                                <input type="number" class="form-control" id="waktu_penyelesaian" name="waktu_penyelesaian[]" placeholder="Masukan waktu penyelesaian" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="waktu_efektif">Waktu efektif penyelesaian:</label>
                                                <input type="number" class="form-control" id="waktu_efektif" name="waktu_efektif[]" placeholder="Masukan waktu efektif penyelesaian" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="kebutuhan_pegawai">Kebutuhan pegawai:</label>
                                                <input type="number" class="form-control" id="kebutuhan_pegawai" name="kebutuhan_pegawai[]" placeholder="Masukan kebutuhan pegawai" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="add-form-btn1">Tambah</button>
                                    <br> <br>

                                    <script>
                                        document.getElementById('add-form-btn1').addEventListener('click', function() {
                                            var formContainer = document.getElementById('form-container1');

                                            // Create a new form section
                                            var newForm = document.createElement('div');
                                            newForm.classList.add('form-group');
                                            newForm.innerHTML = `
                                                            <div class="form-group">
                                                                <label for="uraian_tugas">Uraian Tugas:</label>
                                                                <textarea class="form-control" id="uraian_tugas" name="uraian_tugas[]" rows="3" placeholder="Masukan uraian tugas"></textarea>
                                                            <div>
                                                            <div class="form-group">
                                                                <label for="hasil_krj">Hasil Kerja:</label>
                                                                <select class="form-control" id="hasil_krj" name="hasil_krj[]" required onchange="toggleInput()">
                                                                    <option value="">Pilih Hasil Kerja</option>
                                                                    <option value="Dokumen">Dokumen</option>
                                                                    <option value="Kegiatan">Kegiatan</option>
                                                                </select>
                                                            </div>
                                                
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="jumlah_beban">Jumlah beban kerja 1 tahun:</label>
                                                                    <input type="number" class="form-control" id="jumlah_beban" name="jumlah_beban[]" placeholder="Masukan jumlah beban kerja 1 tahun" required>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="waktu_penyelesaian">Waktu penyelesaian:</label>
                                                                    <input type="number" class="form-control" id="waktu_penyelesaian" name="waktu_penyelesaian[]" placeholder="Masukan waktu penyelesaian" required>
                                                                </div>
                                                            </div>
                                                
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="waktu_efektif">Waktu efektif penyelesaian:</label>
                                                                    <input type="number" class="form-control" id="waktu_efektif" name="waktu_efektif[]" placeholder="Masukan waktu efektif penyelesaian" required>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="kebutuhan_pegawai">Kebutuhan pegawai:</label>
                                                                    <input type="number" class="form-control" id="kebutuhan_pegawai" name="kebutuhan_pegawai[]" placeholder="Masukan kebutuhan pegawai" required>
                                                                </div>
                                                            </div>
                                                        `;

                                            // Append the new form section to the container
                                            formContainer.appendChild(newForm);
                                        });

                                        function toggleInput() {
                                            // Optional: Logic to handle the select dropdown onchange
                                            console.log('Hasil Kerja selected');
                                        }
                                    </script>
                                    <!-- TUGAS POKOK END -->

                                    <!-- HASIL KERJA -->
                                    <div class="card-header">
                                        <h4>Hasil Kerja</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container2">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label for="hasil_kerja">Hasil Kerja:</label>
                                                    <textarea class="form-control" name="hasil_kerja[]" rows="3" placeholder="Masukkan hasil kerja"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-form-btn2">Tambah</button> <br> <br>
                                    </div>

                                    <script>
                                        document.getElementById('add-form-btn2').addEventListener('click', function() {
                                            var formContainer = document.getElementById('form-container2');

                                            // Buat elemen form baru
                                            var newForm = document.createElement('div');
                                            newForm.classList.add('form-group', 'row');
                                            newForm.innerHTML = `
                                                <div class="col-md-12">
                                                    <label for="hasil_kerja">Hasil Kerja:</label>
                                                    <textarea class="form-control" name="hasil_kerja[]" rows="3" placeholder="Masukkan hasil kerja"></textarea>
                                                </div>
                                            `;

                                            // Tambahkan elemen baru ke dalam form-container
                                            formContainer.appendChild(newForm);
                                        });
                                    </script>
                                    <!-- HASIL KERJA END -->

                                    <!-- BAHAN KERJA -->
                                    <div class="card-header">
                                        <h4>Bahan Kerja</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container3">
                                            <div class="form-group row"> <!-- Gunakan class row -->
                                                <div class="col-md-6 px-3">
                                                    <div class="form-group">
                                                        <label for="bahan_kerja">Bahan Kerja:</label>
                                                        <textarea class="form-control" name="bahan_kerja[]" rows="3" placeholder="Masukan bahan kerja"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 px-3">
                                                    <div class="form-group">
                                                        <label for="penggunaan_tugas1">Penggunaan dalam Tugas:</label>
                                                        <textarea class="form-control" name="penggunaan_tugas1[]" rows="3" placeholder="Masukan penggunaan dalam tugas"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-form-btn3">Tambah</button> <br> <br>
                                    </div>

                                    <script>
                                        document.getElementById('add-form-btn3').addEventListener('click', function() {
                                            var formContainer = document.getElementById('form-container3');

                                            // Buat elemen form baru
                                            var newForm = document.createElement('div');
                                            newForm.classList.add('row', 'mt-3'); // Tambahkan row dan margin top untuk jarak

                                            newForm.innerHTML = `
                                                <div class="col-md-6 px-3"> 
                                                    <div class="form-group">
                                                        <label>Bahan Kerja:</label>
                                                        <textarea class="form-control" name="bahan_kerja[]" rows="3" placeholder="Masukan bahan kerja"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 px-3"> 
                                                    <div class="form-group">
                                                        <label>Penggunaan dalam Tugas:</label>
                                                        <textarea class="form-control" name="penggunaan_tugas1[]" rows="3" placeholder="Masukan penggunaan dalam tugas"></textarea>
                                                    </div>
                                                </div>
                                            `;

                                            // Tambahkan form baru ke dalam container
                                            formContainer.appendChild(newForm);
                                        });
                                    </script>
                                    <!-- BAHAN KERJA END -->

                                    <!-- PERANGKAT KERJA -->
                                    <div class="card-header">
                                        <h4>Perangkat Kerja</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container4">
                                            <div class="form-group row"> <!-- Gunakan form-row untuk mendefinisikan baris -->
                                                <div class="col-md-6 px-3"> <!-- Kolom kiri untuk Bahan Kerja -->
                                                    <div class="form-group">
                                                        <label for="perangkat_kerja">Perangkat Kerja:</label>
                                                        <textarea class="form-control" id="perangkat_kerja" name="perangkat_kerja[]" rows="3" placeholder="Masukan perangkat kerja"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 px-3"> <!-- Kolom kanan untuk Penggunaan dalam Tugas -->
                                                    <div class="form-group">
                                                        <label for="penggunaan_tugas2">Penggunaan dalam Tugas:</label>
                                                        <textarea class="form-control" id="penggunaan_tugas2" name="penggunaan_tugas2[]" rows="3" placeholder="Masukan penggunaan dalam tugas"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-form-btn4">Tambah</button> <br> <br>
                                    </div>
                                    <script>
                                        document.getElementById('add-form-btn4').addEventListener('click', function() {
                                            var formContainer = document.getElementById('form-container4');

                                            // Create a new form section
                                            var newForm = document.createElement('div');
                                            newForm.classList.add('form-row'); // Menambahkan class form-row untuk mendefinisikan baris

                                            newForm.innerHTML = `
                                                    <div class="col-md-6 px-3"> <!-- Kolom kiri untuk Bahan Kerja -->
                                                        <div class="form-group">
                                                            <label for="perangkat_kerja">Perangkat Kerja:</label>
                                                            <textarea class="form-control" id="perankat_kerja" name="perangkat_kerja[]" rows="3" placeholder="Masukan perangkat kerja"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 px-3"> <!-- Kolom kanan untuk Penggunaan dalam Tugas -->
                                                        <div class="form-group">
                                                            <label for="penggunaan_tugas2">Penggunaan dalam Tugas:</label>
                                                            <textarea class="form-control" id="penggunaan_tugas2" name="penggunaan_tugas2[]" rows="3" placeholder="Masukan penggunaan dalam tugas"></textarea>
                                                        </div>
                                                    </div>
                                                `;

                                            // Append the new form section to the container
                                            formContainer.appendChild(newForm);
                                        });
                                    </script>
                                    <!-- PERANGKAT KERJA END -->

                                    <!-- TANGGUNG JAWAB -->
                                    <div class="card-header">
                                        <h4>Tanggung Jawab</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container5">
                                            <div class="form-group row"> <!-- Gunakan form-row untuk mendefinisikan baris -->
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group">
                                                        <label for="tanggung_jawab">Tanggung Jawab:</label>
                                                        <textarea class="form-control" id="tanggung_jawab" name="tanggung_jawab[]" rows="3" placeholder="Masukan tanggung jawab"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-form-btn5">Tambah</button> <br> <br>
                                    </div>
                                    <script>
                                        document.getElementById('add-form-btn5').addEventListener('click', function() {
                                            var formContainer = document.getElementById('form-container5');

                                            // Create a new form section
                                            var newForm = document.createElement('div');
                                            newForm.classList.add('form-row'); // Menambahkan class form-row untuk mendefinisikan baris

                                            newForm.innerHTML = `
                                                    <div class="col-md-12 px-3"> <!-- Kolom kiri untuk Bahan Kerja -->
                                                        <div class="form-group row">
                                                            <label for="tanggung_jawab">Tanggung Jawab:</label>
                                                            <textarea class="form-control" id="tanggung_jawab" name="tanggung_jawab[]" rows="3" placeholder="Masukan tanggung jawab"></textarea>
                                                        </div>
                                                    </div>
                                                `;
                                            // Append the new form section to the container
                                            formContainer.appendChild(newForm);
                                        });
                                    </script>
                                    <!-- TANGGUNG JAWAB END -->

                                    <!-- WEWENANG -->
                                    <div class="card-header">
                                        <h4>Wewenang</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container6">
                                            <div class="form-group row"> <!-- Gunakan form-row untuk mendefinisikan baris -->
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group">
                                                        <label for="wewenang">Wewenang:</label>
                                                        <textarea class="form-control" id="wewenang" name="wewenang[]" rows="3" placeholder="Masukan wewenang"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-form-btn6">Tambah</button> <br> <br>
                                    </div>
                                    <script>
                                        document.getElementById('add-form-btn6').addEventListener('click', function() {
                                            var formContainer = document.getElementById('form-container6');

                                            // Create a new form section
                                            var newForm = document.createElement('div');
                                            newForm.classList.add('form-row'); // Menambahkan class form-row untuk mendefinisikan baris

                                            newForm.innerHTML = `
                                                    <div class="col-md-12 px-3"> <!-- Kolom kiri untuk Bahan Kerja -->
                                                        <div class="form-group row">
                                                            <label for="wewenang">Wewenang:</label>
                                                            <textarea class="form-control" id="wewenang" name="wewenang[]" rows="3" placeholder="Masukan wewenang"></textarea>
                                                        </div>
                                                    </div>
                                                `;
                                            // Append the new form section to the container
                                            formContainer.appendChild(newForm);
                                        });
                                    </script>
                                    <!-- WEWENANG END -->

                                    <!-- KOLERASI JABATAN -->
                                    <div class="card-header">
                                        <h4>Kolerasi Jabatan</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container7">
                                            <div class="form-row"> <!-- Gunakan form-row untuk mendefinisikan baris -->
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="jabatan">Jabatan:</label>
                                                        <textarea class="form-control" id="jabatan" name="jabatan[]" rows="3" placeholder="Masukan jabatan"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="instansi">Unit kerja/Instansi:</label>
                                                        <textarea class="form-control" id="instansi" name="instansi[]" rows="3" placeholder="Masukan unit kerja/instasi"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="dalam_hal">Dalam hal:</label>
                                                        <textarea class="form-control" id="dalam_hal" name="dalam_hal[]" rows="3" placeholder="Masukan dalam hal"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-form-btn7">Tambah</button> <br> <br>
                                    </div>
                                    <script>
                                        document.getElementById('add-form-btn7').addEventListener('click', function() {
                                            var formContainer = document.getElementById('form-container7');

                                            // Create a new form section
                                            var newForm = document.createElement('div');
                                            newForm.classList.add('form-row'); // Menambahkan class form-row untuk mendefinisikan baris

                                            newForm.innerHTML = `
                                                    <div class="col-md-12 px-3"> 
                                                                <div class="form-group row">
                                                                    <label for="jabatan">Jabatan:</label>
                                                                    <textarea class="form-control" id="jabatan" name="jabatan[]" rows="3" placeholder="Masukan jabatan"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 px-3"> 
                                                                <div class="form-group row">
                                                                    <label for="instansi">Unit kerja/Instansi:</label>
                                                                    <textarea class="form-control" id="instansi" name="instansi[]" rows="3" placeholder="Masukan unit kerja/instasi"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 px-3"> 
                                                                <div class="form-group row">
                                                                    <label for="dalam_hal">Dalam hal:</label>
                                                                    <textarea class="form-control" id="dalam_hal" name="dalam_hal[]" rows="3" placeholder="Masukan dalam hal"></textarea>
                                                                </div>
                                                            </div>
                                                `;
                                            // Append the new form section to the container
                                            formContainer.appendChild(newForm);
                                        });
                                    </script>
                                    <!-- KOLERASI JABATAN END -->

                                    <!-- KONDISI LINGKUNGAN KERJA -->
                                    <div class="card-header">
                                        <h4>Kondisi Lingkungan Kerja</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container8">
                                            <div class="form-row"> <!-- Gunakan form-row untuk mendefinisikan baris -->
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="tempat_kerja">Tempat kerja:</label>
                                                        <input type="text" class="form-control" id="tempat_kerja" name="tempat_kerja" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="suhu">Suhu:</label>
                                                        <input type="text" class="form-control" id="suhu" name="suhu" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="udara">Udara:</label>
                                                        <input type="text" class="form-control" id="udara" name="udara" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="keadaan_ruangan">Keadaan ruangan:</label>
                                                        <input type="text" class="form-control" id="keadaan_ruangan" name="keadaan_ruangan" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="letak">Letak:</label>
                                                        <input type="text" class="form-control" id="letak" name="letak" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="penerangan">Penerangan:</label>
                                                        <input type="text" class="form-control" id="penerangan" name="penerangan" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="suara">Suara:</label>
                                                        <input type="text" class="form-control" id="suara" name="suara" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="keadaan_tempat_kerja">Keadaan tempat kerja:</label>
                                                        <input type="text" class="form-control" id="keadaan_tempat_kerja" name="keadaan_tempat_kerja" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="getaran">Getaran:</label>
                                                        <input type="text" class="form-control" id="getaran" name="getaran" placeholder="Masukkan faktor" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <br>

                                    <!-- KONDISI LINGKUNGAN KERJA END -->

                                    <!-- RISIKO BAHAYA -->
                                    <div class="card-header">
                                        <h4>Risiko Bahaya</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-row"> <!-- Gunakan form-row untuk mendefinisikan baris -->
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="fisik_mental">Fisik/Mental:</label>
                                                        <textarea class="form-control" id="fisik_mental" name="fisik_mental[]" rows="3" placeholder="Masukan fisik/mental"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="penyebab">Penyebab:</label>
                                                        <textarea class="form-control" id="penyebab" name="penyebab[]" rows="3" placeholder="Masukan penyebab"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-form-btn9">Tambah</button> <br> <br>
                                    </div>
                                    <script>
                                        document.getElementById('add-form-btn9').addEventListener('click', function() {
                                            var formContainer = document.getElementById('form-container9');

                                            // Create a new form section
                                            var newForm = document.createElement('div');
                                            newForm.classList.add('form-row'); // Menambahkan class form-row untuk mendefinisikan baris

                                            newForm.innerHTML = `
                                                        <div class="col-md-12 px-3"> 
                                                            <div class="form-group row">
                                                                <label for="fisik_mental">Fisik/Mental:</label>
                                                                <textarea class="form-control" id="fisik_mental" name="fisik_mental[]" rows="3" placeholder="Masukan fisik/mental"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 px-3"> 
                                                            <div class="form-group row">
                                                                <label for="penyebab">Penyebab:</label>
                                                                <textarea class="form-control" id="penyebab" name="penyebab[]" rows="3" placeholder="Masukan penyebab"></textarea>
                                                            </div>
                                                        </div>
                                            `;
                                            // Append the new form section to the container
                                            formContainer.appendChild(newForm);
                                        });
                                    </script>
                                    <!-- RISIKO BAHAYA END -->

                                    <!-- SYARAT JABATAN LAIN -->
                                    <div class="card-header">
                                        <h4>Syarat Jabatan Lain</h4>
                                    </div> <br>
                                    <!-- KETERAMPILAN KERJA -->
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-row">
                                                <label for="keterampilan_kerja">Keterampilan Kerja:</label>
                                                <select class="form-control" id="keterampilan_kerja" name="keterampilan_kerja" required>
                                                    <option value="">Pilih Keterampilan Kerja</option>
                                                    <option value="keterampilan_komputer">Keterampilan Komputer</option>
                                                    <option value="keterampilan_analisis">Keterampilan Analisis dan Evaluasi Data</option>
                                                    <option value="keterampilan_penyusunan">Keterampilan Penyusunan Data Organisasi</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> <br>
                                    <!-- KETERAMPILAN KERJA END -->
                                    <!-- BAKAT KERJA -->
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-row">
                                                <label for="bakat_kerja">Bakat Kerja:</label>
                                                <select class="form-control" id="bakat_kerja" name="bakat_kerja" required>
                                                    <option value="">Pilih Bakat Kerja</option>
                                                    <option value="integelensia">Integelensia</option>
                                                    <option value="bakat_verbal">Bakat Verbal</option>
                                                    <option value="bakat_pandang_ruang">Bakat Pandang Ruang</option>
                                                    <option value="bakat_ketelitian">Bakat Ketelitian</option>
                                                    <option value="koordinasi_motorik">Koordinasi Motorik</option>
                                                    <option value="kecekatan_jari">Kecekatan Jari</option>
                                                    <option value="koordinasi_mata">Koordinasi Mata, Tangan, Kaki</option>
                                                    <option value="kemampuan_membedakan_warna">Kemampuan Membedakan Warna</option>
                                                    <option value="kecekatan_tangan">Kecekatan Tangan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> <br>
                                    <!-- BAKAT KERJA END -->
                                    <!-- TEMPERAMEN KERJA -->
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-row">
                                                <label for="temperamen_kerja">Temperamen Kerja:</label>
                                                <select class="form-control" id="temperamen_kerja" name="temperamen_kerja" required>
                                                    <option value="">Pilih Temperamen Kerja</option>
                                                    <option value="DCP">Directing Control Planning (DCP) </option>
                                                    <option value="FIF">Feeling- Idea- Fact (FIF) </option>
                                                    <option value="INFLU">Influencing (INFLU) </option>
                                                    <option value="SJC">Sensory & Judgmental Creteria (SJC) </option>
                                                    <option value="MVC">Measurable and Verifiable Creteria (MVC) </option>
                                                    <option value="DEPL">Dealing with People (DEPL) </option>
                                                    <option value="REPCON">Repetitive and Continuous (REPCON) </option>
                                                    <option value="PUS">Performing Under Stress (PUS) </option>
                                                    <option value="STS">Set of Limits, Tolerance and Other Standart (STS) </option>
                                                    <option value="VARCH">Variety and Changing Conditions (VARCH)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> <br>
                                    <!-- TEMPERAMEN KERJA END-->
                                    <!-- MINAT KERJA -->
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-row">
                                                <label for="minat_kerja">Minat Kerja:</label>
                                                <select class="form-control" id="minat_kerja" name="minat_kerja" required>
                                                    <option value="">Pilih Minat Kerja</option>
                                                    <option value="realistik">Realistik </option>
                                                    <option value="investigatif">Investigatif </option>
                                                    <option value="artistik">Artistik </option>
                                                    <option value="sosial">Sosial </option>
                                                    <option value="kewirausahaan">Kewirausahaan</option>
                                                    <option value="konvensional">Konvensional </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> <br>
                                    <!-- MINAT KERJA END -->
                                    <!-- UPAYA FISIK -->
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-group">
                                                <label for="aktivitas">Upaya Fisik:</label><br>
                                                <div id="aktivitas" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                                                    <label><input type="checkbox" name="aktivitas[]" value="Berdiri"> Berdiri</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Berjalan"> Berjalan</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Duduk"> Duduk</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Mengangkat"> Mengangkat</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Membawa"> Membawa</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Mendorong"> Mendorong</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Menarik"> Menarik</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Memanjat"> Memanjat</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Menyimpan imbangan"> Menyimpan imbangan</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Menunduk"> Menunduk</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Berlutut"> Berlutut</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Membungkuk"> Membungkuk</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Merangkak"> Merangkak</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Menjangkau"> Menjangkau</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Memegang"> Memegang</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Bekerja dengan jari"> Bekerja dengan jari</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Meraba"> Meraba</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Berbicara"> Berbicara</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Mendengar"> Mendengar</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Melihat"> Melihat</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Ketajaman jarak jauh"> Ketajaman jarak jauh</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Ketajaman jarak dekat"> Ketajaman jarak dekat</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Pengamatan secara mendalam"> Pengamatan secara mendalam</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Penyesuaian lensa mata"> Penyesuaian lensa mata</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Melihat berbagai warna"> Melihat berbagai warna</label>
                                                    <label><input type="checkbox" name="aktivitas[]" value="Luas"> Luas</label>
                                                </div>
                                            </div>

                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    let checkboxes = document.querySelectorAll('input[name="aktivitas[]"]');

                                                    checkboxes.forEach(function(checkbox) {
                                                        checkbox.addEventListener("change", function() {
                                                            let checkedBoxes = document.querySelectorAll('input[name="aktivitas[]"]:checked');
                                                            if (checkedBoxes.length > 3) {
                                                                alert("Maksimal hanya bisa memilih 3 aktivitas!");
                                                                this.checked = false;
                                                            }
                                                        });
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div><br>
                                    <!-- UPAYA FISIK END -->
                                    <!-- KONDISI FISIK -->
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="jenis_kelamin">Jenis Kelamin:</label>
                                                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required onchange="toggleInput()">
                                                        <option value="">Pilih jenis kelamin</option>
                                                        <option value="Laki-laki">Laki-laki</option>
                                                        <option value="Perempuan">Perempuan</option>
                                                        <option value="Laki-laki/Perempuan">Laki-laki/Perempuan</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="umur_maksimal">Umur Maksimal:</label>
                                                    <input type="number" id="umur_maksimal" name="umur_maksimal" class="form-control" placeholder="Masukkan umur maksimal">
                                                </div>

                                                <div class="form-group">
                                                    <label for="tinggi_badan">Tinggi Badan (cm):</label>
                                                    <input type="number" id="tinggi_badan" name="tinggi_badan" class="form-control" placeholder="Masukkan tinggi badan">
                                                </div>

                                                <div class="form-group">
                                                    <label for="berat_badan">Berat Badan (kg):</label>
                                                    <input type="number" id="berat_badan" name="berat_badan" class="form-control" placeholder="Masukkan berat badan">
                                                </div>

                                                <div class="form-group">
                                                    <label for="postur_badan">Postur Badan:</label>
                                                    <input type="text" id="postur_badan" name="postur_badan" class="form-control" placeholder="Masukkan postur badan">
                                                </div>

                                                <div class="form-group">
                                                    <label for="penampilan">Penampilan:</label>
                                                    <input type="text" id="penampilan" name="penampilan" class="form-control" placeholder="Masukkan penampilan">
                                                </div>

                                                <div class="form-group">
                                                    <label for="keadaan_fisik">Keadaan Fisik:</label>
                                                    <input type="text" id="keadaan_fisik" name="keadaan_fisik" class="form-control" placeholder="Masukkan keadaan fisik">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- KONDISI FISIK END -->
                                    <!-- FUNGSI PEKERJAAN -->
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-group">
                                                <label for="berhubungan_data">Fungsi Pekerjaan yang berhubungan dengan Data:</label>
                                                <select class="form-control" id="berhubungan_data" name="berhubungan_data" required>
                                                    <option value="">Pilih pekerjaan</option>
                                                    <option value="Memasang mesin">Memasang mesin</option>
                                                    <option value="Mengerjakan persisi">Mengerjakan persisi</option>
                                                    <option value="Menjalankan mengontrol mesin">Menjalankan mengontrol mesin</option>
                                                    <option value="Mengemudikan/menjalankan mesin">Mengemudikan/menjalankan mesin</option>
                                                    <option value="Mengerjakan benda dengan tangan atau perkakas">Mengerjakan benda dengan tangan atau perkakas</option>
                                                    <option value="Melayani mesin">Melayani mesin</option>
                                                    <option value="Memasukkan, mengeluarkan barang ke/dari mesin">Memasukkan, mengeluarkan barang ke/dari mesin</option>
                                                    <option value="Memegang">Memegang</option>
                                                    <option value="Memadukan data">Memadukan data</option>
                                                    <option value="Mengkoordinasi data">Mengkoordinasi data</option>
                                                    <option value="Menganalisis data">Menganalisis data</option>
                                                    <option value="Menyusun data">Menyusun data</option>
                                                    <option value="Menghitung data">Menghitung data</option>
                                                    <option value="Menyalin data">Menyalin data</option>
                                                    <option value="Membandingkan data">Membandingkan data</option>
                                                    <option value="Menasehati">Menasehati</option>
                                                    <option value="Berunding">Berunding</option>
                                                    <option value="Mengajar">Mengajar</option>
                                                    <option value="Menyelia">Menyelia</option>
                                                    <option value="Menghibur">Menghibur</option>
                                                    <option value="Mempengaruhi">Mempengaruhi</option>
                                                    <option value="Berbicara memberi tanda">Berbicara memberi tanda</option>
                                                    <option value="Melayani orang">Melayani orang</option>
                                                    <option value="Menerima instruksi">Menerima instruksi</option>
                                                </select> <br>
                                                <label for="berhubungan_orang">Fungsi Pekerjaan yang berhubungan dengan Orang:</label>
                                                <select class="form-control" id="berhubungan_orang" name="berhubungan_orang" required>
                                                    <option value="">Pilih pekerjaan</option>
                                                    <option value="Memasang mesin">Memasang mesin</option>
                                                    <option value="Mengerjakan persisi">Mengerjakan persisi</option>
                                                    <option value="Menjalankan mengontrol mesin">Menjalankan mengontrol mesin</option>
                                                    <option value="Mengemudikan/menjalankan mesin">Mengemudikan/menjalankan mesin</option>
                                                    <option value="Mengerjakan benda dengan tangan atau perkakas">Mengerjakan benda dengan tangan atau perkakas</option>
                                                    <option value="Melayani mesin">Melayani mesin</option>
                                                    <option value="Memasukkan, mengeluarkan barang ke/dari mesin">Memasukkan, mengeluarkan barang ke/dari mesin</option>
                                                    <option value="Memegang">Memegang</option>
                                                    <option value="Memadukan data">Memadukan data</option>
                                                    <option value="Mengkoordinasi data">Mengkoordinasi data</option>
                                                    <option value="Menganalisis data">Menganalisis data</option>
                                                    <option value="Menyusun data">Menyusun data</option>
                                                    <option value="Menghitung data">Menghitung data</option>
                                                    <option value="Menyalin data">Menyalin data</option>
                                                    <option value="Membandingkan data">Membandingkan data</option>
                                                    <option value="Menasehati">Menasehati</option>
                                                    <option value="Berunding">Berunding</option>
                                                    <option value="Mengajar">Mengajar</option>
                                                    <option value="Menyelia">Menyelia</option>
                                                    <option value="Menghibur">Menghibur</option>
                                                    <option value="Mempengaruhi">Mempengaruhi</option>
                                                    <option value="Berbicara memberi tanda">Berbicara memberi tanda</option>
                                                    <option value="Melayani orang">Melayani orang</option>
                                                    <option value="Menerima instruksi">Menerima instruksi</option>
                                                </select> <br>
                                                <label for="berhubungan_benda">Fungsi Pekerjaan yang berhubungan dengan Benda:</label>
                                                <select class="form-control" id="berhubungan_benda" name="berhubungan_benda" required>
                                                    <option value="">Pilih pekerjaan</option>
                                                    <option value="Memasang mesin">Memasang mesin</option>
                                                    <option value="Mengerjakan persisi">Mengerjakan persisi</option>
                                                    <option value="Menjalankan mengontrol mesin">Menjalankan mengontrol mesin</option>
                                                    <option value="Mengemudikan/menjalankan mesin">Mengemudikan/menjalankan mesin</option>
                                                    <option value="Mengerjakan benda dengan tangan atau perkakas">Mengerjakan benda dengan tangan atau perkakas</option>
                                                    <option value="Melayani mesin">Melayani mesin</option>
                                                    <option value="Memasukkan, mengeluarkan barang ke/dari mesin">Memasukkan, mengeluarkan barang ke/dari mesin</option>
                                                    <option value="Memegang">Memegang</option>
                                                    <option value="Memadukan data">Memadukan data</option>
                                                    <option value="Mengkoordinasi data">Mengkoordinasi data</option>
                                                    <option value="Menganalisis data">Menganalisis data</option>
                                                    <option value="Menyusun data">Menyusun data</option>
                                                    <option value="Menghitung data">Menghitung data</option>
                                                    <option value="Menyalin data">Menyalin data</option>
                                                    <option value="Membandingkan data">Membandingkan data</option>
                                                    <option value="Menasehati">Menasehati</option>
                                                    <option value="Berunding">Berunding</option>
                                                    <option value="Mengajar">Mengajar</option>
                                                    <option value="Menyelia">Menyelia</option>
                                                    <option value="Menghibur">Menghibur</option>
                                                    <option value="Mempengaruhi">Mempengaruhi</option>
                                                    <option value="Berbicara memberi tanda">Berbicara memberi tanda</option>
                                                    <option value="Melayani orang">Melayani orang</option>
                                                    <option value="Menerima instruksi">Menerima instruksi</option>
                                                </select> <br>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FUNGSI PEKERJAAN END -->
                                    <!-- SYARAT JABATAN LAIN END -->

                                    <!-- PRESTASI YANG DIHARAPKAN -->
                                    <div class="card-header">
                                        <h4>Prestasi yang Diharapkan</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-row"> <!-- Gunakan form-row untuk mendefinisikan baris -->
                                                <div class="col-md-12 px-3">
                                                    <div class="form-group row">
                                                        <label for="prestasi">Prestasi yang diharapkan:</label>
                                                        <textarea class="form-control" id="prestasi" name="prestasi" rows="3" placeholder="Masukkan prestasi yang diharapkan"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <br>
                                    <!-- PRESTASI YANG DIHARAPKAN END -->

                                    <!-- KELAS JABATAN -->
                                    <div class="card-header">
                                        <h4>Kelas Jabatan</h4>
                                    </div> <br>
                                    <div>
                                        <div id="form-container9">
                                            <div class="form-row"> <!-- Gunakan form-row untuk mendefinisikan baris -->
                                                <div class="col-md-12 px-3">
                                                    <div class="form-row">
                                                        <label for="kelas_jabatan">Kelas jabatan:</label>
                                                        <textarea class="form-control" id="kelas_jabatan" name="kelas_jabatan" rows="3" placeholder="Masukkan kelas jabatan"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <br>
                                    <!-- KELAS JABATAN END -->
<!-- TOMBOL SUBMIT -->
<?php
if (isset($_GET['edit'])) {
    echo "<button type='submit' class='btn btn-primary form-control' name='btnProses' value='edit'>Edit Data</button>";
} else {
    echo "<button type='submit' class='btn btn-primary form-control' name='btnProses' value='simpan'>Simpan Data</button>";
}
?>
</form>
</div>
</div>
</div>

<!-- Core JS Files -->
<script src="assets/js/core/jquery-3.7.1.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/kaiadmin.min.js"></script>
<!-- Modal Notifikasi Berhasil -->
<div class="modal fade success-modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content animate__animated animate__fadeInDown">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Sukses!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="success-icon animate__animated animate__bounceIn animate__delay-1s">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                    </svg>
                </div>
                <h4 class="success-message">Data berhasil disimpan!</h4>
                <p class="success-detail">Data Anda telah berhasil disimpan ke database</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success-custom animate__animated animate__pulse animate__infinite" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successMessages = <?php echo json_encode(isset($_SESSION['success_messages']) ? $_SESSION['success_messages'] : []); ?>;
        
        if (successMessages.length > 0) {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            <?php
            // Hapus session success_messages setelah ditampilkan
            unset($_SESSION['success_messages']);
            ?>
        }
    });
</script>
<style>
        .success-modal .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .success-modal .modal-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            border-bottom: none;
            padding: 1.5rem;
        }
        
        .success-modal .modal-title {
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .success-modal .modal-body {
            padding: 2.5rem 1.5rem;
        }
        
        .success-modal .modal-footer {
            border-top: none;
            padding: 1rem 1.5rem 1.5rem;
        }
        
        .success-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background-color: rgba(40, 167, 69, 0.1);
            border-radius: 50%;
            margin-bottom: 1.5rem;
        }
        
        .success-icon svg {
            width: 50px;
            height: 50px;
            color: #28a745;
        }
        
        .success-message {
            color: #333;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .success-detail {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 0;
        }
        
        .btn-success-custom {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 50px;
            padding: 0.6rem 2rem;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-success-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
        }
        
        .modal-backdrop.show {
            opacity: 0.7;
        }
    </style>


</body>
</html>
