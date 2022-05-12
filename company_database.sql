-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2022 at 08:07 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `company_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `CompanyID` int(10) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`CompanyID`, `Name`) VALUES
(1, 'Best Buy'),
(2, 'Google'),
(3, 'Frontier'),
(4, 'Comcast but good'),
(5, 'Xfinity'),
(6, 'CableOne');

-- --------------------------------------------------------

--
-- Table structure for table `internet`
--

CREATE TABLE `internet` (
  `InternetID` int(10) NOT NULL,
  `DownloadSpeed` int(10) NOT NULL,
  `UploadSpeed` int(10) NOT NULL,
  `DataCap` int(10) NOT NULL,
  `InternetType` varchar(50) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `internet`
--

INSERT INTO `internet` (`InternetID`, `DownloadSpeed`, `UploadSpeed`, `DataCap`, `InternetType`, `Name`) VALUES
(1, 10, 5, 1000, 'cable', 'cable-10-5'),
(2, 50, 10, 1500, 'cable', 'cable-50-10'),
(3, 100, 100, 2000, 'cable', 'cable-100-100'),
(4, 250, 100, 2500, 'cable', 'cable-250-100'),
(5, 1000, 1000, 100000, 'fiber', 'fiber-1000-1000'),
(6, 2000, 2000, 100000, 'fiber', 'fiber-2000-2000'),
(7, 25, 10, 1000, 'cable', 'cable-25-10');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `CompanyID` int(10) NOT NULL,
  `PlanID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`CompanyID`, `PlanID`) VALUES
(1, 1),
(2, 1),
(4, 1),
(6, 1),
(1, 2),
(3, 2),
(6, 2),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(2, 4),
(5, 4),
(6, 4),
(3, 5),
(2, 6),
(3, 6),
(6, 6),
(4, 7),
(5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE `phones` (
  `PhoneID` int(10) NOT NULL,
  `ScreenSize` int(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Manufacturer` varchar(50) NOT NULL,
  `RefreshRate` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `phones`
--

INSERT INTO `phones` (`PhoneID`, `ScreenSize`, `Name`, `Manufacturer`, `RefreshRate`) VALUES
(1, 7, 'iPhone 21 Max', 'Apple', 120),
(2, 6, 'iPhone 21', 'Apple', 60),
(3, 5, 'iPhone 21 Mini', 'Apple', 60),
(4, 7, 'Note 20 Plus', 'Samsung', 120),
(5, 6, 'Note 19 Plus', 'Samsung', 120),
(6, 5, 'Note 19', 'Samsung', 60),
(7, 2, 'Nokia Brick', 'Nokia', 50),
(8, 6, 'Pixel 14', 'Google', 60),
(9, 6, 'Pixel Pro', 'Google', 120);

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `Price` int(10) NOT NULL,
  `PlanID` int(10) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`Price`, `PlanID`, `Name`) VALUES
(150, 1, 'Big Plan'),
(250, 2, 'Corporation Plan'),
(100, 3, 'Family Plan'),
(300, 4, 'Mega Business Plan'),
(50, 5, 'Empty Wallet Plan'),
(75, 6, 'Starter Plan'),
(125, 7, 'Family Plus Plan');

-- --------------------------------------------------------

--
-- Table structure for table `provides`
--

CREATE TABLE `provides` (
  `PlanID` int(10) NOT NULL,
  `ItemID` int(10) NOT NULL,
  `ItemType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `provides`
--

INSERT INTO `provides` (`PlanID`, `ItemID`, `ItemType`) VALUES
(5, 3, 'phone'),
(5, 1, 'internet'),
(6, 2, 'internet'),
(6, 2, 'television'),
(7, 2, 'internet'),
(7, 3, 'television'),
(7, 3, 'phone'),
(4, 7, 'television'),
(4, 6, 'internet'),
(4, 5, 'television'),
(4, 1, 'phone'),
(4, 4, 'phone'),
(4, 2, 'phone'),
(4, 5, 'phone'),
(2, 3, 'television'),
(2, 6, 'phone'),
(2, 4, 'phone'),
(2, 2, 'phone'),
(2, 5, 'internet'),
(2, 4, 'television'),
(1, 4, 'internet'),
(1, 2, 'phone'),
(1, 4, 'phone'),
(1, 2, 'television'),
(1, 3, 'phone'),
(3, 3, 'internet'),
(3, 7, 'television'),
(3, 3, 'phone'),
(3, 6, 'phone');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `UserID` int(10) NOT NULL,
  `PlanID` int(10) NOT NULL,
  `ShippingID` int(10) NOT NULL,
  `Date` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`UserID`, `PlanID`, `ShippingID`, `Date`) VALUES
(2, 5, 3, '05/01/2022'),
(3, 2, 1, '04/11/2022'),
(5, 1, 3, '05/11/2022'),
(6, 5, 4, '04/02/2022');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `ShippingID` int(10) NOT NULL,
  `Price` int(10) NOT NULL,
  `Speed` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`ShippingID`, `Price`, `Speed`) VALUES
(1, 65, 'Overnight Air'),
(2, 35, 'Next Day Express'),
(3, 15, '3-4 Business Days'),
(4, 0, '1 Week Minimum');

-- --------------------------------------------------------

--
-- Table structure for table `televisions`
--

CREATE TABLE `televisions` (
  `TVID` int(10) NOT NULL,
  `Size` int(10) NOT NULL,
  `ScreenType` varchar(50) NOT NULL,
  `HDR` varchar(5) NOT NULL,
  `Resolution` varchar(20) NOT NULL,
  `RefreshRate` int(5) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Manufacturer` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `televisions`
--

INSERT INTO `televisions` (`TVID`, `Size`, `ScreenType`, `HDR`, `Resolution`, `RefreshRate`, `Name`, `Manufacturer`) VALUES
(1, 32, 'QLED', 'no', 'FHD', 60, 'LED3260', 'Samsung'),
(2, 40, 'QLED', 'no', '4K', 60, 'QLED4060', 'Samsung'),
(3, 43, 'QLED', 'yes', '4K', 120, 'QLED43120', 'Samsung'),
(4, 50, 'QLED', 'yes', '4K', 120, 'QLED50120', 'Samsung'),
(5, 60, 'OLED', 'yes', '4K', 120, 'OLED60120', 'Samsung'),
(6, 30, 'QLED', 'no', 'FHD', 60, 'LED3060', 'Samsung'),
(7, 55, 'QLED', 'yes', '4K', 60, 'LED5060', 'Samsung');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(12) NOT NULL,
  `password` varchar(12) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `isCustomer` tinyint(1) NOT NULL,
  `isCompany` tinyint(1) NOT NULL,
  `UserID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `isAdmin`, `isCustomer`, `isCompany`, `UserID`) VALUES
('admin', 'password', 1, 0, 0, 1),
('chandler', 'chandlerpass', 0, 1, 0, 2),
('gabe', 'gabepass', 0, 1, 0, 3),
('GoogleAcc', 'googlepass', 0, 0, 1, 4),
('hackerman', '01_sd72?*', 0, 1, 0, 5),
('iwantbuy', 'dogowner77', 0, 1, 0, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`CompanyID`);

--
-- Indexes for table `internet`
--
ALTER TABLE `internet`
  ADD PRIMARY KEY (`InternetID`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`PlanID`,`CompanyID`);

--
-- Indexes for table `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`PhoneID`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`PlanID`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`ShippingID`);

--
-- Indexes for table `televisions`
--
ALTER TABLE `televisions`
  ADD PRIMARY KEY (`TVID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
