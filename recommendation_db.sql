-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2025 at 04:09 AM
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
-- Database: `recommendation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2025-09-12 07:57:08', '2025-09-12 07:57:08'),
(2, 1, 3, 2, '2025-09-12 07:57:08', '2025-09-12 07:57:08'),
(4, 3, 6, 1, '2025-09-12 07:57:08', '2025-09-12 07:57:08'),
(5, 3, 8, 3, '2025-09-12 07:57:08', '2025-09-12 07:57:08');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `status`, `payment_method`, `payment_proof`, `approved_by`, `approved_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 2, 350000.00, 'shipped', 'transfer', 'payment_1_1757667712_aku-mini.png', 1, '2025-09-12 10:52:01', NULL, '2025-09-12 08:01:48', '2025-09-12 10:52:01'),
(2, 2, 350000.00, 'processing', 'transfer', 'payment_1.jpg', NULL, NULL, NULL, '2025-09-09 09:19:15', '2025-09-12 09:19:15'),
(3, 3, 1200000.00, 'processing', 'transfer', 'payment_2.jpg', NULL, NULL, NULL, '2025-09-10 09:19:15', '2025-09-12 09:19:15'),
(4, 4, 360000.00, 'pending', 'transfer', NULL, NULL, NULL, NULL, '2025-09-11 09:19:15', '2025-09-12 09:19:15'),
(5, 5, 15000000.00, 'paid', 'transfer', 'payment_3.jpg', NULL, NULL, NULL, '2025-09-12 09:19:15', '2025-09-12 09:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 5, 1, 350000.00, 350000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `category`, `created_at`, `is_active`) VALUES
(1, 'Laptop Gaming ASUS ROG', 'Laptop gaming dengan GPU RTX 4060, RAM 16GB', 15000000.00, 'Electronics', '2025-09-12 03:52:00', 1),
(2, 'Smartphone Samsung S23', 'Smartphone flagship dengan kamera 200MP', 12000000.00, 'Electronics', '2025-09-12 03:52:00', 1),
(3, 'Headphone Sony WH-1000XM5', 'Headphone noise cancelling premium', 4500000.00, 'Electronics', '2025-09-12 03:52:00', 1),
(4, 'Buku Pemrograman Python', 'Buku belajar Python untuk pemula hingga advanced', 250000.00, 'Books', '2025-09-12 03:52:00', 1),
(5, 'Kemeja Formal Putih', 'Kemeja formal bahan katun premium', 350000.00, 'Fashion', '2025-09-12 03:52:00', 1),
(6, 'Sepatu Running Nike', 'Sepatu lari dengan cushioning terbaik', 1200000.00, 'Sports', '2025-09-12 03:52:00', 1),
(7, 'Monitor LG 24 inch', 'Monitor IPS 1080p untuk gaming dan kerja', 2200000.00, 'Electronics', '2025-09-12 03:52:00', 1),
(8, 'Mouse Wireless Logitech', 'Mouse ergonomis untuk kerja seharian', 500000.00, 'Electronics', '2025-09-12 03:52:00', 1),
(9, 'Tas Ransuel Backpack', 'Tas laptop anti air dengan banyak compartment', 750000.00, 'Fashion', '2025-09-12 03:52:00', 1),
(10, 'Kopi Arabika 250gr', 'Biji kopi arabika premium dari Aceh', 120000.00, 'Food', '2025-09-12 03:52:00', 1),
(11, 'Keyboard Mechanical', 'Keyboard mechanical RGB 87 keys', 850000.00, 'Electronics', '2025-09-12 03:52:00', 1),
(12, 'Mouse Pad Gaming', 'Mouse pad large size dengan RGB lighting', 250000.00, 'Electronics', '2025-09-12 03:52:00', 1),
(13, 'Webcam HD 1080p', 'Webcam untuk streaming dan meeting online', 650000.00, 'Electronics', '2025-09-12 03:52:00', 1),
(14, 'Buku Data Science', 'Buku belajar data science dengan Python', 300000.00, 'Books', '2025-09-12 03:52:00', 1),
(15, 'Kaos Casual Cotton', 'Kaos casual bahan cotton comfort', 150000.00, 'Fashion', '2025-09-12 03:52:00', 1),
(16, 'ali', 'ali', 69000.00, 'Electronics', '2025-09-12 10:42:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `recommendation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recommended_product_id` int(11) NOT NULL,
  `score` decimal(10,4) NOT NULL COMMENT 'Skor similarity/prediksi',
  `algorithm` varchar(50) NOT NULL DEFAULT 'item_based_cf',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL COMMENT 'Password tanpa enkripsi untuk demo',
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `email`, `created_at`, `last_login`) VALUES
(1, 'john_doe', 'password123', 'admin', 'john@example.com', '2025-09-12 03:51:12', '2025-09-12 03:51:12'),
(2, 'jane_smith', 'pass1234', 'user', 'jane@example.com', '2025-09-12 03:51:12', '2025-09-12 03:51:12'),
(3, 'bob_wilson', 'bobpassword', 'user', 'bob@example.com', '2025-09-12 03:51:12', '2025-09-12 03:51:12'),
(4, 'alice_jones', 'alice123', 'user', 'alice@example.com', '2025-09-12 03:51:12', '2025-09-12 03:51:12'),
(5, 'charlie_brown', 'charliepass', 'user', 'charlie@example.com', '2025-09-12 03:51:12', '2025-09-12 03:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_behavior_logs`
--

CREATE TABLE `user_behavior_logs` (
  `log_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `behavior_type` enum('view','cart_add','purchase','wishlist','rating') NOT NULL,
  `behavior_value` decimal(10,2) DEFAULT NULL COMMENT 'Nilai rating atau jumlah purchase',
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_behavior_logs`
--

INSERT INTO `user_behavior_logs` (`log_id`, `user_id`, `product_id`, `behavior_type`, `behavior_value`, `session_id`, `created_at`) VALUES
(1, 1, 1, 'view', NULL, 'sess_001', '2025-09-07 03:52:00'),
(2, 1, 1, 'cart_add', NULL, 'sess_001', '2025-09-07 03:52:00'),
(3, 1, 3, 'view', NULL, 'sess_001', '2025-09-07 03:52:00'),
(4, 1, 7, 'view', NULL, 'sess_002', '2025-09-09 03:52:00'),
(5, 1, 7, 'purchase', 1.00, 'sess_002', '2025-09-09 03:52:00'),
(6, 2, 5, 'view', NULL, 'sess_003', '2025-09-08 03:52:00'),
(7, 2, 5, 'purchase', 2.00, 'sess_003', '2025-09-08 03:52:00'),
(8, 2, 9, 'view', NULL, 'sess_003', '2025-09-08 03:52:00'),
(9, 2, 4, 'view', NULL, 'sess_004', '2025-09-10 03:52:00'),
(10, 2, 4, 'wishlist', NULL, 'sess_004', '2025-09-10 03:52:00'),
(11, 3, 6, 'view', NULL, 'sess_005', '2025-09-09 03:52:00'),
(12, 3, 6, 'purchase', 1.00, 'sess_005', '2025-09-09 03:52:00'),
(13, 3, 2, 'view', NULL, 'sess_006', '2025-09-11 03:52:00'),
(14, 3, 8, 'view', NULL, 'sess_006', '2025-09-11 03:52:00'),
(15, 3, 8, 'cart_add', NULL, 'sess_006', '2025-09-11 03:52:00'),
(16, 4, 10, 'view', NULL, 'sess_007', '2025-09-10 03:52:00'),
(17, 4, 10, 'purchase', 3.00, 'sess_007', '2025-09-10 03:52:00'),
(18, 4, 4, 'view', NULL, 'sess_008', '2025-09-11 03:52:00'),
(19, 4, 3, 'view', NULL, 'sess_008', '2025-09-11 03:52:00'),
(20, 5, 1, 'view', NULL, 'sess_009', '2025-09-12 03:52:00'),
(21, 5, 2, 'view', NULL, 'sess_009', '2025-09-12 03:52:00'),
(22, 1, 2, 'view', NULL, NULL, '2025-09-12 07:07:45'),
(23, 1, 10, 'view', NULL, NULL, '2025-09-12 07:13:47'),
(24, 1, 10, 'view', NULL, NULL, '2025-09-12 07:43:43'),
(25, 1, 10, 'view', NULL, NULL, '2025-09-12 07:43:46'),
(26, 1, 10, 'view', NULL, NULL, '2025-09-12 07:51:50'),
(27, 1, 10, 'view', NULL, NULL, '2025-09-12 07:51:51'),
(28, 2, 3, 'view', NULL, NULL, '2025-09-12 08:03:19'),
(29, 2, 3, 'view', NULL, NULL, '2025-09-12 09:00:42'),
(30, 1, 5, 'view', NULL, NULL, '2025-09-13 10:11:06'),
(31, 1, 5, 'view', NULL, NULL, '2025-09-13 10:11:16'),
(32, 1, 5, 'view', NULL, NULL, '2025-09-13 10:11:23'),
(33, 1, 5, 'view', NULL, NULL, '2025-09-13 10:13:38'),
(34, 1, 5, 'cart_add', NULL, NULL, '2025-09-13 10:13:48'),
(35, 1, 5, 'view', NULL, NULL, '2025-09-13 10:13:48'),
(36, 1, 2, 'view', NULL, NULL, '2025-09-13 10:19:06'),
(37, 1, 2, 'view', NULL, NULL, '2025-09-13 10:26:10'),
(38, 1, 2, 'view', NULL, NULL, '2025-09-13 10:52:26'),
(39, 1, 2, 'wishlist', NULL, NULL, '2025-09-13 10:52:30'),
(40, 1, 2, 'view', NULL, NULL, '2025-09-13 10:52:30'),
(41, 1, 2, 'view', NULL, NULL, '2025-09-13 10:53:03'),
(42, 1, 2, 'wishlist', NULL, NULL, '2025-09-13 10:53:25'),
(43, 1, 2, 'view', NULL, NULL, '2025-09-13 10:53:25'),
(44, 1, 2, 'wishlist', NULL, NULL, '2025-09-13 10:53:27'),
(45, 1, 2, 'view', NULL, NULL, '2025-09-13 10:53:27'),
(46, 1, 2, 'wishlist', NULL, NULL, '2025-09-13 10:53:28'),
(47, 1, 2, 'view', NULL, NULL, '2025-09-13 10:53:28'),
(48, 1, 2, 'wishlist', NULL, NULL, '2025-09-13 10:53:29'),
(49, 1, 2, 'view', NULL, NULL, '2025-09-13 10:53:29'),
(50, 1, 2, 'wishlist', NULL, NULL, '2025-09-13 10:53:29'),
(51, 1, 2, 'view', NULL, NULL, '2025-09-13 10:53:30'),
(52, 1, 2, 'wishlist', NULL, NULL, '2025-09-13 10:53:30'),
(53, 1, 2, 'view', NULL, NULL, '2025-09-13 10:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `session_id` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ended_at` timestamp NULL DEFAULT NULL,
  `device_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`session_id`, `user_id`, `started_at`, `ended_at`, `device_info`) VALUES
('sess_001', 1, '2025-09-07 03:52:00', '2025-09-07 04:22:00', 'Chrome on Windows'),
('sess_002', 1, '2025-09-09 03:52:00', '2025-09-09 04:37:00', 'Firefox on Mac'),
('sess_003', 2, '2025-09-08 03:52:00', '2025-09-08 04:12:00', 'Safari on iPhone'),
('sess_004', 2, '2025-09-10 03:52:00', '2025-09-10 04:07:00', 'Chrome on Android'),
('sess_005', 3, '2025-09-09 03:52:00', '2025-09-09 04:17:00', 'Edge on Windows'),
('sess_006', 3, '2025-09-11 03:52:00', '2025-09-11 04:27:00', 'Chrome on Windows'),
('sess_007', 4, '2025-09-10 03:52:00', '2025-09-10 04:02:00', 'Safari on Mac'),
('sess_008', 4, '2025-09-11 03:52:00', '2025-09-11 04:32:00', 'Chrome on Windows'),
('sess_009', 5, '2025-09-12 03:52:00', '2025-09-12 04:12:00', 'Firefox on Linux');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_admin_dashboard`
-- (See below for the actual view)
--
CREATE TABLE `view_admin_dashboard` (
`total_users` bigint(21)
,`total_products` bigint(21)
,`today_interactions` bigint(21)
,`total_purchases` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_admin_orders`
-- (See below for the actual view)
--
CREATE TABLE `view_admin_orders` (
`order_id` int(11)
,`user_id` int(11)
,`total_amount` decimal(10,2)
,`status` enum('pending','paid','processing','shipped','completed','cancelled')
,`payment_method` varchar(50)
,`payment_proof` varchar(255)
,`approved_by` int(11)
,`approved_at` timestamp
,`rejection_reason` text
,`created_at` timestamp
,`updated_at` timestamp
,`customer_username` varchar(50)
,`customer_email` varchar(100)
,`item_count` bigint(21)
,`approver_username` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_product_popularity`
-- (See below for the actual view)
--
CREATE TABLE `view_product_popularity` (
`product_id` int(11)
,`product_name` varchar(255)
,`category` varchar(100)
,`total_interactions` bigint(21)
,`unique_users` bigint(21)
,`total_purchases` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user_analytics`
-- (See below for the actual view)
--
CREATE TABLE `view_user_analytics` (
`user_id` int(11)
,`username` varchar(50)
,`role` enum('admin','user')
,`email` varchar(100)
,`total_interactions` bigint(21)
,`last_activity` timestamp
,`total_purchases` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user_behavior_summary`
-- (See below for the actual view)
--
CREATE TABLE `view_user_behavior_summary` (
`user_id` int(11)
,`username` varchar(50)
,`product_id` int(11)
,`product_name` varchar(255)
,`category` varchar(100)
,`total_interactions` bigint(21)
,`view_count` decimal(22,0)
,`cart_add_count` decimal(22,0)
,`purchase_count` decimal(22,0)
,`last_interaction` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user_cart_count`
-- (See below for the actual view)
--
CREATE TABLE `view_user_cart_count` (
`user_id` int(11)
,`username` varchar(50)
,`cart_items_count` bigint(21)
,`total_quantity` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user_wishlists`
-- (See below for the actual view)
--
CREATE TABLE `view_user_wishlists` (
`user_id` int(11)
,`username` varchar(50)
,`product_id` int(11)
,`product_name` varchar(255)
,`price` decimal(10,2)
,`added_date` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `view_admin_dashboard`
--
DROP TABLE IF EXISTS `view_admin_dashboard`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_admin_dashboard`  AS SELECT (select count(0) from `users`) AS `total_users`, (select count(0) from `products` where `products`.`is_active` = 1) AS `total_products`, (select count(0) from `user_behavior_logs` where cast(`user_behavior_logs`.`created_at` as date) = curdate()) AS `today_interactions`, (select count(0) from `user_behavior_logs` where `user_behavior_logs`.`behavior_type` = 'purchase') AS `total_purchases` ;

-- --------------------------------------------------------

--
-- Structure for view `view_admin_orders`
--
DROP TABLE IF EXISTS `view_admin_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_admin_orders`  AS SELECT `o`.`order_id` AS `order_id`, `o`.`user_id` AS `user_id`, `o`.`total_amount` AS `total_amount`, `o`.`status` AS `status`, `o`.`payment_method` AS `payment_method`, `o`.`payment_proof` AS `payment_proof`, `o`.`approved_by` AS `approved_by`, `o`.`approved_at` AS `approved_at`, `o`.`rejection_reason` AS `rejection_reason`, `o`.`created_at` AS `created_at`, `o`.`updated_at` AS `updated_at`, `u`.`username` AS `customer_username`, `u`.`email` AS `customer_email`, count(`oi`.`order_item_id`) AS `item_count`, `a`.`username` AS `approver_username` FROM (((`orders` `o` join `users` `u` on(`o`.`user_id` = `u`.`user_id`)) left join `order_items` `oi` on(`o`.`order_id` = `oi`.`order_id`)) left join `users` `a` on(`o`.`approved_by` = `a`.`user_id`)) GROUP BY `o`.`order_id` ORDER BY `o`.`created_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_product_popularity`
--
DROP TABLE IF EXISTS `view_product_popularity`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_product_popularity`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `p`.`category` AS `category`, count(`ubl`.`log_id`) AS `total_interactions`, count(distinct `ubl`.`user_id`) AS `unique_users`, sum(case when `ubl`.`behavior_type` = 'purchase' then coalesce(`ubl`.`behavior_value`,1) else 0 end) AS `total_purchases` FROM (`products` `p` left join `user_behavior_logs` `ubl` on(`p`.`product_id` = `ubl`.`product_id`)) GROUP BY `p`.`product_id`, `p`.`product_name`, `p`.`category` ORDER BY count(`ubl`.`log_id`) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_user_analytics`
--
DROP TABLE IF EXISTS `view_user_analytics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user_analytics`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`username` AS `username`, `u`.`role` AS `role`, `u`.`email` AS `email`, count(`ubl`.`log_id`) AS `total_interactions`, max(`ubl`.`created_at`) AS `last_activity`, sum(case when `ubl`.`behavior_type` = 'purchase' then 1 else 0 end) AS `total_purchases` FROM (`users` `u` left join `user_behavior_logs` `ubl` on(`u`.`user_id` = `ubl`.`user_id`)) GROUP BY `u`.`user_id`, `u`.`username`, `u`.`role`, `u`.`email` ORDER BY count(`ubl`.`log_id`) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_user_behavior_summary`
--
DROP TABLE IF EXISTS `view_user_behavior_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user_behavior_summary`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`username` AS `username`, `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `p`.`category` AS `category`, count(0) AS `total_interactions`, sum(case when `ubl`.`behavior_type` = 'view' then 1 else 0 end) AS `view_count`, sum(case when `ubl`.`behavior_type` = 'cart_add' then 1 else 0 end) AS `cart_add_count`, sum(case when `ubl`.`behavior_type` = 'purchase' then 1 else 0 end) AS `purchase_count`, max(`ubl`.`created_at`) AS `last_interaction` FROM ((`user_behavior_logs` `ubl` join `users` `u` on(`ubl`.`user_id` = `u`.`user_id`)) join `products` `p` on(`ubl`.`product_id` = `p`.`product_id`)) GROUP BY `u`.`user_id`, `u`.`username`, `p`.`product_id`, `p`.`product_name`, `p`.`category` ;

-- --------------------------------------------------------

--
-- Structure for view `view_user_cart_count`
--
DROP TABLE IF EXISTS `view_user_cart_count`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user_cart_count`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`username` AS `username`, count(`c`.`cart_id`) AS `cart_items_count`, sum(`c`.`quantity`) AS `total_quantity` FROM (`users` `u` left join `carts` `c` on(`u`.`user_id` = `c`.`user_id`)) GROUP BY `u`.`user_id`, `u`.`username` ;

-- --------------------------------------------------------

--
-- Structure for view `view_user_wishlists`
--
DROP TABLE IF EXISTS `view_user_wishlists`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user_wishlists`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`username` AS `username`, `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `p`.`price` AS `price`, `ubl`.`created_at` AS `added_date` FROM ((`user_behavior_logs` `ubl` join `users` `u` on(`ubl`.`user_id` = `u`.`user_id`)) join `products` `p` on(`ubl`.`product_id` = `p`.`product_id`)) WHERE `ubl`.`behavior_type` = 'wishlist' ORDER BY `ubl`.`created_at` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_orders_approver` (`approved_by`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`recommendation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recommended_product_id` (`recommended_product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_behavior_logs`
--
ALTER TABLE `user_behavior_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `behavior_type` (`behavior_type`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `recommendation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_behavior_logs`
--
ALTER TABLE `user_behavior_logs`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_approver` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD CONSTRAINT `fk_recommendations_product` FOREIGN KEY (`recommended_product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_recommendations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_behavior_logs`
--
ALTER TABLE `user_behavior_logs`
  ADD CONSTRAINT `fk_user_behavior_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_behavior_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `fk_user_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
