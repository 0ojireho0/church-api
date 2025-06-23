CREATE DATABASE  IF NOT EXISTS `llibiapp_churchconnect_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `llibiapp_churchconnect_db`;
-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: llibiapp_churchconnect_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `church_id` int(11) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `admin_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,NULL,'Jeremiah Mones Quintano','admin1','$2y$12$bZ3BQ0TOHpTRZoB64NIJe.XVRa1GVuTYH24kZeic4BGXtgLdeLMFu','jeremiahquintano16@gmail.com','Super Admin','2025-06-19 09:30:50','2025-06-19 09:30:50'),(10,1,'Jd Quintano','admin2','$2y$12$7aHUdP6dODPib02uWw71wOsFQDvH1r0JvLZm/JiAYScdUGh066p4e','jeremiahquintano17@gmail.com','Admin','2025-06-19 19:43:47','2025-06-19 20:22:59'),(11,2,'Juan Dela Cruz','admin3','$2y$12$.fBLCHVkacd3fJ/thzdXSOpHVAue9mpipDbFxc5G2/mrgw1LNMJMu','jeremiahquintano@llibi.com','Admin','2025-06-19 20:23:51','2025-06-19 20:23:51');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `church_id` bigint(20) DEFAULT NULL,
  `reference_num` varchar(255) DEFAULT NULL,
  `wedding_rehearsal_id` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_slot` time DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `book_type` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `mop` varchar(255) DEFAULT NULL,
  `mop_status` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL,
  `set_status` tinyint(1) DEFAULT NULL,
  `form_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`form_data`)),
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,1,1,'1750590426',NULL,'2025-06-23','08:00:00','baptism','schedule',NULL,NULL,'online','Not Paid','Pending',NULL,'{\"fullname\":\"Juan Dela Cruz\",\"gender\":\"male\",\"pob\":\"Manila\",\"father\":\"Ferdinando Quintano\",\"mother\":\"Carmelita Quintano\",\"address\":\"Blk 5 Lot 16 San Patricio St. Delpan Tondo, Manila, Manila\",\"contact\":\"+63 (939)-897-1380\",\"godfather\":\"dsadsa\",\"godmother\":\"dsadasdsa\",\"dob\":\"2222-02-22T00:04:00Z\"}',NULL,'2025-06-22 03:07:06','2025-06-22 03:07:06');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel_cache_eqwewq|127.0.0.1','i:1;',1745813224),('laravel_cache_eqwewq|127.0.0.1:timer','i:1745813224;',1745813224),('laravel_cache_ewqeqw|127.0.0.1','i:1;',1745813378),('laravel_cache_ewqeqw|127.0.0.1:timer','i:1745813378;',1745813378),('laravel_cache_ewqewq|127.0.0.1','i:1;',1745592560),('laravel_cache_ewqewq|127.0.0.1:timer','i:1745592560;',1745592560);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `churches`
--

