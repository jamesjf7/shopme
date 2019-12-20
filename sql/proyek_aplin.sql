-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2019 at 10:58 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyek_aplin`
--
CREATE DATABASE IF NOT EXISTS `proyek_aplin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `proyek_aplin`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `Account_ID` int(11) NOT NULL,
  `Account_Username` varchar(255) NOT NULL,
  `Account_Password` varchar(255) NOT NULL,
  `Account_Name` varchar(255) NOT NULL,
  `Account_Balance` int(11) NOT NULL,
  `Account_Image` blob NOT NULL,
  `Account_RegisterDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`Account_ID`, `Account_Username`, `Account_Password`, `Account_Name`, `Account_Balance`, `Account_Image`, `Account_RegisterDate`) VALUES
(1, 'james', '$2y$10$Aj.GsDkF8yLZdhYlBLQcBe.8UMR7hwk2m8icjvxnqtkniqWJvzI8W', 'james', 2600000, '', '2011-11-11 20:00:00'),
(2, 'ivan', '$2y$10$NaOqLVAOTvbw6qr81FX47OGBJ7meADzaDLPxTY49LyfDTDiyVtfhi', 'ivan', 0, '', '2011-12-01 20:00:00'),
(3, 'yulius', '$2y$10$8FPVaBY62WmASJ9vZAsZKunY/7YaFRcO8E/qexrHIquMsF57EJK7q', 'yulius', 510000, '', '2011-07-09 20:00:00'),
(4, 'davin', '$2y$10$NXOdk6XESP2AcAkTx9ABMuiJwzZhxPqS2uJixLiSSxEwpV8N5E/t.', 'davin', 0, '', '2011-12-10 20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `dtransaction`
--

DROP TABLE IF EXISTS `dtransaction`;
CREATE TABLE `dtransaction` (
  `Htransaction_ID` int(11) NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `Dtransaction_Price` int(11) NOT NULL,
  `Dtransaction_Qty` int(11) NOT NULL,
  `Dtransaction_Subtotal` int(11) NOT NULL,
  `Dtransaction_Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dtransaction`
--

INSERT INTO `dtransaction` (`Htransaction_ID`, `Item_ID`, `Dtransaction_Price`, `Dtransaction_Qty`, `Dtransaction_Subtotal`, `Dtransaction_Status`) VALUES
(1, 3, 500000, 2, 1000000, 0),
(2, 1, 700000, 2, 1400000, 0),
(3, 2, 400000, 2, 800000, 0),
(4, 1, 700000, 12, 8400000, 0),
(5, 2, 400000, 1, 400000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `htransaction`
--

DROP TABLE IF EXISTS `htransaction`;
CREATE TABLE `htransaction` (
  `Htransaction_ID` int(11) NOT NULL,
  `Account_ID` int(11) NOT NULL,
  `Store_ID` int(11) NOT NULL,
  `Htransaction_OrderDate` datetime NOT NULL,
  `Htransaction_ResponseDate` datetime NOT NULL,
  `Htransaction_Status` int(11) NOT NULL,
  `Htransaction_Total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `htransaction`
--

INSERT INTO `htransaction` (`Htransaction_ID`, `Account_ID`, `Store_ID`, `Htransaction_OrderDate`, `Htransaction_ResponseDate`, `Htransaction_Status`, `Htransaction_Total`) VALUES
(1, 1, 2, '2010-12-16 20:00:00', '2010-12-16 20:00:00', 0, 1000000),
(2, 2, 1, '2010-12-16 20:00:00', '2010-12-16 20:00:00', 1, 1400000),
(3, 3, 1, '2010-12-16 20:00:00', '2010-12-16 20:00:00', 1, 800000),
(4, 1, 1, '2019-11-22 08:58:18', '0000-00-00 00:00:00', 1, 8400000),
(5, 3, 1, '2019-11-22 10:53:39', '0000-00-00 00:00:00', 1, 400000);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `Item_ID` int(11) NOT NULL,
  `Store_ID` int(11) NOT NULL,
  `Item_Name` varchar(255) NOT NULL,
  `Item_Price` int(11) NOT NULL,
  `Item_Stock` int(11) NOT NULL,
  `Item_Description` varchar(255) NOT NULL,
  `Item_Image` blob NOT NULL,
  `Item_RegisterDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`Item_ID`, `Store_ID`, `Item_Name`, `Item_Price`, `Item_Stock`, `Item_Description`, `Item_Image`, `Item_RegisterDate`) VALUES
(1, 1, 'Sepatu Adidaw', 700000, 12, 'Sepatu yang adidaw kualitas trusted 100% beli sebelah kiri dapat sebelah kanan', '', '2010-12-16 20:00:00'),
(2, 1, 'Sepatu Naiki', 400000, 10, 'Sepatu yang bisa meNaiki gunung', '', '2010-12-16 20:00:00'),
(3, 2, 'Baju Diadiego', 500000, 9, 'Diadora eits tapi dia sebenarnya diego', '', '2010-12-16 20:00:00'),
(4, 3, 'Sandal Gucci', 100000, 30, 'Gucccccccccccccccccccccccccccccccccci', '', '2010-12-16 20:00:00'),
(5, 3, 'Gelang Kotak', 5000, 102, 'Geleng-geleng', '', '2010-12-16 20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `item_tag`
--

DROP TABLE IF EXISTS `item_tag`;
CREATE TABLE `item_tag` (
  `Item_ID` int(11) NOT NULL,
  `Tag_ID` int(11) NOT NULL,
  `Item_Tag_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_tag`
--

INSERT INTO `item_tag` (`Item_ID`, `Tag_ID`, `Item_Tag_Date`) VALUES
(1, 1, '2010-12-16 20:00:00'),
(1, 2, '2010-12-16 20:00:00'),
(1, 2, '2010-12-16 20:00:00'),
(4, 3, '2010-12-16 20:00:00'),
(3, 4, '2010-12-16 20:00:00'),
(5, 5, '2010-12-16 20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `Htransaction_ID` int(11) NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `Review_Rating` int(11) NOT NULL,
  `Review_Comment` varchar(255) NOT NULL,
  `Review_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`Htransaction_ID`, `Item_ID`, `Review_Rating`, `Review_Comment`, `Review_Date`) VALUES
