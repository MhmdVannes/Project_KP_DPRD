<?php
require 'vendor/autoload.php'; 

include 'session_check.php';
// Load Dompdf dari Composer

use Dompdf\Dompdf;
use Dompdf\Options;

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "dprdproject");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID user dari parameter URL
$id_user = $_GET['id_user'] ?? 1;

// Query untuk data jabatan
$query_jabatan = "SELECT ij.nama_jabatan, ij.unit_kerja, ij.ikhtisar_jabatan, ij.unit_kerja1, ij.kode_jabatan,
                         sj.pangkat, sj.pendidikan, sj.jenjang, sj.teknis, sj.fungsional, sj.pengalaman_kerja
                  FROM tb_input_jabatan ij
                  LEFT JOIN tb_syarat_jabatan sj ON ij.id_user = sj.id_user
                  WHERE ij.id_user = ?";
$stmt_jabatan = $conn->prepare($query_jabatan);
$stmt_jabatan->bind_param("i", $id_user);
$stmt_jabatan->execute();
$result_jabatan = $stmt_jabatan->get_result();
$data_jabatan = $result_jabatan->fetch_assoc();

// Query untuk tugas pokok
$query_tugas = "SELECT uraian_tugas, hasil_krj, jumlah_beban, waktu_penyelesaian, waktu_efektif, kebutuhan_pegawai 
                FROM tb_tugas_pokok WHERE id_user = ?";
$stmt_tugas = $conn->prepare($query_tugas);
$stmt_tugas->bind_param("i", $id_user);
$stmt_tugas->execute();
$result_tugas = $stmt_tugas->get_result();

// Query untuk hasil kerja
$query_hasil_kerja = "SELECT hasil_kerja FROM tb_hasil_kerja WHERE id_user = ?";
$stmt_hasil_kerja = $conn->prepare($query_hasil_kerja);
$stmt_hasil_kerja->bind_param("i", $id_user);
$stmt_hasil_kerja->execute();
$result_hasil_kerja = $stmt_hasil_kerja->get_result();

// Query untuk mengambil data bahan kerja
$query_bahan_kerja = "SELECT bahan_kerja, penggunaan_tugas1 FROM tb_bahan_kerja WHERE id_user = ?";
$stmt_bahan_kerja = $conn->prepare($query_bahan_kerja);
$stmt_bahan_kerja->bind_param("i", $id_user);
$stmt_bahan_kerja->execute();
$result_bahan_kerja = $stmt_bahan_kerja->get_result();

// Query untuk mengambil data perangkat kerja
$query_perangkat_kerja = "SELECT perangkat_kerja, penggunaan_tugas2 FROM tb_perangkat_kerja WHERE id_user = ?";
$stmt_perangkat_kerja = $conn->prepare($query_perangkat_kerja);
$stmt_perangkat_kerja->bind_param("i", $id_user);
$stmt_perangkat_kerja->execute();
$result_perangkat_kerja = $stmt_perangkat_kerja->get_result();

// Query untuk mengambil data tanggung jawab
$query_tanggung_jawab = "SELECT tanggung_jawab FROM tb_tanggung_jawab WHERE id_user = ?";
$stmt_tanggung_jawab = $conn->prepare($query_tanggung_jawab);
$stmt_tanggung_jawab->bind_param("i", $id_user);
$stmt_tanggung_jawab->execute();
$result_tanggung_jawab = $stmt_tanggung_jawab->get_result();

// Query untuk mengambil data wewenang
$query_wewenang = "SELECT wewenang FROM tb_wewenang WHERE id_user = ?";
$stmt_wewenang = $conn->prepare($query_wewenang);
$stmt_wewenang->bind_param("i", $id_user);
$stmt_wewenang->execute();
$result_wewenang = $stmt_wewenang->get_result();

// Query untuk mengambil data korelasi jabatan
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
$ah = $result_lingkungan->fetch_assoc(); // Ambil data

// Query untuk mengambil data dari tb_risiko_bahaya
$query_risiko = "SELECT fisik_mental, penyebab FROM tb_risiko_bahaya WHERE id_user = ?";
$stmt_risiko = $conn->prepare($query_risiko);
$stmt_risiko->bind_param("i", $id_user);
$stmt_risiko->execute();
$result_risiko = $stmt_risiko->get_result();

