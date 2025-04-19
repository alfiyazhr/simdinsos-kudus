-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2025 at 01:42 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_link` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `admin_remarks` text DEFAULT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `status` enum('pending','done','completed','rejected') DEFAULT NULL,
  `is_received` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `task_link`, `description`, `admin_remarks`, `year`, `month`, `status`, `is_received`, `created_at`, `updated_at`) VALUES
(2, 'tugas baru', 'www.tugasbaru.com', 'tugas baru', 'sudah lengkap', 2025, 2, 'done', 0, '2025-02-10 06:49:07', '2025-02-17 00:22:10'),
(6, 'tugas baru', 'www.tugasbaru.com', 'ajb2hwbs', NULL, 2025, 2, 'completed', 0, '2025-02-11 08:13:29', '2025-02-16 08:00:43'),
(7, 'tugasssss', 'https://docs.google.com/spreadsheets/d/1JXUqQ0yOJfyQSkL9tKg-fKih079G1vYmqquhvqQQJEU/edit?usp=sharing', 'ndanggg garapppp', NULL, 2025, 2, 'pending', 0, '2025-02-17 01:00:41', '2025-02-19 02:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `created_at`) VALUES
(1, 'user', '$2y$10$GKUbfYXxKAAqSWnjpx.VouIaai1I.f1QSq4/t31Ry7B1/X4.DIMPO', '2025-02-10 07:02:58'),
(3, 'fiya', '$2y$10$qOgnuwe2WcWTFRYzE0F1w.n6kpqAsgdG6fIJ5FCL2uyQAjCm0cJte', '2025-02-17 00:30:32'),
(4, 'dinsosp3ap2kb', '$2y$10$baDyx.X71R8itgZjkSZ4weZAIp1uXnzongnEb2VhXyngMAg0Kqpii', '2025-02-20 06:57:43'),
(6, 'fiyaa', '$2y$10$6yvJSshmKINboeV2JY/aiegft2vTtAwxshvCjTOr.lLKJxc51n8FO', '2025-02-20 07:00:06'),
(9, 'fiyaaa', '$2y$10$ZN.4mlnjpotxcFz/LHHutOi3/nkYdmEgjr0PrV28w7bVwYFR3s3Iu', '2025-02-20 07:01:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `idx_tasks_year_month` (`year`,`month`),
  ADD KEY `idx_tasks_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
