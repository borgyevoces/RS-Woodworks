-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2025 at 10:04 AM
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
-- Database: `rswoodworks`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `admin_id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_image` varchar(255) NOT NULL,
  `admin_contact` varchar(255) NOT NULL,
  `admin_ip` varchar(255) NOT NULL,
  `role` enum('super_admin','manager','editor') NOT NULL DEFAULT 'editor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`admin_id`, `username`, `password`, `full_name`, `admin_email`, `admin_image`, `admin_contact`, `admin_ip`, `role`) VALUES
(2, 'admin', '$2y$10$XGeSFdJSZjZMUJF7jC/mf.t/zsSg8/gzcFKEWjFSmK4iSg7klYpOC', 'Borgy Evoces', 'eborgy20@gmail.com', '', '09206218682', '::1', 'super_admin'),
(9, 'master', '$2y$10$6jeFfs2FtDTVL8TKKrkVe.5utcZCqH8l/Lv4bYdyrm.F2zMer77Hi', 'Borgy Evoces', 'evoces20@gmail.com', 'user_images/testsimage1.jpg', '09923071359', '::1', 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE `cart_details` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_details`
--

INSERT INTO `cart_details` (`cart_id`, `product_id`, `user_id`, `quantity`) VALUES
(11, 58, 35, 3),
(14, 58, 107, 3),
(15, 59, 107, 1),
(52, 58, 136, 3),
(57, 58, 19, 1),
(58, 53, 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(100) NOT NULL,
  `category_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_title`) VALUES
(5, 'Plant Rack'),
(6, 'Table'),
(7, 'Bench'),
(9, 'Fruits Basket'),
(10, 'Planks'),
(11, 'Shelf'),
(12, 'Vase Holder'),
(13, 'Stool'),
(14, 'Pallet');

-- --------------------------------------------------------

--
-- Table structure for table `completed_order`
--

CREATE TABLE `completed_order` (
  `order_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_price` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `date_received` date NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `completed_order`
--

INSERT INTO `completed_order` (`order_id`, `user_id`, `full_name`, `user_email`, `product_id`, `product_name`, `product_image`, `product_price`, `quantity`, `payment_status`, `user_address`, `date_received`, `status`) VALUES
(17, 19, 'Borgy Evoces', 'Eborgy20@gmail.com', 62, 'Wooden Fruits Basket', 'pre-made fruit basket.png', '450', '1', 'Payment Complete', '123 Looban St. Aniban 1 Bacoor Cavite', '2024-10-12', 'completed'),
(18, 19, 'Borgy Evoces', 'Eborgy20@gmail.com', 59, 'Wooden Vase Holder', 'vase holder.png', '450', '1', 'Payment Complete', '123 Looban St. Aniban 1 Bacoor Cavite', '2024-09-15', 'completed'),
(19, 19, 'Borgy Evoces', 'Eborgy20@gmail.com', 59, 'Wooden Vase Holder', 'vase holder.png', '450', '1', 'Payment Complete', '123 Looban St. Aniban 1 Bacoor Cavite', '2024-08-15', 'completed'),
(20, 19, 'Borgy Evoces', 'Eborgy20@gmail.com', 58, 'Plant Rack ', 'pre-made plant rack 1.png', '600', '3', 'Payment Complete', '123 Looban St. Aniban 1 Bacoor Cavite', '2024-07-15', 'completed'),
(21, 19, 'Borgy Evoces', 'Eborgy20@gmail.com', 59, 'Wooden Vase Holder', 'vase holder.png', '450', '1', 'Payment Complete', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2024-11-27', 'completed'),
(22, 35, 'conan', 'conan@gmail.com', 62, 'Wooden Fruits Basket', 'pre-made fruit basket.png', '450', '1', 'Completed', '123 Looban St. Aniban 1 Bacoor Cavite', '2024-12-11', 'completed'),
(23, 35, 'conan', 'conan@gmail.com', 58, 'Plant Rack ', 'pre-made plant rack 1.png', '600', '1', 'Payment Complete', '123 Looban St. Aniban 1 Bacoor Cavite', '2024-12-11', 'completed'),
(24, 133, 'evoces', 'bc.borgy.evoces@cvsu.edu.ph', 56, 'Wood Planks Bundle', '1x3x47.png', '550', '1', 'Completed', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-03', 'completed'),
(25, 19, 'Borgy Evoces', 'Eborgy20@gmail.com', 66, 'Bangkito colored (orange)', 'bangkito colored (orange).png', '250', '1', 'Completed', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-04', 'completed'),
(26, 131, 'Dyrus Dean', 'dyrusdean20@gmail.com', 58, 'Plant Rack ', 'pre-made plant rack 1.png', '600', '1', 'Pending', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-04', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `receiver_type` enum('user','admin') NOT NULL,
  `sender_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `is_automated_reply` tinyint(4) DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `receiver_type`, `sender_type`, `is_automated_reply`, `is_read`) VALUES
