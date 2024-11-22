
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cold_storage`
--

-- --------------------------------------------------------

--
-- Table structure for table `cold_storage_units`
--

CREATE TABLE `cold_storage_units` (
  `id` int NOT NULL,
  `owner_id` int NOT NULL,
  `temperature_range` tinyint(1) NOT NULL,
  `total_capacity` int NOT NULL COMMENT 'in litres',
  `has_stability` tinyint(1) NOT NULL,
  `has_remote_monitoring` tinyint(1) NOT NULL,
  `has_backup_power` tinyint(1) NOT NULL,
  `has_humidity_control` tinyint(1) NOT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('stationare','portable') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `production_year` int NOT NULL,
  `usage_start_year` int NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for table `cold_storage_units`
--
ALTER TABLE `cold_storage_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id_idx` (`owner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cold_storage_units`
--
ALTER TABLE `cold_storage_units`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
