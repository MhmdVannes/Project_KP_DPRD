<?php
include 'session_check.php';
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user']; // Ambil ID user dari session

// Initialize success messages array only if it doesn't exist
if (!isset($_SESSION['success_messages'])) {
    $_SESSION['success_messages'] = [];
}

// Rest of your code...
// Proses tb_input_jabatan
if (isset($_POST['btnProses']) && $_POST['btnProses'] == "simpan") {
    $nama_jabatan = mysqli_real_escape_string($conn, $_POST['nama_jabatan']);
    $unit_kerja1 = mysqli_real_escape_string($conn, $_POST['unit_kerja1']);
    $unit_kerja = mysqli_real_escape_string($conn, $_POST['unit_kerja']);
    $ikhtisar_jabatan = mysqli_real_escape_string($conn, $_POST['ikhtisar_jabatan']);

    $query = "INSERT INTO tb_input_jabatan (nama_jabatan, unit_kerja1, unit_kerja, ikhtisar_jabatan, id_user) 
              VALUES ('$nama_jabatan', '$unit_kerja1', '$unit_kerja', '$ikhtisar_jabatan', '$id_user')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_messages'][] = "Data Jabatan berhasil ditambahkan!";
    }
}

// Proses tb_syarat_jabatan
if (isset($_POST['btnProses']) && $_POST['btnProses'] == "simpan") {
    $pangkat = mysqli_real_escape_string($conn, $_POST['pangkat']);
    $pendidikan = mysqli_real_escape_string($conn, $_POST['pendidikan']);
    $jenjang = mysqli_real_escape_string($conn, $_POST['jenjang']);
    $teknis = mysqli_real_escape_string($conn, $_POST['teknis']);
    $fungsional = mysqli_real_escape_string($conn, $_POST['fungsional']);
    $pengalaman_kerja = mysqli_real_escape_string($conn, $_POST['pengalaman_kerja']);

    $query = "INSERT INTO tb_syarat_jabatan (pangkat, pendidikan, jenjang, teknis, fungsional, pengalaman_kerja, id_user) 
              VALUES ('$pangkat', '$pendidikan', '$jenjang', '$teknis', '$fungsional', '$pengalaman_kerja', '$id_user')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_messages'][] = "Data Syarat Jabatan berhasil ditambahkan!";
    }
}
// Proses tb_tugas_pokok
if (isset($_POST['btnProses']) && $_POST['btnProses'] == "simpan") {
    if (
        !empty($_POST['uraian_tugas']) && !empty($_POST['hasil_krj']) &&
        !empty($_POST['jumlah_beban']) && !empty($_POST['waktu_penyelesaian']) &&
        !empty($_POST['waktu_efektif']) && !empty($_POST['kebutuhan_pegawai'])
    ) {
        foreach ($_POST['uraian_tugas'] as $key => $uraian) {
            if (
                !empty($uraian) && !empty($_POST['hasil_krj'][$key]) &&
                !empty($_POST['jumlah_beban'][$key]) && !empty($_POST['waktu_penyelesaian'][$key]) &&
                !empty($_POST['waktu_efektif'][$key]) && !empty($_POST['kebutuhan_pegawai'][$key])
            ) {
                $uraian_tugas = mysqli_real_escape_string($conn, $uraian);
                $hasil_krj = mysqli_real_escape_string($conn, $_POST['hasil_krj'][$key]);
                $jumlah_beban = mysqli_real_escape_string($conn, $_POST['jumlah_beban'][$key]);
                $waktu_penyelesaian = mysqli_real_escape_string($conn, $_POST['waktu_penyelesaian'][$key]);
                $waktu_efektif = mysqli_real_escape_string($conn, $_POST['waktu_efektif'][$key]);
                $kebutuhan_pegawai = mysqli_real_escape_string($conn, $_POST['kebutuhan_pegawai'][$key]);

                $query = "INSERT INTO tb_tugas_pokok (uraian_tugas, hasil_krj, jumlah_beban, waktu_penyelesaian, waktu_efektif, kebutuhan_pegawai, id_user) 
                          VALUES ('$uraian_tugas', '$hasil_krj', '$jumlah_beban', '$waktu_penyelesaian', '$waktu_efektif', '$kebutuhan_pegawai', '$id_user')";
                
                if (mysqli_query($conn, $query)) {
                    $_SESSION['success_messages'][] = "Tugas pokok berhasil ditambahkan!";
                }
            }
        }
    }
}