DROP TABLE IF EXISTS `churches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `churches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `church_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `landline` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `churches`
--

LOCK TABLES `churches` WRITE;
/*!40000 ALTER TABLE `churches` DISABLE KEYS */;
INSERT INTO `churches` VALUES (1,'Archdiocesan Shrine of Sto. Niño de Tondo - Tondo Church\n','Ilaya St, Tondo, Manila, Metro Manila','Manila','123','321','tondochurch@gmail.com','Tondo Church','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/tondochurch.jpg',14.6078,120.967,'2025-04-29 11:47:16','2025-04-29 11:47:16'),(2,'Sta. Cruz Church','Plaza Santa Cruz','Manila','(02) 733-0245','(02) 733-0245','(02) 733-0245','Sta. Cruz Church','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/stacruz-church.jpg',14.5995,120.98,'2025-05-05 11:39:15','2025-05-05 11:39:21'),(3,'San Jose De Trozo Parish','1340 Masangkay St, Sta. Cruz','Manila','(02) 8256 3435','(02) 8256 3435','(02) 8256 3435','San Jose De Trozo Parish','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/San-Jose-Trozo.jpg',14.6089,120.979,'2025-05-05 11:40:50','2025-05-05 11:40:54'),(4,'Our Lady of Loreto Parish','Jose Figueras (Bustillos) St., Sampaloc','Manila','(02) 8734 6961','(02) 8734 6961','(02) 8734 6961','Our Lady of Loreto Parish','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/Archdiocesan-Shrine-of-Our-Lady-of-Loreto-Parish.jpg',14.6033,120.993,'2025-05-05 11:41:10','2025-05-05 11:41:10'),(5,'Quiapo Church','910 Plaza Miranda, Quezon Blvd., Quiapo','Manila','(02) 8735 0336 / 736 8254','(02) 8735 0336 / 736 8254','(02) 8735 0336 / 736 8254','Quiapo Church','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/quiapochurch.jpg',14.5987,120.984,NULL,NULL),(6,'San Sebastian Church','Plaza del Carmen, Bilibid Viejo St., Quiapo','Manila ','(02) 734 8931 / 736 1185\r\n','(02) 734 8931 / 736 1185\r\n','(02) 734 8931 / 736 1185\r\n','San Sebastian Church','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/san-sebastian-church.jpg',14.602,120.989,'2025-05-05 11:43:29','2025-05-05 11:43:29'),(7,'Our Lady of Fatima Parish','Lubiran cor Mag Abad Santos Sts. Bacood Sta. Mesa, Manila','Manila ','8713-5776','0908-514-3024, 0956-578-3611\r\n','email','Our Lady of Fatima Parish','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/our-lady-of-fatima.jpg',14.6093,121.021,'2025-05-05 11:44:44','2025-05-05 11:44:44'),(8,'National Shrine of Our Lady of the Abandoned, Sta. Ana, Manila','Pedro Gil Street 888, 2600 Lamayan, Santa Ana, Manila, 1009 Metro Manila\r\n','Manila ','0905 513 6950','(02) 8564 4203','email','National Shrine of Our Lady of the Abandoned, Sta. Ana, Manila','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/santa-ana-church.jpg',14.5822,121.014,'2025-05-05 11:46:04','2025-05-05 11:46:04'),(9,'Our Lady of Lourdes Church','46 P. Sanchez St, Santa Mesa, Manila, 1000 Metro Manila\r\n','Manila ','(02) 8716 3901','(02) 8716 3901','(02) 8716 3901','Our Lady of Lourdes Church','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/our-lady-of-lourdes.jpg',14.6329,120.997,'2025-05-05 11:47:32','2025-05-05 11:47:32'),(10,'Abbey of Our Lady of Montserrat','638 Mendiola St, San Miguel, Manila','Manila','(0906) 277-0381','(02) 8735-5992','ewqeqwewq','Abbey of Our Lady of Montserrat','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/Abbey%20of%20Our%20Lady%20of%20Montserrat.jpg',14.5992,120.993,NULL,NULL),(11,'National Shrine of Saint Michael and the Archangels (Archdiocese of Manila)','1000 J . P. Laurel St, corner Gen. Solano St, San Miguel, Manila, Metro Manila','Manila','09772409731','(02) 8716 3901',NULL,'National Shrine of Saint Michael and the Archangels (Archdiocese of Manila)','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/national-shrine-of-stmichael.jpg',14.5924,120.992,NULL,NULL),(12,'National Shrine of Saint Jude Thaddeus (Archdiocese of Manila)','Jose Laurel St, San Miguel, Manila, 1005 Metro Manila','Manila','09287356408','09287356408',NULL,'National Shrine of Saint Jude Thaddeus (Archdiocese of Manila)','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/NationalShrineofSaintJudeThaddeusManila.JPG',14.5975,120.995,NULL,NULL),(13,'Minor Basilica and National Shrine of Saint Lorenzo Ruiz','1006 Plaza Lorenzo Ruiz, Binondo, 1006 Metro Manila\r\n','Manila','282424850','282424850',NULL,'Minor Basilica and National Shrine of Saint Lorenzo Ruiz','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/Minor%20Basilica%20and%20National%20Shrine%20of%20Saint%20Lorenzo%20Ruiz.png',14.6002,120.974,NULL,NULL),(14,'San Agustin Church','General Luna St, Intramuros, Manila, 1002 Metro Manila','Manila','09381311285','09381311285',NULL,'San Agustin Church','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/San%20Agustin%20Church.jpg',14.5893,120.975,NULL,NULL),(15,'Saint Joseph Parish\r\n','2671 Juan Luna St, Gagalangin, Tondo, Manila, 1012 Metro Manila','Manila','282538283','282538283',NULL,'Saint Joseph Parish\r\n','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/Saint%20Joseph%20Parish.jpg',14.6307,120.974,NULL,NULL),(16,'Immaculate Conception Parish ','287 Tayuman Street, Tondo, Manila\r\n','Manila','0936 352 9501','0936 352 9501',NULL,'Immaculate Conception Parish ','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/Immaculate%20Conception%20Parish.jpg',14.6174,120.976,NULL,NULL),(17,'Archdiocesan Shrine of Espiritu Santo','1912 Rizal Ave, Santa Cruz, Manila','Manila','(02) 8711 1332','(02) 8711 1332',NULL,'Archdiocesan Shrine of Espiritu Santo','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/Archdiocesan%20Shrine%20of%20Espiritu%20Santo.jpg',14.6174,120.983,NULL,NULL),(18,'San Vicente de Paul Parish - Archdiocesan Shrine of Our Lady of the Miraculous Medal','959 San Marcelino St., Manila, Philippines','Manila',NULL,'+63 (02) 8524-2022 | +63 (02) 8525-7853\r\n',NULL,'San Vicente de Paul Parish - Archdiocesan Shrine of Our Lady of the Miraculous Medal','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/San%20Vicente%20de%20Paul%20Parish%20-%20Archdiocesan%20Shrine%20of%20Our%20Lady%20of%20the%20Miraculous%20Medal.jpg',14.5864,120.986,NULL,NULL),(19,'Our Lady of Remedies Parish (Malate Church)','2000 M. H. del Pilar St., Malate, Manila','Manila',NULL,'(+63 2) 8400-5876 | 8523-2593',NULL,'Our Lady of Remedies Parish (Malate Church)','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/Our%20Lady%20of%20Remedies%20Parish%20(Malate%20Church).jpg',14.5694,120.984,NULL,NULL),(20,'Archdiocesan Shrine of Nuestra Señora De Guia (Ermita Church)','M.H. del Pilar cor. A. Flores St., Ermita, Manila','Manila',NULL,'(+63 2) 8523‑2754',NULL,'Archdiocesan Shrine of Nuestra Señora De Guia (Ermita Church)','https://llibi-dms.sgp1.cdn.digitaloceanspaces.com/church/Archdiocesan%20Shrine%20of%20Nuestra%20Se%C3%B1ora%20De%20Guia%20(Ermita%20Church).jpg',14.5789,120.98,NULL,NULL);
/*!40000 ALTER TABLE `churches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fully_books`
--

DROP TABLE IF EXISTS `fully_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fully_books` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `church_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fully_books`
--

LOCK TABLES `fully_books` WRITE;
/*!40000 ALTER TABLE `fully_books` DISABLE KEYS */;
/*!40000 ALTER TABLE `fully_books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (6,'0001_01_01_000000_create_users_table',1),(7,'0001_01_01_000001_create_cache_table',1),(8,'0001_01_01_000002_create_jobs_table',1),(9,'2025_04_22_132312_create_personal_access_tokens_table',1),(10,'2025_04_25_133712_create_admins_table',1),(14,'2025_04_29_114138_create_churches_table',2),(26,'2025_05_25_141040_create_bookings_table',3),(27,'2025_05_25_160855_create_fully_books_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('KTEJ6sPlVN9awhhFj26MGtkelPukB63eJ7r6ZecL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiQVJTdHhjMUp0VkFTS2N5dkh5ejZ1VFFZdWRiVHA5QWxCNEgxdnNCRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1750666823);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Jeremiah Mones Quintano','jeremiahquintano16@gmail.com',NULL,'$2y$12$eEQUnXRUGwP2b1jCpzfize9hlzipkcyngxP1vv5e..RBaPLrk9AGW','loV0jFwoD0FC2sKRuyLYyzQ6z0NeEcnrXeduZAYWUCmeW0Vfj2nlxLJejspe','0ojireho0','+639398971380','2025-06-17 23:46:01','2025-06-23 00:16:40');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-23 16:22:14
