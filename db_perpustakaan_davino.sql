-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2026 at 01:55 AM
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
-- Database: `db_perpustakaan_davino`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul_buku` varchar(150) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `kategori` varchar(60) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul_buku`, `penulis`, `kategori`, `status`) VALUES
(1, 'atomic habits', 'james clear', 'filsafat', 'Tersedia'),
(2, 'matahari', 'tere liye', 'komik', 'Tersedia'),
(3, 'semangat 45', 'davino', 'motivasi', 'Tersedia'),
(4, 'dilan 1990', 'farel', 'drama', 'Tersedia'),
(5, 'milea 1991', 'bambang', 'romantis', 'Tersedia'),
(6, 'robin hood', 'farel', 'action', 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` int(11) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `status_pinjam` varchar(30) NOT NULL DEFAULT 'Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_pinjam`, `nama_siswa`, `id_buku`, `tgl_pinjam`, `tgl_kembali`, `status_pinjam`) VALUES
(1, 'huad', 3, '2026-11-24', '2026-11-26', 'Dipinjam'),
(2, 'huad', 4, '2026-11-24', NULL, 'Dikembalikan'),
(3, 'zaki', 5, '2026-11-25', '2026-11-25', 'Dikembalikan'),
(4, 'kuki', 6, '2026-11-25', '2026-11-27', 'Dikembalikan'),
(5, 'vino', 4, '2026-11-25', '2026-11-26', 'Dipinjam'),
(6, 'kanu', 2, '2026-11-25', NULL, 'Dipinjam'),
(7, 'yati', 1, '2026-11-25', NULL, 'Dikembalikan'),
(8, 'wow', 5, '2026-11-25', NULL, 'Dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_kembali` int(11) NOT NULL,
  `id_pinjam` int(11) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`id_kembali`, `id_pinjam`, `nama_siswa`, `id_buku`, `tgl_pinjam`, `tgl_kembali`) VALUES
(1, 6, 'kanu', 2, '2026-11-25', '2026-11-30'),
(2, 6, 'kanu', 2, '2026-11-25', '2026-11-30'),
(3, 2, 'huad', 4, '2026-11-24', '2026-11-30'),
(4, 2, 'huad', 4, '2026-11-24', '2026-11-30'),
(5, 4, 'kuki', 6, '2026-11-25', '2026-11-25'),
(6, 3, 'zaki', 5, '2026-11-25', '2026-11-25'),
(7, 7, 'yati', 1, '2026-11-25', '2026-11-25'),
(8, 8, 'wow', 5, '2026-11-25', '2026-11-26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nomor_induk` varchar(50) NOT NULL,
  `kode_hash` varchar(255) NOT NULL,
  `role` enum('admin','anggota') NOT NULL DEFAULT 'anggota',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `nomor_induk`, `kode_hash`, `role`, `created_at`) VALUES
(5, 'Pak Hendra', '74829104', 'HASH_ASLI_PERTAMA_DARI_BROWSER', 'admin', '2026-11-25 07:58:34'),
(6, 'Bu Ratna', '91530827', 'HASH_ASLI_KEDUA_DARI_BROWSER', 'admin', '2026-11-25 07:58:34'),
(7, 'Vino', '79673', '$2y$10$5xKPqa7/7S5EDkJlf2aMC.e.g5KVOP1xBSv4e5fiFATVIlfsLY9Yu', 'anggota', '2026-11-26 00:40:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `fk_pinjam_buku` (`id_buku`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_kembali`),
  ADD KEY `fk_kembali_pinjam` (`id_pinjam`),
  ADD KEY `fk_kembali_buku` (`id_buku`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_kembali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_pinjam_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `fk_kembali_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kembali_pinjam` FOREIGN KEY (`id_pinjam`) REFERENCES `peminjaman` (`id_pinjam`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
