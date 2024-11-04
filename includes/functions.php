-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 11:04 AM
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
-- Database: `mssp_events`
--

-- --------------------------------------------------------

--
-- Table structure for table `event_list`
--

CREATE TABLE `event_list` (
  `id` int(30) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `venue` text NOT NULL,
  `limit_registration` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Don''t Close, 1= entry has timeout',
  `datetime_start` datetime NOT NULL,
  `datetime_end` datetime NOT NULL,
  `limit_time` float DEFAULT NULL,
  `user_id` int(30) NOT NULL,
  `survey_link_wo` varchar(255) DEFAULT NULL,
  `survey_link_non_wo` varchar(255) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_update` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_list`
--

INSERT INTO `event_list` (`id`, `title`, `description`, `venue`, `limit_registration`, `datetime_start`, `datetime_end`, `limit_time`, `user_id`,`survey_link_wo`,`survey_link_non_wo`, `date_created`, `date_update`) VALUES
(1, 'PEPFAR IP MEETING', 'Sample Event Only', 'RUMBA', 1, `NULL`, `NULL`, '2024-05-07 08:00:00', '2024-05-07 17:00:00', 30, 3, '2021-07-26 11:10:12', '2024-10-14 14:11:45'),
(3, 'Supply Chain Design Workshop', 'Sample only', 'OKAPI', 1,`NULL`,`NULL`, '2024-06-11 00:00:00', '2024-06-26 17:00:00', 30, 3, '2021-07-26 13:36:43', '2024-10-14 14:14:20'),
(4, 'A+A Industry Day', 'This event...', 'BONOBO', 0, `NULL`, `NULL`, '2024-05-20 08:00:00', '2024-05-20 17:00:00', 0, 3, '2024-10-14 14:21:10', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event_list`
--
ALTER TABLE `events`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