(289, 41, NULL, 'How can I contact support?', '2024-10-28 07:14:12', 'admin', 'user', 0, 0),
(291, 41, NULL, 'hello', '2024-10-28 07:14:17', 'admin', 'user', 0, 0),
(292, 19, NULL, 'Where can I find the tracking information?', '2024-10-28 00:26:15', 'admin', 'user', 0, 0),
(293, 1, 19, 'You can find tracking information in your order confirmation email.', '2024-10-28 00:26:15', 'user', 'admin', 0, 0),
(294, 19, NULL, 'What are your business hours?', '2024-10-28 00:26:20', 'admin', 'user', 0, 0),
(295, 1, 19, 'Our business hours are 9 AM to 5 PM, Monday to Friday.', '2024-10-28 00:26:20', 'user', 'admin', 0, 0),
(296, 19, NULL, 'How can I contact support?', '2024-10-28 00:26:23', 'admin', 'user', 0, 0),
(297, 1, 19, 'You can contact support via this chat or by email at support@example.com.', '2024-10-28 00:26:23', 'user', 'admin', 0, 0),
(298, 19, NULL, 'Hello', '2024-10-28 00:26:38', 'admin', 'user', 0, 0),
(299, 46, NULL, 'What is your return policy?', '2024-11-27 04:29:16', 'admin', 'user', 0, 0),
(301, 46, NULL, 'Where can I find the tracking information?', '2024-11-27 04:29:19', 'admin', 'user', 0, 0),
(303, 46, NULL, 'hello', '2024-11-27 04:29:26', 'admin', 'user', 0, 0),
(304, 2, 19, 'hello\n', '2024-11-27 04:54:08', 'user', 'admin', 0, 0),
(305, 46, NULL, 'What are your business hours?', '2024-11-27 04:56:18', 'admin', 'user', 0, 0),
(307, 46, NULL, 'Where can I find the tracking information?', '2024-11-27 04:57:36', 'admin', 'user', 0, 0),
(309, 46, NULL, 'You can find your tracking information in your order history or via the tracking email.', '2024-11-27 04:57:36', 'admin', 'user', 0, 0),
(310, 46, NULL, 'Where can I find the tracking information?', '2024-11-27 04:57:43', 'admin', 'user', 0, 0),
(312, 47, NULL, 'What are your business hours?', '2024-11-27 06:13:56', 'admin', 'user', 0, 0),
(314, 47, NULL, 'hello', '2024-11-27 06:14:10', 'admin', 'user', 0, 0),
(315, 47, NULL, 'Where can I find the tracking information?', '2024-11-27 06:14:15', 'admin', 'user', 0, 0),
(317, 45, NULL, 'What are your business hours?', '2024-12-02 15:23:27', 'admin', 'user', 0, 0),
(319, 45, NULL, 'How can I contact support?', '2024-12-02 15:23:29', 'admin', 'user', 0, 0),
(321, 45, NULL, 'What is your return policy?', '2024-12-02 15:23:32', 'admin', 'user', 0, 0),
(323, 5, 19, 'hello\n', '2024-12-03 09:25:26', 'user', 'admin', 0, 0),
(324, 19, NULL, 'What are your business hours?', '2024-12-03 09:25:42', 'admin', 'user', 0, 0),
(325, 1, 19, 'Our business hours are 9 AM to 5 PM, Monday to Friday.', '2024-12-03 09:25:42', 'user', 'admin', 0, 0),
(326, 19, NULL, 'I wanted to ask something', '2024-12-03 09:25:55', 'admin', 'user', 0, 0),
(327, 19, NULL, 'What is your return policy?', '2025-01-31 10:41:06', 'admin', 'user', 0, 0),
(328, 1, 19, 'We offer a 30-day return policy for unopened products.', '2025-01-31 10:41:06', 'user', 'admin', 0, 0),
(329, 107, NULL, 'How can I contact support?', '2025-02-01 08:15:32', 'admin', 'user', 0, 0),
(331, 131, NULL, 'What are your business hours?', '2025-02-03 08:21:15', 'admin', 'user', 0, 0),
(332, 1, 131, 'Our business hours are 9 AM to 5 PM, Monday to Friday.', '2025-02-03 08:21:15', 'user', 'admin', 0, 0),
(333, 133, NULL, 'hello', '2025-02-03 21:31:51', 'admin', 'user', 0, 0),
(334, 133, NULL, 'What is your return policy?', '2025-02-03 21:32:03', 'admin', 'user', 0, 0),
(336, 133, NULL, 'How can I contact support?', '2025-02-03 21:32:09', 'admin', 'user', 0, 0),
(339, 131, NULL, 'How can I contact support?', '2025-02-05 07:22:47', 'admin', 'user', 0, 0),
(340, 1, 131, 'You can contact support via this chat or by email at support@example.com.', '2025-02-05 07:22:47', 'user', 'admin', 0, 0),
(341, 131, NULL, 'hello', '2025-02-05 07:22:52', 'admin', 'user', 0, 0),
(342, 131, NULL, 'test', '2025-02-05 08:13:49', 'admin', 'user', 0, 0),
(343, 131, NULL, 'What is your return policy?', '2025-02-05 08:13:59', 'admin', 'user', 0, 0),
(344, 1, 131, 'We offer a 30-day return policy for unopened products.', '2025-02-05 08:13:59', 'user', 'admin', 0, 0),
(345, 136, NULL, 'How can I contact support?', '2025-02-20 08:52:19', 'admin', 'user', 0, 0),
(347, 136, NULL, 'What are your business hours?', '2025-02-20 08:52:33', 'admin', 'user', 0, 0),
(349, 136, NULL, 'Do you offer international shipping?', '2025-02-20 09:16:11', 'admin', 'user', 0, 0),
(351, 136, NULL, 'Where can I find the tracking information?', '2025-02-20 09:16:27', 'admin', 'user', 0, 0),
(353, 136, NULL, 'okay thank you', '2025-02-20 09:29:52', 'admin', 'user', 0, 0),
(354, 136, NULL, 'How can I contact support?', '2025-02-20 09:48:06', 'admin', 'user', 0, 0),
(356, 136, NULL, 'What is your return policy?', '2025-02-20 09:48:09', 'admin', 'user', 0, 0),
(358, 19, NULL, 'What is your return policy?', '2025-02-20 10:17:52', 'admin', 'user', 0, 0),
(359, 1, 19, 'We offer a 14-days return policy if the products are broken or damaged upon delivery.', '2025-02-20 10:17:52', 'user', 'admin', 0, 0),
(360, 19, NULL, 'hello', '2025-02-20 14:20:19', 'admin', 'user', 0, 0),
(361, 138, NULL, 'What are your business hours?', '2025-02-21 01:56:49', 'admin', 'user', 0, 0),
(362, 1, 138, 'Our website is open 24/7 you can order our beloved products anytime.', '2025-02-21 01:56:49', 'user', 'admin', 0, 0),
(363, 138, NULL, 'How can I contact support?', '2025-02-21 01:56:53', 'admin', 'user', 0, 0),
(364, 1, 138, 'You can contact support via this chat or by email at rswoodworks@gmail.com.', '2025-02-21 01:56:53', 'user', 'admin', 0, 0),
(365, 138, NULL, 'How can I contact support?', '2025-02-21 01:56:53', 'admin', 'user', 0, 0),
(366, 1, 138, 'You can contact support via this chat or by email at rswoodworks@gmail.com.', '2025-02-21 01:56:53', 'user', 'admin', 0, 0),
(367, 19, NULL, 'How can I contact support?', '2025-02-21 02:53:27', 'admin', 'user', 0, 0),
(368, 1, 19, 'You can contact support via this chat or by email at rswoodworks@gmail.com.', '2025-02-21 02:53:27', 'user', 'admin', 0, 0),
(369, 19, NULL, 'What is your return policy?', '2025-02-21 02:53:30', 'admin', 'user', 0, 0),
(370, 1, 19, 'We offer a 14-days return policy if the products are broken or damaged upon delivery.', '2025-02-21 02:53:30', 'user', 'admin', 0, 0),
(371, 19, NULL, 'What is your return policy?', '2025-02-21 02:53:30', 'admin', 'user', 0, 0),
(372, 1, 19, 'We offer a 14-days return policy if the products are broken or damaged upon delivery.', '2025-02-21 02:53:30', 'user', 'admin', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `recipient` varchar(255) NOT NULL DEFAULT 'all'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`, `type`, `recipient`) VALUES
(266, 19, 'Welcome to RS Woodworks! 🎉\r\n\r\nWe\'re thrilled to have you here! 🛍️ Explore our collection of premium Palochina furniture and find the perfect pieces to elevate your space.\r\n\r\n✨ Why shop with us?\r\n✅ High-quality, eco-friendly materials\r\n✅ Unique handcrafted designs\r\n✅ Secure & hassle-free shopping\r\n\r\nHappy shopping! 🛒💛', 1, '2025-02-03 22:23:55', 'info', 'all'),
(267, 34, 'Welcome to RS Woodworks! 🎉\r\n\r\nWe\'re thrilled to have you here! 🛍️ Explore our collection of premium Palochina furniture and find the perfect pieces to elevate your space.\r\n\r\n✨ Why shop with us?\r\n✅ High-quality, eco-friendly materials\r\n✅ Unique handcrafted designs\r\n✅ Secure & hassle-free shopping\r\n\r\nHappy shopping! 🛒💛', 0, '2025-02-03 22:23:55', 'info', 'all'),
(268, 35, 'Welcome to RS Woodworks! 🎉\r\n\r\nWe\'re thrilled to have you here! 🛍️ Explore our collection of premium Palochina furniture and find the perfect pieces to elevate your space.\r\n\r\n✨ Why shop with us?\r\n✅ High-quality, eco-friendly materials\r\n✅ Unique handcrafted designs\r\n✅ Secure & hassle-free shopping\r\n\r\nHappy shopping! 🛒💛', 0, '2025-02-03 22:23:55', 'info', 'all'),
(269, 37, 'Welcome to RS Woodworks! 🎉\r\n\r\nWe\'re thrilled to have you here! 🛍️ Explore our collection of premium Palochina furniture and find the perfect pieces to elevate your space.\r\n\r\n✨ Why shop with us?\r\n✅ High-quality, eco-friendly materials\r\n✅ Unique handcrafted designs\r\n✅ Secure & hassle-free shopping\r\n\r\nHappy shopping! 🛒💛', 0, '2025-02-03 22:23:55', 'info', 'all'),
(270, 131, 'Welcome to RS Woodworks! 🎉\r\n\r\nWe\'re thrilled to have you here! 🛍️ Explore our collection of premium Palochina furniture and find the perfect pieces to elevate your space.\r\n\r\n✨ Why shop with us?\r\n✅ High-quality, eco-friendly materials\r\n✅ Unique handcrafted designs\r\n✅ Secure & hassle-free shopping\r\n\r\nHappy shopping! 🛒💛', 1, '2025-02-03 22:23:55', 'info', 'all'),
(271, 133, 'Welcome to RS Woodworks! 🎉\r\n\r\nWe\'re thrilled to have you here! 🛍️ Explore our collection of premium Palochina furniture and find the perfect pieces to elevate your space.\r\n\r\n✨ Why shop with us?\r\n✅ High-quality, eco-friendly materials\r\n✅ Unique handcrafted designs\r\n✅ Secure & hassle-free shopping\r\n\r\nHappy shopping! 🛒💛', 0, '2025-02-03 22:23:55', 'info', 'all'),
(274, 34, 'Introducing the Palochina Plant Rack! 🌱✨\r\n\r\nElevate your indoor garden with our new Palochina Plant Rack. This sleek and modern rack is perfect for showcasing your favorite plants while adding a touch of style to any space. 🏡🌿\r\n\r\nSpecial Launch Offer: Enjoy a limited-time discount on your purchase! 🎉💚\r\n\r\nGrab yours today and give your plants the display they deserve! 🛒🌸', 0, '2025-02-03 22:26:23', 'error', 'all'),
(275, 35, 'Introducing the Palochina Plant Rack! 🌱✨\r\n\r\nElevate your indoor garden with our new Palochina Plant Rack. This sleek and modern rack is perfect for showcasing your favorite plants while adding a touch of style to any space. 🏡🌿\r\n\r\nSpecial Launch Offer: Enjoy a limited-time discount on your purchase! 🎉💚\r\n\r\nGrab yours today and give your plants the display they deserve! 🛒🌸', 0, '2025-02-03 22:26:23', 'error', 'all'),
(276, 37, 'Introducing the Palochina Plant Rack! 🌱✨\r\n\r\nElevate your indoor garden with our new Palochina Plant Rack. This sleek and modern rack is perfect for showcasing your favorite plants while adding a touch of style to any space. 🏡🌿\r\n\r\nSpecial Launch Offer: Enjoy a limited-time discount on your purchase! 🎉💚\r\n\r\nGrab yours today and give your plants the display they deserve! 🛒🌸', 0, '2025-02-03 22:26:23', 'error', 'all'),
(278, 133, 'Introducing the Palochina Plant Rack! 🌱✨\r\n\r\nElevate your indoor garden with our new Palochina Plant Rack. This sleek and modern rack is perfect for showcasing your favorite plants while adding a touch of style to any space. 🏡🌿\r\n\r\nSpecial Launch Offer: Enjoy a limited-time discount on your purchase! 🎉💚\r\n\r\nGrab yours today and give your plants the display they deserve! 🛒🌸', 0, '2025-02-03 22:26:23', 'error', 'all'),
(375, 19, 'TEST', 0, '2025-02-20 18:56:53', 'info', 'all'),
(376, 34, 'TEST', 0, '2025-02-20 18:56:53', 'info', 'all'),
(377, 35, 'TEST', 0, '2025-02-20 18:56:53', 'info', 'all'),
(378, 131, 'TEST', 0, '2025-02-20 18:56:53', 'info', 'all');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(250) NOT NULL,
  `product_price` int(100) NOT NULL,
  `quantity` int(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(100) NOT NULL,
  `delivery_option` varchar(255) NOT NULL,
  `user_ip` varchar(100) NOT NULL,
  `user_contact` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `transaction_id`, `user_id`, `user_email`, `product_id`, `product_name`, `product_image`, `product_price`, `quantity`, `payment_method`, `address`, `date`, `payment_status`, `delivery_option`, `user_ip`, `user_contact`) VALUES
(885, 'transaction_67a20524a3120', 133, 'bc.borgy.evoces@cvsu.edu.ph', '58', 'Plant Rack ', 'pre-made plant rack 1.png', 600, 1, 'Cash On Delivery', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-04', 'Pending', 'Standard Delivery/120', '::1', NULL),
(888, 'transaction_67a21f79b9664', 131, 'dyrusdean20@gmail.com', '64', 'Wooden Plant Rack ', 'pre-made plant rack 3.png', 600, 1, 'Cash On Delivery', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-04', 'Pending', 'Standard/120', '::1', NULL),
(903, 'transaction_67a4c0a1946b1', 131, 'dyrusdean20@gmail.com', '62', 'Wooden Fruits Basket', 'pre-made fruit basket.png', 450, 1, 'Cash On Delivery', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-06', 'Pending', 'Standard/120', '::1', NULL),
(904, 'transaction_67a4c0a1946b1', 131, 'dyrusdean20@gmail.com', '65', 'Bangkito colored (Red)', 'bangkito colored (red).png', 250, 1, 'Cash On Delivery', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-06', 'Pending', 'Standard/120', '::1', NULL),
(905, 'transaction_67a4c0a1946b1', 131, 'dyrusdean20@gmail.com', '64', 'Wooden Plant Rack ', 'pre-made plant rack 3.png', 600, 1, 'Cash On Delivery', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-06', 'Pending', 'Standard/120', '::1', NULL),
(908, 'f5b741281b398852aaec', 19, 'Eborgy20@gmail.com', '64', 'Wooden Plant Rack ', 'pre-made plant rack 3.png', 600, 1, 'PayMongo', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-06', 'Completed', 'Truck/400', '', '09206218680'),
(909, 'f5b741281b398852aaec', 19, 'Eborgy20@gmail.com', '49', 'Wood Plank Bundle', '1.5x4x47.png', 700, 1, 'PayMongo', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-06', 'Completed', 'Truck/400', '', '09206218680'),
(910, 'transaction_67a93f543880c', 19, 'Eborgy20@gmail.com', '58', 'Plant Rack ', 'pre-made plant rack 1.png', 600, 1, 'Cash On Delivery', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-10', 'Pending', 'Standard/120', '::1', NULL),
(912, 'fe84c4d422cf1ecabf00', 131, 'dyrusdean20@gmail.com', '58', 'Plant Rack ', 'pre-made plant rack 1.png', 600, 1, 'PayMongo', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-20', 'Completed', 'Standard/120', '', '09923071359'),
(913, 'fe84c4d422cf1ecabf00', 131, 'dyrusdean20@gmail.com', '59', 'Wooden Vase Holder', 'vase holder.png', 450, 1, 'PayMongo', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-20', 'Completed', 'Standard/120', '', '09923071359'),
(914, 'transaction_67b717466a8f1', 137, 'borgygenshin20@gmail.com', '58', 'Plant Rack ', 'pre-made plant rack 1.png', 600, 1, 'Cash On Delivery', '', '2025-02-20', 'Pending', 'Standard/120', '::1', NULL),
(915, '7829291378ed9a4c34c6', 138, 'bc.borgy.evoces@cvsu.edu.ph', '58', 'Plant Rack ', 'pre-made plant rack 1.png', 600, 1, 'PayMongo', '', '2025-02-20', 'Completed', 'Standard/120', '', '09206218680');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `product_description2` varchar(255) NOT NULL,
  `product_keyword` varchar(255) NOT NULL,
  `category_title` varchar(30) NOT NULL,
  `product_image1` varchar(255) NOT NULL,
  `product_image2` varchar(255) NOT NULL,
  `product_image3` varchar(255) NOT NULL,
  `product_image4` varchar(255) NOT NULL,
  `product_price` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(100) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `promo` varchar(255) DEFAULT NULL,
  `hidden` enum('YES','NO') DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_description`, `product_description2`, `product_keyword`, `category_title`, `product_image1`, `product_image2`, `product_image3`, `product_image4`, `product_price`, `date`, `status`, `stock`, `promo`, `hidden`) VALUES
