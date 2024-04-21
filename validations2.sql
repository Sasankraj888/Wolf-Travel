-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 05:46 AM
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
-- Table structure for table `validations2`
--

CREATE TABLE `validations2` (
  `AccId` int(10) NOT NULL,
  `CardNum` bigint(16) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `UserPassword` varchar(50) NOT NULL,
  `Balance` bigint(15) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `Cvv` int(5) NOT NULL,
  `ExpDate` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `validations2`
--

INSERT INTO `validations2` (`AccId`, `CardNum`, `UserName`, `Email`, `UserPassword`, `Balance`, `Phone`, `Cvv`, `ExpDate`) VALUES
(85000000, 5412653298568745, 'MDS Vishnu Vardhan', 'v@v.in', '123456', 421000, '+918500800703', 850, '10/35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `validations2`
--
ALTER TABLE `validations2`
  ADD PRIMARY KEY (`AccId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `validations2`
--
ALTER TABLE `validations2`
  MODIFY `AccId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85000003;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