// Query untuk mengambil data dari tb_prestasi
$query_prestasi = "SELECT prestasi FROM tb_prestasi WHERE id_user = ?";
$stmt_prestasi = $conn->prepare($query_prestasi);
$stmt_prestasi->bind_param("i", $id_user);
$stmt_prestasi->execute();
$result_prestasi = $stmt_prestasi->get_result();

// Query untuk mengambil data dari tb_kelas_jabatan
$query_kelas_jabatan = "SELECT kelas_jabatan FROM tb_kelas_jabatan WHERE id_user = ?";
$stmt_kelas_jabatan = $conn->prepare($query_kelas_jabatan);
$stmt_kelas_jabatan->bind_param("i", $id_user);
$stmt_kelas_jabatan->execute();
$result_kelas_jabatan = $stmt_kelas_jabatan->get_result();

$query_user = "SELECT nip, nama FROM tb_user WHERE id_user = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("i", $id_user);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

$query_syarat_jabatan_lain = "SELECT keterampilan_kerja, bakat_kerja, temperamen_kerja, minat_kerja, aktivitas FROM tb_syarat_jabatan_lain WHERE id_user = ?";
$stmt_syarat_jabatan_lain = $conn->prepare($query_syarat_jabatan_lain);
$stmt_syarat_jabatan_lain->bind_param("i", $id_user);
$stmt_syarat_jabatan_lain->execute();
$result_syarat_jabatan_lain = $stmt_syarat_jabatan_lain->get_result();
$row = $result_syarat_jabatan_lain->fetch_assoc();

$query_kondisi_fisik = "SELECT jenis_kelamin, umur_maksimal, tinggi_badan, berat_badan, postur_badan, penampilan, keadaan_fisik FROM tb_kondisi_fisik WHERE id_user = ?";
$stmt_kondisi_fisik = $conn->prepare($query_kondisi_fisik);
$stmt_kondisi_fisik->bind_param("i", $id_user);
$stmt_kondisi_fisik->execute();
$result_kondisi_fisik = $stmt_kondisi_fisik->get_result();
$row = $result_kondisi_fisik->fetch_assoc();

$query_fungsi_pekerjaan = "SELECT berhubungan_data, berhubungan_orang, berhubungan_benda FROM tb_fungsi_pekerjaan WHERE id_user = ?";
$stmt_fungsi_pekerjaan = $conn->prepare($query_fungsi_pekerjaan);
$stmt_fungsi_pekerjaan->bind_param("i", $id_user);
$stmt_fungsi_pekerjaan->execute();
$result_fungsi_pekerjaan = $stmt_fungsi_pekerjaan->get_result();

$html = '
        <h3>INFORMASI JABATAN PEGAWAI DPRD PROVINSI SULAWESI TENGGARA</h3>
<table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <td style="background-color: #f2f2f2; width: 10%;"><b>Nama</b></td>
                <td>' . htmlspecialchars($user['nama'] ?? 'Tidak ada data') . '</td>
            </tr>
            <tr>
                <td style="background-color: #f2f2f2;"><b>NIP</b></td>
                <td>' . htmlspecialchars($user['nip'] ?? 'Tidak ada data') . '</td>
            </tr>
        </table> <br>';

// Buat tampilan HTML untuk PDF
$html .= '<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid black; padding: 8px; text-align: left; }
    h3 { text-align: center; }
</style>';

