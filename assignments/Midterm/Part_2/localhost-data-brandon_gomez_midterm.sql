-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 07, 2019 at 03:01 PM
-- Server version: 10.3.12-MariaDB
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brandon_gomez_midterm`
--

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE `form` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL,
  `fav` varchar(100) NOT NULL,
  `otherpet` varchar(25) NOT NULL,
  `number` int(11) NOT NULL,
  `cats` varchar(3) NOT NULL,
  `catscount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `form`
--

INSERT INTO `form` (`id`, `name`, `fav`, `otherpet`, `number`, `cats`, `catscount`) VALUES
(1, 'justin', 'Dog, Cat, Bird', '', 1, 'yes', 8),
(2, 'justin', 'Dog, Cat, Bird', '', 1, 'yes', 8),
(3, 'justin', 'Dog, Cat, Bird', '', 1, 'yes', 8),
(4, 'justin', 'Dog, Cat, Bird', '', 1, 'yes', 8),
(5, 'justin', 'Dog, Cat', '', 3, 'no', 0),
(6, 'justin', 'Dog, Cat', '', 3, 'no', 0),
(7, 'justin', 'Dog, Cat', '', 3, 'no', 0),
(8, 'Brandon A Gomez', 'Dog', '', 1, 'no', 0),
(9, 'Brandon A Gomez', 'Dog', '', 1, 'no', 0),
(10, 'Brandon A Gomez', 'Mouse', '', -2, 'no', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `form`
--
ALTER TABLE `form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `form`
--
ALTER TABLE `form`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
