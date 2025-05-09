create database if not exists login_register_dollarsign;
use login_register_dollarsign;



-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2025 at 04:01 AM
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
-- Database: `login_register_dollarsign`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `imageFront` varchar(255) DEFAULT NULL,
  `imageBack` varchar(255) DEFAULT NULL,
  `isNew` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `imageFront`, `imageBack`, `isNew`, `created_at`) VALUES
(1, 'DOLLARSIGN - BLACK', 500.00, 'Elevate your streetwear with this bold t-shirt featuring graffiti-inspired graphics and powerful typography. A perfect blend of urban energy and rebellious attitude for those who embrace hustle culture.', 'images/black-front.jpg', 'images/black-back.jpg', 0, '2025-05-08 08:32:57'),
(2, 'DOLLARSIGN - WHITE', 500.00, 'Elevate your streetwear with this bold t-shirt featuring graffiti-inspired graphics and powerful typography. A perfect blend of urban energy and rebellious attitude for those who embrace hustle culture.', 'images/white-front.jpg', 'images/white-back.jpg', 0, '2025-05-08 08:32:57'),
(3, 'DOLLARSIGN - RED', 500.00, 'Elevate your streetwear with this bold t-shirt featuring graffiti-inspired graphics and powerful typography. A perfect blend of urban energy and rebellious attitude for those who embrace hustle culture.', 'images/red-front.jpg', 'images/red-back.jpg', 0, '2025-05-08 08:32:57'),
(4, 'DOLLARSIGN - BLUE', 500.00, 'Elevate your streetwear with this bold t-shirt featuring graffiti-inspired graphics and powerful typography. A perfect blend of urban energy and rebellious attitude for those who embrace hustle culture.', 'images/blue-front.jpg', 'images/blue-back.jpg', 0, '2025-05-08 08:32:57'),
(5, 'SMOKE WEED - BLACK', 500.00, 'A bold t-shirt featuring eye-catching artwork of hands exchanging cash with striking design elements and an edgy aesthetic, it\'s perfect for those who live the hustle life unapologetically.', 'images/smoke-weed-black-front.jpg', 'images/smoke-weed-black-back.jpg', 0, '2025-05-08 08:32:57'),
(6, 'SMOKE WEED - WHITE', 500.00, 'A bold t-shirt featuring eye-catching artwork of hands exchanging cash with striking design elements and an edgy aesthetic, it\'s perfect for those who live the hustle life unapologetically.', 'images/smoke-weed-white-front.jpg', 'images/smoke-weed-white-back.jpg', 0, '2025-05-08 08:32:57'),
(7, 'SMOKE WEED - RED', 500.00, 'A bold t-shirt featuring eye-catching artwork of hands exchanging cash with striking design elements and an edgy aesthetic, it\'s perfect for those who live the hustle life unapologetically.', 'images/smoke-weed-red-front.jpg', 'images/smoke-weed-red-back.jpg', 0, '2025-05-08 08:32:57'),
(8, 'SMOKE WEED - BLUE', 500.00, 'A bold t-shirt featuring eye-catching artwork of hands exchanging cash with striking design elements and an edgy aesthetic, it\'s perfect for those who live the hustle life unapologetically.', 'images/smoke-weed-blue-front.jpg', 'images/smoke-weed-blue-back.jpg', 0, '2025-05-08 08:32:57'),
(9, 'DOLLAR - BLACK', 250.00, 'A bold t-shirt with intricate paisley patterns fused with dollar-themed graphics. This monochromatic design combines streetwear edge and detailed artistry for a stylish hustle statement.', 'images/dollar-black-front.jpg', 'images/dollar-black-back.jpg', 0, '2025-05-08 08:32:57'),
(10, 'DOLLAR - WHITE', 250.00, 'A bold t-shirt with intricate paisley patterns fused with dollar-themed graphics. This monochromatic design combines streetwear edge and detailed artistry for a stylish hustle statement.', 'images/dollar-white-front.jpg', 'images/dollar-white-back.jpg', 0, '2025-05-08 08:32:57'),
(11, 'DOLLAR - RED', 250.00, 'A bold t-shirt with intricate paisley patterns fused with dollar-themed graphics. This monochromatic design combines streetwear edge and detailed artistry for a stylish hustle statement.', 'images/dollar-red-front.jpg', 'images/dollar-red-back.jpg', 0, '2025-05-08 08:32:57'),
(12, 'DOLLAR - BLUE', 250.00, 'A bold t-shirt with intricate paisley patterns fused with dollar-themed graphics. This monochromatic design combines streetwear edge and detailed artistry for a stylish hustle statement.', 'images/dollar-blue-front.jpg', 'images/dollar-blue-back.jpg', 0, '2025-05-08 08:32:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Last_Name`, `First_Name`, `Email`, `Password`, `token`, `is_active`, `created_at`, `is_admin`) VALUES
(48, 'User', 'Admin', 'yuan.amby@gmail.com', '$2y$10$iTps8Hh5f88dqrvzxf5fhunZlw.K/0NO5HwkIFQSxous6/hqMp8D2', 'e83fd953b354661545d04a908d127553da22ff24e2f68c48f29bfc5d456a0530416c638b88ffcf108f60e4dd6a16403b32bc', 0, '2025-05-08 06:59:54', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_ibfk_1` (`user_email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_ibfk_1` (`user_email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Email_2` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `users` (`Email`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `users` (`Email`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