$html .= '
<table>
    <tbody>
        <tr>
            <th colspan="2" style="background-color: #f2f2f2;">A. Nama Jabatan</th>
            <td>' . htmlspecialchars($data_jabatan['nama_jabatan']) . '</td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #f2f2f2;">B. Kode Jabatan</th>
            <td>' . htmlspecialchars($data_jabatan['kode_jabatan']) . '</td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #f2f2f2;">C. Unit Kerja</th>
            <td>' . htmlspecialchars($data_jabatan['unit_kerja']) . '</td>
        </tr>
        <tr>
            <td colspan="2">Unit</td>
            <td>' . htmlspecialchars($data_jabatan['unit_kerja1']) . '</td>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #f2f2f2;">D. Ikhtisar Jabatan</th>
            <td>' . nl2br(htmlspecialchars($data_jabatan['ikhtisar_jabatan'])) . '</td>
        </tr>
        <tr>
            <th colspan="3" style="background-color: #f2f2f2;">E. Syarat Jabatan</th>
        </tr>
        <tr>
            <td colspan="2">a. Pangkat/Gol. Ruang</td>
            <td>' . htmlspecialchars($data_jabatan['pangkat']) . '</td>
        </tr>
        <tr>
            <td colspan="2">b. Pendidikan Formal</td>
            <td>' . htmlspecialchars($data_jabatan['pendidikan']) . '</td>
        </tr>
        <tr>
            <td rowspan="3">c. Diklat</td>
            <td>Penjenjangan</td>
            <td>' . htmlspecialchars($data_jabatan['jenjang']) . '</td>
        </tr>
        <tr>
            <td>Teknis</td>
            <td>' . htmlspecialchars($data_jabatan['teknis']) . '</td>
        </tr>
        <tr>
            <td>Fungsional</td>
            <td>' . htmlspecialchars($data_jabatan['fungsional']) . '</td>
        </tr>
        <tr>
            <td colspan="2">d. Pengalaman Kerja</td>
            <td>' . nl2br(htmlspecialchars($data_jabatan['pengalaman_kerja'])) . '</td>
        </tr>
    </tbody>
</table> <br>';

$html .=
    '<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid black; padding: 8px; text-align: lift; }
</style>

<table>
    <thead>
        <tr>
            <th colspan="7" style="background-color: #f2f2f2;">F. Tugas Pokok</th>
        </tr>
        <tr>
            <th style="width: 5%;">No.</th>
            <th style="width: 25%;">Uraian Tugas</th>
            <th style="width: 20%;">Hasil Kerja</th>
            <th style="width: 10%;">Jumlah Beban Kerja 1 Tahun</th>
            <th style="width: 10%;">Waktu Penyelesaian (Jam)</th>
            <th style="width: 10%;">Waktu Efektif Penyelesaian</th>
            <th style="width: 10%;">Kebutuhan Pegawai</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($row = $result_tugas->fetch_assoc()) {
    $html .= '<tr>
                <td>' . $no . '</td>
                <td style="text-align: left;">' . htmlspecialchars($row['uraian_tugas']) . '</td>
                <td style="text-align: left;">' . htmlspecialchars($row['hasil_krj']) . '</td>
                <td>' . htmlspecialchars($row['jumlah_beban']) . '</td>
                <td>' . htmlspecialchars($row['waktu_penyelesaian']) . '</td>
                <td>' . htmlspecialchars($row['waktu_efektif']) . '</td>
                <td>' . htmlspecialchars($row['kebutuhan_pegawai']) . '</td>
              </tr>';
    $no++;
}

$html .= '</tbody></table> <br>';

$html .= '
<style>
    table { width: 100%; border-collapse: collapse; border: 1px solid black; }
    th, td { border: 1px solid black; padding: 8px; text-align: left; }
    h2 { text-align: left; }
</style>

<table>
    <thead>
        <tr>
            <th colspan="2" style="background-color: #f2f2f2;">G. Hasil Kerja</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($row = $result_hasil_kerja->fetch_assoc()) {
    $html .= '<tr>
                <td style="width: 5%;  text-align: center;">' . $no . '.</td>
                <td>' . htmlspecialchars($row['hasil_kerja']) . '</td>
              </tr>';
    $no++;
}

$html .= '</tbody></table> <br>';

$html .= '
<style>
    table { width: 100%; border-collapse: collapse; border: 1px solid black; }
    th, td { border: 1px solid black; padding: 8px; text-align: left; }
</style>