// Proses tb_hasil_kerja
if (isset($_POST['btnProses']) && $_POST['btnProses'] == "simpan") {
    if (!empty($_POST['hasil_kerja'])) {
        foreach ($_POST['hasil_kerja'] as $hasil) {
            $hasil_kerja = mysqli_real_escape_string($conn, $hasil);
            $query = "INSERT INTO tb_hasil_kerja (hasil_kerja, id_user) VALUES ('$hasil_kerja', '$id_user')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['success_messages'][] = "Hasil kerja berhasil ditambahkan!";
            }
        }
    }
}
// Proses tb_bahan_kerja
if (isset($_POST['btnProses']) && $_POST['btnProses'] == "simpan") {
    if (!empty($_POST['bahan_kerja']) && !empty($_POST['penggunaan_tugas1'])) {
        foreach ($_POST['bahan_kerja'] as $key => $bahan) {
            if (!empty($bahan) && !empty($_POST['penggunaan_tugas1'][$key])) {
                $bahan_kerja = mysqli_real_escape_string($conn, $bahan);
                $penggunaan_tugas1 = mysqli_real_escape_string($conn, $_POST['penggunaan_tugas1'][$key]);

                $query = "INSERT INTO tb_bahan_kerja (bahan_kerja, penggunaan_tugas1, id_user) 
                          VALUES ('$bahan_kerja', '$penggunaan_tugas1', '$id_user')";

                if (mysqli_query($conn, $query)) {
                    $_SESSION['success_messages'][] = "Bahan kerja berhasil ditambahkan!";
                }
            }
        }
    }
}

// Proses tb_perangkat_kerja
if (!empty($_POST['perangkat_kerja']) && !empty($_POST['penggunaan_tugas2'])) {
    foreach ($_POST['perangkat_kerja'] as $key => $perangkat) {
        if (!empty($perangkat) && !empty($_POST['penggunaan_tugas2'][$key])) {
            $perangkat_kerja = mysqli_real_escape_string($conn, $perangkat);
            $penggunaan_tugas2 = mysqli_real_escape_string($conn, $_POST['penggunaan_tugas2'][$key]);

            $query = "INSERT INTO tb_perangkat_kerja (perangkat_kerja, penggunaan_tugas2, id_user) 
                      VALUES ('$perangkat_kerja', '$penggunaan_tugas2', '$id_user')";

            if (mysqli_query($conn, $query)) {
                $_SESSION['success_messages'][] = "Perangkat kerja berhasil ditambahkan!";
            }
        }
    }
}

// Proses tb_tanggung_jawab
if (!empty($_POST['tanggung_jawab'])) {
    foreach ($_POST['tanggung_jawab'] as $tanggungjawab) {
        $tanggung_jawab = mysqli_real_escape_string($conn, $tanggungjawab);

        $query = "INSERT INTO tb_tanggung_jawab (tanggung_jawab, id_user) 
                  VALUES ('$tanggung_jawab', '$id_user')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['success_messages'][] = "Tanggung jawab berhasil ditambahkan!";
        }
    }
}

// Proses tb_wewenang
if (!empty($_POST['wewenang'])) {
    foreach ($_POST['wewenang'] as $wewenang) {
        $wewenang = mysqli_real_escape_string($conn, $wewenang);

        $query = "INSERT INTO tb_wewenang (wewenang, id_user) 
                  VALUES ('$wewenang', '$id_user')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['success_messages'][] = "Wewenang berhasil ditambahkan!";
        }
    }
}

