-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2026 at 07:09 AM
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
-- Database: `eco_tracker_lazaridou`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE `actions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `municipality` varchar(100) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `actions`
--

INSERT INTO `actions` (`id`, `user_id`, `category_id`, `title`, `subtitle`, `description`, `location_name`, `municipality`, `lat`, `lng`, `start_date`, `end_date`, `image_path`, `created_at`) VALUES
(1, 1, 1, 'Αναδάσωση Πάρνηθας', 'Φύτευση 500 δέντρων', 'Εθελοντική δράση για την αποκατάσταση του δάσους.', 'Πάρνηθα', 'Αχαρνές', 38.17420000, 23.71720000, '2026-03-20', '2026-03-21', 'ano.jpg', '2026-02-22 10:55:11'),
(2, 2, 2, 'Καθαρισμός Σχοινιά', 'Μαζεύουμε πλαστικά από την ακτή', 'Δράση καθαρισμού με διαλογή στην πηγή.', 'Παραλία Σχοινιά', 'Μαραθώνας', 38.14890000, 24.01520000, '2026-04-10', NULL, 'sxoinia.jpg', '2026-02-22 10:55:11'),
(3, 3, 3, 'Ημέρα Ανακύκλωσης Ηλεκτρονικών', 'Φέρε τις παλιές σου συσκευές', 'Σημείο συλλογής για κινητά και laptop.', 'Κεντρική Πλατεία', 'Νέα Σμύρνη', 37.94000000, 23.71000000, '2026-05-05', NULL, 'recycle_electrovic_appliances.png', '2026-02-22 10:55:11'),
(4, 4, 1, 'Φροντίδα Νέων Φυτών', 'Πότισμα και λίπανση', 'Συντήρηση της περσινής αναδάσωσης.', 'Υμηττός', 'Βύρωνας', 37.95000000, 23.78000000, '2026-06-01', '2026-06-02', 'care_new_plants.png', '2026-02-22 10:55:11'),
(5, 5, 2, 'Δράση στον Άλιμο', 'Καθαρισμός βυθού και ακτής', 'Συνεργασία με δύτες για καθαρισμό.', 'Μαρίνα Αλίμου', 'Άλιμος', 37.91000000, 23.70000000, '2026-07-15', NULL, 'cleaning_sea.jpg', '2026-02-22 10:55:11'),
(6, 6, 4, 'Φύτευση Αστικού Κήπου', 'Δημιουργούμε πράσινες γωνιές', 'Φύτευση αρωματικών φυτών στο κέντρο.', 'Πάρκο Τρίτση', 'Ίλιον', 38.04000000, 23.72000000, '2026-04-20', '2026-04-22', 'city-garden-design.jpg', '2026-02-22 10:55:11'),
(7, 7, 5, 'Σεμινάριο Ενέργειας', 'Πώς να μειώσετε το ρεύμα', 'Ενημέρωση για ενεργειακή αναβάθμιση.', 'Δημαρχείο', 'Αθήνα', 37.98380000, 23.72750000, '2026-03-25', NULL, 'energyblog.jpg', '2026-02-22 10:55:11'),
(8, 8, 3, 'Zero Waste Workshop', 'Φτιάξε το δικό σου κομπόστ', 'Εργαστήριο για οικιακή διαχείριση απορριμμάτων.', 'Πνευματικό Κέντρο', 'Χαλάνδρι', 38.02000000, 23.80000000, '2026-05-12', NULL, 'kompost.png', '2026-02-22 10:55:11'),
(9, 1, 4, 'Κάθετος Κήπος στο Σχολείο', 'Πράσινο στους τοίχους', 'Εκπαιδευτική δράση για μαθητές.', '1ο Δημοτικό', 'Περιστέρι', 38.01000000, 23.69000000, '2026-03-30', NULL, 'school_garden.jpg', '2026-02-22 10:55:11'),
(10, 2, 1, 'Σπορά με Drones', 'Καινοτόμος αναδάσωση', 'Δοκιμαστική σπορά σε δύσβατες περιοχές.', 'Πεντέλη', 'Πεντέλη', 38.05000000, 23.86000000, '2026-04-25', '2026-04-26', 'drones.jpg', '2026-02-22 10:55:11'),
(11, 3, 2, 'SOS Πλαστικά', 'Ενημέρωση στην παραλία', 'Μοιράζουμε υφασμάτινες τσάντες.', 'Παραλία Γλυφάδας', 'Γλυφάδα', 37.86000000, 23.75000000, '2026-08-01', NULL, 'Paralies.jpg', '2026-02-22 10:55:11'),
(12, 4, 5, 'Έλεγχος Μόνωσης', 'Δωρεάν θερμογραφία', 'Δράση ελέγχου κτιρίων για απώλειες.', 'Κοινωνικό Κέντρο', 'Ζωγράφου', 37.97000000, 23.76000000, '2026-03-15', NULL, 'houses.jpeg', '2026-02-22 10:55:11'),
(13, 1, 3, 'Ανακύκλωση Χαρτιου', 'Ανακύκλωση Χαρτιου σε Σχολικά Κτρίρια', 'Εκαπισευση μαθητών στην ανακύκλωση χαρτιού στην σχολική μονάδα 1ο Δημοτικό Σχολίο Περιστερίου', 'Περιστέρι', 'Περιστερίου', 99.99999999, 999.99999999, '2026-03-09', NULL, '1771768432_699b0a706a542.jpg', '2026-02-22 13:53:52');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Αναδασώσεις'),
(2, 'Καθαρισμοί παραλιών'),
(3, 'Ανακύκλωση'),
(4, 'Αστικοί κήποι'),
(5, 'Εξοικονόμηση ενέργειας');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`) VALUES
(1, 'admin_user', 'admin@ecotracker.gr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(2, 'green_volunteer', 'vol@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(3, 'eco_warrior', 'warrior@earth.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(4, 'forest_fan', 'forest@trees.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(5, 'sea_lover', 'sea@blue.gr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(6, 'urban_gardener', 'garden@city.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(7, 'recycle_pro', 'pro@recycle.gr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(8, 'earth_friend', 'friend@earth.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(9, 'efilaza', 'efilaza@gmail.com', '$2y$10$hKgTOhyW0No0Sp9V3oXufuT4X8wLAJsqUJBtz0hPZlPUjP687Q1Lu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actions`
--
ALTER TABLE `actions`
  ADD CONSTRAINT `actions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
