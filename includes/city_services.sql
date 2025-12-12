-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 06:38 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `city_services`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `bill_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` varchar(20) DEFAULT 'Pending',
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`bill_id`, `booking_id`, `user_id`, `provider_id`, `amount`, `payment_status`, `payment_date`) VALUES
(1, 3, 2, 1, '200.00', 'Pending', '2025-11-03 12:15:44'),
(2, 3, 2, 1, '200.00', 'Pending', '2025-11-03 14:52:42'),
(3, 3, 2, 1, '200.00', 'Pending', '2025-11-03 14:53:41'),
(4, 3, 2, 1, '200.00', 'Pending', '2025-11-03 14:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `status` enum('pending','accepted','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `provider_id`, `service_id`, `booking_date`, `status`, `created_at`) VALUES
(1, 2, 1, 6, '2025-11-03 00:49:35', 'pending', '2025-11-02 23:49:35'),
(2, 2, 1, 6, '2025-11-03 00:53:14', 'pending', '2025-11-02 23:53:14'),
(3, 2, 1, 6, '2025-11-03 06:40:18', 'completed', '2025-11-03 05:40:18'),
(4, 2, 1, 6, '2025-11-03 10:42:48', 'pending', '2025-11-03 09:42:48'),
(5, 2, 1, 6, '2025-11-03 13:44:44', 'pending', '2025-11-03 12:44:44'),
(6, 2, 2, 8, '2025-11-03 14:22:05', 'pending', '2025-11-03 13:22:05'),
(7, 2, 2, 8, '2025-11-03 14:22:39', 'pending', '2025-11-03 13:22:39'),
(8, 2, 2, 8, '2025-11-03 14:22:47', 'pending', '2025-11-03 13:22:47'),
(9, 2, 1, 3, '2025-11-03 14:25:42', 'pending', '2025-11-03 13:25:42'),
(10, 2, 2, 8, '2025-11-03 14:43:13', 'pending', '2025-11-03 13:43:13'),
(11, 2, 2, 7, '2025-11-03 14:46:12', 'pending', '2025-11-03 13:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `provider_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `address` text,
  `verified` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`provider_id`, `name`, `email`, `password`, `phone`, `service_type`, `address`, `verified`, `created_at`) VALUES
(1, 'prathmesh mhetre', 'pmhetre@gmail.com', '202cb962ac59075b964b07152d234b70', '1234567890', 'plumber', 'pune', 0, '2025-11-02 23:00:30'),
(2, 'santosh', 'santosh@gmail.com', '$2y$10$jxTOXeWkaBQwefHk3q1DR.4tg0KkK5HL67R0tSbtW4oYTLx9Q7mw2', '1234567890', 'hardware', 'talegaon', 0, '2025-11-03 13:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `booking_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 3, 2, 0, 'excellent service', '2025-11-03 13:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `provider_id`, `title`, `description`, `price`, `status`, `created_at`) VALUES
(1, 1, 'electrician', 'asdfg', '200.00', 'active', '2025-11-03 18:50:14'),
(2, 1, 'electrician', 'asdfg', '200.00', 'active', '2025-11-03 18:50:14'),
(3, 1, 'electrician', 'asdfg', '200.00', 'active', '2025-11-03 18:50:14'),
(5, 1, 'electrician', 'asdfg', '200.00', 'active', '2025-11-03 18:50:14'),
(6, 1, 'electrician', 'asdfg', '200.00', 'active', '2025-11-03 18:50:14'),
(7, 2, 'hardware', 'hardware repairing', '200.00', 'active', '2025-11-03 18:50:20'),
(8, 2, 'software', 'retrive data or any other software issues', '400.00', 'active', '2025-11-03 18:51:29'),
(9, 2, 'software', 'retrive data or any other software issues', '400.00', 'active', '2025-11-03 19:29:44'),
(10, 2, 'software', 'retrive data or any other software issues', '400.00', 'active', '2025-11-03 19:34:50'),
(11, 2, 'software', 'retrive data or any other software issues', '400.00', 'active', '2025-11-03 19:38:11'),
(12, 2, 'software', 'retrive data or any other software issues', '400.00', 'active', '2025-11-03 19:44:17'),
(13, 2, 'software', 'retrive data or any other software issues', '400.00', 'active', '2025-11-03 19:48:38'),
(14, 2, 'software', 'retrive data or any other software issues', '400.00', 'active', '2025-11-03 19:52:18'),
(15, 2, 'software', 'retrive data or any other software issues', '400.00', 'active', '2025-11-03 19:56:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, 'santosh', 'santosh@gmail.com', '202cb962ac59075b964b07152d234b70', '1234567890', 'oune', '2025-11-02 22:53:56'),
(2, 'user1', 'user@gmail.com', '202cb962ac59075b964b07152d234b70', '1234567890', 'pune', '2025-11-02 23:44:40'),
(4, 'rohan', 'user2@gmail.com', '202cb962ac59075b964b07152d234b70', '1234567890', 'pune', '2025-11-03 12:01:25'),
(5, 'uswe1', 'user1@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '1234567890', 'pune', '2025-11-03 12:02:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`provider_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
  MODIFY `provider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  ADD CONSTRAINT `billing_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `billing_ibfk_3` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`);

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
