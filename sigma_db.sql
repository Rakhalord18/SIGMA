-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 06:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sigma_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_detail`
--

CREATE TABLE `admin_detail` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `notelpon` varchar(20) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_detail`
--

INSERT INTO `admin_detail` (`id`, `id_users`, `nama`, `nip`, `jenis_kelamin`, `jabatan`, `notelpon`, `foto`) VALUES
(1, 1, 'jeko', '20220704001', 'Laki-laki', 'manager', '08234576890', '1748850634_Asset 4.png'),
(5, 20, 'wulan', '20060978', 'Perempuan', 'Pegawai', '081273848001', ''),
(6, 29, 'adit', '2211201010', 'Laki-laki', 'gm', '08387894784', NULL),
(7, 34, 'Kharaaa', '112121', 'Laki-laki', 'manager', '123352', '1749781832_Asset 4.png');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_harian`
--

CREATE TABLE `kegiatan_harian` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(225) NOT NULL,
  `file` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan_harian`
--

INSERT INTO `kegiatan_harian` (`id`, `id_users`, `hari`, `tanggal`, `deskripsi`, `file`) VALUES
(2, 5, 'Senin', '2025-05-12', 'rgfhg', 'kegiatan_6827465175c494.75955584.pdf'),
(3, 5, 'Selasa', '2025-05-13', 'safg', 'kegiatan_682747ba1abba5.87882530.pdf'),
(9, 28, 'Minggu', '2025-05-18', 'libur', 'kegiatan_6829c1826180b3.70582273.pdf'),
(10, 2, 'Selasa', '2025-05-20', 'wded', 'kegiatan_682d43d2c355a4.16916519.pdf'),
(11, 2, 'Kamis', '2025-05-15', 'gjh', 'kegiatan_682d79e30a0370.47144643.pdf'),
(12, 2, 'Senin', '2025-05-19', 'yry', 'kegiatan_682d7d881af616.45476429.pdf'),
(13, 5, 'Minggu', '2025-06-01', 'aa', ''),
(14, 33, 'Selasa', '2025-06-10', 'aaa', ''),
(21, 38, 'Kamis', '2025-06-12', 'zxzzxz', '');

-- --------------------------------------------------------

--
-- Table structure for table `ketidakhadiran`
--

CREATE TABLE `ketidakhadiran` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(225) NOT NULL,
  `file` varchar(225) NOT NULL,
  `status_pengajuan` varchar(20) NOT NULL,
  `disetujui_oleh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ketidakhadiran`
--

