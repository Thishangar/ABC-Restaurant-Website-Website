-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2024 at 03:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `no` tinyint(5) NOT NULL,
  `username` varchar(15) DEFAULT NULL,
  `password` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`no`, `username`, `password`) VALUES
(1, 'Thishangar', 'thisha1234');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(2, 'vakshan', 'v@gmail.com', 'hi', '2024-09-03 09:38:00'),
(4, 'Kiru', 'kiru@gmail.com', 'Hi,\r\nPlease Accept my request', '2024-09-03 14:30:33'),
(7, 'shadshi', 'shadshishakshi@gmail.com', 'please accept my request ', '2024-09-08 05:41:06'),
(8, 'harishanth', 'hari@gmail.com', 'hello abc', '2024-09-08 05:48:24'),
(9, 'kugen', 'kugen@gmail.com', 'hello Abc', '2024-09-08 05:53:02'),
(10, 'Thishan', 'webthisha@gmail.com', 'hello thishan', '2024-09-08 05:58:56');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `phone` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `firstname`, `lastname`, `district`, `gender`, `phone`, `dob`, `address`, `email`, `password`) VALUES
(9, 'frank', 'frank', 'Anuradhapura', 'Male', '0214787845', '2024-09-25', '  jaffna\r\n', 'frank@gmail.com', '$2y$10$iecUmX98JgQMzUVzA.LxeuOLSZnjI7v9mQ.dUFRsHu2yoWlBWzQey'),
(10, 'shadshi', 'shadshu', 'Colombo', 'Male', '0987654321', '2024-09-18', '  \r\njaffna', 'sha@gmail.com', '$2y$10$Li.KxfEqAqffOpzbM/v0JOF9dNX22uqOQmhCIk6FylX2Y0sEQVhzC'),
(11, 'thisha', 'thisha', 'Colombo', 'Male', '0771254780', '2024-09-12', '  \r\ntrinco', 'tkr@gmail.com', '$2y$10$AUfaYq5FT0Venr6YA98pi..wZsdclxSKdEYdSoP9ntVPXSFeCfn8m');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `image_path`) VALUES
(1, 'swimming ', '180928_Luzzu_Lido_Day-1.jpg'),
(4, 'gym', 'gym.jpg'),
(6, 'spa', 'spa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `people` int(11) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `name`, `email`, `date`, `time`, `people`, `submitted_at`) VALUES
(6, 'athiththan', 'athi@gmail.com', '2024-10-04', '15:09:00', 13, '2024-09-03 03:12:22'),
(8, 'dinesh', 'd@gmail.com', '2024-09-20', '15:07:00', 2, '2024-09-03 09:33:23'),
(9, 'Yoganthan', 'yog@gmail.com', '1965-10-12', '20:08:00', 2, '2024-09-03 14:33:50'),
(10, 'Srikantha', 'sri@gmail.com', '1965-04-02', '20:09:00', 1, '2024-09-03 14:34:24'),
(13, 'shadshika', 'shadshishakshi@gmail.com', '2024-09-19', '16:30:00', 2, '2024-09-08 10:56:59'),
(14, 'shadshika yoganathan', 'shadshikashadshika@gmail.com', '2024-12-12', '16:36:00', 2, '2024-09-08 11:56:44'),
(15, 'shadshu', 'shadshishakshi@gmail.com', '2024-09-13', '18:52:00', 2, '2024-09-08 13:18:27');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `image_path`) VALUES
(1, 'Valet Car Parking', 'car park.jpg'),
(2, 'Delivery Service', 'Restaurant-Safe-Food-Delivery.png');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `firstname`, `lastname`, `district`, `gender`, `phone`, `date_of_birth`, `address`, `email`, `password`) VALUES
(1, 'thisha', 'thisha', 'Colombo', 'Male', '0771254780', '2024-09-24', 'jaffna\r\n', 'tkr@gmail.com', '$2y$10$E29hvTJE0F1ATz1WrUv7tOElO5WxLchTW5OBUBECY8.wTAUfMcBei'),
(2, 'hareesh', 'hareesh', 'Colombo', 'Male', '0771254780', '2024-09-03', '  jaffna\r\n', 'hare@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(3, 'uthaya', 'uthaya', 'Trincomalee', 'Male', '0775689456', '2024-09-11', 'colombo', 'uthaya@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(4, 'dinesh', 'dinesh', 'Kilinochchi', 'Male', '0701589630', '2024-10-12', 'jaffna', 'd@gmail.com', 'e10adc3949ba59abbe56e057f20f883e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `no` tinyint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