(3, 2, 5, '', '2019-11-22 10:08:19'),
(3, 2, 5, '', '2019-11-22 10:10:49'),
(3, 2, 5, '', '2019-11-22 10:28:55'),
(5, 2, 5, '', '2019-11-22 11:18:21'),
(5, 2, 4, '', '2019-11-22 11:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE `store` (
  `Store_ID` int(11) NOT NULL,
  `Account_ID` int(11) NOT NULL,
  `Store_Name` varchar(255) NOT NULL,
  `Store_Description` varchar(255) NOT NULL,
  `Store_Image` blob NOT NULL,
  `Store_RegisterDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`Store_ID`, `Account_ID`, `Store_Name`, `Store_Description`, `Store_Image`, `Store_RegisterDate`) VALUES
(1, 1, 'Toko Apapun', 'lengkap murah berkualitas', '', '2011-11-11 20:00:00'),
(2, 2, 'Toko Murah', 'mau yang murah kesini aj', '', '2011-12-01 20:00:00'),
(3, 3, 'Toko Sejati', 'sejati mencari jati diri', '', '2011-07-09 20:00:00'),
(4, 4, 'Toko Komplit', 'komplit barangnya', '', '2011-12-10 20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `Tag_ID` int(11) NOT NULL,
  `Tag_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`Tag_ID`, `Tag_Name`) VALUES
(1, 'Hitam'),
(2, 'Sepatu'),
(3, 'Sandal'),
(4, 'Baju'),
(5, 'Aksesoris');

-- --------------------------------------------------------

--
-- Table structure for table `view`
--

DROP TABLE IF EXISTS `view`;
CREATE TABLE `view` (
  `View_ID` int(11) NOT NULL,
  `Account_ID` int(11) NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `View_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `view`
--

INSERT INTO `view` (`View_ID`, `Account_ID`, `Item_ID`, `View_Date`) VALUES
(1, 1, 1, '2010-12-16 20:00:00'),
(2, 1, 1, '2010-12-16 20:00:00'),
(3, 1, 1, '2010-12-16 20:00:00'),
(4, 1, 2, '2010-12-16 20:00:00'),
(5, 2, 2, '2010-12-16 20:00:00'),
(6, 2, 2, '2010-12-16 20:00:00'),
(7, 3, 2, '2010-12-16 20:00:00'),
(8, 1, 0, '2019-11-22 08:54:17'),
(9, 1, 0, '2019-11-22 08:55:06'),
(10, 1, 0, '2019-11-22 08:56:15'),
(11, 1, 1, '2019-11-22 08:57:58'),
(12, 1, 1, '2019-11-22 08:58:11'),
(13, 3, 0, '2019-11-22 09:06:30'),
(14, 3, 2, '2019-11-22 09:06:39'),
(15, 0, 0, '2019-11-22 09:08:55'),
(16, 3, 0, '2019-11-22 09:10:18'),
(17, 3, 0, '2019-11-22 10:53:19'),
(18, 3, 2, '2019-11-22 10:53:24'),
(19, 3, 2, '2019-11-22 10:53:29');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `Account_ID` int(11) NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `Wishlist_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`Account_ID`, `Item_ID`, `Wishlist_Date`) VALUES
(1, 1, '2010-12-16 20:00:00'),
(2, 3, '2010-12-16 20:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`Account_ID`);

--
-- Indexes for table `htransaction`
--
ALTER TABLE `htransaction`
  ADD PRIMARY KEY (`Htransaction_ID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`Item_ID`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`Store_ID`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`Tag_ID`);

--
-- Indexes for table `view`
--
ALTER TABLE `view`
  ADD PRIMARY KEY (`View_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `Account_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `htransaction`
--
ALTER TABLE `htransaction`
  MODIFY `Htransaction_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `Store_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `Tag_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `view`
--
ALTER TABLE `view`
  MODIFY `View_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
