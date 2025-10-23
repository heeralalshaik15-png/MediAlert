-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2025 at 04:29 PM
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
-- Database: `medialert_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `caretaker_id` int(11) DEFAULT NULL,
  `type` enum('sos','missed_dose') NOT NULL,
  `message` text NOT NULL,
  `is_resolved` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alert_id`, `user_id`, `caretaker_id`, `type`, `message`, `is_resolved`, `created_at`) VALUES
(5, 3, 2, '', 'Eat at 13', 0, '2025-10-16 10:05:22'),
(6, 3, 2, '', 'Eat at 13', 0, '2025-10-16 10:15:48'),
(8, 3, 2, '', 'hi', 0, '2025-10-16 10:57:05'),
(13, 3, 2, '', 'You have a new appointment scheduled on 2025-10-17 at 01:03: dscc', 0, '2025-10-16 23:01:29'),
(14, 3, 2, '', 'Medicine missed: asdf1 (1) at 03:13 on 2025-10-16.', 0, '2025-10-16 23:14:00');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `date`, `time`, `description`, `created_at`) VALUES
(1, 1, '2025-10-17', '02:55:00', 'yes', '2025-10-15 18:29:16'),
(2, 1, '2025-10-17', '02:55:00', 'yes', '2025-10-15 18:29:20'),
(3, 1, '2025-10-17', '00:20:00', 'yes', '2025-10-15 18:48:32'),
(4, 3, '2025-10-03', '10:52:00', 'duiygftygffg', '2025-10-16 05:21:46'),
(5, 1, '2025-10-17', '23:19:00', 'yes', '2025-10-16 16:49:53'),
(6, 1, '2025-10-17', '23:19:00', 'yes', '2025-10-16 16:55:19'),
(7, 1, '2025-10-17', '23:19:00', 'yes', '2025-10-16 16:55:36'),
(8, 1, '2025-10-17', '23:19:00', 'yes', '2025-10-16 16:56:16'),
(9, 1, '2025-10-17', '22:28:00', 'yes', '2025-10-16 16:56:43'),
(10, 3, '2025-10-17', '01:03:00', 'dscc', '2025-10-16 17:31:29');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `specialization` varchar(150) DEFAULT '',
  `contact` varchar(30) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doc_id`, `user_id`, `name`, `specialization`, `contact`, `created_at`) VALUES
(4, 1, 'Sulthan', 'Physician', '9856321474', '2025-10-17 09:34:07'),
(5, 5, 'bkjm', 'jhmgb', '79526415245', '2025-10-17 09:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `medications`
--

CREATE TABLE `medications` (
  `med_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `dosage` varchar(100) DEFAULT '',
  `time` time NOT NULL,
  `added_by` int(11) NOT NULL,
  `status` enum('pending','taken','missed') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('patient','caretaker') NOT NULL,
  `linked_user` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `linked_user`, `created_at`) VALUES
(1, 'asdf', 'asdf@gmail.com', '$2y$10$Y/FIJyf7sntGFnZHNvPHSOtu8XKyaORtC9jxRpf8vMYL3ohwu1go2', 'patient', 2, '2025-10-15 18:28:49'),
(2, 'qwer', 'qwer@gmail.com', '$2y$10$GtaNjy9pRGaaE/opAQLTJuVXbyUR47SiKDit9m4KmbibhNCitard2', 'caretaker', 1, '2025-10-15 20:19:22'),
(3, 'asdf1', 'asdf1@gmail.com', '$2y$10$YGm.ccxMUTqq.DEVJRLO5u/daSJlYkIQGthw9SDZJVzdNtEFE750G', 'patient', 2, '2025-10-16 09:28:58'),
(4, 'viswa', 'viswa@gmail.com', '$2y$10$hlRs/YH0fG5EzFNo89YWe.6v6qaXNLmYe07eMAHdcoULyedE5AbUu', 'patient', NULL, '2025-10-17 09:36:32'),
(5, 'viswa', 'hi@gmail.com', '$2y$10$eQwI/Z6LCw5kuaGloczx1uqQi8GLcsmBuyMJiQVcpe/tunfizNyim', 'patient', NULL, '2025-10-17 09:39:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `caretaker_id` (`caretaker_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`med_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medications`
--
ALTER TABLE `medications`
  MODIFY `med_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alerts_ibfk_2` FOREIGN KEY (`caretaker_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `medications`
--
ALTER TABLE `medications`
  ADD CONSTRAINT `medications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
