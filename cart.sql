-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 05:44 AM
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
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartId` int(10) NOT NULL,
  `UserId` varchar(15) NOT NULL,
  `ProductId` varchar(15) NOT NULL,
  `PlaceName` varchar(50) NOT NULL,
  `HotelName` varchar(50) NOT NULL,
  `Price` int(10) NOT NULL,
  `DiscountPrice` int(10) NOT NULL,
  `Persons` int(10) NOT NULL,
  `RoomType` varchar(50) NOT NULL,
  `RoomsBooked` int(10) NOT NULL,
  `TotalPrice` int(10) NOT NULL,
  `Days` int(10) NOT NULL,
  `StartDate` date NOT NULL,
  `Activity` varchar(15) NOT NULL,
  `TransactionId` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartId`, `UserId`, `ProductId`, `PlaceName`, `HotelName`, `Price`, `DiscountPrice`, `Persons`, `RoomType`, `RoomsBooked`, `TotalPrice`, `Days`, `StartDate`, `Activity`, `TransactionId`) VALUES
(80021, '1000000', 'KB001', 'Kerala Backwaters', '', 70000, 52500, 10, '', 0, 525000, 9, '2024-10-22', 'Active', 'Purchased'),
(80022, '1000000', 'AH001', 'Anantagiri Hills', '', 15000, 11250, 4, '', 0, 60000, 2, '2025-11-22', 'Active', 'Purchased'),
(80023, '1000000', 'HT001', 'Hinkar Thirtha', '', 6000, 4500, 15, '', 0, 67500, 2, '2201-10-22', 'Active', 'Purchased'),
(80024, '1000000', 'UC001', 'Undavalli Caves', '', 4000, 3000, 5, '', 0, 20000, 2, '2024-10-22', 'Active', 'Purchased'),
(80025, '1000000', 'TI001', 'Tirupathi', '', 10000, 7500, 15, '', 0, 112500, 4, '2034-10-10', 'Active', 'Purchased'),
(80026, '1000000', 'GT001', 'Golden Triangle', '', 70000, 52000, 2, '', 0, 140000, 9, '2024-10-22', 'Active', 'Purchased'),
(80027, '1000000', 'VC001', 'Vizag City', '', 10000, 7500, 2, '', 0, 20000, 3, '2024-05-31', 'Active', 'Purchased'),
(80031, '1000000', 'VSPH02D01', 'Visakhapatnam', 'Winsar Park', 4500, 3375, 8, 'Deluxe Room', 2, 6750, 1, '2024-10-22', 'Active', 'Purchased'),
(80032, '1000000', 'ARKH02D01', 'Araku Valley', 'At Home Resorts', 3500, 2625, 6, 'Deluxe Room', 2, 5250, 1, '2024-03-27', 'Active', 'Purchased'),
(80033, '1000018', 'VSPH01D01', 'Visakhapatnam', 'Five Elements', 4500, 3375, 5, 'Deluxe Room', 2, 9000, 1, '2024-04-23', 'Active', 'Purchased'),
(80034, '1000018', 'UC001', 'Undavalli Caves', '', 4000, 3000, 6, '', 0, 0, 2, '2024-04-23', 'Inactive', 'Cart'),
(80035, '1000018', 'GT001', 'Golden Triangle', '', 70000, 52000, 6, '', 0, 420000, 9, '2024-04-24', 'Active', 'Purchased');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80036;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
