-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2025 at 01:01 PM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bag`
--

CREATE TABLE `bag` (
  `id` int(11) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_two` varchar(255) NOT NULL,
  `image_three` varchar(255) NOT NULL,
  `rate` decimal(3,2) NOT NULL,
  `count` int(11) NOT NULL,
  `sale_state` int(1) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bag`
--

INSERT INTO `bag` (`id`, `userId`, `title`, `price`, `description`, `category_id`, `image`, `image_two`, `image_three`, `rate`, `count`, `sale_state`, `added_at`) VALUES
(115, 'vde97BfJyOVhptWu3Ll7GpRZ2ep1', 'Dell UltraSharp Monitor', 400.00, '27\" 4K UHD\nAdjustable Stand\nUSB-C Connectivity, perfect for creative professionals.', 2, 'monitor_img1.jpg', 'monitor_img2.jpg', '0', 4.00, 2, 0, '2025-01-29 18:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'notebook', '2025-01-17 14:27:51'),
(2, 'monitor', '2025-01-17 14:27:51'),
(3, 'console', '2025-01-17 14:27:51'),
(4, 'desktop', '2025-01-17 14:27:51');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_two` varchar(255) DEFAULT NULL,
  `image_three` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `rate` decimal(3,2) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sale_state` tinyint(1) DEFAULT NULL,
  `salePrice` decimal(10,2) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_favorite` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `count`, `description`, `image`, `image_two`, `image_three`, `price`, `rate`, `title`, `sale_state`, `salePrice`, `timestamp`, `is_favorite`) VALUES
(5, 1, 1, '15\" 512 GB i5 16 GB Wifi W11P\nSwitzerland/Lux Platinum\nGreat for professional use and gaming.', 'notebook_img1.jpg', 'notebook_img2.jpg', 'notebook_img3.jpg', 1200.00, 4.50, 'Microsoft Surface Laptop 6', 1, 1000.00, '2025-01-26 10:35:33', 0),
(6, 2, 1, '27\" 4K UHD\nAdjustable Stand\nUSB-C Connectivity, perfect for creative professionals.', 'monitor_img1.jpg', 'monitor_img2.jpg', 'monitor_img3.jpg', 400.00, 4.00, 'Dell UltraSharp Monitor', 1, NULL, '2025-01-26 10:35:33', 0),
(7, 3, 1, 'Latest Gaming Console\nUltra-HD with 825 GB SSD\nPerfect for high-end gaming performance.', 'console_img1.jpg', 'console_img2.jpg', 'console_img3.jpg', 499.99, 4.70, 'PlayStation 5', 1, 450.00, '2025-01-26 10:35:33', 1),
(8, 4, 1, '23.8\" FHD\nRyzen 5, 16GB RAM, 1TB SSD\nWindows 11 ready for everyday tasks.', 'desktop_img1.jpg', 'desktop_img2.jpg', 'desktop_img3.jpg', 750.00, 4.20, 'HP All-in-One PC', 0, NULL, '2025-01-26 10:35:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `created_at`) VALUES
('00pyo6DWp1dZooGeFV7LFNjGPpG3', '2025-01-30 12:17:41'),
('9EuQurqLvSUQZ4BMooUmBUXgA132', '2025-01-21 21:38:22'),
('FqadwJyVRwZGxgAkb8Nq49wjnmn2', '2025-01-22 10:07:48'),
('vde97BfJyOVhptWu3Ll7GpRZ2ep1', '2025-01-29 18:32:21'),
('zCozET00gbUyaamlidXPCOa34tN2', '2025-01-25 12:32:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bag`
--
ALTER TABLE `bag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bag`
--
ALTER TABLE `bag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bag`
--
ALTER TABLE `bag`
  ADD CONSTRAINT `bag_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `bag_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