INSERT INTO `ketidakhadiran` (`id`, `id_users`, `keterangan`, `tanggal`, `deskripsi`, `file`, `status_pengajuan`, `disetujui_oleh`) VALUES
(6, 5, 'Sakit', '2025-05-30', 'wsss', 'bukti_6824b54527a7a4.14384677.pdf', 'Disetujui', NULL),
(7, 5, 'Izin', '2025-05-15', 'decve', 'bukti_6824b5adedc816.60239937.pdf', 'Ditolak', NULL),
(8, 28, 'Izin', '2025-05-26', 'z', 'bukti_683318d57e9f10.51046710.jpg', 'Menunggu', NULL),
(9, 2, 'Sakit', '2025-05-23', 'fhfhg', 'bukti_682d7a42c7e991.87433344.pdf', 'Disetujui', NULL),
(10, 2, 'Izin', '2025-05-27', 'gdgds', 'bukti_682d7dad2d2822.96427501.pdf', 'Ditolak', NULL),
(11, 2, 'Izin', '2025-05-26', 'z', 'bukti_683318d57e9f10.51046710.jpg', 'Menunggu', NULL),
(12, 33, 'Izin', '2025-07-10', 'zzz', 'bukti_68479ffa9f9535.98968713.pdf', 'Disetujui', 1),
(13, 33, 'Sakit', '2025-08-10', 'xsds', 'bukti_6847ab12a62bd0.92042820.pdf', 'Menunggu', NULL),
(14, 5, 'Sakit', '2025-06-12', 'sakit', 'bukti_6847b029242498.19567721.pdf', 'Disetujui', 1),
(18, 21, 'Sakit', '2025-09-10', 'xzxzx', 'bukti_6847b1750114f5.85098381.pdf', 'Disetujui', 34),
(28, 33, 'Izin', '2025-06-10', 'a', 'Tidak Ada File', 'Disetujui', 1),
(31, 33, 'Izin', '2025-06-13', 'mens', 'Tidak Ada File', 'Ditolak', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `laporan_akhir`
--

CREATE TABLE `laporan_akhir` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `file` varchar(225) NOT NULL,
  `tanggal` date NOT NULL,
  `STATUS` enum('Pending','Disetujui','Ditolak') DEFAULT 'Pending',
  `disetujui_oleh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan_akhir`
--

INSERT INTO `laporan_akhir` (`id`, `id_users`, `keterangan`, `file`, `tanggal`, `STATUS`, `disetujui_oleh`) VALUES
(2, 5, 'laporan', 'laporan_682893ce79e641.54669026.pdf', '2025-05-17', 'Disetujui', NULL),
(5, 32, 'anjay', 'laporan_68333f1c2263f1.99370607.pdf', '2025-05-25', 'Disetujui', NULL),
(6, 2, 'ya', 'laporan_6836ce11e5b1e5.87839273.pdf', '2025-05-28', 'Disetujui', NULL),
(22, 33, 'anjay', 'laporan_684906c0711178.97073913.zip', '2025-06-11', 'Disetujui', 1),
(25, 38, 'Kepo', 'laporan_684ac005b1c653.04616518.zip', '2025-06-12', 'Pending', 34),
(26, 21, 'zxa', 'laporan_684b1b6034e675.99990925.zip', '2025-06-13', 'Disetujui', 34);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(225) NOT NULL,
  `alamat_lokasi` varchar(225) NOT NULL,
  `zona_waktu` varchar(4) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `alamat_lokasi`, `zona_waktu`, `jam_masuk`, `jam_keluar`) VALUES
