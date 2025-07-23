-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2024 at 11:38 AM
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
-- Database: `echanem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `created_at`) VALUES
(1, 'Administrator', 'admin', '7fcf4ba391c48784edde599889d6e3f1e47a27db36ecc050cc92f259bfac38afad2c68a1ae804d77075e8fb722503f3eca2b2c1006ee6f6c7b7628cb45fffd1d', '2024-06-16 01:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size` varchar(100) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `size`, `added_at`) VALUES
(24, 1, 1, 2, 'M', '2024-06-26 07:44:53'),
(25, 1, 1, 1, '', '2024-06-26 08:38:50');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `firstname`, `lastname`, `email`, `phone`, `address`, `password`, `created_at`) VALUES
(1, 'Ahmad', 'Somad', 'ahmadsomad13@gmail.com', '01234567898', '36, Jalan Padungan, 93100, Kuching, Sarawak,Uganda, Afrika', '72c9dcdcb460d699f5d6b854064e334d070172d7b10d129cf3e833571e88087b812701d8a88fcbfc9bb841e326a3d652c8bc95fca848a5dfa9d6fd8173206874', '2024-06-15 17:50:09'),
(2, 'Fitri', 'Yahya', 'fitri.yahya@mediaprima.my', '0192345678', 'No. 69', '0dd3e512642c97ca3f747f9a76e374fbda73f9292823c0313be9d78add7cdd8f72235af0c553dd26797e78e1854edee0ae002f8aba074b066dfce1af114e32f8', '2024-06-15 22:08:01'),
(4, 'Firdan', 'Rahmat', 'firdan.rahmat@yahoo.com', '0192345678', NULL, 'e68dce2288b1c7dde4ca9f40939833e63e446b024ea8c108b33a13c935416480ed03681261d7cdbf16b5b4ce099837cd61a02627bed5d4d38c80bb232f48791b', '2024-06-21 02:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `delivery_method` varchar(100) NOT NULL,
  `delivery_cost` decimal(10,2) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `delivery_method`, `delivery_cost`, `payment_method`, `total`, `status`, `address`, `created_at`) VALUES
(11, 1, 'Regular Postage', 2.40, 'Bank Transfer', 82.38, 'Pending', '36, Jalan Padungan, 93100, Kuching, Sarawak, Malaysia', '2024-06-19 21:50:01'),
(13, 1, 'Express Postage', 4.70, 'PayPal', 164.68, 'Completed', '36, Jalan Padungan, 93100, Kuching, Sarawak, Malaysia', '2024-06-20 22:44:37'),
(14, 1, 'Self-Pickup', 0.00, 'Bank Transfer', 39.99, 'Pending', '36, Jalan Padungan, 93100, Kuching, Sarawak, Malaysia', '2024-06-21 02:52:37'),
(15, 1, 'Regular Postage', 2.40, 'Bank Transfer', 242.37, 'Completed', '36, Jalan Padungan, 93100, Kuching, Sarawak, Malaysia', '2024-06-21 03:22:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `size`) VALUES
(11, 11, 1, 2, 39.99, 'XXL'),
(13, 13, 4, 2, 79.99, 'L'),
(14, 14, 1, 1, 39.99, 'M'),
(15, 15, 4, 3, 79.99, 'S');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`sizes`)),
  `category` varchar(100) NOT NULL,
  `gendertype` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `sizes`, `category`, `gendertype`, `stock`, `image_url`, `created_at`) VALUES
(1, 'Oversized Graphic Tee', 'This comfy tee features a bold graphic print, perfect for a relaxed streetwear look.', 39.99, '[\"M\", \"L\", \"XL\", \"XXL\"]', 'Tops & Tees', 'Men', 17, '../resources/products/oversize_tshirt.webp', '2024-06-18 03:58:14'),
(2, 'Cargo Pants', 'Stay comfortable and stylish with these baggy cargo pants with multiple pockets.', 64.99, '[\"28\", \"30\", \"32\", \"34\", \"36\"]', 'Bottoms', 'Unisex', 0, '../resources/products/cargo_pants.webp', '2024-06-18 03:58:14'),
(3, 'White Sneakers', 'A classic streetwear staple, this bucket hat adds a touch of coolness to any outfit.', 124.99, '[\"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"12\"]', 'Footwear', 'Unisex', 5, '../resources/products/white_sneakers.avif', '2024-06-18 03:58:14'),
(4, 'Windbreaker Jacket', 'Lightweight and perfect for layering, this windbreaker features a water-resistant finish and bold color scheme.', 79.99, '[\"S\", \"M\", \"L\", \"XL\"]', 'Tops & Tees', 'Unisex', 5, '../resources/products/windbreaker.avif', '2024-06-18 03:58:14'),
(5, 'Techwear Leggings', 'Crafted for comfort and movement, these joggers feature a sleek techwear design and multiple pockets.', 59.99, '[\"28\", \"30\", \"32\", \"34\", \"36\"]', 'Bottoms', 'Women', 14, '../resources/products/techwear_leggings.webp', '2024-06-18 03:58:14'),
(6, 'Sleeveless Hoodie', 'New SUMMER sleeveless hoodie.', 39.99, '[\"S\",\"M\",\"L\"]', 'Tops & Tees', 'Men', 0, '../resources/products/sleeveless_hoodie.webp', '2024-06-18 13:09:04');

-- --------------------------------------------------------

--
-- Table structure for table `sales_reports`
--

CREATE TABLE `sales_reports` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `total_sales` decimal(10,2) DEFAULT NULL,
  `sales_count` int(11) DEFAULT NULL,
  `report_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sales_reports`
--
ALTER TABLE `sales_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD CONSTRAINT `sales_reports_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
