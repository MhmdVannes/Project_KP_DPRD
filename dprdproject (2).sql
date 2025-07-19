-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 05:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dprdproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_bahan_kerja`
--

CREATE TABLE `tb_bahan_kerja` (
  `id_user` int(11) NOT NULL,
  `bahan_kerja` text NOT NULL,
  `penggunaan_tugas1` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_bahan_kerja`
--

INSERT INTO `tb_bahan_kerja` (`id_user`, `bahan_kerja`, `penggunaan_tugas1`) VALUES
(1, 's', 's'),
(13, 'f', 'q'),
(16, 's', 's'),
(17, 'f', 'f'),
(16, 'adad', 'dsds');

-- --------------------------------------------------------

--
-- Table structure for table `tb_fungsi_pekerjaan`
--

CREATE TABLE `tb_fungsi_pekerjaan` (
  `id_user` int(250) NOT NULL,
  `berhubungan_data` varchar(250) NOT NULL,
  `berhubungan_orang` varchar(250) NOT NULL,
  `berhubungan_benda` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_fungsi_pekerjaan`
--

INSERT INTO `tb_fungsi_pekerjaan` (`id_user`, `berhubungan_data`, `berhubungan_orang`, `berhubungan_benda`) VALUES
(1, 'Berunding', 'Menghitung data', 'Menjalankan mengontrol mesin'),
(13, 'Menyalin data', 'Berunding', 'Berunding'),
(16, 'Menghitung data', 'Berunding', 'Menyalin data'),
(17, 'Menganalisis data', 'Menyusun data', 'Menyalin data'),
(16, 'Memasang mesin', 'Mengerjakan persisi', 'Mengerjakan persisi');

-- --------------------------------------------------------

--
-- Table structure for table `tb_hasil_kerja`
--

CREATE TABLE `tb_hasil_kerja` (
  `id_user` int(250) NOT NULL,
  `hasil_kerja` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_hasil_kerja`
--

INSERT INTO `tb_hasil_kerja` (`id_user`, `hasil_kerja`) VALUES
(1, 'ds'),
(13, 'w'),
(16, 's'),
(17, 'f'),
(16, 'adad'),
(16, 'adada');

-- --------------------------------------------------------

--
-- Table structure for table `tb_input_jabatan`
--

CREATE TABLE `tb_input_jabatan` (
  `id_user` int(255) NOT NULL,
  `nama_jabatan` varchar(250) NOT NULL,
  `unit_kerja` varchar(250) NOT NULL,
  `ikhtisar_jabatan` text NOT NULL,
  `unit_kerja1` varchar(250) NOT NULL,
  `kode_jabatan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_input_jabatan`
--

INSERT INTO `tb_input_jabatan` (`id_user`, `nama_jabatan`, `unit_kerja`, `ikhtisar_jabatan`, `unit_kerja1`, `kode_jabatan`) VALUES
(1, 'aaaaaaaaaaaaa', 'JPT Madya', 'a', 'Sekretariat Dewan Perwakilan Rakyat Daerah', '1234566'),
(13, 's', 'JPT Pratama', 'h', 'Sekretariat Dewan Perwakilan Rakyat Daerah', ''),
(16, 'aaaaaaaaaaaaa', 'JPT Pratama', 's', 'Sekretariat Dewan Perwakilan Rakyat Daerah', ''),
(17, 'aaaaaaaaaaaaa', 'JPT Madya', 'd', 'Sekretariat Dewan Perwakilan Rakyat Daerah', ''),
(16, 'aaaaaaaaaaaaa', 'JPT Pratama', 'ksndks', 'Sekretariat Dewan Perwakilan Rakyat Daerah', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas_jabatan`
--

CREATE TABLE `tb_kelas_jabatan` (
  `id_user` int(250) NOT NULL,
  `kelas_jabatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas_jabatan`
--

INSERT INTO `tb_kelas_jabatan` (`id_user`, `kelas_jabatan`) VALUES
(1, 'f'),
(13, 'f'),
(16, 'g'),
(17, 'v'),
(16, 'sdsd');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kolerasi_jabatan`
--

CREATE TABLE `tb_kolerasi_jabatan` (
  `id_user` int(250) NOT NULL,
  `jabatan` text NOT NULL,
  `instansi` text NOT NULL,
  `dalam_hal` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kolerasi_jabatan`
--

INSERT INTO `tb_kolerasi_jabatan` (`id_user`, `jabatan`, `instansi`, `dalam_hal`) VALUES
(1, 's', 's', 's'),
(13, 'f', 'w', 'f'),
(16, 'u', 'y', 'y'),
(17, 'v', 'r', 'v'),
(16, 'sssd', 'sdsds', 'sdsds');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kondisi_fisik`
--

CREATE TABLE `tb_kondisi_fisik` (
  `id_user` int(250) NOT NULL,
  `jenis_kelamin` varchar(250) NOT NULL,
  `umur_maksimal` int(250) NOT NULL,
  `tinggi_badan` int(250) NOT NULL,
  `berat_badan` int(250) NOT NULL,
  `postur_badan` varchar(250) NOT NULL,
  `penampilan` varchar(250) NOT NULL,
  `keadaan_fisik` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kondisi_fisik`
--

INSERT INTO `tb_kondisi_fisik` (`id_user`, `jenis_kelamin`, `umur_maksimal`, `tinggi_badan`, `berat_badan`, `postur_badan`, `penampilan`, `keadaan_fisik`) VALUES
(1, 'Perempuan', 3, 2, 3, '23', 'd', 'f'),
(13, 'Perempuan', 7, 7, 7, '7', '7', 'b'),
(16, 'Perempuan', 12, 12, 12, '12', 'vdgd', 'sfsf'),
(17, 'Perempuan', 12, 12, 12, '12', 'vdgd', 'sfsf'),
(16, 'Laki-laki', 12, 12, 12, '12', 'vdgd', 'sfsf');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kondisi_lingkungan_kerja`
--

CREATE TABLE `tb_kondisi_lingkungan_kerja` (
  `id_user` int(250) NOT NULL,
  `tempat_kerja` varchar(250) NOT NULL,
  `suhu` varchar(250) NOT NULL,
  `udara` varchar(250) NOT NULL,
  `keadaan_ruangan` varchar(250) NOT NULL,
  `letak` varchar(250) NOT NULL,
  `penerangan` varchar(250) NOT NULL,
  `suara` varchar(250) NOT NULL,
  `keadaan_tempat_kerja` varchar(250) NOT NULL,
  `getaran` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kondisi_lingkungan_kerja`
--

INSERT INTO `tb_kondisi_lingkungan_kerja` (`id_user`, `tempat_kerja`, `suhu`, `udara`, `keadaan_ruangan`, `letak`, `penerangan`, `suara`, `keadaan_tempat_kerja`, `getaran`) VALUES
(1, '2', '3', 'd', 's', 'c', 'w', 'f', 'v', 'd'),
(13, 'ff', 'h', 'g', 'g', 'g', 'g', 'g', 'g', 'g'),
(16, 'a', 'F', 'sfs', 'ss', 'sfs', 'sfs', 'sfsf', 'sfs', 'sfs'),
(17, 'a', 'F', 'sfs', 'ss', 'sfs', 'sfs', 'sfsf', 'sfs', 'sfs'),
(16, 'as', 'Fsds', 'sfssds', 'sssds', 'sfssdsds', 'sfssdssd', 'sfsfsds', 'sfssds', 'sfssds');

-- --------------------------------------------------------

--
-- Table structure for table `tb_perangkat_kerja`
--

CREATE TABLE `tb_perangkat_kerja` (
  `id_user` int(250) NOT NULL,
  `perangkat_kerja` text NOT NULL,
  `penggunaan_tugas2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_perangkat_kerja`
--

INSERT INTO `tb_perangkat_kerja` (`id_user`, `perangkat_kerja`, `penggunaan_tugas2`) VALUES
(1, 's', 'c'),
(13, 'f', 'f'),
(16, 's', 's'),
(17, 'e', 'v'),
(16, 'sdsd', 'ssss');

-- --------------------------------------------------------

--
-- Table structure for table `tb_prestasi`
--

CREATE TABLE `tb_prestasi` (
  `id_user` int(250) NOT NULL,
  `prestasi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_prestasi`
--

INSERT INTO `tb_prestasi` (`id_user`, `prestasi`) VALUES
(1, 'e'),
(13, 'w'),
(16, 'j'),
(17, 'f'),
(16, 'dffdd');

-- --------------------------------------------------------

--
-- Table structure for table `tb_risiko_bahaya`
--

CREATE TABLE `tb_risiko_bahaya` (
  `id_user` int(250) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `fisik_mental` text NOT NULL,
  `penyebab` text NOT NULL,
  `input_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_risiko_bahaya`
--

INSERT INTO `tb_risiko_bahaya` (`id_user`, `jabatan_id`, `fisik_mental`, `penyebab`, `input_by`) VALUES
(1, 0, 'd', 'v', 0),
(13, 0, 'w', 'f', 0),
(16, 0, 'y', 't', 0),
(17, 0, 'v', 'v', 0),
(16, 0, 'sds', 'sdsds', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_syarat_jabatan`
--

CREATE TABLE `tb_syarat_jabatan` (
  `id_user` int(250) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `pangkat` varchar(250) NOT NULL,
  `pendidikan` varchar(250) NOT NULL,
  `jenjang` varchar(250) NOT NULL,
  `teknis` varchar(250) NOT NULL,
  `fungsional` varchar(250) NOT NULL,
  `pengalaman_kerja` text NOT NULL,
  `input_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_syarat_jabatan`
--

INSERT INTO `tb_syarat_jabatan` (`id_user`, `jabatan_id`, `pangkat`, `pendidikan`, `jenjang`, `teknis`, `fungsional`, `pengalaman_kerja`, `input_by`) VALUES
(1, 0, 'a', 'a', 'Diklatpim Tingkat III', 'Diklat Teknis Kepemimpinan Organisasi', 'a', 'a', 0),
(13, 0, 'a', 'aaaaaaaaaaa', 'Diklatpim Tingkat IV', 'Diklat pengadaan barang dan jasa', 'cG', 'h', 0),
(16, 0, 'addddddddd', 'nnnnnnnnnnnnn', 'Diklatpim Tingkat IV', 'Diklat pengadaan barang dan jasa', 'aaaaaaa', 's', 0),
(17, 0, 'addddddddd', 'nnnnnnnnnnnnn', 'Diklatpim Tingkat II', 'Diklat Teknis Kepemimpinan Organisasi', 'aaaaaaa', 'f', 0),
(16, 0, 'abbb', 'abbb', 'Diklatpim Tingkat IV', 'Diklat pengadaan barang dan jasa', 'abbbb', 'abbbb', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_syarat_jabatan_lain`
--

CREATE TABLE `tb_syarat_jabatan_lain` (
  `id_user` int(250) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `keterampilan_kerja` varchar(250) NOT NULL,
  `bakat_kerja` varchar(250) NOT NULL,
  `temperamen_kerja` varchar(250) NOT NULL,
  `minat_kerja` varchar(250) NOT NULL,
  `aktivitas` varchar(250) NOT NULL,
  `input_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_syarat_jabatan_lain`
--

INSERT INTO `tb_syarat_jabatan_lain` (`id_user`, `jabatan_id`, `keterampilan_kerja`, `bakat_kerja`, `temperamen_kerja`, `minat_kerja`, `aktivitas`, `input_by`) VALUES
(1, 0, 'keterampilan_komputer', 'koordinasi_mata', 'STS', 'kewirausahaan', 'Mengangkat', 0),
(13, 0, 'keterampilan_komputer', 'kemampuan_membedakan_warna', 'STS', 'kewirausahaan', 'Berdiri', 0),
(16, 0, 'keterampilan_komputer', 'koordinasi_mata', 'VARCH', 'kewirausahaan', 'Berdiri', 0),
(17, 0, 'keterampilan_komputer', 'koordinasi_mata', 'REPCON', 'sosial', 'Berjalan', 0),
(16, 0, 'keterampilan_penyusunan', 'kemampuan_membedakan_warna', 'FIF', 'investigatif', 'Bekerja dengan jari', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_tanggung_jawab`
--

CREATE TABLE `tb_tanggung_jawab` (
  `id_user` int(250) NOT NULL,
  `tanggung_jawab` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_tanggung_jawab`
--

INSERT INTO `tb_tanggung_jawab` (`id_user`, `tanggung_jawab`) VALUES
(1, 'd'),
(13, 'v'),
(16, 'j'),
(17, 'f'),
(16, 'sdsdsd');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tugas_pokok`
--

CREATE TABLE `tb_tugas_pokok` (
  `id_user` int(250) NOT NULL,
  `uraian_tugas` text NOT NULL,
  `hasil_krj` varchar(250) NOT NULL,
  `jumlah_beban` int(250) NOT NULL,
  `waktu_penyelesaian` int(250) NOT NULL,
  `waktu_efektif` int(250) NOT NULL,
  `kebutuhan_pegawai` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_tugas_pokok`
--

INSERT INTO `tb_tugas_pokok` (`id_user`, `uraian_tugas`, `hasil_krj`, `jumlah_beban`, `waktu_penyelesaian`, `waktu_efektif`, `kebutuhan_pegawai`) VALUES
(1, 'a', 'Dokumen', 1, 2, 3, 4),
(13, 'd', 'Dokumen', 7, 70, 2, 3),
(16, 's', 'Kegiatan', 12, 70, 840, 9),
(17, 'f', 'Dokumen', 12, 70, 840, 9),
(16, 'fssaa', 'Dokumen', 11, 12, 12, 21);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(250) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `nip` varchar(250) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `peran` enum('user','admin') NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `nama`, `nip`, `jabatan`, `peran`, `password`) VALUES
(1, 'vannes', 'E1E122071', 'blalala', 'admin', 'vannes123'),
(13, 'sanaya', '242424', 'wakil', 'user', '$2y$10$i7ftHhwWjc2tDF8x3BgguuEzT.KPLVLtZY1YhbkqLucLMjW/yJepW'),
(14, 'weri', '11111', 'sk', 'user', '$2y$10$2QJlDiXBX81R0KZLoBsbe.EEUNuU/gvrDrux58WZ9C2E7j1hHk4SW'),
(16, 'dela', '726726732784', 'sk', 'user', '$2y$10$P80lLLbiVb.UXYx3j66fDOyJGl42NWbqK7iHU5QyRJU3sB9pM5DlW'),
(17, 'ami', 'E1E1220145', 'link', 'user', '$2y$10$uvZJxRp0NO1lymB3FDjYKOZBMyr7csEUTqkuy2VR4E9Q4RfwUbVsO');

-- --------------------------------------------------------

--
-- Table structure for table `tb_wewenang`
--

CREATE TABLE `tb_wewenang` (
  `id_user` int(250) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `wewenang` text NOT NULL,
  `input_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_wewenang`
--

INSERT INTO `tb_wewenang` (`id_user`, `jabatan_id`, `wewenang`, `input_by`) VALUES
(1, 0, 'c', 0),
(13, 0, 'e', 0),
(16, 0, 'u', 0),
(17, 0, 'r', 0),
(16, 0, 'sdsdss', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_bahan_kerja`
--
ALTER TABLE `tb_bahan_kerja`
  ADD KEY `fk_bahan_kerja_id` (`id_user`);

--
-- Indexes for table `tb_fungsi_pekerjaan`
--
ALTER TABLE `tb_fungsi_pekerjaan`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_hasil_kerja`
--
ALTER TABLE `tb_hasil_kerja`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_input_jabatan`
--
ALTER TABLE `tb_input_jabatan`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_kelas_jabatan`
--
ALTER TABLE `tb_kelas_jabatan`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_kolerasi_jabatan`
--
ALTER TABLE `tb_kolerasi_jabatan`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_kondisi_fisik`
--
ALTER TABLE `tb_kondisi_fisik`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_kondisi_lingkungan_kerja`
--
ALTER TABLE `tb_kondisi_lingkungan_kerja`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_perangkat_kerja`
--
ALTER TABLE `tb_perangkat_kerja`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_prestasi`
--
ALTER TABLE `tb_prestasi`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_risiko_bahaya`
--
ALTER TABLE `tb_risiko_bahaya`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_syarat_jabatan`
--
ALTER TABLE `tb_syarat_jabatan`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_syarat_jabatan_lain`
--
ALTER TABLE `tb_syarat_jabatan_lain`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_tanggung_jawab`
--
ALTER TABLE `tb_tanggung_jawab`
  ADD KEY `tb_tanggung_jawab_ibfk_1` (`id_user`);

--
-- Indexes for table `tb_tugas_pokok`
--
ALTER TABLE `tb_tugas_pokok`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tb_wewenang`
--
ALTER TABLE `tb_wewenang`
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_bahan_kerja`
--
ALTER TABLE `tb_bahan_kerja`
  ADD CONSTRAINT `fk_bahan_kerja_id` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_fungsi_pekerjaan`
--
ALTER TABLE `tb_fungsi_pekerjaan`
  ADD CONSTRAINT `tb_fungsi_pekerjaan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_hasil_kerja`
--
ALTER TABLE `tb_hasil_kerja`
  ADD CONSTRAINT `tb_hasil_kerja_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_input_jabatan`
--
ALTER TABLE `tb_input_jabatan`
  ADD CONSTRAINT `tb_input_jabatan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_kelas_jabatan`
--
ALTER TABLE `tb_kelas_jabatan`
  ADD CONSTRAINT `tb_kelas_jabatan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_kolerasi_jabatan`
--
ALTER TABLE `tb_kolerasi_jabatan`
  ADD CONSTRAINT `tb_kolerasi_jabatan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_kondisi_fisik`
--
ALTER TABLE `tb_kondisi_fisik`
  ADD CONSTRAINT `tb_kondisi_fisik_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_kondisi_lingkungan_kerja`
--
ALTER TABLE `tb_kondisi_lingkungan_kerja`
  ADD CONSTRAINT `tb_kondisi_lingkungan_kerja_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_perangkat_kerja`
--
ALTER TABLE `tb_perangkat_kerja`
  ADD CONSTRAINT `tb_perangkat_kerja_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_prestasi`
--
ALTER TABLE `tb_prestasi`
  ADD CONSTRAINT `tb_prestasi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_risiko_bahaya`
--
ALTER TABLE `tb_risiko_bahaya`
  ADD CONSTRAINT `tb_risiko_bahaya_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_syarat_jabatan`
--
ALTER TABLE `tb_syarat_jabatan`
  ADD CONSTRAINT `tb_syarat_jabatan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_syarat_jabatan_lain`
--
ALTER TABLE `tb_syarat_jabatan_lain`
  ADD CONSTRAINT `tb_syarat_jabatan_lain_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_tanggung_jawab`
--
ALTER TABLE `tb_tanggung_jawab`
  ADD CONSTRAINT `tb_tanggung_jawab_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_tugas_pokok`
--
ALTER TABLE `tb_tugas_pokok`
  ADD CONSTRAINT `tb_tugas_pokok_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);

--
-- Constraints for table `tb_wewenang`
--
ALTER TABLE `tb_wewenang`
  ADD CONSTRAINT `tb_wewenang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
