/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.4.3-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: reax
-- ------------------------------------------------------
-- Server version	11.4.3-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_lead_id_foreign` (`lead_id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES
(1,11,1,'updated_lead','Lead updated: Ahmed Gomaa','{\"changes\":{\"notes\":{\"old\":\"test\",\"new\":\"testok\"}}}','2025-03-04 09:13:35','2025-03-04 09:13:35'),
(2,11,1,'updated_lead','Lead updated: Ahmed Gomaa 2','{\"changes\":{\"last_name\":{\"old\":\"Gomaa\",\"new\":\"Gomaa 2\"}}}','2025-03-04 09:13:56','2025-03-04 09:13:56'),
(3,1,1,'updated_lead','Lead updated: Interested property changed from \'est molestiae Residence\' to \'est molestiae Residence\', Notes were updated','{\"changes\":{\"property_interest\":{\"old\":8,\"new\":\"8\"},\"notes\":{\"old\":\"Eveniet qui voluptate labore dignissimos sint recusandae. In quae molestiae nam aut eius. Qui numquam exercitationem sint.\",\"new\":\"Eveniet qui voluptate labore dignissimos sint recusandae. In quae molestiae nam aut eius. Qui numquam exercitationem sint. mm\"}}}','2025-03-04 09:17:29','2025-03-04 09:17:29'),
(4,1,1,'updated_field_property_interest','Changed interested property from \'est molestiae Residence\' to \'est molestiae Residence\'','{\"field\":\"property_interest\",\"old_value\":8,\"new_value\":\"8\"}','2025-03-04 09:17:29','2025-03-04 09:17:29'),
(5,11,1,'updated_lead','Lead updated: Interested property changed from \'veniam aut Villa\' to \'veniam aut Villa\', Notes were updated, Assigned user changed from \'Ahmed Gomaa\' to \'Ahmed Gomaa\'','{\"changes\":{\"property_interest\":{\"old\":1,\"new\":\"1\"},\"notes\":{\"old\":\"testok\",\"new\":\"testok-ok\"},\"assigned_to\":{\"old\":1,\"new\":\"1\"}}}','2025-03-04 09:17:43','2025-03-04 09:17:43'),
(6,11,1,'updated_field_property_interest','Changed interested property from \'veniam aut Villa\' to \'veniam aut Villa\'','{\"field\":\"property_interest\",\"old_value\":1,\"new_value\":\"1\"}','2025-03-04 09:17:43','2025-03-04 09:17:43'),
(7,11,1,'updated_field_notes','Changed notes from \'testok\' to \'testok-ok\'','{\"field\":\"notes\",\"old_value\":\"testok\",\"new_value\":\"testok-ok\"}','2025-03-04 09:17:43','2025-03-04 09:17:43'),
(8,11,1,'updated_field_assigned_to','Changed assigned user from \'Ahmed Gomaa\' to \'Ahmed Gomaa\'','{\"field\":\"assigned_to\",\"old_value\":1,\"new_value\":\"1\"}','2025-03-04 09:17:43','2025-03-04 09:17:43'),
(9,11,1,'added_note','Added a note to lead','{\"note\":\"test note i am a seller or agent\"}','2025-03-04 09:37:28','2025-03-04 09:37:28'),
(10,11,1,'created_event','Scheduled Meeting: \"his birthday\" for Ahmed Gomaa 2 on Mar 04, 2025 12:00 PM','{\"event_id\":1,\"event_date\":\"2025-03-04T12:00:00.000000Z\",\"event_type\":\"meeting\",\"title\":\"his birthday\",\"description\":null}','2025-03-04 09:38:00','2025-03-04 09:38:00'),
(11,NULL,1,'exported_leads','Exported 11 leads to xlsx','{\"format\":\"xlsx\",\"scope\":\"all\",\"count\":11}','2025-03-04 10:22:07','2025-03-04 10:22:07'),
(12,NULL,1,'exported_leads','Exported 11 leads to csv','{\"format\":\"csv\",\"scope\":\"all\",\"count\":11}','2025-03-04 10:22:31','2025-03-04 10:22:31'),
(13,NULL,1,'exported_leads','Exported 11 leads to xlsx','{\"format\":\"xlsx\",\"scope\":\"all\",\"count\":11}','2025-03-04 10:22:45','2025-03-04 10:22:45'),
(14,NULL,1,'exported_leads','Exported 11 leads to xlsx','{\"format\":\"xlsx\",\"scope\":\"all\",\"count\":11}','2025-03-04 10:22:51','2025-03-04 10:22:51');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` enum('meeting','call','email','birthday','follow_up','other') NOT NULL DEFAULT 'meeting',
  `event_date` datetime NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completion_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_lead_id_foreign` (`lead_id`),
  KEY `events_user_id_foreign` (`user_id`),
  CONSTRAINT `events_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES
(1,11,1,'his birthday',NULL,'meeting','2025-03-04 12:00:00',0,NULL,'2025-03-04 09:38:00','2025-03-04 09:38:00');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
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
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` enum('new','contacted','qualified','proposal','negotiation','won','lost') NOT NULL DEFAULT 'new',
  `source` varchar(255) DEFAULT NULL,
  `property_interest` bigint(20) unsigned DEFAULT NULL,
  `budget` decimal(12,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_property_interest_foreign` (`property_interest`),
  KEY `leads_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_property_interest_foreign` FOREIGN KEY (`property_interest`) REFERENCES `properties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
INSERT INTO `leads` VALUES
(1,'Jose','Botsford','golda09@example.org','+1 (445) 842-8249','lost','advertisement',8,1426726.00,'Eveniet qui voluptate labore dignissimos sint recusandae. In quae molestiae nam aut eius. Qui numquam exercitationem sint. mm',NULL,'2025-03-04 08:58:11','2025-03-04 09:17:29'),
(2,'Ewald','Johnston','sanford.iva@example.net','+1 (913) 767-1890','qualified','other',7,1886287.00,'Eum eveniet qui quod quia. Odio et dolorem placeat et. Iure quisquam quis quis voluptatibus quisquam dolores magni veritatis. Sint saepe amet nobis.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(3,'Scarlett','Balistreri','loyce26@example.net','+1.857.314.9266','contacted','referral',4,1737699.00,'Quia vel corporis repellendus. Quaerat qui consequatur aperiam quia. Doloribus atque qui quasi ut tenetur a consequatur illum.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(4,'Newell','Doyle','xbahringer@example.org','+18784985789','new','advertisement',8,1549496.00,'Est quas illo excepturi qui. Et ad consequatur mollitia. Soluta eum repellendus provident quidem eligendi quo ut. Sit consequatur similique maxime provident cum nemo voluptas.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(5,'Dewitt','Jacobs','kcarter@example.com','516.207.4210','won','advertisement',9,1858526.00,'Voluptas nisi exercitationem et nam non. Quas excepturi molestiae cum similique dolorem est optio. Rerum illo cupiditate reiciendis autem officia. Praesentium quia omnis laborum est nostrum.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(6,'Clifford','Spinka','harvey.leopold@example.com','380.256.2357','contacted','referral',4,1164669.00,'Aut praesentium qui et et. Vero assumenda veniam expedita iure in. Eius occaecati eum impedit quidem. Est et magni iusto. Fuga enim architecto iste impedit.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(7,'Myah','Bode','lorenza.sanford@example.org','1-680-586-7361','negotiation','website',9,1328810.00,'Harum blanditiis sequi animi deleniti aut aut aperiam. Rerum quia amet autem fuga. Ipsum beatae dolorum porro sint modi ex. Sed ut eum perspiciatis quaerat illum accusantium.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(8,'Lucienne','Tromp','connie.rath@example.org','283-617-3005','proposal','advertisement',5,865199.00,'Et rem repellat id est qui. Iure occaecati adipisci quis nisi minus minus. Eum similique eligendi veniam eos.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(9,'Frankie','Considine','sauer.rickie@example.org','520.339.7430','won','referral',10,859504.00,'Voluptatem et tempora iusto soluta ipsam. Et quidem iusto aut rerum aut repellendus dolorem. Soluta non voluptatem commodi id assumenda.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(10,'Corrine','Zulauf','padberg.cristina@example.net','717.287.2196','qualified','website',9,1285952.00,'Quod rerum consequuntur esse quas exercitationem. Sed molestias aut asperiores. Laudantium pariatur iusto amet.',NULL,'2025-03-04 08:58:11','2025-03-04 08:58:11'),
(11,'Ahmed','Gomaa 2','ahmedgfathy@gmail.com','01002778090','negotiation','social media',1,1000000.00,'testok-ok\n\ntest note i am a seller or agent',1,'2025-03-04 09:02:00','2025-03-04 09:37:28');
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2023_10_10_000001_create_properties_table',1),
(5,'2023_10_10_000002_update_properties_table',1),
(6,'2023_10_12_000000_create_leads_table',1),
(7,'2025_03_04_074559_0001_01_01_000000_create_users_table',1),
(8,'2023_10_15_000000_create_events_table',2),
(9,'2023_10_15_000001_create_activity_logs_table',2),
(10,'2024_03_04_make_lead_id_nullable_in_activity_logs',3),
(11,'2024_03_04_create_projects_table',4),
(12,'2024_03_04_create_property_media_table',4),
(13,'2024_03_04_update_properties_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `developer` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `launch_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `properties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `rooms` int(11) NOT NULL,
  `bathrooms` int(11) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `compound_name` varchar(255) DEFAULT NULL,
  `property_number` varchar(255) DEFAULT NULL,
  `unit_for` varchar(255) DEFAULT NULL,
  `phase` varchar(255) DEFAULT NULL,
  `building` varchar(255) DEFAULT NULL,
  `floor` varchar(255) DEFAULT NULL,
  `finished` varchar(255) DEFAULT NULL,
  `property_props` text DEFAULT NULL,
  `location_type` varchar(255) DEFAULT NULL,
  `price_per_meter` decimal(12,2) DEFAULT NULL,
  `project_id` bigint(20) unsigned DEFAULT NULL,
  `last_follow_up` timestamp NULL DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `rent_from` timestamp NULL DEFAULT NULL,
  `rent_to` timestamp NULL DEFAULT NULL,
  `land_area` decimal(10,2) DEFAULT NULL,
  `space_earth` decimal(10,2) DEFAULT 0.00,
  `garden_area` decimal(10,2) DEFAULT 0.00,
  `unit_area` decimal(10,2) DEFAULT 0.00,
  `property_offered_by` varchar(255) DEFAULT NULL,
  `owner_mobile` varchar(255) DEFAULT NULL,
  `owner_tel` varchar(255) DEFAULT NULL,
  `update_calls` varchar(255) DEFAULT NULL,
  `handler_id` bigint(20) unsigned DEFAULT NULL,
  `sales_person_id` bigint(20) unsigned DEFAULT NULL,
  `sales_category` varchar(255) DEFAULT NULL,
  `sales_notes` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` VALUES
(1,'veniam aut Villa','7749 Oberbrunner Rapids Apt. 294\nHirthestad, SC 91668',691771,123,'Condo','Nihil est a accusamus ea. Vel eos quasi recusandae reprehenderit nesciunt voluptatum temporibus. Dolores error nobis omnis.',3,2,'EUR','Brannon Homenick V','https://images.unsplash.com/photo-1600566753086-00f18fb6b3d7','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(2,'dolores provident Penthouse','4279 Gutkowski Circles Suite 582\nO\'Konport, MD 15907',442816,392,'House','Possimus esse facere optio totam voluptas quae consequatur nostrum. Ut recusandae et suscipit nostrum et dolores. Non vel velit quaerat expedita. Dolor exercitationem corporis quia ut.',5,2,'EUR','Amparo Baumbach','https://images.unsplash.com/photo-1512917774080-9991f1c4c750','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(3,'similique aut Residence','6580 Ashton Corner\nO\'Konside, AL 54448',233727,136,'Condo','Quia dolores hic et. Ratione sed quaerat aut quidem. Aut est illum laborum in. Error et sed ratione perspiciatis autem.',4,2,'EUR','Prof. Koby Watsica V','https://images.unsplash.com/photo-1600047509807-f8261a3f6dab','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(4,'quis iure Penthouse','15667 Klein Mission Suite 458\nSouth Eliane, NC 70817-1913',437599,448,'House','Quam numquam corrupti nesciunt aliquam eligendi qui. Iste asperiores non et fugit nihil. In voluptatem ad amet.',3,3,'GBP','Savanna O\'Kon','https://images.unsplash.com/photo-1600607687939-ce8a6c25118c','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(5,'quia est Residence','4924 Murphy Ridges Suite 490\nLibbieland, GA 36898',321195,444,'House','Adipisci officiis eveniet ut voluptas. Animi enim aut itaque ducimus laudantium nesciunt. Dolor adipisci voluptatem ipsa vitae provident. Dolorum aut mollitia doloremque id hic quibusdam odio. Impedit sed ut dolores aut rem.',2,3,'USD','Mac Kassulke III','https://images.unsplash.com/photo-1512917774080-9991f1c4c750','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(6,'quis a Penthouse','31087 Hill Road Suite 857\nLondonfurt, LA 19420',709154,472,'Penthouse','Libero necessitatibus nihil illum ut ut esse. Architecto odio ex aut dolorum alias. Exercitationem commodi repudiandae facilis occaecati ad. Consequatur earum id magnam qui cum.',5,3,'GBP','Miss Monica Raynor V','https://images.unsplash.com/photo-1600566752355-35792bedcfea','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(7,'dolorum consequatur Penthouse','35359 Amparo Road Suite 990\nGottliebville, MS 56088-9455',649957,231,'Penthouse','Velit qui et velit adipisci hic nihil optio. Impedit recusandae molestiae iusto possimus et et minima. Et sed non voluptas totam. Consequuntur recusandae neque aspernatur esse quia quae dolorem alias.',5,3,'EUR','Mr. Tatum Towne','https://images.unsplash.com/photo-1600607687939-ce8a6c25118c','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(8,'est molestiae Residence','2693 Bernhard Junctions Apt. 004\nWest Marshall, DC 19729',920261,265,'House','Aut ut facilis aperiam magnam omnis. Recusandae dicta ad maiores dicta rerum. Natus sint molestias in sequi harum ea dolores.',3,1,'USD','Niko Jacobs','https://images.unsplash.com/photo-1600566753086-00f18fb6b3d7','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(9,'dolorem cumque Villa','351 Runte Extensions\nDustinview, SD 03724-2770',198281,468,'Condo','Quia quis tempore quidem itaque iure nesciunt a. Voluptatem et quae et quis. Accusamus fugiat voluptate sed. Dolorem ut qui debitis.',4,1,'GBP','Tiara Pfeffer','https://images.unsplash.com/photo-1600047509807-f8261a3f6dab','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(10,'eaque eos Penthouse','5385 Geovany Passage Apt. 131\nCarolinechester, NV 86915-1442',579854,85,'Condo','Soluta ipsam unde blanditiis dolores facere corporis iusto. Aut quos ut neque vel veritatis culpa. Quis ipsa excepturi iste et rerum eveniet. Quo architecto velit fugit voluptas.',3,1,'GBP','Elna Ritchie','https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3','2025-03-04 08:58:11','2025-03-04 08:58:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,0.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property_media`
--

DROP TABLE IF EXISTS `property_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property_media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `property_id` bigint(20) unsigned NOT NULL,
  `media_type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_media_property_id_foreign` (`property_id`),
  CONSTRAINT `property_media_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property_media`
--

LOCK TABLES `property_media` WRITE;
/*!40000 ALTER TABLE `property_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `property_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
INSERT INTO `sessions` VALUES
('4GXoUzENedT01ffNPhZpWzlGG2BiRHOsbEdrh82E',1,'192.168.1.100','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36 Edg/133.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiN01VeHVwSmNVSnRRQUZTd0xiaXhPQUhWNk42R05lVGF2SHZIejllRCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTkyLjE2OC4xLjM6ODAwMC9wcm9wZXJ0aWVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjEyOiJsZWFkX2ZpbHRlcnMiO2E6MDp7fX0=',1741112250),
('9oCPC0TNzgbceHyOz8SahQ2W7CBQgcRJkpDeK24d',NULL,'198.235.24.159','curl/7.68.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMm81ZWY2TGRNblNPeFJyVDNvY1psb2dTTmN1YWFtbG1Ob0FaV29TVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly84MS4xMC42Mi4xMDE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1741105872),
('b1cpHUtF5yY0mYKUHnlbFmi6cGUe7BmTCvfkpeVH',NULL,'91.196.152.58','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:130.0) Gecko/20100101 Firefox/130.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicnAwMjZrS3IxdWFKY3JNREJMUUI3UXpxQ3FLdlVDWUdBZ3ZTQU1UdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly84MS4xMC42Mi4xMDE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1741105211),
('jO8CSXcfpDE5zGVm6ZzTUynveXkjqQkrz3Y092aw',NULL,'45.156.129.112','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36 ','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2JuYUtISXd4TnI4Um1CRThtYnRxN3czMVNydlV0NUNsSUtXbWpEeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly84MS4xMC42Mi4xMDE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1741110408),
('MtL5ObFoa9Z103QGUmys611KVaNa34ZF4ZPHLyU3',NULL,'45.156.129.113','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36 ','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1paaDYwWXNuYTJPOUtsbDdGbWV6VVpGWVJLTnVKNEVsbFJwQmNUWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly84MS4xMC42Mi4xMDE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1741110384),
('pIB5isIDWdLmBwxKzele9hjChn77CXlrlpAmPt5Y',NULL,'118.193.40.88','curl/7.29.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNDF0NThJRENjVlFJQmlzNEM2clZaNHZYcXNYMFR2Z3R1Mk1lNG5JVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTk6Imh0dHA6Ly84MS4xMC42Mi4xMDEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1741108189),
('t1Q98f7VNCGCFOGAxQNRTXgutbpEto9181X4dEaS',NULL,'135.148.63.211','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:130.0) Gecko/20100101 Firefox/130.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjY1MERhRGNYaU41MXJkdnpUVUluMndxd2x3aW5oWVBEUTBxdlRrOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly84MS4xMC42Mi4xMDE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1741110991),
('TSiEI5RyinR5MQsem2dh6G9IQDqEWbEtgBkVvmuv',NULL,'118.193.38.134','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/577.44 (KHTML, like Gecko) Chrome/89.0.1795 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidVR3Q1VNU0R1d1ZzTmVkbnJNR2lOemN4YldBSEhLbTBuQzFoeWRCTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly84MS4xMC42Mi4xMDE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1741108216);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
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
INSERT INTO `users` VALUES
(1,'Ahmed Gomaa','ahmedgfathy@gmail.com',NULL,'$2y$12$E7f5E9s5/NLYe9IrK9uw0OpeKT7Fw..Ffw6CQfLgcJqZQIn6SMjdG',1,NULL,'2025-03-04 08:58:34','2025-03-04 08:58:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-03-04 18:18:14
