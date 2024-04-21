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
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `Id` int(10) NOT NULL,
  `HotelId` varchar(16) NOT NULL,
  `Place` varchar(50) NOT NULL,
  `HotelName` varchar(50) NOT NULL,
  `RoomType` varchar(50) NOT NULL,
  `RoomBal` int(10) NOT NULL,
  `Price` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`Id`, `HotelId`, `Place`, `HotelName`, `RoomType`, `RoomBal`, `Price`) VALUES
(1, 'VSPH01S01', 'Visakhapatnam', 'Five Elements', 'Single Room', 10, 1500),
(2, 'VSPH01D01', 'Visakhapatnam', 'Five Elements', 'Deluxe Room', 4, 4500),
(3, 'VSPH01B01', 'Visakhapatnam', 'Five Elements', 'Suite Room', 4, 6000),
(4, 'VSPH02S01', 'Visakhapatnam', 'Winsar Park', 'Single Room', 10, 1500),
(5, 'VSPH02D01', 'Visakhapatnam', 'Winsar Park', 'Deluxe Room', 6, 4500),
(6, 'VSPH02B01', 'Visakhapatnam', 'Winsar Park', 'Suite Room', 5, 6000),
(7, 'VSPH03S01', 'Visakhapatnam', 'Lorven Hotel', 'Single Room', 13, 2000),
(8, 'VSPH03D01', 'Visakhapatnam', 'Lorven Hotel', 'Deluxe Room', 6, 4500),
(9, 'VSPH03B01', 'Visakhapatnam', 'Lorven Hotel', 'Suite Room', 6, 7000),
(10, 'BZAH01S01', 'Vijayawada', 'Lemontree Premier', 'Single Room', 15, 2500),
(11, 'BZAH01D01', 'Vijayawada', 'Lemontree Premier', 'Deluxe Room', 8, 5000),
(12, 'BZAH01B01', 'Vijayawada', 'Lemontree Premier', 'Suite Room', 6, 7000),
(13, 'BZAH02S01', 'Vijayawada', 'Fab Hotel', 'Single Room', 10, 1500),
(14, 'BZAH02D01', 'Vijayawada', 'Fab Hotel', 'Deluxe Room', 5, 4500),
(15, 'BZAH02B01', 'Vijayawada', 'Fab Hotel', 'Suite Room', 4, 6000),
(16, 'BZAH03S01', 'Vijayawada', 'Novotel', 'Single Room', 20, 2500),
(17, 'BZAH03D01', 'Vijayawada', 'Novotel', 'Deluxe Room', 10, 5000),
(18, 'BZAH03B01', 'Vijayawada', 'Novotel', 'Suite Room', 10, 6500),
(19, 'TPTH01S01', 'Tirupathi', 'Pai Viceroy', 'Single Room', 10, 1500),
(20, 'TPTH01D01', 'Tirupathi', 'Pai Viceroy', 'Deluxe Room', 5, 4000),
(21, 'TPTH01B01', 'Tirupathi', 'Pai Viceroy', 'Suite Room', 4, 5500),
(22, 'TPTH02S01', 'Tirupathi', 'Ramee Guestline', 'Single Room', 7, 1000),
(23, 'TPTH02D01', 'Tirupathi', 'Ramee Guestline', 'Deluxe Room', 4, 3000),
(24, 'TPTH02B01', 'Tirupathi', 'Ramee Guestline', 'Suite Room', 3, 5000),
(25, 'ARKH01S01', 'Araku Valley', 'Tara Comforts', 'Single Room', 15, 2000),
(26, 'ARKH01D01', 'Araku Valley', 'Tara Comforts', 'Deluxe Room', 8, 3500),
(27, 'ARKH01B01', 'Araku Valley', 'Tara Comforts', 'Suite Room', 3, 5000),
(28, 'ARKH02S01', 'Araku Valley', 'At Home Resorts', 'Single Room', 12, 2500),
(29, 'ARKH02D01', 'Araku Valley', 'At Home Resorts', 'Deluxe Room', 7, 3500),
(30, 'ARKH02B01', 'Araku Valley', 'At Home Resorts', 'Suite Room', 5, 5000),
(31, 'KHTH01S01', 'Sri Kalahasthi', 'KSR Grand', 'Single Room', 13, 1500),
(32, 'KHTH01D01', 'Sri Kalahasthi', 'KSR Grand', 'Deluxe Room', 7, 3000),
(33, 'KHTH01B01', 'Sri Kalahasthi', 'KSR Grand', 'Suite Room', 4, 5000),
(34, 'KHTH02S01', 'Sri Kalahasthi', 'Mayura Valley', 'Single Room', 15, 2000),
(35, 'KHTH02D01', 'Sri Kalahasthi', 'Mayura Valley', 'Deluxe Room', 8, 4500),
(36, 'KHTH02B01', 'Sri Kalahasthi', 'Mayura Valley', 'Suite Room', 5, 6000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
