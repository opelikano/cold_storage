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
-- Table structure for table `cold_storage_requests`
--

CREATE TABLE `cold_storage_requests` (
  `id` int NOT NULL,
  `requester_id` int NOT NULL,
  `avaliability_id` int NOT NULL,
  `region_id` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `required_capacity` int NOT NULL COMMENT 'in litres',
  `type` enum('stationare','portable') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','approved','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `priority` enum('low','middle','hight','highest') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approved_aviability_id` int DEFAULT NULL,
  `comments` json DEFAULT NULL,
  `updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for table `cold_storage_requests`
--
ALTER TABLE `cold_storage_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_cold_storage_unit_id` (`approved_aviability_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cold_storage_requests`
--
ALTER TABLE `cold_storage_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cold_storage_requests`
--
ALTER TABLE `cold_storage_requests`
  ADD CONSTRAINT `cold_storage_requests_ibfk_1` FOREIGN KEY (`approved_aviability_id`) REFERENCES `cold_storage_units` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
