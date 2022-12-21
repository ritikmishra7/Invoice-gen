-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2022 at 11:36 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbms`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_details`
--

CREATE TABLE `contact_details` (
  `contact_id` int(20) NOT NULL,
  `contact_name` varchar(50) NOT NULL,
  `contact_desc` varchar(255) NOT NULL,
  `contact_email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact_details`
--

INSERT INTO `contact_details` (`contact_id`, `contact_name`, `contact_desc`, `contact_email`) VALUES
(1, 'Ritik Mishra', '', '$ritikmishra7@gmail.com'),
(2, 'Ritik Mishra', 'dfsbsevsevseffes', '$ritikmishra7@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `cust_info`
--

CREATE TABLE `cust_info` (
  `inv_id` varchar(20) NOT NULL,
  `cname` varchar(20) NOT NULL,
  `caddress` varchar(50) NOT NULL,
  `cphone` int(20) NOT NULL,
  `cemail` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cust_info`
--

INSERT INTO `cust_info` (`inv_id`, `cname`, `caddress`, `cphone`, `cemail`, `username`) VALUES
('43532323', 'NIE', 'kfn', 12433242, 'ritikmishra7@gmail.com', 'ritik'),
('5677', 'NIE', 'asdwq', 3556, 'Nie@gmail.com', 'ritik'),
('78912318949', 'Ritik', 'MANANTHVADI ROAD', 2147483647, 'ritikmishra7@gmail.com', 'prateek'),
('987666', 'TEST', 'MANANTHVADI ROAD', 2147483647, 'ritikmishra7@gmail.com', 'guru');

-- --------------------------------------------------------

--
-- Table structure for table `inv_info`
--

CREATE TABLE `inv_info` (
  `inv_id` int(20) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `item_desc` varchar(150) NOT NULL,
  `item_rate` int(50) NOT NULL,
  `item_qty` int(50) NOT NULL,
  `item_total` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inv_info`
--

INSERT INTO `inv_info` (`inv_id`, `item_name`, `item_desc`, `item_rate`, `item_qty`, `item_total`) VALUES
(5677, 'I2', 'NA', 566, 78, 44148),
(987666, 'Item1', 'ITEM DESC', 7899, 5, 39495),
(2147483647, 'Item1', 'dsve', 55, 52, 2860),
(2147483647, 'Item2', 'sndis', 800, 56, 44800),
(43532323, 'Item1', 'weknw', 5666, 2, 11332),
(43532323, 'Item2', 'dsrklgns', 400, 5, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `total_details`
--

CREATE TABLE `total_details` (
  `inv_id` varchar(20) NOT NULL,
  `subtotal` int(100) NOT NULL,
  `tax` int(10) NOT NULL,
  `discount` int(100) NOT NULL,
  `total` int(100) NOT NULL,
  `date` date NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `total_details`
--

INSERT INTO `total_details` (`inv_id`, `subtotal`, `tax`, `discount`, `total`, `date`, `username`) VALUES
('43532323', 13332, 15, 5, 14565, '2022-12-21', 'ritik'),
('5677', 44148, 18, 7, 48448, '2022-12-12', 'ritik'),
('78912318949', 47660, 18, 5, 53427, '2022-12-15', 'prateek'),
('987666', 39495, 18, 4, 44740, '2022-12-11', 'guru');

-- --------------------------------------------------------

--
-- Table structure for table `user_additional_info`
--

CREATE TABLE `user_additional_info` (
  `username` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phone` bigint(50) NOT NULL,
  `GST` varchar(20) NOT NULL,
  `inv_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_additional_info`
--

INSERT INTO `user_additional_info` (`username`, `name`, `email`, `address`, `phone`, `GST`, `inv_id`) VALUES
('ritik', 'Harshit', 'harshit@gmail.com', '123 road', 12312321, 'q3wer21', '43532323'),
('ritik', 'Guru', 'guru@gmail.com', '23 Road', 124666, '36643', '5677'),
('prateek', 'Shaukat', 'ritikmishra7@gmail.c', '2 Dilarjung Road', 8478902990, '486468464', '78912318949'),
('guru', 'trial', 'trail@gmail.com', '111,Cossipore Road', 9903400757, '12344', '987666');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `s_id` int(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`s_id`, `username`, `email`, `password`) VALUES
(8, 'guru', 'guru@email.com', '$2y$10$GeOvEBqlY5HFMUUSnhe3UOKICsmHN6NDH5w4GRK5eupWjI3nhqVT6'),
(9, 'sh', 'shaukat@email.com', '$2y$10$.UfT1hY33cIaANVdpH37A.VI0jEioq7jo5LwmLaEpaYUXv0nHcePm'),
(11, 'rm7', 'ritikmishra7@gmail.com', '$2y$10$7E3z4KZDiRp5xkjtbI9ly.UGynDxnkbsD9KeZV/borbZh/pIiMLLe'),
(12, 'ritik', 'ritikmishra7@gmail.com', '$2y$10$/kc4pg1Y4EGPmLKzf29ZM.1TWzLzm3lEdVcTV9K7pxq2NgBAt8IJ6'),
(14, 'shaukat', 'shaukat@gmail.com', '$2y$10$egeL3/wxFtA9yAU0A3ntielMqhXrqg59J./l04dJBAk4MR36EXNme'),
(15, 'prateek', 'prateek@gmail.com', '$2y$10$PvEpBKtP5zQ0MrHC251UuOu0znoXWUZHzmZQr9kUoVRrCYKB2FkMO'),
(16, '', '', '$2y$10$42BHqwN6qbJkv2W.X.Xp3umbqEQB0vHl0sD2F1s/TRzvIgOjbCRBi'),
(17, 'Guru11', 'gurumadwa@gmail.com', '$2y$10$ySUD7ks3Oqep9CKqawgyJ.ghgeS/D6Z3SkVRVscAe0Nu3Eb92jovi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `cust_info`
--
ALTER TABLE `cust_info`
  ADD UNIQUE KEY `inv_id` (`inv_id`),
  ADD UNIQUE KEY `inv_id_2` (`inv_id`);

--
-- Indexes for table `total_details`
--
ALTER TABLE `total_details`
  ADD UNIQUE KEY `inv_id` (`inv_id`),
  ADD UNIQUE KEY `inv_id_2` (`inv_id`);

--
-- Indexes for table `user_additional_info`
--
ALTER TABLE `user_additional_info`
  ADD UNIQUE KEY `inv_id` (`inv_id`),
  ADD UNIQUE KEY `inv_id_2` (`inv_id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`s_id`),
  ADD UNIQUE KEY `Name` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `contact_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `s_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
