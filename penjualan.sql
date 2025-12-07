-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 07, 2025 at 01:05 PM
-- Server version: 10.5.29-MariaDB
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stonebon_highcharts`
--

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` int(11) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `sub_kategori` varchar(50) DEFAULT NULL,
  `produk` varchar(50) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id`, `kategori`, `sub_kategori`, `produk`, `jumlah`) VALUES
(1, 'Elektronik', 'Handphone', 'Samsung', 50),
(2, 'Elektronik', 'Handphone', 'iPhone', 40),
(3, 'Elektronik', 'Handphone', 'Xiaomi', 30),
(4, 'Elektronik', 'Laptop', 'Asus', 20),
(5, 'Elektronik', 'Laptop', 'Lenovo', 15),
(6, 'Furniture', 'Meja', 'Meja Kayu', 10),
(7, 'Furniture', 'Meja', 'Meja Kaca', 5),
(8, 'Furniture', 'Kursi', 'Kursi Kantor', 25),
(9, 'Furniture', 'Kursi', 'Kursi Gaming', 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
