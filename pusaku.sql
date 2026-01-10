-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 10, 2026 at 01:45 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pusaku`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `nim` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `jurusan` varchar(40) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `status_mhs` enum('Aktif','Tidak Aktif') NOT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `password` varchar(50) DEFAULT 'mhsudb123'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`nim`, `nama`, `jenis_kelamin`, `jurusan`, `kelas`, `tgl_lahir`, `status_mhs`, `no_telp`, `password`) VALUES
(1, 'Andi Saputra', 'Laki-Laki', 'Informatika', 'IF-1', '2003-05-12', 'Aktif', '081234567890', '123456'),
(2, 'Siti Aminah', 'Perempuan', 'Sistem Informasi', 'SI-2', '2002-11-20', 'Aktif', '082345678901', '123456'),
(23123452, 'Aji Ramdani', 'Laki-Laki', 'S1 Teknik Informatika', 'asas', '2026-01-10', 'Aktif', '+6289508742700', 'mhsudb123');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `kode_buku` varchar(10) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `judul_buku` varchar(100) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tanggal_terbit` date NOT NULL,
  `jumlah_halaman` int NOT NULL,
  `bahasa` varchar(20) NOT NULL,
  `deskripsi_buku` text NOT NULL,
  `stok` int DEFAULT '0',
  `status` enum('Tersedia','Dipinjam','Kosong') NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`kode_buku`, `cover`, `kategori`, `judul_buku`, `pengarang`, `penerbit`, `tanggal_terbit`, `jumlah_halaman`, `bahasa`, `deskripsi_buku`, `stok`, `status`) VALUES
('7', 'rumah dan jalan kembali__.jpg', 'Jaringan dan Keamanan', 'hhhh', 'yyy', 'jjj', '2026-01-01', 12, 'Inggris', 'jjj', 12, 'Tersedia'),
('BK001', 'bk001.jpg', 'Pemrograman', 'Belajar Laravel', 'Taylor Otwellj', 'Laravel Press', '2022-01-01', 350, 'Indonesia', 'Panduan Laravel lengkap', 4, 'Tersedia'),
('BK002', 'bk002.jpg', 'Database', 'Mastering MySQL', 'Oracle Team', 'Oracle Press', '2021-06-10', 280, 'Indonesia', 'Panduan MySQL', 2, 'Tersedia'),
('BK003', 'bk003.jpg', 'UI/UX', 'Dasar UI UX', 'Don Norman', 'UX Media', '2020-03-15', 200, 'Indonesia', 'Konsep UI UX dasar', 0, 'Kosong');

--
-- Triggers `buku`
--
DELIMITER $$
CREATE TRIGGER `update_buku_status` BEFORE UPDATE ON `buku` FOR EACH ROW BEGIN
  IF NEW.stok <= 0 THEN
    SET NEW.status = 'Kosong';
  ELSE
    SET NEW.status = 'Tersedia';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id` int NOT NULL,
  `kode_pinjam` varchar(6) NOT NULL,
  `kode_buku` varchar(10) NOT NULL,
  `kondisi_buku_pinjam` enum('Bagus','Rusak') NOT NULL DEFAULT 'Bagus'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id`, `kode_pinjam`, `kode_buku`, `kondisi_buku_pinjam`) VALUES
(4, 'PN001', 'BK001', 'Bagus'),
(5, 'PN002', 'BK002', 'Bagus'),
(6, 'PN003', 'BK001', 'Bagus');

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id_owner` int NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `nama_pemilik` varchar(100) NOT NULL,
  `profil_gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`id_owner`, `username`, `password`, `nama_pemilik`, `profil_gambar`) VALUES
(1, 'ownerpusaku', 'owner123', 'Pemilik Perpustakaan', 'owner.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `kode_pinjam` varchar(6) NOT NULL,
  `nim` int NOT NULL,
  `id_petugas` int NOT NULL,
  `tgl_pinjam` datetime DEFAULT CURRENT_TIMESTAMP,
  `estimasi_pinjam` datetime DEFAULT NULL,
  `status` enum('Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`kode_pinjam`, `nim`, `id_petugas`, `tgl_pinjam`, `estimasi_pinjam`, `status`) VALUES
('PN001', 2, 1, '2026-01-17 00:00:00', '2026-01-28 00:00:00', 'Dikembalikan'),
('PN002', 23123452, 1, '2026-01-15 00:00:00', '2026-01-22 00:00:00', 'Dikembalikan'),
('PN003', 1, 1, '2026-01-01 00:00:00', '2026-01-03 00:00:00', 'Dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `kode_kembali` varchar(6) NOT NULL,
  `kode_pinjam` varchar(6) NOT NULL,
  `tgl_kembali` datetime DEFAULT CURRENT_TIMESTAMP,
  `kondisi_buku` enum('Bagus','Rusak','Hilang') NOT NULL DEFAULT 'Bagus',
  `denda` double(10,2) DEFAULT NULL,
  `status` enum('Lunas','Belum Lunas') DEFAULT NULL,
  `pembayaran` enum('Tidak Ada','Cash','Transfer') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`kode_kembali`, `kode_pinjam`, `tgl_kembali`, `kondisi_buku`, `denda`, `status`, `pembayaran`) VALUES
('KB001', 'PN001', '2026-01-10 00:00:00', 'Bagus', 0.00, 'Lunas', 'Tidak Ada'),
('KB002', 'PN002', '2026-01-10 00:00:00', 'Bagus', 0.00, 'Belum Lunas', 'Tidak Ada'),
('KB003', 'PN003', '2026-01-10 00:00:00', 'Bagus', 35000.00, NULL, 'Tidak Ada');

--
-- Triggers `pengembalian`
--
DELIMITER $$
CREATE TRIGGER `update_status_after_return` AFTER INSERT ON `pengembalian` FOR EACH ROW BEGIN
  UPDATE peminjaman
  SET status = 'Dikembalikan'
  WHERE kode_pinjam = NEW.kode_pinjam;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `no_telp` char(15) NOT NULL,
  `profil_gambar` varchar(255) NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `jenis_kelamin`, `no_telp`, `profil_gambar`, `status`) VALUES
(1, 'Budi Admin', 'admin', 'admin123', 'Laki-Laki', '081111111111', 'admin.jpg', 'Aktif'),
(2, 'Rina Petugas', 'rina', 'rina123', 'Perempuan', '082222222222', 'rina.jpg', 'Aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`kode_buku`);

--
-- Indexes for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kode_pinjam` (`kode_pinjam`),
  ADD KEY `kode_buku` (`kode_buku`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id_owner`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`kode_pinjam`),
  ADD KEY `nim` (`nim`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`kode_kembali`),
  ADD KEY `kode_pinjam` (`kode_pinjam`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `nim` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23123453;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id_owner` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `detail_peminjaman_ibfk_1` FOREIGN KEY (`kode_pinjam`) REFERENCES `peminjaman` (`kode_pinjam`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_peminjaman_ibfk_2` FOREIGN KEY (`kode_buku`) REFERENCES `buku` (`kode_buku`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `anggota` (`nim`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`kode_pinjam`) REFERENCES `peminjaman` (`kode_pinjam`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