<table>
    <thead>
        <tr>
            <th colspan="3" style="background-color: #f2f2f2;">H. BAHAN KERJA :</th>
        </tr>
        <tr>
            <th style="width: 5%; text-align: center;">No.</th>
            <th style="width: 45%;">BAHAN KERJA</th>
            <th style="width: 50%;">PENGGUNAAN DALAM TUGAS</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($row = $result_bahan_kerja->fetch_assoc()) {
    $html .= '<tr>
                <td style="text-align: center;">' . $no . '.</td>
                <td>' . htmlspecialchars($row['bahan_kerja']) . '</td>
                <td>' . htmlspecialchars($row['penggunaan_tugas1']) . '</td>
              </tr>';
    $no++;
}

$html .= '</tbody></table> <br>';

$html .= '
<style>
    table { width: 100%; border-collapse: collapse; border: 1px solid black; }
    th, td { border: 1px solid black; padding: 8px; text-align: left; }
</style>

<table>
    <thead>
        <tr>
            <th colspan="3" style="background-color: #f2f2f2;">I. PERANGKAT KERJA :</th>
        </tr>
        <tr>
            <th style="width: 5%; text-align: center;">No.</th>
            <th style="width: 45%;">PERANGKAT KERJA</th>
            <th style="width: 50%;">PENGGUNAAN DALAM TUGAS</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($row = $result_perangkat_kerja->fetch_assoc()) {
    $html .= '<tr>
                <td style="text-align: center;">' . $no . '.</td>
                <td>' . htmlspecialchars($row['perangkat_kerja']) . '</td>
                <td>' . htmlspecialchars($row['penggunaan_tugas2']) . '</td>
              </tr>';
    $no++;
}

$html .= '</tbody></table> <br>';

$html .= '
<style>
    table { width: 100%; border-collapse: collapse; border: 1px solid black; }
    th, td { border: 1px solid black; padding: 8px; text-align: left; }
</style>

<table>
    <thead>
        <tr>
            <th colspan="2" style="background-color: #f2f2f2;">J. TANGGUNG JAWAB</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($row = $result_tanggung_jawab->fetch_assoc()) {
    $html .= '<tr>
                <td style="text-align: center; width: 5%;">' . $no . '.</td>
                <td>' . htmlspecialchars($row['tanggung_jawab']) . '</td>
              </tr>';
    $no++;
}

$html .= '</tbody></table> <br>';

$html .= '
<style>
    table { width: 100%; border-collapse: collapse; border: 1px solid black; }
    th, td { border: 1px solid black; padding: 8px; text-align: left; }
</style>

<table>
    <thead>
        <tr>
            <th colspan="2" style="background-color: #f2f2f2;">K. WEWENANG</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($row = $result_wewenang->fetch_assoc()) {
    $html .= '<tr>
                <td style="text-align: center; width: 5%;">' . $no . '.</td>
                <td>' . htmlspecialchars($row['wewenang']) . '</td>
              </tr>';
    $no++;
}

$html .= '</tbody></table> <br>';

$html .= '
<style>
    table { width: 100%; border-collapse: collapse; border: 1px solid black; }
    th, td { border: 1px solid black; padding: 8px; text-align: left; }
</style>

<table>
    <thead>
        <tr>
            <th colspan="4" style="background-color: #f2f2f2;">L. KORELASI JABATAN</th>
        </tr>
        <tr>
            <th style="width: 5%;">No.</th>
            <th style="width: 30%;">JABATAN</th>
            <th style="width: 35%;">UNIT KERJA/ INSTANSI</th>
            <th style="width: 30%;">DALAM HAL</th>
        </tr>
    </thead>
    <tbody>';

$no = 1;
while ($row = $result_korelasi->fetch_assoc()) {
    $html .= '<tr>
                <td style="text-align: center;">' . $no . '.</td>
                <td>' . htmlspecialchars($row['jabatan']) . '</td>
                <td>' . htmlspecialchars($row['instansi']) . '</td>
                <td>' . htmlspecialchars($row['dalam_hal']) . '</td>
              </tr>';
    $no++;
}

$html .= '</tbody></table> <br>';

$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr>
                <th colspan="3" style="background-color: #f2f2f2;"">M. KONDISI LINGKUNGAN KERJA :</th>
            </tr>
            <tr style="text-align: center;">
                <th width="10%" align="center"><b>No.</b></th>
                <th width="40%" align="center"><b>Aspek</b></th>
                <th width="50%" align="center"><b>Faktor</b></th>
            </tr>';