// Proses tb_kolerasi_jabatan
if (!empty($_POST['jabatan']) && !empty($_POST['instansi']) && !empty($_POST['dalam_hal'])) {
    foreach ($_POST['jabatan'] as $key => $jbtn) {
        if (!empty($jbtn) && !empty($_POST['instansi'][$key]) && !empty($_POST['dalam_hal'][$key])) {
            $jabatan = mysqli_real_escape_string($conn, $jbtn);
            $instansi = mysqli_real_escape_string($conn, $_POST['instansi'][$key]);
            $dalam_hal = mysqli_real_escape_string($conn, $_POST['dalam_hal'][$key]);

            $query = "INSERT INTO tb_kolerasi_jabatan (jabatan, instansi, dalam_hal, id_user) 
                      VALUES ('$jabatan', '$instansi', '$dalam_hal', '$id_user')";

            if (mysqli_query($conn, $query)) {
                $_SESSION['success_messages'][] = "Kolerasi jabatan berhasil ditambahkan!";
            }
        }
    }
}

// Proses tb_kondisi_lingkungan_kerja
if (isset($_POST['btnProses']) && $_POST['btnProses'] == "simpan") {
    $tempat_kerja = mysqli_real_escape_string($conn, $_POST['tempat_kerja']);
    $suhu = mysqli_real_escape_string($conn, $_POST['suhu']);
    $udara = mysqli_real_escape_string($conn, $_POST['udara']);
    $keadaan_ruangan = mysqli_real_escape_string($conn, $_POST['keadaan_ruangan']);
    $letak = mysqli_real_escape_string($conn, $_POST['letak']);
    $penerangan = mysqli_real_escape_string($conn, $_POST['penerangan']);
    $suara = mysqli_real_escape_string($conn, $_POST['suara']);
    $keadaan_tempat_kerja = mysqli_real_escape_string($conn, $_POST['keadaan_tempat_kerja']);
    $getaran = mysqli_real_escape_string($conn, $_POST['getaran']);

    $query = "INSERT INTO tb_kondisi_lingkungan_kerja (tempat_kerja, suhu, udara, keadaan_ruangan, letak, penerangan, suara, keadaan_tempat_kerja, getaran, id_user) 
              VALUES ('$tempat_kerja', '$suhu', '$udara', '$keadaan_ruangan', '$letak', '$penerangan', '$suara', '$keadaan_tempat_kerja', '$getaran', '$id_user')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_messages'][] = "Data kondisi lingkungan kerja berhasil ditambahkan!";
    }
}

// Proses tb_risiko_bahaya
if (!empty($_POST['fisik_mental']) && !empty($_POST['penyebab'])) {
    foreach ($_POST['fisik_mental'] as $key => $fisik) {
        if (!empty($fisik) && !empty($_POST['penyebab'][$key])) {
            $fisik_mental = mysqli_real_escape_string($conn, $fisik);
            $penyebab = mysqli_real_escape_string($conn, $_POST['penyebab'][$key]);

            $query = "INSERT INTO tb_risiko_bahaya (fisik_mental, penyebab, id_user) 
                      VALUES ('$fisik_mental', '$penyebab', '$id_user')";

            if (mysqli_query($conn, $query)) {
                $_SESSION['success_messages'][] = "Data risiko bahaya berhasil ditambahkan!";
            }
        }
    }
}

// Proses tb_prestasi
if (!empty($_POST['prestasi'])) {
    $prestasi = mysqli_real_escape_string($conn, $_POST['prestasi']);
    $query = "INSERT INTO tb_prestasi (prestasi, id_user) VALUES ('$prestasi', '$id_user')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_messages'][] = "Data prestasi berhasil ditambahkan!";
    }
}

