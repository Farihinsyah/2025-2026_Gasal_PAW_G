-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2025 at 02:49 PM
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
-- Database: `store`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(10) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG011', 'Kompor Gas 2 Tungku', 420000, 40, 1),
(2, 'BRG012', 'Dispenser Air Panas', 370000, 25, 2),
(3, 'BRG013', 'Setrika Listrik', 280000, 60, 3),
(4, 'BRG014', 'Rice Cooker 1.8L', 490000, 30, 4),
(5, 'BRG015', 'Blender Serbaguna', 330000, 35, 5),
(6, 'BRG016', 'Microwave Oven', 1200000, 15, 6),
(7, 'BRG017', 'Speaker Bluetooth', 250000, 45, 7),
(8, 'BRG018', 'Vacuum Cleaner', 850000, 20, 8),
(9, 'BRG019', 'Mesin Cuci 7 Kg', 2300000, 12, 9),
(10, 'BRG020', 'AC 1 PK', 3400000, 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
(1, 'Rafi', 'L', '081222334455', 'Yogyakarta'),
(2, 'Nadia', 'P', '081233445566', 'Surakarta'),
(3, 'Bagus', 'L', '081244556677', 'Banjarmasin'),
(4, 'Cindy', 'P', '081255667788', 'Palembang'),
(5, 'Rizky', 'L', '081266778899', 'Balikpapan'),
(6, 'Tia', 'P', '081277889900', 'Manado'),
(7, 'Andra', 'L', '081288990011', 'Pekanbaru'),
(8, 'Lina', 'P', '081299001122', 'Samarinda'),
(9, 'Yoga', 'L', '081300112233', 'Batam'),
(10, 'Salsa', 'P', '081311223344', 'Tasikmalaya');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `metode` enum('TUNAI','TRANSFER','EDC') DEFAULT NULL,
  `transaksi_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2025-10-26 09:35:00', 1850000, 'TUNAI', 1),
(2, '2025-10-26 10:50:00', 750000, 'TRANSFER', 2),
(3, '2025-10-26 11:25:00', 2900000, 'TUNAI', 3),
(4, '2025-10-26 12:10:00', 1500000, 'EDC', 4),
(5, '2025-10-26 13:15:00', 2300000, 'TUNAI', 5),
(6, '2025-10-26 14:30:00', 610000, 'TRANSFER', 6),
(7, '2025-10-26 15:20:00', 4600000, 'EDC', 7),
(8, '2025-10-26 16:45:00', 670000, 'TRANSFER', 8),
(9, '2025-10-26 17:35:00', 1250000, 'TUNAI', 9),
(10, '2025-10-26 18:25:00', 3000000, 'EDC', 10);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT Prima Jaya', '021987654', 'Jalan Mawar 2, Bekasi'),
(2, 'CV Mulia Sejahtera', '022654321', 'Jalan anggrek 21, Cirebon'),
(3, 'UD Sukses Bersama', '031765432', 'Jalan Soekarno 12, Kediri'),
(4, 'PT Gemilang Makmur', '036143210', 'Jalan melati 3, Gianyar'),
(5, 'CV Karya Indah', '041176543', 'Jalan Bau Massepe 1, Parepare'),
(6, 'PT Cipta Mandiri', '061234765', 'Jalan Gatot Subroto 7, Binjai'),
(7, 'CV Tunas Abadi', '075123654', 'Jalan Istana 15, Bukittinggi'),
(8, 'PT Sinar Mulya', '056145678', 'Jalan Bhayangkara 23, Bontang'),
(9, 'UD Cahaya Baru', '071789456', 'Jalan Gajah Mada 21, Jambi'),
(10, 'PT Berkah Lestari', '024123987', 'Jalan Pancasila 19, Tegal'),
(13, 'PT Gold Planet', '027678267', 'Jalan Manyar 32, Gresik'),
(23, 'PT Indodrink and food', '028367287', 'Jalan Cendana 34, blok 2, Jakarta');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `waktu_transaksi` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `pelanggan_id` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2025-10-26', 'Pembelian alat rumah tangga', 1850000, 1),
(2, '2025-10-26', 'Pembelian perlengkapan dapur', 750000, 2),
(3, '2025-10-26', 'Pembelian peralatan elektronik', 2900000, 3),
(4, '2025-10-26', 'Pembelian kebutuhan rumah', 1500000, 4),
(5, '2025-10-26', 'Pembelian mesin cuci', 2300000, 5),
(6, '2025-10-26', 'Pembelian setrika dan blender', 610000, 6),
(7, '2025-10-26', 'Pembelian AC dan microwave', 4600000, 7),
(8, '2025-10-26', 'Pembelian speaker dan kompor', 670000, 8),
(9, '2025-10-26', 'Pembelian peralatan dapur lengkap', 1250000, 9),
(10, '2025-10-26', 'Pembelian barang elektronik rumah tangga', 3000000, 10);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `transaksi_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `harga` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qty`) VALUES
(1, 1, 420000, 2),
(2, 5, 330000, 2),
(3, 9, 2300000, 1),
(4, 2, 370000, 2),
(5, 9, 2300000, 1),
(6, 5, 330000, 1),
(7, 10, 3400000, 1),
(8, 1, 420000, 1),
(9, 4, 490000, 1),
(10, 9, 2300000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` tinyint(2) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(35) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'superadmin', 'f35364bc808b079853de5a1e343e7159', 'Super Administrator', 'Head Office', '081111111111', 1),
(2, 'operator1', '2407bd807d6ca01d1bcd766c730cec9a', 'Operator Gudang', 'Bekasi', '081222222222', 0),
(3, 'operator2', 'b3b21b37dcd4d70f9361b204844816ce', 'Operator Kasir', 'Cirebon', '081333333333', 0),
(4, 'manager1', '253d7996526921b36e4ff23d11a7a80c', 'Manager Cabang', 'Surabaya', '081444444444', 1),
(5, 'staffgudang', 'c898e0dc8c4d67bdf6b2c7e6d4b24b9b', 'Petugas Gudang', 'Semarang', '081555555555', 0),
(6, 'staffpengiriman', 'ad44af8a55b40a4ee8819102175f6c60', 'Petugas Pengiriman', 'Makassar', '081666666666', 0),
(7, 'support2', 'b3436f425e7967066fc2fbaaa3b3c17d', 'Customer Support', 'Medan', '081777777777', 0),
(8, 'itmanager', '62427e564d06ea7b2e75365c2bd61ab9', 'IT Manager', 'Denpasar', '081888888888', 1),
(9, 'auditor2', 'd1b700d86fb89b808da841c6efc16844', 'Auditor Operasional', 'Yogyakarta', '081999999999', 1),
(10, 'owner2', '7e92c7635c29d1c5c7907de782b57b90', 'Pemilik Baru', 'Jakarta', '082000000000', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`transaksi_id`,`barang_id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`);

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`),
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