$aspek = ["Tempat kerja", "Suhu", "Udara", "Keadaan Ruangan", "Letak", "Penerangan", "Suara", "Keadaan tempat kerja", "Getaran"];
$faktor_keys = ["tempat_kerja", "suhu", "udara", "keadaan_ruangan", "letak", "penerangan", "suara", "keadaan_tempat_kerja", "getaran"];


for ($i = 0; $i < count($aspek); $i++) {
    $faktor_value = htmlspecialchars($ah[$faktor_keys[$i]] ?? '-'); // Gunakan $ah
    $html .= '<tr>
                <td align="center">' . ($i + 1) . '</td>
                <td>' . htmlspecialchars($aspek[$i]) . '</td>
                <td>' . $faktor_value . '</td>
              </tr>';
}

$html .= '</table> <br>';

$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr>
                <th colspan="3" align="left" style="font-size: 12pt; background-color: #f2f2f2;"><b>N. RISIKO BAHAYA :</b></th>
            </tr>
            <tr style="font-weight: bold;">
                <th width="10%" align="center"><b>No.</b></th>
                <th width="40%" align="center"><b>Fisik/Mental</b></th>
                <th width="50%" align="center"><b>Penyebab</b></th>
            </tr>';
$no = 1;
while ($row = $result_risiko->fetch_assoc()) {
    $html .= '<tr>
                <td align="center">' . $no . '</td>
                <td>' . htmlspecialchars($row['fisik_mental']) . '</td>
                <td>' . htmlspecialchars($row['penyebab']) . '</td>
              </tr>';
    $no++;
}

$html .= '</table> <br>';

$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr>
                <th colspan="3" align="left" style="font-size: 12pt; background-color: #f2f2f2;"><b>O. SYARAT JABATAN LAIN</b></th>
            </tr>
            <tr style="font-weight: bold;">
                <th width="33%" align="center"><b>Keterampilan Kerja</b></th>
                <th width="33%" align="center"><b>Bakat Kerja</b></th>
                <th width="33%" align="center"><b>Temperamen Kerja</b></th>
            </tr>';

if ($row = $result_syarat_jabatan_lain->fetch_assoc()) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['keterampilan_kerja'] ?? '-') . '</td>
                <td>' . htmlspecialchars($row['bakat_kerja'] ?? '-') . '</td>
                <td>' . htmlspecialchars($row['temperamen_kerja'] ?? '-') . '</td>
              </tr>';
}

$html .= '</table> <br>';

$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr style="font-weight: bold;">
                <th width="33%" align="center"><b>Minat Kerja</b></th>
                <th width="33%" align="center"><b>Upaya Fisik</b></th>
                <th width="33%" align="center"><b>Jenis Kelamin</b></th>
            </tr>';

$row1 = $result_syarat_jabatan_lain->fetch_assoc();
$row2 = $result_kondisi_fisik->fetch_assoc();

$html .= '<tr>
            <td>' . htmlspecialchars($row1['minat_kerja'] ?? '-') . '</td>
            <td>' . htmlspecialchars($row1['aktivitas'] ?? '-') . '</td>
            <td>' . htmlspecialchars($row2['jenis_kelamin'] ?? '-') . '</td>
          </tr>';

$html .= '</table> <br>';

$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr style="font-weight: bold;">
                <th width="33%" align="center"><b>Umur Maksimal</b></th>
                <th width="33%" align="center"><b>Tinggi Badan (cm)</b></th>
                <th width="33%" align="center"><b>Berat Badan (kg)</b></th>
            </tr>';


$html .= '<tr>
            <td align="center">' . htmlspecialchars($row2['umur_maksimal'] ?? '-') . '</td>
            <td align="center">' . htmlspecialchars($row2['tinggi_badan'] ?? '-') . '</td>
            <td align="center">' . htmlspecialchars($row2['berat_badan'] ?? '-') . '</td>
          </tr>';

$html .= '</table> <br>';