(2, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', 'Jalan Jenderal Sudirman No. 3, Muntang Tapus, Prabumulih\r\nBarat, Kota Prabumulih, Sumatera Selatan 31113, Indonesia.', 'WIB', '08:00:00', '16:00:00'),
(3, 'Home', 'Ds.Sungai Langan Kec.Penukal Kab.PALI', 'WIB', '08:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa_detail`
--

CREATE TABLE `mahasiswa_detail` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nis_nim` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `asal_instansi` varchar(50) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `notelpon` varchar(15) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `lokasi_presensi` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa_detail`
--

INSERT INTO `mahasiswa_detail` (`id`, `id_users`, `nama`, `nis_nim`, `jenis_kelamin`, `jurusan`, `asal_instansi`, `alamat`, `notelpon`, `tanggal_mulai`, `tanggal_selesai`, `lokasi_presensi`, `foto`) VALUES
(1, 2, 'Selly', '221410105', 'Perempuan', 'Manajemen', 'Universitas Bina Darma', 'Jl.Pertamina Ds.Sungai Langan Kec.Penukal Kab.PALI', '081273848041', '2025-03-03', '2025-06-03', 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', ''),
(4, 5, 'Aditya', '221410101', 'Laki-laki', 'Sistem Informasi', 'Universitas Bina Darma', 'Ds.Purun Kec.Penukal Kab.Pali', '081273848001', '2025-05-02', '2025-07-09', 'Rumah', NULL),
(6, 21, 'akbar', '0072546', 'Laki-laki', 'TKRO', 'SMKN 1 Penukal', 'Ds.babat Kec.Penukal Kab.PALI', '08234576809', '2025-04-02', '2025-06-11', 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', ''),
(11, 28, 'Eko', '00725460990', 'Perempuan', 'TKJ', 'SMKN 1 Penukal', 't7yudfu', '08234576809', '2025-05-10', '2025-06-19', 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', ''),
(12, 30, 'Rakha Pratama Putra', '1234567890', 'Laki-laki', 'TEKNIK KOMPUTER DAN JARINGAN', 'Pedalaman jambi', 'talang keramat', '0987654321', '1986-01-23', '2025-05-23', 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4', '1748850525_IMG_326111.jpg'),
(13, 32, 'Rakha Pratama', '1223', 'Laki-laki', 'Tekkom', 'Polsri', 'anaa', '0119', '2004-11-18', '2025-05-25', 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4', NULL),
(14, 33, 'Bro', '125323', 'Laki-laki', 'TEKNIK KOMPUTER DAN JARINGAN', 'SMA 1 Palembang', 'asa', '08234576809', '2025-06-03', '2025-07-03', 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', '1749781723_20220530_211819.jpg'),
(17, 38, 'usop', '12141241', 'Laki-laki', 'TIMD', 'Polsri', 'Plaju', '08129228291', '2025-06-12', '2025-09-12', 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `presensi_detail`
--

CREATE TABLE `presensi_detail` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `nama_lokasi` varchar(255) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time NOT NULL,
  `total_waktu` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi_detail`
--

INSERT INTO `presensi_detail` (`id`, `id_users`, `nama_lokasi`, `tanggal_masuk`, `tanggal_keluar`, `jam_masuk`, `jam_keluar`, `total_waktu`) VALUES
(20, 5, 'Rumah', '2025-04-10', '2025-04-10', '07:50:00', '16:00:00', '08:10:00'),
(23, 5, 'Rumah', '2025-05-11', '2025-05-11', '08:31:00', '16:00:00', '07:29:00'),
(35, 5, 'Rumah', '2025-05-12', '0000-00-00', '08:15:00', '00:00:00', '00:00:00'),
(36, 5, 'Rumah', '2025-05-13', '2025-05-13', '00:35:20', '16:16:49', '15:41:29'),
(37, 28, 'Rumah', '2025-05-13', '2025-05-13', '00:53:08', '16:13:48', '15:20:40'),
(38, 5, 'Rumah', '2025-05-14', '2025-05-14', '19:48:44', '19:48:45', '00:00:01'),
(39, 5, 'Rumah', '2025-05-15', '2025-05-15', '19:19:54', '20:11:05', '00:51:11'),
(40, 28, 'Rumah', '2025-05-16', '2025-05-16', '19:40:36', '19:40:38', '00:00:02'),
(41, 5, 'Rumah', '2025-05-16', '2025-05-16', '19:41:23', '19:41:26', '00:00:03'),
(42, 5, 'Rumah', '2025-05-17', '2025-05-17', '20:36:44', '20:36:46', '00:00:02'),
(43, 28, 'Rumah', '2025-05-17', '2025-05-17', '21:33:57', '21:33:59', '00:00:02'),
(44, 28, 'Rumah', '2025-05-18', '2025-05-18', '17:37:12', '18:14:24', '00:37:12'),
(45, 28, 'Rumah', '2025-05-19', '2025-05-19', '20:49:18', '20:49:22', '00:00:04'),
(49, 2, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4', '2025-05-21', '0000-00-00', '14:10:48', '00:00:00', '00:00:00'),
(50, 30, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4', '2025-05-23', '0000-00-00', '09:58:02', '00:00:00', '00:00:00'),
(51, 2, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4', '2025-06-02', '0000-00-00', '15:16:44', '00:00:00', '00:00:00'),
(52, 33, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4', '2025-06-04', '0000-00-00', '09:16:29', '00:00:00', '00:00:00'),
(53, 33, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4', '2025-06-05', '0000-00-00', '10:01:19', '00:00:00', '00:00:00'),
(54, 33, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4', '2025-06-09', '0000-00-00', '02:16:16', '00:00:00', '00:00:00'),
(55, 33, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', '2025-06-10', '0000-00-00', '11:35:09', '00:00:00', '00:00:00'),
(56, 21, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', '2025-06-11', '0000-00-00', '10:23:27', '00:00:00', '00:00:00'),
(57, 38, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', '2025-06-12', '2025-06-12', '18:37:53', '18:37:54', '00:00:01'),
(58, 33, 'PT. PERTAMINA (PERSERO) HULU ROKAN ZONA 4 hahay', '2025-06-13', '2025-06-13', '09:26:20', '21:50:14', '12:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `status` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `status`, `role`) VALUES
(1, 'jeko@gmail.com', '$2y$10$Yw4QftOxOfcNYK0xE1PR6u86wlcYnzDc7Bgkoz.6y/7/15nQmlauO', 'Aktif', 'admin'),
(2, 'selly@gmail.com', '$2y$10$6ZdrBL/Jedl0Ydm8Ojc0EuAyAHXhas/bok0OeYvZoo7OyTNSwwKp.', 'Tidak Aktif', 'mahasiswa'),
(5, 'aditya@gmail.com', '$2y$10$Ni1X6cl3cowYm9pKS31.n.6GnlMiF7lWfMYZvxrbhlArTyWbGQXTO', 'Aktif', 'mahasiswa'),
(20, 'wulan@gmail.com', '$2y$10$K5Ggak0PbIjQ5PynPHE0lOjGGuXJ8L0gZ2XfMlhT7bp.4vXpofrPO', 'Aktif', 'pembimbing'),
(21, 'akbar@gmail.com', '$2y$10$6bDiY7Nk1WL8YtIR2RXcz.HnUfNd3nX4iGR0sNM4cUMxd2RjfMSf2', 'Tidak Aktif', 'mahasiswa'),
(28, 'eko@gmail.com', '$2y$10$G5WylYhxYy8yb6UfvvUFY.6Hr27NvZd8IzPbeQn1/KTjAcO3hjcAy', 'Tidak Aktif', 'mahasiswa'),
(29, 'adit@gmail.com', '$2y$10$LEcnbPWJWP1vMwu9jDIZCueJa12ST7ltNjNmB5u4CB10xhSqJWusW', 'Aktif', 'admin'),
(30, 'yuuka@gmail.com', '$2y$10$B6pfzsYWKjPxWyCNX4uv6uKqn2grDlT2sIfE87jDlJY8HXVzaHsj.', 'Tidak Aktif', 'mahasiswa'),
(32, 'rakha@gmail.com', '$2y$10$0R2vbusnQlWlI99IlCju9eyXNgozup.5zF.FVrMFmjI1KUQZer6PG', 'Tidak Aktif', 'mahasiswa'),
(33, 'bro@gmail.com', '$2y$10$xpZfkq0IslCWE5862Cno4e4gfrBJh4ltLY1vxnU.XUXijtYPbFUU2', 'Aktif', 'siswa'),
(34, 'khara@gmail.com', '$2y$10$SYLXd7GiXWTLcREuaajVVuZVwcfb64gPoS9weH.J9F7izUwuzabWy', 'Aktif', 'pembimbing'),
(38, 'usop@gmail.com', '$2y$10$.sPSnD3vmFK.ELUGzOp9U.hPVp20ABA7L0GcBvmhCACcdAHG1PRva', 'Aktif', 'mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_detail`
--
ALTER TABLE `admin_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_users` (`id_users`);

--
-- Indexes for table `kegiatan_harian`
--
ALTER TABLE `kegiatan_harian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_users` (`id_users`);

--
-- Indexes for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_users` (`id_users`);

--
-- Indexes for table `laporan_akhir`
--
ALTER TABLE `laporan_akhir`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_users` (`id_users`);

--
-- Indexes for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mahasiswa_detail`
--
ALTER TABLE `mahasiswa_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_users` (`id_users`);

--
-- Indexes for table `presensi_detail`
--
ALTER TABLE `presensi_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mahasiswa_siswa` (`id_users`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_detail`
--
ALTER TABLE `admin_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kegiatan_harian`
--
ALTER TABLE `kegiatan_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `laporan_akhir`
--
ALTER TABLE `laporan_akhir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mahasiswa_detail`
--
ALTER TABLE `mahasiswa_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `presensi_detail`
--
ALTER TABLE `presensi_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_detail`
--
ALTER TABLE `admin_detail`
  ADD CONSTRAINT `admin_detail_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kegiatan_harian`
--
ALTER TABLE `kegiatan_harian`
  ADD CONSTRAINT `kegiatan_harian_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD CONSTRAINT `ketidakhadiran_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `laporan_akhir`
--
ALTER TABLE `laporan_akhir`
  ADD CONSTRAINT `laporan_akhir_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa_detail`
--
ALTER TABLE `mahasiswa_detail`
  ADD CONSTRAINT `mahasiswa_detail_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `presensi_detail`
--
ALTER TABLE `presensi_detail`
  ADD CONSTRAINT `presensi_detail_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