(49, 'Wood Plank Bundle', 'Size: 1.5 inches (L) 4 inches (W) 47 inches (h)', 'Palochina Planks bundle 10pcs', 'bundle 10pcs palochina planks', 'Planks', '1.5x4x47.png', '1.5x4x47 (Sideways).png', '1.5x4 long.jpg', '1.5x4 long (sideways).jpg', '700', '2025-02-20 05:30:45', '', 27, '', 'NO'),
(53, 'Palochina Wood Planks', 'Size: 1 inches (L) 2 inches(W) 32Inches (H)', 'Palochina Wood Plank Bundle 10pcs', 'Palochina Wood Plank Bundle 10pcs', 'Planks', '1x2x32.png', '1x2x32.jpg', '', '', '250', '2024-11-24 06:51:54', '', 30, '', 'NO'),
(54, 'Wood Plank Bundle', 'Size: 1 inches (L) 2 inches (W) 39 inches(H)', 'Palochina Wood Plank Bundle 10pcs', 'Palochina wood palochina Plank plank 10pcs bundle', 'Planks', '1x2x39.png', '1x2x39.jpg', '', '', '350', '2024-11-24 06:52:03', '', 25, '', 'NO'),
(55, 'Wood Planks Bundle', 'Size: 1 inch (L) 3 inches (W) 47 inches (H)', 'Palochina Wood Planks Bundle 10pcs', 'wood planks bundle 10pcs palochina', 'Planks', '1x3x47.png', '1x4 long.jpg', '', '', '550', '2024-11-24 06:52:13', '', 20, '', 'NO'),
(56, 'Wood Planks Bundle', 'Size: 1 inch (L) 3 inches (W) 47 inches (H)', 'Palochina Wood Planks Bundle 10pcs', 'wood planks bundle 10pcs palochina', 'Planks', '1x3x47.png', '1x4 long.jpg', '', '', '550', '2024-11-24 06:52:15', '', 20, '', 'NO'),
(57, 'Wood Planks Bundle', 'Size: 1 inch (L) 4 inches (W) 32 inches (H)', 'Wood Planks Bundle 10pcs', 'wood planks bundle 10pcs palochina', 'Planks', '1x4x32.png', '1x4x32.jpg', '', '', '350', '2024-11-25 01:39:18', '', 20, '', 'NO'),
(58, 'Plant Rack ', 'Size: 15 Inches (L) 21 Inches (W) 40 Inches (H)', 'Made from high-quality Palochina wood, these racks offer a natural and rustic look that complements any decor effortlessly.', 'plant rack palochina ', 'Plant Rack', 'pre-made plant rack 1.png', '', '', '', '600', '2024-11-26 10:53:48', '', 17, '20% OFF', 'NO'),
(59, 'Wooden Vase Holder', 'Size: 6 inches (L) 6 inches (W) 13 inches (H)', 'Upgrade your decor effortlessly with our versatile and stylish vase holder, designed to enhance the beauty of your floral displays and bring a sense of sophistication to your space.', 'vase holder palochina wood', 'Vase Holder', 'vase holder.png', '437264369_446932887856554_2128223931397813682_n.jpg', '', '', '450', '2024-12-03 09:53:35', '', 20, '', 'NO'),
(62, 'Wooden Fruits Basket', 'Size: 18 inches (L) 18 inches (W) 10 inches (H)', 'Crafted with care, our basket offers ample space for a variety of fruits, keeping them organized and within reach.\r\n\r\nMade from sturdy materials, it provides reliable support for your fruits, ensuring they stay fresh longer.', 'fruits basket wooden wood palochina', 'Fruits Basket', 'pre-made fruit basket.png', '', '', '', '450', '2024-11-24 07:42:10', '', 23, '', 'NO'),
(63, 'Small Square Table', 'Size: 18 inches (L) 18 inches (W) 13 inches (H)', 'Crafted from durable materials, it provides a stable platform for your belongings, whether you\'re using it to hold a lamp, display decorative items, or simply place your morning coffee.', 'small square table palochina wood', 'Table', 'pre-made small table.png', '', '', '', '500', '2024-11-24 07:42:11', '', 16, '', 'NO'),
(64, 'Wooden Plant Rack ', 'Size: 18 inches (L) 17 inches (W) 40 inches (H)', '\r\nIntroducing our Palochina Wood Plant Racks – the perfect choice for showcasing your plants with style and simplicity.\r\n\r\nMade from high-quality Palochina wood, these racks offer a natural and rustic look that complements any decor effortlessly.', 'plant rack palochina plant rack wooden', 'Plant Rack', 'pre-made plant rack 3.png', '432226843_1484275748819290_7843500760064730326_n.jpg', '', '', '600', '2024-11-24 07:42:13', '', 27, '', 'NO'),
(65, 'Bangkito colored (Red)', 'Size: 12 inches (L) 12 inches (W) 6 inches (H)', 'Crafted with simplicity and durability in mind, our bangkito/stool offers a versatile seating or accent solution for any room.\r\n\r\nMade from sturdy materials, it provides a stable and comfortable seat for relaxation or additional seating when needed.', 'bangkito stoll wooden wood palochina ', 'Stool', 'bangkito colored (red).png', '437653317_1474061443516425_7878747649065890440_n.jpg', '', '', '250', '2024-11-24 06:52:23', '', 20, '', 'NO'),
(66, 'Bangkito colored (orange)', 'Size: 12 inches (L) 12 inches (W) 6 inches (H)', 'Crafted with simplicity and durability in mind, our bangkito/stool offers a versatile seating or accent solution for any room.\r\n\r\nMade from sturdy materials, it provides a stable and comfortable seat for relaxation or additional seating when needed.', 'palochina wood wooden bangkito stool', 'Stool', 'bangkito colored (orange).png', '437473317_7447720122009648_6534690560858086244_n.jpg', '', '', '250', '2024-11-24 07:42:21', '', 29, '', 'NO'),
(71, 'Bangkito original color ', 'Size: 12 inches (L) 12 inches (W) 6 inches (H)', 'Crafted with simplicity and durability in mind, our bangkito/stool offers a versatile seating or accent solution for any room.\r\n\r\nMade from sturdy materials, it provides a stable and comfortable seat for relaxation or additional seating when needed.', 'bangkito stool palochina wood wooden ', 'Stool', 'bangkito original color.png', '437706896_925532802379192_78234317442514659_n.jpg', '', '', '200', '2024-11-26 10:55:22', '', 14, 'BEST SELLER!', 'NO'),
(72, 'Wooden Pallet', 'Size: 47 inches (L) 31 inches (W) 6 inches (H)', 'Whether you\'re repurposing it into a rustic coffee table, vertical garden, or simply using it to organize your garage or warehouse, our pallet offers endless possibilities for creativity and organization.\r\n\r\nAdd a touch of rustic charm and practicality to', 'wooden palochina pallet wood ', 'Pallet', 'pallet (epal).png', '', '', '', '600', '2024-11-24 07:42:14', '', 14, '', 'NO'),
(73, 'Wooden Plant Racks', 'Size: 32 inches (L) 27 inches (W) 45 inches (H)', 'Designed to withstand the elements, our plant rack is suitable for both indoor and outdoor use, making it a versatile addition to your home or garden.\r\n\r\nWhether you\'re creating a lush indoor jungle or enhancing your outdoor oasis, our plant rack provides', 'plant rack palochin wooden wood', 'Plant Rack', 'pre-made plant rack 4.png', '431660511_1523273888236528_245397744884710400_n.jpg', '', '', '600', '2024-11-24 07:42:23', '', 16, '', 'NO'),
(74, 'Wooden Plant Rack', 'Size: 20 inches (L) 20 inches (W) 40 inches (H)', 'Featuring multiple tiers, our plant rack maximizes vertical space, allowing you to create a captivating display while conserving valuable floor space.\r\n\r\nPerfect for balconies, patios, or any room in your home, our wooden plant rack provides a charming ba', 'plant rack palochina wood wooden', 'Plant Rack', 'pre-made plant rack 2.png', '432326302_1085617156216839_3813603065410743879_n.jpg', '', '', '600', '2024-11-24 07:42:23', '', 17, '', 'NO');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `review`, `created_at`, `image_path`, `video_path`) VALUES
(48, 35, 58, 2, 'Mid', '2024-10-06 11:11:33', NULL, NULL),
(49, 34, 58, 1, 'This product is not good! Please do not buy this!', '2024-10-06 11:13:53', NULL, NULL),
(53, 19, 58, 5, 'This product is really good! I recommend buying it.', '2024-10-06 12:57:21', NULL, 'media/reviews_media/670289313f4db-461976008_8906415566056431_1717119582493328032_n.mp4'),
(58, 19, 59, 5, 'this product is great i recommend buying it\r\n', '2025-02-03 21:20:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shipped_orders`
--

CREATE TABLE `shipped_orders` (
  `order_id` int(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_price` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `payment_method` varchar(40) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `delivery_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipped_orders`
--

INSERT INTO `shipped_orders` (`order_id`, `user_id`, `full_name`, `user_email`, `product_id`, `product_name`, `product_image`, `product_price`, `quantity`, `payment_method`, `payment_status`, `user_address`, `delivery_date`) VALUES
(97, '131', 'Dyrus Dean', 'dyrusdean20@gmail.com', 53, 'Palochina Wood Planks', '1x2x32.png', '250', 1, 'Cash On Delivery', 'Pending', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-06'),
(98, '131', 'Dyrus Dean', 'dyrusdean20@gmail.com', 58, 'Plant Rack ', 'pre-made plant rack 1.png', '600', 1, 'Cash On Delivery', 'Pending', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-13'),
(99, '19', 'Borgy Evoces', 'Eborgy20@gmail.com', 58, 'Plant Rack ', 'pre-made plant rack 1.png', '600', 1, 'PayMongo', 'Completed', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-18'),
(100, '19', 'Borgy Evoces', 'Eborgy20@gmail.com', 59, 'Wooden Vase Holder', 'vase holder.png', '450', 1, 'PayMongo', 'Completed', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-13');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_id` int(255) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `user_contact` varchar(11) NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `date_created` date NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `username`, `password`, `full_name`, `user_email`, `user_image`, `user_contact`, `user_ip`, `user_address`, `date_created`, `last_login`) VALUES
(19, 'borgy', '$2y$10$nDNijm88ArYC37x11xpHyu.2FGzLOEtxdrpTrNYMcHcjHpLXuD8CG', 'Borgy Evoces', 'Eborgy20@gmail.com', './user_images/674650cc3e2223.49558945.jpg', '09206218680', '::1', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2024-04-10', '2025-02-20 17:59:51'),
(34, 'lander', '$2y$10$BNNIUWReOguesa5K8UpcoeAX8qWzk6TDHbtohCzRwAcGCp9cB4goK', 'lander', 'lander@gmail.com', 'user_images/defaultuser.png', '09212314455', '::1', '123 Looban St. Aniban 1 Bacoor Cavite', '2024-04-10', NULL),
(35, 'Conan', '$2y$10$wL5I/aEmSq9Dg2e7ML5auOA99wtT2J7946d7DkU70dRTC0bnEHEOm', 'conan', 'conan@gmail.com', 'user_images/testimage1.jpg', '09213125552', '::1', '123 Looban St. Aniban 1 Bacoor Cavite', '2024-04-10', NULL),
(131, 'dyrus', '$2y$10$1Pgu02aYzLjP9sG9KK36Je85Bd1zYJI5CXe/LqdNDNFroeNA9wXd2', 'Dyrus Dean', 'dyrusdean20@gmail.com', 'user_images/defaultuser.png', '09923071359', '::1', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-01', '2025-02-20 03:46:46'),
(138, 'testing', '$2y$10$iZJPIs8CCpUYvw09UtF3eO3TXDT2kknInFxW4p.Tt5MK1Uskvxama', 'Borgy Evoces', 'bc.borgy.evoces@cvsu.edu.ph', 'user_images/testimage1.jpg', '09206218680', '::1', 'Cavite, Aniban 1 Bacoor City, 9021 Quezon St.  Markville Apt., 3rd Floor Unit G', '2025-02-20', '2025-02-20 17:56:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `completed_order`
--
ALTER TABLE `completed_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `messages_ibfk_2` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shipped_orders`
--
ALTER TABLE `shipped_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `admin_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart_details`
--
ALTER TABLE `cart_details`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `completed_order`
--
ALTER TABLE `completed_order`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=379;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=916;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `shipped_orders`
--
ALTER TABLE `shipped_orders`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `user_table` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