$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr style="font-weight: bold;">
                <th width="33%" align="center"><b>Postur Badan</b></th>
                <th width="33%" align="center"><b>Penampilan</b></th>
                <th width="33%" align="center"><b>Keadaan Fisik</b></th>
            </tr>';
$html .= '<tr>
            <td align="center">' . htmlspecialchars($row2['postur_badan'] ?? '-') . '</td>
            <td align="center">' . htmlspecialchars($row2['penampilan'] ?? '-') . '</td>
            <td align="center">' . htmlspecialchars($row2['keadaan_fisik'] ?? '-') . '</td>
          </tr>';

$html .= '</table> <br>';

$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr style="font-weight: bold;">
                <th width="33%" align="center"><b>Fungsi Pekerjaan yang berhubungan dengan Data</b></th>
                <th width="33%" align="center"><b>Fungsi Pekerjaan yang berhubungan dengan Orang</b></th>
                <th width="33%" align="center"><b>Fungsi Pekerjaan yang berhubungan dengan Benda</b></th>
            </tr>';

while ($yami = $result_fungsi_pekerjaan->fetch_assoc()) {
    $html .= '<tr>
                <td align="center">' . (!empty($yami['berhubungan_data']) ? htmlspecialchars($yami['berhubungan_data']) : '-') . '</td>
                <td align="center">' . (!empty($yami['berhubungan_orang']) ? htmlspecialchars($yami['berhubungan_orang']) : '-') . '</td>
                <td align="center">' . (!empty($yami['berhubungan_benda']) ? htmlspecialchars($yami['berhubungan_benda']) : '-') . '</td>
              </tr>';
}

$html .= '</table> <br>';


$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr>
                <th colspan="2" align="left" style="font-size: 12pt; background-color: #f2f2f2;"><b>P. PRESTASI YANG DIHARAPKAN</b></th>
            </tr>
            <tr style="font-weight: bold;">
                <th width="10%" align="center"><b>No.</b></th>
                <th width="90%" align="center"><b>Prestasi</b></th>
            </tr>';

$no = 1;
while ($row = $result_prestasi->fetch_assoc()) {
    $html .= '<tr>
                <td align="center">' . $no . '</td>
                <td>' . htmlspecialchars($row['prestasi']) . '</td>
              </tr>';
    $no++;
}

// Jika tidak ada data, tambahkan baris kosong
if ($no === 1) {
    $html .= '<tr>
                <td colspan="2" align="center">Tidak ada data prestasi.</td>
              </tr>';
}

$html .= '</table> <br>';

$html .= '<table border="1" cellspacing="3" cellpadding="4">
            <tr>
                <th colspan="2" align="left" style="font-size: 12pt; background-color: #f2f2f2;"><b>Q. KELAS JABATAN</b></th>
            </tr>
            <tr style="font-weight: bold;">
                <th width="10%" align="center"><b>No.</b></th>
                <th width="90%" align="center"><b>Kelas Jabatan</b></th>
            </tr>';

$no = 1;
while ($row = $result_kelas_jabatan->fetch_assoc()) {
    $html .= '<tr>
                <td align="center">' . $no . '</td>
                <td>' . htmlspecialchars($row['kelas_jabatan']) . '</td>
              </tr>';
    $no++;
}

// Jika tidak ada data, tambahkan baris kosong
if ($no === 1) {
    $html .= '<tr>
                <td colspan="2" align="center">Tidak ada data kelas jabatan.</td>
              </tr>';
}

$html .= '</table>';

// Tambahkan section tanda tangan di bawah tabel
$html .= '
<br><br>
<div style="text-align: right;">
    <div style="display: inline-block; text-align: left;">
        <div>Mengetahui:</div>
        <div>Plt. Sekretariat DPRD</div>
        <div>Provinsi Sulawesi Tenggara</div>
        <br><br><br><br><br>
        <div><b>Andi Rajalangi Sadapotto, S.H., M.H</b></div>
        <div>Pembina TK. I, Gol. IV/B</div>
        <div>NIP. 197609232008031001</div>
    </div>
</div>';


// Buat PDF
$options = new Options();
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Data_Jabatan_Tugas.pdf", array("Attachment" => true));