// Proses tb_kelas_jabatan
if (!empty($_POST['kelas_jabatan'])) {
    $kelas_jabatan = mysqli_real_escape_string($conn, $_POST['kelas_jabatan']);
    $query = "INSERT INTO tb_kelas_jabatan (kelas_jabatan, id_user) VALUES ('$kelas_jabatan', '$id_user')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_messages'][] = "Data kelas jabatan berhasil ditambahkan!";
    }
}

// Proses tb_syarat_jabatan_lain
if (!empty($_POST['keterampilan_kerja']) && !empty($_POST['bakat_kerja']) && !empty($_POST['temperamen_kerja']) && !empty($_POST['minat_kerja'])) {
    $keterampilan_kerja = mysqli_real_escape_string($conn, $_POST['keterampilan_kerja']);
    $bakat_kerja = mysqli_real_escape_string($conn, $_POST['bakat_kerja']);
    $temperamen_kerja = mysqli_real_escape_string($conn, $_POST['temperamen_kerja']);
    $minat_kerja = mysqli_real_escape_string($conn, $_POST['minat_kerja']);

    // Ambil nilai checkbox aktivitas (array) dan gabungkan dengan koma
    $aktivitas = isset($_POST['aktivitas']) ? implode(", ", $_POST['aktivitas']) : "";

    $query = "INSERT INTO tb_syarat_jabatan_lain (id_user, keterampilan_kerja, bakat_kerja, temperamen_kerja, minat_kerja, aktivitas) 
              VALUES ('$id_user', '$keterampilan_kerja', '$bakat_kerja', '$temperamen_kerja', '$minat_kerja', '$aktivitas')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_messages'][] = "Data syarat jabatan lain berhasil ditambahkan!";
    }
}

// Proses tb_kondisi_fisik
if (!empty($_POST['jenis_kelamin']) && !empty($_POST['umur_maksimal'])) {
    $jenis_kelamin  = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $umur_maksimal  = mysqli_real_escape_string($conn, $_POST['umur_maksimal']);
    $tinggi_badan   = mysqli_real_escape_string($conn, $_POST['tinggi_badan']);
    $berat_badan    = mysqli_real_escape_string($conn, $_POST['berat_badan']);
    $postur_badan   = mysqli_real_escape_string($conn, $_POST['postur_badan']);
    $penampilan     = mysqli_real_escape_string($conn, $_POST['penampilan']);
    $keadaan_fisik  = mysqli_real_escape_string($conn, $_POST['keadaan_fisik']);

    $query = "INSERT INTO tb_kondisi_fisik (id_user, jenis_kelamin, umur_maksimal, tinggi_badan, berat_badan, postur_badan, penampilan, keadaan_fisik) 
              VALUES ('$id_user', '$jenis_kelamin', '$umur_maksimal', '$tinggi_badan', '$berat_badan', '$postur_badan', '$penampilan', '$keadaan_fisik')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_messages'][] = "Data kondisi fisik berhasil ditambahkan!";
    }
}

// Proses tb_fungsi_pekerjaan
if (!empty($_POST['berhubungan_data']) && !empty($_POST['berhubungan_orang']) && !empty($_POST['berhubungan_benda'])) {
    $berhubungan_data  = mysqli_real_escape_string($conn, $_POST['berhubungan_data']);
    $berhubungan_orang = mysqli_real_escape_string($conn, $_POST['berhubungan_orang']);
    $berhubungan_benda = mysqli_real_escape_string($conn, $_POST['berhubungan_benda']);

    $query = "INSERT INTO tb_fungsi_pekerjaan (id_user, berhubungan_data, berhubungan_orang, berhubungan_benda) 
              VALUES ('$id_user', '$berhubungan_data', '$berhubungan_orang', '$berhubungan_benda')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_messages'][] = "Data fungsi pekerjaan berhasil ditambahkan!";
    }
}

// Redirect kembali ke halaman input
header("Location: input_data.php");
exit();
?>
