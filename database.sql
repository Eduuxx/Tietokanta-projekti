-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2023 at 10:53 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edu`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `participants` int(11) DEFAULT 0,
  `last_timeout` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `additional_fields` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `start_date`, `end_date`, `location`, `participants`, `last_timeout`, `created_at`, `updated_at`, `additional_fields`) VALUES
(99, 'Edu', 'Edu', '2023-10-25 19:01:00', '2023-10-26 17:50:00', '0', 10, NULL, '2023-10-11 20:48:21', '2023-10-11 20:48:21', '{\"start_date\":\"2023-11-21T23:48\",\"end_date\":\"2023-12-28T04:53\",\"location\":\"Edu\",\"participants\":\"15\"}');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Ylläpitäjä'),
(2, 'Tapahtumanjärjestäjä'),
(3, 'henkilö'),
(4, 'Ylläpitäjä'),
(5, 'Tapahtumanjärjestäjä'),
(6, 'henkilö'),
(7, 'Ylläpitäjä'),
(8, 'Tapahtumanjärjestäjä'),
(9, 'henkilö');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `type`, `name`) VALUES
(26, 'title', 'sihteeri'),
(27, 'country', 'Juhannuskukkula'),
(28, 'city', 'Turun ammatti-instituutti'),
(30, 'title', 'luokanvalvoja'),
(31, 'title', 'rehtori'),
(32, 'country', 'Finland'),
(34, 'title', 'lehtori');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `reg_date`, `role_id`) VALUES
(11, 'lalala', 'lalala@hotmail.com', '$2y$10$uXF/KdZhW1dwSlIe.OVY4eLsGc0zgE/84TsBgO.H3ph/IoVih1UpW', '2023-10-11 16:27:08', NULL),
(16, 'Eddy', 'eduard.osmani@outlook.com', '$2y$10$T4LlFAQay/RTWxz5pG09AeHbo/0PdqhsOZTGZRZ0Mvyr/lIpSKWeu', '2023-10-11 22:38:09', 1),
(17, 'eduntestaus1', 'edutestaus1@hotmail.com', '$2y$10$ppb9fDBavdm0Pfc2xhwYn.GnXFaljlBGTVqZSkKip6kSZHzbyFce2', '2023-10-11 23:33:58', NULL),
(18, 'eduntestaus2', 'eduntestaus2@hotmail.com', '$2y$10$zkBgD8AsSUCmiEE2xbleaOnPHynwkM7a3z1YY6Y7XKy41r1raSJt.', '2023-10-11 23:34:10', NULL),
(19, 'eduntestaus3', 'eduntestaus3@hotmail.com', '$2y$10$oo1PXKGVieqG3VKuWUjSR.x.j9v7T/h3c6ae1mJE9PqjqLOEbgog.', '2023-10-11 23:34:18', NULL),
(20, 'eduntestaus4', 'eduntestaus4@hotmail.com', '$2y$10$VCfkz7Mi9Va5y5fSuhASDOLhILoM7RommMaqu2u8b5lHvLhWdXgn2', '2023-10-11 23:34:29', NULL),
(21, 'Eduntestaus5', 'eduntestaus5@hotmail.com', '$2y$10$Ivk2HCIPvqzFbaoTYsftduz7FzOYVsBM/6YcM.b5UAsaUTuwjZdMG', '2023-10-11 23:42:09', NULL),
(22, 'eduntestaus6', 'eduntestaus6@hotmail.com', '$2y$10$r/FHrJXTbnh1C3WEW3gNIOyC1.vLqxeEopPO1ZtdTz1TqcRxl1Rlu', '2023-10-11 23:47:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
