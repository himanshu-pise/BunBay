-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 06, 2024 at 01:05 AM
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
-- Database: `bunbay`
--

-- --------------------------------------------------------

--
-- Table structure for table `buy`
--

CREATE TABLE `buy` (
  `id` int(11) NOT NULL,
  `card_details` text NOT NULL,
  `purchase_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `buyer_username` varchar(50) NOT NULL,
  `buyer_email` varchar(255) NOT NULL,
  `seller_username` varchar(50) NOT NULL,
  `seller_email` varchar(255) NOT NULL,
  `paid_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `feedback_text` text NOT NULL,
  `service_rating` int(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `username`, `email`, `feedback_text`, `service_rating`, `timestamp`) VALUES
(1, 'dev', 'dev@bunbay.com', 'Nice work commarade', 4, '2024-03-31 18:25:56'),
(2, 'dev', 'dev@bunbay.com', 'Needs improvement', 1, '2024-03-31 18:27:20'),
(3, 'admin', 'admin@bunbay.com', 'This is great. Need more stuff like this.', 3, '2024-04-01 00:31:30'),
(4, 'admin', 'admin@bunbay.com', 'I wish the team best of luck with the project. Great work.', 0, '2024-04-01 00:50:07'),
(5, 'hima', 'hima@bunbay.com', 'asdasdf', 5, '2024-04-01 00:51:56'),
(6, 'himanshu', 'himanshu@bunbay.com', 'Did not like the website. Its buggy and and dont feel like buying stuff from here.', 2, '2024-04-01 01:23:19'),
(7, 'dev', 'dev@bunbay.com', '&lt;script&gt;alert(123)&lt;/script&gt;', 4, '2024-04-03 01:52:30'),
(8, 'teja', 't@j.com', 'Very bad UI but all things work.', 5, '2024-04-04 01:19:38'),
(9, 'admin123', 'admin123@bunbay.com', '&lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt;', 5, '2024-04-04 14:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `buyer_username` varchar(50) DEFAULT NULL,
  `buyer_email` varchar(255) DEFAULT NULL,
  `sold` tinyint(1) NOT NULL DEFAULT 0,
  `purchase_id` varchar(255) DEFAULT NULL,
  `card_details` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `username`, `email`, `title`, `description`, `price`, `category`, `location`, `created_on`, `updated_on`, `buyer_username`, `buyer_email`, `sold`, `purchase_id`, `card_details`) VALUES
(1, 'admin', 'admin@bunbay.com', 'Calculator for sale', 'Decently used calculator for sale. All the buttons and functions working properly. Bought it for 100$ from BestBuy with valid bill. Out of warranty but brand support is good.', 30.00, 'Product', 'img/calc.jpg', '2024-04-04 14:45:43', '2024-03-31 21:39:40', 'admin123', 'admin123@bunbay.com', 1, '660ebd17980ed', 'a2c2339691fc48fbd14fb307292dff3e21222712d9240810742d7df0c6d74dfb'),
(2, 'admin', 'admin@bunbay.com', 'Programming Assignments', 'I do python, c, c++ programmming assignments. Will do other languages if enough time is given to me to work on it. Price is mostly fixed unless told explicitly depending on the assignment.', 35.00, 'Service', 'img/progass.jpeg', '2024-04-04 03:55:00', '2024-04-01 01:30:26', NULL, NULL, 0, NULL, NULL),
(3, 'hima', 'hima@bunbay.com', 'Computer Desk', 'A computer desk for sale. I bought it for 300$ and now I am giving it away because i got a new one.\r\n	', 60.00, 'Product', 'img/table.jpg', '2024-04-04 03:05:47', '2024-04-01 01:40:31', NULL, NULL, 0, NULL, NULL),
(4, 'himanshu', 'himanshu@bunbay.com', 'Reports', 'Reports for submission', 25.32, 'Service', 'img/report.jpeg', '2024-04-04 03:05:51', '2024-04-01 02:36:19', NULL, '', 0, NULL, NULL),
(5, 'himanshu', 'himanshu@bunbay.com', 'Mechanical Projects', 'Car Mechanical available.', 300.00, 'Service', 'img/carmech.jpeg', '2024-04-04 03:05:56', '2024-04-01 02:54:31', NULL, NULL, 0, NULL, NULL),
(6, 'admin', 'admin@bunbay.com', 'Take professional pictures', 'I take professional pictures of wildlife and lanscapes.', 30.00, 'Service', 'img/wildlifepics.jpeg', '2024-04-04 04:02:23', '2024-04-02 20:43:29', NULL, NULL, 0, NULL, NULL),
(7, 'teja', 't@j.com', 'Counseling', 'I am a certified counseling specialist and a student. And will help you sort any mental distress. My rates are hourly.', 40.00, 'Service', 'img/counsel.jpeg', '2024-04-04 03:56:50', '2024-04-04 01:28:49', NULL, NULL, 0, NULL, 'a2c2339691fc48fbd14fb307292dff3e21222712d9240810742d7df0c6d74dfb');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_user` tinyint(1) NOT NULL DEFAULT 1,
  `creation_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `is_user`, `creation_time`) VALUES
(1, 'admin', 'admin@bunbay.com', '5284cf71e2e036f3d7eb5a5deaeef23288e426d5ad266590bcbd456c83ee27cc', 1, '2024-03-08 13:45:28'),
(2, 'hima', 'hima@bunbay.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 1, '2024-03-16 16:16:25'),
(3, 'admin123', 'admin123@bunbay.com', '3b612c75a7b5048a435fb6ec81e52ff92d6d795a8b5a9c17070f6a63c97a53b2', 1, '2024-03-16 16:16:37'),
(4, 'himanshu', 'himanshu@bunbay.com', '5284cf71e2e036f3d7eb5a5deaeef23288e426d5ad266590bcbd456c83ee27cc', 1, '2024-03-31 16:45:38'),
(5, 'dev', 'dev@bunbay.com', '0a4664c8fcb6cb6f462ca6c18b0b85cce1878012773c4e014b23130adf967503', 1, '2024-03-31 17:07:02'),
(7, 'teja', 't@j.com', 'afe4cf293c77f375c8a89a23837a9f6f89774f989a8b087bcd2024eaefec0ab4', 1, '2024-04-04 01:18:30'),
(8, 'Sankar', 'sankar@bunbay.com', '95a62e4c10513d9ae45bab14628b9592cccc0b8611827072e882c247c55ee6d7', 1, '2024-04-04 01:31:50'),
(9, 'saqib', 'saqib@bunbay.com', '504274ce47aa0a673d5fc0687fcbaf982c0096342f4b833001d9ea19b0432d9a', 1, '2024-04-04 14:43:06'),
(11, 'rest', '!--@bunbay.com', '504274ce47aa0a673d5fc0687fcbaf982c0096342f4b833001d9ea19b0432d9a', 1, '2024-04-04 14:53:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buy`
--
ALTER TABLE `buy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buy`
--
ALTER TABLE `buy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
