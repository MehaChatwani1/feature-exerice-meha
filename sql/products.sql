-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2022 at 09:24 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoppingcart`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `offer_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '''N'' -> active, ''Y'' -> Soft Delete',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `qty`, `price`, `offer_values`, `is_deleted`, `updated_at`, `created_at`) VALUES
(1, 'A', 11, '50.00', '[{\"qty\":\"3\",\"price\":\"130.00\", \"add\":\"\"}]', 'N', '2022-11-05 13:05:43', '2022-11-05 13:05:43'),
(2, 'B', 5, '30.00', '[{\"qty\":\"2\",\"price\":\"40.00\", \"add\":\"\"}]', 'N', '2022-11-05 13:05:43', '2022-11-05 13:05:43'),
(3, 'C', 10, '20.00', '[{\"qty\":\"3\",\"price\":\"50.00\",\"add\":\"\"},{\"qty\":\"2\",\"price\":\"38.00\",\"add\":\"\"}]', 'N', '2022-11-05 13:13:23', '2022-11-05 13:13:23'),
(4, 'D', 15, '15.00', '[{\"qty\":\"2\",\"price\":\"5.00\",\"add\":\"A\"}]', 'N', '2022-11-05 13:13:23', '2022-11-05 13:13:23'),
(5, 'E', 12, '5.00', NULL, 'N', '2022-11-05 13:19:35', '2022-11-05 13:19:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
