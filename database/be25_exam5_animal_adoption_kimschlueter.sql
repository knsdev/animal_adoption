-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2025 at 02:02 PM
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
-- Database: `be25_exam5_animal_adoption_kimschlueter`
--
CREATE DATABASE IF NOT EXISTS `be25_exam5_animal_adoption_kimschlueter` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `be25_exam5_animal_adoption_kimschlueter`;

-- --------------------------------------------------------

--
-- Table structure for table `animal`
--

CREATE TABLE `animal` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `size` enum('small','default','big') NOT NULL DEFAULT 'default',
  `age` int(11) DEFAULT NULL,
  `vaccinated` tinyint(1) NOT NULL,
  `status` enum('available','adopted') NOT NULL DEFAULT 'available',
  `breed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `animal`
--

INSERT INTO `animal` (`id`, `name`, `picture`, `location`, `description`, `size`, `age`, `vaccinated`, `status`, `breed_id`) VALUES
(1, 'Bella', NULL, 'Musterstraße 12, 1010 Wien', 'Golden Retriever, friendly and playful.', 'big', 3, 1, 'available', 1),
(2, 'Whiskers', NULL, 'Hauptplatz 9, 4020 Linz', 'Siamese cat, very vocal and affectionate.', 'small', 2, 1, 'available', 3),
(3, 'Thumper', NULL, 'Mozartstraße 4, 5020 Salzburg', 'Netherland Dwarf rabbit, gentle and quiet.', 'small', 1, 0, 'available', 5),
(4, 'Milo', NULL, 'Bahnhofstraße 8, 8010 Graz', 'Beagle, energetic and curious.', 'default', 9, 1, 'available', 2),
(5, 'Luna', NULL, 'Am Ring 5, 1010 Wien', 'Maine Coon cat, calm and fluffy.', 'default', 9, 1, 'available', 4),
(6, 'Chirpy', NULL, 'Landstraße 22, 4020 Linz', 'Cockatiel bird, social and sings often.', 'small', 2, 1, 'available', 6),
(7, 'Fluffy', NULL, 'Schönbrunner Allee 3, 1120 Wien', 'Persian cat, quiet and loves to nap.', 'small', 3, 0, 'available', 7),
(8, 'Rex', NULL, 'Getreidegasse 18, 5020 Salzburg', 'Labrador, loyal and great with kids.', 'big', 10, 1, 'available', 8),
(9, 'Ziggy', NULL, 'Herrengasse 14, 8010 Graz', 'Abyssinian cat, very active and curious.', 'small', 12, 1, 'available', 9),
(10, 'Cocoa', NULL, 'Linzer Gasse 7, 5020 Salzburg', 'Mini Lop rabbit, loves to be cuddled.', 'small', 1, 1, 'available', 10);

-- --------------------------------------------------------

--
-- Table structure for table `breed`
--

CREATE TABLE `breed` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breed`
--

INSERT INTO `breed` (`id`, `name`) VALUES
(1, 'Golden Retriever'),
(2, 'Beagle'),
(3, 'Siamese Cat'),
(4, 'Maine Coon'),
(5, 'Netherland Dwarf'),
(6, 'Cockatiel'),
(7, 'Persian Cat'),
(8, 'Labrador'),
(9, 'Abyssinian'),
(10, 'Mini Lop');

-- --------------------------------------------------------

--
-- Table structure for table `pet_adoption`
--

CREATE TABLE `pet_adoption` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `authority` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `picture`, `authority`) VALUES
(1, 'admin@admin.com', 'cbfad02f9ed2a8d1e08d8f74f5303e9eb93637d47f82ab6f1c15871cf8dd0481', 'Admin', 'istrator', '+4312345678912345', 'Entenstraße 123, 12345 Entenhausen', 'img_688e5d863fba0.jpg', 'admin'),
(2, 'user@user.com', 'cbfad02f9ed2a8d1e08d8f74f5303e9eb93637d47f82ab6f1c15871cf8dd0481', 'Thomas', 'Anderson', '+49 999 1000000', 'Geldspeicherstraße 1, 12345 Entenhausen', 'img_688e5db21fa50.png', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `breed_id` (`breed_id`);

--
-- Indexes for table `breed`
--
ALTER TABLE `breed`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pet_id` (`pet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animal`
--
ALTER TABLE `animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `breed`
--
ALTER TABLE `breed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `animal_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`);

--
-- Constraints for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  ADD CONSTRAINT `pet_adoption_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `pet_adoption_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `animal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
