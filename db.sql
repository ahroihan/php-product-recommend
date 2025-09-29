-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               MariaDB 10.6.12
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40101 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40101 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Create database
--
CREATE DATABASE IF NOT EXISTS `recommendation_db` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `recommendation_db`;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(50) NOT NULL COMMENT 'Password tanpa enkripsi untuk demo',
  `role` ENUM('admin','user') NOT NULL DEFAULT 'user',
  `email` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `products`
--
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `category` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `user_behavior_logs`
-- Ini tabel utama untuk menyimpan perilaku user
--
DROP TABLE IF EXISTS `user_behavior_logs`;
CREATE TABLE `user_behavior_logs` (
  `log_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `behavior_type` ENUM('view','cart_add','purchase','wishlist','rating') NOT NULL,
  `behavior_value` DECIMAL(10,2) DEFAULT NULL COMMENT 'Nilai rating atau jumlah purchase',
  `session_id` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `behavior_type` (`behavior_type`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_user_behavior_user` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_behavior_product` 
    FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `user_sessions`
--
DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE `user_sessions` (
  `session_id` VARCHAR(100) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `started_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ended_at` TIMESTAMP NULL DEFAULT NULL,
  `device_info` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_user_sessions_user` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel keranjang belanja
CREATE TABLE IF NOT EXISTS `carts` (
  `cart_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  UNIQUE KEY `unique_cart_item` (`user_id`, `product_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_carts_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel orders/pesanan
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','paid','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` VARCHAR(50) DEFAULT NULL,
  `payment_proof` VARCHAR(255) DEFAULT NULL,
  `approved_by` INT(11) NULL,
  `approved_at` TIMESTAMP NULL,
  `rejection_reason` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_orders_approver` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel order items
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `recommendations`
-- Untuk menyimpan hasil rekomendasi yang sudah di-generate
--
DROP TABLE IF EXISTS `recommendations`;
CREATE TABLE `recommendations` (
  `recommendation_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `recommended_product_id` INT(11) NOT NULL,
  `score` DECIMAL(10,4) NOT NULL COMMENT 'Skor similarity/prediksi',
  `algorithm` VARCHAR(50) NOT NULL DEFAULT 'item_based_cf',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`recommendation_id`),
  KEY `user_id` (`user_id`),
  KEY `recommended_product_id` (`recommended_product_id`),
  CONSTRAINT `fk_recommendations_user` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_recommendations_product` 
    FOREIGN KEY (`recommended_product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
-- Password tanpa enkripsi (hanya untuk demo/testing)
--
INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `email`, `created_at`, `last_login`) VALUES
(1, 'john_doe', 'password123', 'admin', 'john@example.com', NOW(), NOW()),
(2, 'jane_smith', 'pass1234', 'user', 'jane@example.com', NOW(), NOW()),
(3, 'bob_wilson', 'bobpassword', 'user', 'bob@example.com', NOW(), NOW()),
(4, 'alice_jones', 'alice123', 'user', 'alice@example.com', NOW(), NOW()),
(5, 'charlie_brown', 'charliepass', 'user', 'charlie@example.com', NOW(), NOW());

--
-- Dumping data for table `products`
--
INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `category`, `created_at`, `is_active`) VALUES
(1, 'Laptop Gaming ASUS ROG', 'Laptop gaming dengan GPU RTX 4060, RAM 16GB', 15000000.00, 'Electronics', NOW(), 1),
(2, 'Smartphone Samsung S23', 'Smartphone flagship dengan kamera 200MP', 12000000.00, 'Electronics', NOW(), 1),
(3, 'Headphone Sony WH-1000XM5', 'Headphone noise cancelling premium', 4500000.00, 'Electronics', NOW(), 1),
(4, 'Buku Pemrograman Python', 'Buku belajar Python untuk pemula hingga advanced', 250000.00, 'Books', NOW(), 1),
(5, 'Kemeja Formal Putih', 'Kemeja formal bahan katun premium', 350000.00, 'Fashion', NOW(), 1),
(6, 'Sepatu Running Nike', 'Sepatu lari dengan cushioning terbaik', 1200000.00, 'Sports', NOW(), 1),
(7, 'Monitor LG 24 inch', 'Monitor IPS 1080p untuk gaming dan kerja', 2200000.00, 'Electronics', NOW(), 1),
(8, 'Mouse Wireless Logitech', 'Mouse ergonomis untuk kerja seharian', 500000.00, 'Electronics', NOW(), 1),
(9, 'Tas Ransuel Backpack', 'Tas laptop anti air dengan banyak compartment', 750000.00, 'Fashion', NOW(), 1),
(10, 'Kopi Arabika 250gr', 'Biji kopi arabika premium dari Aceh', 120000.00, 'Food', NOW(), 1),
(11, 'Keyboard Mechanical', 'Keyboard mechanical RGB 87 keys', 850000.00, 'Electronics', NOW(), 1),
(12, 'Mouse Pad Gaming', 'Mouse pad large size dengan RGB lighting', 250000.00, 'Electronics', NOW(), 1),
(13, 'Webcam HD 1080p', 'Webcam untuk streaming dan meeting online', 650000.00, 'Electronics', NOW(), 1),
(14, 'Buku Data Science', 'Buku belajar data science dengan Python', 300000.00, 'Books', NOW(), 1),
(15, 'Kaos Casual Cotton', 'Kaos casual bahan cotton comfort', 150000.00, 'Fashion', NOW(), 1);

--
-- Dumping data for table `user_behavior_logs`
-- Data dummy untuk mensimulasikan perilaku user
--
INSERT INTO `user_behavior_logs` (`user_id`, `product_id`, `behavior_type`, `behavior_value`, `session_id`, `created_at`) VALUES
-- User 1 (john_doe) - tertarik electronics
(1, 1, 'view', NULL, 'sess_001', NOW() - INTERVAL 5 DAY),
(1, 1, 'cart_add', NULL, 'sess_001', NOW() - INTERVAL 5 DAY),
(1, 3, 'view', NULL, 'sess_001', NOW() - INTERVAL 5 DAY),
(1, 7, 'view', NULL, 'sess_002', NOW() - INTERVAL 3 DAY),
(1, 7, 'purchase', 1.00, 'sess_002', NOW() - INTERVAL 3 DAY),

-- User 2 (jane_smith) - tertarik fashion dan books
(2, 5, 'view', NULL, 'sess_003', NOW() - INTERVAL 4 DAY),
(2, 5, 'purchase', 2.00, 'sess_003', NOW() - INTERVAL 4 DAY),
(2, 9, 'view', NULL, 'sess_003', NOW() - INTERVAL 4 DAY),
(2, 4, 'view', NULL, 'sess_004', NOW() - INTERVAL 2 DAY),
(2, 4, 'wishlist', NULL, 'sess_004', NOW() - INTERVAL 2 DAY),

-- User 3 (bob_wilson) - tertarik sports dan electronics
(3, 6, 'view', NULL, 'sess_005', NOW() - INTERVAL 3 DAY),
(3, 6, 'purchase', 1.00, 'sess_005', NOW() - INTERVAL 3 DAY),
(3, 2, 'view', NULL, 'sess_006', NOW() - INTERVAL 1 DAY),
(3, 8, 'view', NULL, 'sess_006', NOW() - INTERVAL 1 DAY),
(3, 8, 'cart_add', NULL, 'sess_006', NOW() - INTERVAL 1 DAY),

-- User 4 (alice_jones) - berbagai kategori
(4, 10, 'view', NULL, 'sess_007', NOW() - INTERVAL 2 DAY),
(4, 10, 'purchase', 3.00, 'sess_007', NOW() - INTERVAL 2 DAY),
(4, 4, 'view', NULL, 'sess_008', NOW() - INTERVAL 1 DAY),
(4, 3, 'view', NULL, 'sess_008', NOW() - INTERVAL 1 DAY),

-- User 5 (charlie_brown) - baru mulai
(5, 1, 'view', NULL, 'sess_009', NOW()),
(5, 2, 'view', NULL, 'sess_009', NOW());

--
-- Dumping data for table `user_sessions`
--
INSERT INTO `user_sessions` (`session_id`, `user_id`, `started_at`, `ended_at`, `device_info`) VALUES
('sess_001', 1, NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY + INTERVAL 30 MINUTE, 'Chrome on Windows'),
('sess_002', 1, NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY + INTERVAL 45 MINUTE, 'Firefox on Mac'),
('sess_003', 2, NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY + INTERVAL 20 MINUTE, 'Safari on iPhone'),
('sess_004', 2, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY + INTERVAL 15 MINUTE, 'Chrome on Android'),
('sess_005', 3, NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY + INTERVAL 25 MINUTE, 'Edge on Windows'),
('sess_006', 3, NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY + INTERVAL 35 MINUTE, 'Chrome on Windows'),
('sess_007', 4, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY + INTERVAL 10 MINUTE, 'Safari on Mac'),
('sess_008', 4, NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY + INTERVAL 40 MINUTE, 'Chrome on Windows'),
('sess_009', 5, NOW(), NOW() + INTERVAL 20 MINUTE, 'Firefox on Linux');

-- Insert sample data keranjang
INSERT INTO `carts` (`user_id`, `product_id`, `quantity`) VALUES
(1, 2, 1),
(1, 3, 2),
(2, 5, 1),
(3, 6, 1),
(3, 8, 3);

-- Insert sample orders dengan status berbeda
INSERT INTO `orders` (`user_id`, `total_amount`, `status`, `payment_method`, `payment_proof`, `created_at`) VALUES
(2, 350000.00, 'processing', 'transfer', 'payment_1.jpg', NOW() - INTERVAL 3 DAY),
(3, 1200000.00, 'processing', 'transfer', 'payment_2.jpg', NOW() - INTERVAL 2 DAY),
(4, 360000.00, 'pending', 'transfer', NULL, NOW() - INTERVAL 1 DAY),
(5, 15000000.00, 'paid', 'transfer', 'payment_3.jpg', NOW());

--
-- Create views untuk memudahkan analysis
--
CREATE OR REPLACE VIEW view_user_behavior_summary AS
SELECT 
    u.user_id,
    u.username,
    p.product_id,
    p.product_name,
    p.category,
    COUNT(*) as total_interactions,
    SUM(CASE WHEN ubl.behavior_type = 'view' THEN 1 ELSE 0 END) as view_count,
    SUM(CASE WHEN ubl.behavior_type = 'cart_add' THEN 1 ELSE 0 END) as cart_add_count,
    SUM(CASE WHEN ubl.behavior_type = 'purchase' THEN 1 ELSE 0 END) as purchase_count,
    MAX(ubl.created_at) as last_interaction
FROM user_behavior_logs ubl
JOIN users u ON ubl.user_id = u.user_id
JOIN products p ON ubl.product_id = p.product_id
GROUP BY u.user_id, u.username, p.product_id, p.product_name, p.category;

CREATE OR REPLACE VIEW view_product_popularity AS
SELECT 
    p.product_id,
    p.product_name,
    p.category,
    COUNT(ubl.log_id) as total_interactions,
    COUNT(DISTINCT ubl.user_id) as unique_users,
    SUM(CASE WHEN ubl.behavior_type = 'purchase' THEN COALESCE(ubl.behavior_value, 1) ELSE 0 END) as total_purchases
FROM products p
LEFT JOIN user_behavior_logs ubl ON p.product_id = ubl.product_id
GROUP BY p.product_id, p.product_name, p.category
ORDER BY total_interactions DESC;

-- Buat view untuk analytics
CREATE OR REPLACE VIEW view_admin_dashboard AS
SELECT 
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM products WHERE is_active = 1) as total_products,
    (SELECT COUNT(*) FROM user_behavior_logs WHERE DATE(created_at) = CURDATE()) as today_interactions,
    (SELECT COUNT(*) FROM user_behavior_logs WHERE behavior_type = 'purchase') as total_purchases;

CREATE OR REPLACE VIEW view_user_analytics AS
SELECT 
    u.user_id,
    u.username,
    u.role,
    u.email,
    COUNT(ubl.log_id) as total_interactions,
    MAX(ubl.created_at) as last_activity,
    SUM(CASE WHEN ubl.behavior_type = 'purchase' THEN 1 ELSE 0 END) as total_purchases
FROM users u
LEFT JOIN user_behavior_logs ubl ON u.user_id = ubl.user_id
GROUP BY u.user_id, u.username, u.role, u.email
ORDER BY total_interactions DESC;

-- Buat view untuk cart count
CREATE OR REPLACE VIEW view_user_cart_count AS
SELECT 
    u.user_id,
    u.username,
    COUNT(c.cart_id) as cart_items_count,
    SUM(c.quantity) as total_quantity
FROM users u
LEFT JOIN carts c ON u.user_id = c.user_id
GROUP BY u.user_id, u.username;

-- Buat view untuk orders admin
CREATE OR REPLACE VIEW view_admin_orders AS
SELECT 
    o.*,
    u.username as customer_username,
    u.email as customer_email,
    COUNT(oi.order_item_id) as item_count,
    a.username as approver_username
FROM orders o
JOIN users u ON o.user_id = u.user_id
LEFT JOIN order_items oi ON o.order_id = oi.order_id
LEFT JOIN users a ON o.approved_by = a.user_id
GROUP BY o.order_id
ORDER BY o.created_at DESC;

-- View untuk melihat wishlist user
CREATE OR REPLACE VIEW view_user_wishlists AS
SELECT 
    u.user_id,
    u.username,
    p.product_id,
    p.product_name,
    p.price,
    ubl.created_at as added_date
FROM user_behavior_logs ubl
JOIN users u ON ubl.user_id = u.user_id
JOIN products p ON ubl.product_id = p.product_id
WHERE ubl.behavior_type = "wishlist"
ORDER BY ubl.created_at DESC;

/*!40101 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;