-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 05:45 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sas`
--

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `Id` int(5) NOT NULL,
  `PackageId` varchar(10) NOT NULL,
  `Place` varchar(50) NOT NULL,
  `Price` int(10) NOT NULL,
  `DPrice` int(10) NOT NULL,
  `Days` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`Id`, `PackageId`, `Place`, `Price`, `DPrice`, `Days`) VALUES
(1, 'AV001', 'Araku Valley', 5000, 3750, 3),
(3, 'VC001', 'Vizag City', 10000, 7500, 3),
(4, 'BI001', 'Bhavani Island', 5000, 3750, 2),
(5, 'KF001', 'Kondapalli Fort', 6000, 4500, 2),
(6, 'HT001', 'Hinkar Thirtha', 6000, 4500, 2),
(7, 'UC001', 'Undavalli Caves', 4000, 3000, 2),
(8, 'TI001', 'Tirupathi', 10000, 7500, 4),
(9, 'AN001', 'Anantapur', 15000, 11250, 3),
(10, 'AH001', 'Anantagiri Hills', 15000, 11250, 2),
(11, 'AV002', 'Araku Valley', 10000, 7500, 7),
(12, 'LD001', 'Ladakh', 70000, 52000, 8),
(13, 'GT001', 'Golden Triangle', 70000, 52000, 9),
(14, 'NP001', 'Nepal', 60000, 45000, 6),
(15, 'KB001', 'Kerala Backwaters', 70000, 52500, 9),
(16, 'ST001', 'South Indian Temples', 80000, 60000, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `Id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
