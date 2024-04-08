-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 08:02 AM
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
-- Database: `dbclothes`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(8) NOT NULL,
  `user_id` int(8) NOT NULL,
  `item_id` int(8) DEFAULT NULL,
  `combo_id` int(8) DEFAULT NULL,
  `quantity` int(8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `user_id`, `item_id`, `combo_id`, `quantity`, `created_at`) VALUES
(119, 11, 3, NULL, 1, '2024-04-04 11:15:42');

-- --------------------------------------------------------

--
-- Table structure for table `combo_clothes`
--

CREATE TABLE `combo_clothes` (
  `combo_id` int(11) NOT NULL,
  `item_id1` int(11) DEFAULT NULL,
  `item_id2` int(11) DEFAULT NULL,
  `item_id3` int(11) DEFAULT NULL,
  `item_id4` int(11) DEFAULT NULL,
  `combo_name` varchar(255) NOT NULL,
  `image_URL` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `available_quantity` int(255) DEFAULT NULL,
  `sold_quantity` int(255) DEFAULT NULL,
  `total_quantity` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `combo_clothes`
--

INSERT INTO `combo_clothes` (`combo_id`, `item_id1`, `item_id2`, `item_id3`, `item_id4`, `combo_name`, `image_URL`, `description`, `price`, `available_quantity`, `sold_quantity`, `total_quantity`) VALUES
(2, 2, 4, 10, NULL, 'off white x nike x adidas', 'https://static.nike.com/a/images/t_prod_ss/w_960,c_limit,f_auto/f8e6c644-3469-4179-857d-26dc914986b3/air-force-1-mid-x-off-white%E2%84%A2-white-and-varsity-maize-dr0500-101-release-date.jpg', 'nice', 12.99, 21, 29, 50),
(9, 11, 5, NULL, NULL, 'chuck x adidas', 'https://cdn.shopify.com/s/files/1/0616/3517/files/livestock-Adidas-Campus-Prince-Albert-032c-Group04.jpg?v=1605031273', 'hello', 50.99, 24, 1, NULL),
(14, 1, 2, 3, NULL, 'dbtk x mstr x nike', 'https://media.karousell.com/media/photos/products/2024/2/22/mstr_1708592087_54688545_progressive.jpg', 'nice', 9999.00, 23, NULL, NULL),
(16, 1, 12, NULL, 11, 'ayus', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQFzzwBzAcikrmTaHmK3YZRttIqNm1tVyqLRHC4WfAwaA&s', 'mew', 11.00, 109, NULL, NULL),
(17, 5, 4, NULL, NULL, '123', 'uploads/66137e163de96_bg-orangegradient.png', 'hello', 121.00, 123, NULL, 123);

-- --------------------------------------------------------

--
-- Table structure for table `individual_clothes`
--

CREATE TABLE `individual_clothes` (
  `id` int(8) NOT NULL,
  `name` varchar(45) NOT NULL,
  `brand` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL,
  `color` varchar(45) NOT NULL,
  `price` double NOT NULL,
  `gender` varchar(45) NOT NULL,
  `size` varchar(3) NOT NULL,
  `available_quantity` int(8) DEFAULT NULL,
  `image_URL` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sold_quantity` int(12) DEFAULT NULL,
  `total_quantity` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `individual_clothes`
--

INSERT INTO `individual_clothes` (`id`, `name`, `brand`, `category`, `color`, `price`, `gender`, `size`, `available_quantity`, `image_URL`, `description`, `sold_quantity`, `total_quantity`) VALUES
(1, 'Black Adidas Jacket', 'Adidas', 'Jacket', 'Black', 99.99, 'Unisex', 'M', 41, 'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/c6c5c294aed64a5da355ad120148b3c1_9366/Adicolor_Classics_Trefoil_Hoodie_Black_H06667_01_laydown.jpg', 'Warm and stylish hoodie for all seasons', 32, 73),
(2, 'Green H&M Dress', 'H&M', 'Dresses', 'Green', 39.99, 'Female', 'S', 17, 'https://images.squarespace-cdn.com/content/v1/580cf7ce6b8f5b3576a3da9f/1614531814117-M12UJV54Q636S6SD6P0Q/hmgoepprod.jpeg', 'Elegant green dress perfect for parties', 17, 34),
(3, 'Chuck Taylor', 'Converse', 'Shoes', 'Black', 49.99, 'Unisex', '8', 37, 'https://www.converse.ph/media/catalog/product/0/8/0802-CONM9160C00010H-1.jpg', 'Classic white sneakers suitable for everyday wear', 52, 89),
(4, 'Swoosh Short', 'Nike', 'Shorts', 'Blue', 29.99, 'Male', 'L', 51, 'https://i.pinimg.com/736x/d2/59/c9/d259c9aa3b49cc9c459dcd69c4b9c436.jpg', 'Comfortable and durable shorts for sports and leisure', 52, 103),
(5, 'Red Skirt', 'Zara', 'Skirts', 'Red', 34.99, 'Female', 'M', 36, 'https://static.zara.net/photos///2023/I/0/1/p/9214/187/632/2/w/1920/9214187632_6_2_1.jpg?ts=1699009778336', 'Fashionable red skirt with a modern design', 29, 65),
(7, 'Black Adidas Jacket', 'Adidas', 'Jacket', 'Black', 99.99, 'Unisex', 'S', 49, 'https://assets.adidas.com/images/h_840,f_auto,q_auto,fl_lossy,c_fill,g_auto/c6c5c294aed64a5da355ad120148b3c1_9366/Adicolor_Classics_Trefoil_Hoodie_Black_H06667_01_laydown.jpg', 'Warm and stylish hoodie for all seasons', 24, 73),
(10, 'Rhenz Bhovie', 'asd', 'asd', 'asd', 546, 'asd', 'asd', NULL, 'https://www.udiscovermusic.com/wp-content/uploads/2020/04/Kendrick-Lamar-Damn-album-cover-820-820x820.jpg', 'like that', 6, NULL),
(11, 'Chuck Taylor', 'Converse', 'Shoes', 'Black', 49.99, 'Unisex', '9', 1, 'https://www.converse.ph/media/catalog/product/0/8/0802-CONM9160C00010H-1.jpg', 'Classic white sneakers suitable for everyday wear', 3, NULL),
(12, 'Rhenz Bhovie', 'Converse', 'Shoes', 'Black', 65, 'Unisex', '9', 50, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQ5VsN2DLLlEl3uVj7au1xnXPW0OwxSkJHMqHWMV2dOQ&s', 'hello!', NULL, 50);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `post_id`, `user_id`, `timestamp`) VALUES
(4, 2, 1, '2024-03-27 12:57:11'),
(17, 2, 9, '2024-04-04 10:47:19'),
(18, 1, 9, '2024-04-04 10:47:21'),
(19, 2, 12, '2024-04-04 10:57:51'),
(20, 1, 12, '2024-04-04 10:57:57'),
(22, 15, 11, '2024-04-05 09:58:41');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(8) NOT NULL,
  `user_id` int(8) NOT NULL,
  `total_price` double NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `order_date`) VALUES
(1, 3, 49.99, '2024-02-18 09:18:33'),
(2, 4, 89.97, '2024-02-18 09:18:33'),
(3, 5, 34.99, '2024-02-18 09:18:33'),
(4, 2, 59.99, '2024-02-18 09:18:33'),
(5, 1, 119.97, '2024-02-18 09:18:33'),
(6, 7, 49.99, '2024-03-29 04:31:11'),
(7, 7, 49.99, '2024-03-29 04:31:24'),
(8, 7, 49.99, '2024-03-29 04:32:49'),
(9, 7, 49.99, '2024-03-29 04:33:40'),
(10, 8, 99.99, '2024-04-02 15:04:12'),
(11, 8, 39.99, '2024-04-02 15:04:13'),
(12, 8, 49.99, '2024-04-02 15:04:13'),
(13, 8, 29.99, '2024-04-02 15:04:15'),
(14, 8, 34.99, '2024-04-02 15:04:15'),
(15, 8, 39.99, '2024-04-02 15:05:48'),
(16, 8, 49.99, '2024-04-02 15:57:39'),
(17, 8, 99.99, '2024-04-02 16:10:28'),
(18, 8, 39.99, '2024-04-02 16:10:29'),
(19, 8, 49.99, '2024-04-02 16:11:41'),
(20, 8, 99.99, '2024-04-02 16:11:42'),
(21, 8, 39.99, '2024-04-02 16:11:42'),
(22, 8, 29.99, '2024-04-02 16:11:42'),
(23, 8, 34.99, '2024-04-02 16:11:43'),
(24, 8, 99.99, '2024-04-02 16:12:41'),
(25, 8, 39.99, '2024-04-02 16:12:41'),
(26, 8, 49.99, '2024-04-02 16:12:42'),
(27, 8, 39.99, '2024-04-02 16:16:51'),
(28, 10, 49.99, '2024-04-03 10:13:05'),
(29, 10, 39.99, '2024-04-03 10:13:06'),
(30, 10, 29.99, '2024-04-03 10:13:25'),
(31, 10, 39.99, '2024-04-03 10:53:43'),
(32, 10, 39.99, '2024-04-03 10:54:45'),
(33, 10, 39.99, '2024-04-03 10:55:00'),
(35, 11, 11, '2024-04-04 10:40:05'),
(36, 11, 12.99, '2024-04-04 10:46:37'),
(37, 11, 49.99, '2024-04-04 10:46:37'),
(38, 11, 49.99, '2024-04-04 10:46:37'),
(39, 9, 34.99, '2024-04-04 10:48:59'),
(40, 12, 99.99, '2024-04-04 10:59:58'),
(41, 12, 99.99, '2024-04-04 11:00:40'),
(42, 12, 99.99, '2024-04-04 11:00:40'),
(43, 7, 99.99, '2024-04-04 11:03:58'),
(44, 7, 99.99, '2024-04-04 11:05:03'),
(45, 7, 50.99, '2024-04-04 11:05:03'),
(46, 7, 99.99, '2024-04-04 11:05:26'),
(47, 7, 12.99, '2024-04-04 11:05:26'),
(48, 7, 29.99, '2024-04-04 11:05:26'),
(49, 7, 11, '2024-04-04 11:05:26'),
(50, 7, 546, '2024-04-04 11:05:26'),
(51, 7, 12.99, '2024-04-04 11:05:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(8) NOT NULL,
  `order_id` int(8) NOT NULL,
  `item_id` int(8) DEFAULT NULL,
  `combo_id` int(8) DEFAULT NULL,
  `quantity` int(8) NOT NULL,
  `price_per_unit` double NOT NULL,
  `subtotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `item_id`, `combo_id`, `quantity`, `price_per_unit`, `subtotal`) VALUES
(6, 1, 3, NULL, 1, 49.99, 49.99),
(7, 2, 4, NULL, 2, 29.99, 59.98),
(8, 3, 5, NULL, 1, 34.99, 34.99),
(9, 4, 1, NULL, 1, 59.99, 59.99),
(10, 5, 2, NULL, 3, 39.99, 119.97),
(11, 6, 3, NULL, 1, 49.99, 49.99),
(12, 7, 3, NULL, 1, 49.99, 49.99),
(13, 8, 3, NULL, 1, 49.99, 49.99),
(14, 9, 3, NULL, 1, 49.99, 49.99),
(15, 10, 1, NULL, 1, 99.99, 99.99),
(16, 11, 2, NULL, 1, 39.99, 39.99),
(17, 12, 3, NULL, 1, 49.99, 49.99),
(18, 13, 4, NULL, 1, 29.99, 29.99),
(19, 14, 5, NULL, 1, 34.99, 34.99),
(20, 15, 2, NULL, 1, 39.99, 39.99),
(21, 16, 3, NULL, 1, 49.99, 49.99),
(22, 17, 1, NULL, 1, 99.99, 99.99),
(23, 18, 2, NULL, 1, 39.99, 39.99),
(24, 19, 3, NULL, 1, 49.99, 49.99),
(25, 20, 1, NULL, 1, 99.99, 99.99),
(26, 21, 2, NULL, 1, 39.99, 39.99),
(27, 22, 4, NULL, 1, 29.99, 29.99),
(28, 23, 5, NULL, 1, 34.99, 34.99),
(29, 24, 1, NULL, 1, 99.99, 99.99),
(30, 25, 2, NULL, 1, 39.99, 39.99),
(31, 26, 3, NULL, 1, 49.99, 49.99),
(32, 27, 2, NULL, 1, 39.99, 39.99),
(33, 28, 3, NULL, 1, 49.99, 49.99),
(34, 29, 2, NULL, 1, 39.99, 39.99),
(35, 30, 4, NULL, 1, 29.99, 29.99),
(37, 31, 2, NULL, 1, 39.99, 39.99),
(38, 32, 2, NULL, 1, 39.99, 39.99),
(39, 33, 2, NULL, 1, 39.99, 39.99),
(42, 36, NULL, 2, 1, 12.99, 12.99),
(43, 37, 11, NULL, 1, 49.99, 49.99),
(44, 38, 3, NULL, 1, 49.99, 49.99),
(45, 39, 5, NULL, 1, 34.99, 34.99),
(46, 40, 1, NULL, 1, 99.99, 99.99),
(47, 41, 1, NULL, 1, 99.99, 99.99),
(48, 42, 7, NULL, 1, 99.99, 99.99),
(49, 43, 1, NULL, 1, 99.99, 99.99),
(50, 44, 1, NULL, 1, 99.99, 99.99),
(51, 45, NULL, 9, 1, 50.99, 50.99),
(52, 46, 1, NULL, 1, 99.99, 99.99),
(53, 47, NULL, 2, 1, 12.99, 12.99),
(54, 48, 4, NULL, 1, 29.99, 29.99),
(56, 50, 10, NULL, 1, 546, 546),
(57, 51, NULL, 2, 1, 12.99, 12.99);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `caption` text DEFAULT NULL,
  `image_URL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `caption`, `image_URL`) VALUES
(1, 9, '123', 'https://images.squarespace-cdn.com/content/v1/580cf7ce6b8f5b3576a3da9f/1614531814117-M12UJV54Q636S6SD6P0Q/hmgoepprod.jpeg'),
(2, 9, 'like that sir!', 'https://imageio.forbes.com/specials-images/imageserve/5ed578988b3c370006234c35/0x0.jpg'),
(10, 12, '123', 'https://www.converse.ph/media/catalog/product/0/8/0802-CONM9160C00010H-1.jpg'),
(15, 11, '123', 'uploads/660fcb4b5cce0_IMG_20190921_092142.jpeg'),
(16, 11, 'NICE', 'uploads/660fcb6b60870_IMG_20190921_092142.jpeg'),
(17, 11, '123', 'uploads/660fcb856d54a_IMG_20190921_111620.jpeg'),
(18, 11, '123', 'uploads/660fcc0586417_IMG_20190921_111620.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receiptid` int(8) NOT NULL,
  `item_id` int(8) DEFAULT NULL,
  `combo_id` int(8) DEFAULT NULL,
  `quantity` int(8) NOT NULL,
  `price` double NOT NULL,
  `subtotal` double NOT NULL,
  `receipt_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_id`
--

CREATE TABLE `user_id` (
  `userid` int(8) NOT NULL,
  `balance` double DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `user_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_id`
--

INSERT INTO `user_id` (`userid`, `balance`, `name`, `username`, `user_password`) VALUES
(1, 10000, 'Alice Johnson', 'alice_johnson', 'aXnHKDta0MtdMNVFUCCLGA==:V6K1xhOgzWrbEwf2mH+jGg=='),
(2, 10000, 'Bob Williams', 'bob_williams', 'aXnHKDta0MtdMNVFUCCLGA==:E0rYHxfiLRKBgdIf0KC1Dg=='),
(3, 10000, 'Eva Martinez', 'asd', 'aXnHKDta0MtdMNVFUCCLGA==:aHCVFMyD3c4Bj4muRxowfw=='),
(4, 10000, 'Michael Brown', 'michael_brown', 'aXnHKDta0MtdMNVFUCCLGA==:UybLL8LQTD8MvNpJjdQqpw=='),
(5, 10000, 'Sophia Lee', 'sophia_lee', 'aXnHKDta0MtdMNVFUCCLGA==:5e5GsHg7G6hVLI1ovkyiFQ=='),
(6, 10000, 'Rhenz Bhovie', 'asdawdaw', 'aXnHKDta0MtdMNVFUCCLGA==:y/1rXF4wJPt+Cw0yBrJbpA'),
(7, 8836.110000000002, 'hello123', '1232', 'aXnHKDta0MtdMNVFUCCLGA==:y/1rXF4wJPt+Cw0yBrJbpA'),
(8, 9030.180000000004, '12345', '12345', 'aXnHKDta0MtdMNVFUCCLGA==:ubZo0b7FHyfyFt+I+jFUGQ=='),
(9, 9965.01, 'kendrick', 'kendrick', 'aXnHKDta0MtdMNVFUCCLGA==:y/1rXF4wJPt+Cw0yBrJbpA'),
(10, 9709.070000000002, 'Steven Yu', 'stib', 'aXnHKDta0MtdMNVFUCCLGA==:+3EkmfVjH3CDroHtqYT/kw=='),
(11, 9875.03, 'admin', 'admin', 'aXnHKDta0MtdMNVFUCCLGA==:GML75c2WMFKBQAbyVivujg=='),
(12, 9700.03, 'rhenz bhovie', 'rhenz123', 'aXnHKDta0MtdMNVFUCCLGA==:y/1rXF4wJPt+Cw0yBrJbpA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `combo_id` (`combo_id`);

--
-- Indexes for table `combo_clothes`
--
ALTER TABLE `combo_clothes`
  ADD PRIMARY KEY (`combo_id`),
  ADD KEY `fk_item_id1` (`item_id1`),
  ADD KEY `fk_item_id2` (`item_id2`),
  ADD KEY `fk_item_id3` (`item_id3`),
  ADD KEY `fk_item_id4` (`item_id4`);

--
-- Indexes for table `individual_clothes`
--
ALTER TABLE `individual_clothes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receiptid`);

--
-- Indexes for table `user_id`
--
ALTER TABLE `user_id`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `combo_clothes`
--
ALTER TABLE `combo_clothes`
  MODIFY `combo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `individual_clothes`
--
ALTER TABLE `individual_clothes`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receiptid` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_id`
--
ALTER TABLE `user_id`
  MODIFY `userid` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_id` (`userid`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `individual_clothes` (`id`),
  ADD CONSTRAINT `carts_ibfk_3` FOREIGN KEY (`combo_id`) REFERENCES `combo_clothes` (`combo_id`);

--
-- Constraints for table `combo_clothes`
--
ALTER TABLE `combo_clothes`
  ADD CONSTRAINT `fk_item_id1` FOREIGN KEY (`item_id1`) REFERENCES `individual_clothes` (`id`),
  ADD CONSTRAINT `fk_item_id2` FOREIGN KEY (`item_id2`) REFERENCES `individual_clothes` (`id`),
  ADD CONSTRAINT `fk_item_id3` FOREIGN KEY (`item_id3`) REFERENCES `individual_clothes` (`id`),
  ADD CONSTRAINT `fk_item_id4` FOREIGN KEY (`item_id4`) REFERENCES `individual_clothes` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_id` (`userid`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_id` (`userid`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `individual_clothes` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_id` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
