-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 01:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `profiling_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`) VALUES
(1, 'Dog'),
(2, 'Cat');

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `owner_id` int(11) UNSIGNED NOT NULL,
  `firstname` varchar(190) NOT NULL,
  `middlename` varchar(190) NOT NULL,
  `lastname` varchar(190) NOT NULL,
  `contact` varchar(13) NOT NULL,
  `dog_own` int(11) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`owner_id`, `firstname`, `middlename`, `lastname`, `contact`, `dog_own`, `date_created`) VALUES
(1, 'Joshua', 'Mendoza', 'Pacho', '0945866121', 3, '2024-10-14 10:38:50'),
(2, 'Joshua', 'Mendoza', 'Pacho', '0945866121', 1, '2024-10-17 11:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `dog_id` int(11) UNSIGNED NOT NULL,
  `owner_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `dogtag` varchar(100) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `pet_age` varchar(190) NOT NULL,
  `color` varchar(50) NOT NULL,
  `breed` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `pet_status` enum('Alive','Death','Missing','Archived') NOT NULL DEFAULT 'Alive',
  `is_archived` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`dog_id`, `owner_id`, `category_id`, `dogtag`, `pet_name`, `pet_age`, `color`, `breed`, `image`, `pet_status`, `is_archived`) VALUES
(1, 1, 1, '2674166809', 'roanne', '1', 'black', 'Aspin', '1728873530_dog-8198719_640.webp', 'Death', 1),
(3, 1, 1, '1990000', 'Annetot', '2', 'black', 'Aspin', '3.png', 'Alive', 0),
(4, 1, 2, '19089898', 'Sowie', '2', 'Orange', 'Aspin', 'cat.jfif', 'Alive', 0),
(5, 2, 1, '2793041796', 'Sampiee', '2', 'Black and White', 'Aspin', '1729135410_solane.jfif', 'Alive', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barangay`
--

CREATE TABLE `tbl_barangay` (
  `barangay_id` int(11) UNSIGNED NOT NULL,
  `municipality_id` int(11) UNSIGNED NOT NULL,
  `barangay` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_barangay`
--

INSERT INTO `tbl_barangay` (`barangay_id`, `municipality_id`, `barangay`) VALUES
(1, 1, 'Palutan'),
(2, 2, 'Palutan');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_municipality`
--

CREATE TABLE `tbl_municipality` (
  `municipality_id` int(11) UNSIGNED NOT NULL,
  `province_id` int(11) UNSIGNED NOT NULL,
  `municipality` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_municipality`
--

INSERT INTO `tbl_municipality` (`municipality_id`, `province_id`, `municipality`) VALUES
(1, 1, 'San Mariano'),
(2, 2, 'San Mariano');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_province`
--

CREATE TABLE `tbl_province` (
  `province_id` int(11) UNSIGNED NOT NULL,
  `province` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_province`
--

INSERT INTO `tbl_province` (`province_id`, `province`) VALUES
(1, 'Isabela'),
(2, 'Isabela');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purok`
--

CREATE TABLE `tbl_purok` (
  `purok_id` int(11) UNSIGNED NOT NULL,
  `owner_id` int(11) UNSIGNED NOT NULL,
  `barangay_id` int(11) UNSIGNED NOT NULL,
  `purok` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_purok`
--

INSERT INTO `tbl_purok` (`purok_id`, `owner_id`, `barangay_id`, `purok`) VALUES
(1, 1, 1, 'Purok 3'),
(2, 2, 2, 'Purok 2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` varchar(50) NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `account_type`) VALUES
(1, 'Roanne Bartolome', 'annetolome020311@gmail.com', '$2y$10$98QKIbaDdfMY1J5p9Q6gw.04av1fWuC.jkNm2r9nBh7tdit/r0QSa', 'Administrator'),
(6, 'Sample User', 'sample@gmail.com', '$2y$10$beNZnC8jx/Vn6xn27MpHqucIVW9C48pR3hcn3hT/fo72jQIMP.gFa', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `id` int(11) UNSIGNED NOT NULL,
  `vaccine_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`id`, `vaccine_name`) VALUES
(1, 'Sample Vaccine');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_records`
--

CREATE TABLE `vaccine_records` (
  `record_id` int(11) UNSIGNED NOT NULL,
  `dog_id` int(11) UNSIGNED NOT NULL,
  `vaccine_id` int(11) UNSIGNED NOT NULL,
  `date_of_vaccination` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Vaccinated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccine_records`
--

INSERT INTO `vaccine_records` (`record_id`, `dog_id`, `vaccine_id`, `date_of_vaccination`, `status`) VALUES
(1, 1, 1, '2024-10-10', 'Vaccinated'),
(2, 3, 1, '2024-10-18', 'Vaccinated');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`owner_id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`dog_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tbl_barangay`
--
ALTER TABLE `tbl_barangay`
  ADD PRIMARY KEY (`barangay_id`),
  ADD KEY `municipality_id` (`municipality_id`);

--
-- Indexes for table `tbl_municipality`
--
ALTER TABLE `tbl_municipality`
  ADD PRIMARY KEY (`municipality_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `tbl_province`
--
ALTER TABLE `tbl_province`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `tbl_purok`
--
ALTER TABLE `tbl_purok`
  ADD PRIMARY KEY (`purok_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vaccine_records`
--
ALTER TABLE `vaccine_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `dog_id` (`dog_id`),
  ADD KEY `vaccine_id` (`vaccine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `owner_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `dog_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_barangay`
--
ALTER TABLE `tbl_barangay`
  MODIFY `barangay_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_municipality`
--
ALTER TABLE `tbl_municipality`
  MODIFY `municipality_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_province`
--
ALTER TABLE `tbl_province`
  MODIFY `province_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_purok`
--
ALTER TABLE `tbl_purok`
  MODIFY `purok_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vaccine_records`
--
ALTER TABLE `vaccine_records`
  MODIFY `record_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pets_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_barangay`
--
ALTER TABLE `tbl_barangay`
  ADD CONSTRAINT `tbl_barangay_ibfk_1` FOREIGN KEY (`municipality_id`) REFERENCES `tbl_municipality` (`municipality_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_municipality`
--
ALTER TABLE `tbl_municipality`
  ADD CONSTRAINT `tbl_municipality_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `tbl_province` (`province_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_purok`
--
ALTER TABLE `tbl_purok`
  ADD CONSTRAINT `tbl_purok_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_purok_ibfk_2` FOREIGN KEY (`barangay_id`) REFERENCES `tbl_barangay` (`barangay_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vaccine_records`
--
ALTER TABLE `vaccine_records`
  ADD CONSTRAINT `vaccine_records_ibfk_1` FOREIGN KEY (`dog_id`) REFERENCES `pets` (`dog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vaccine_records_ibfk_2` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
