/*M!999999\- enable the sandbox mode */ 
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `event_type` varchar(191) NOT NULL,
  `action_type` varchar(191) NOT NULL,
  `module_name` varchar(191) NOT NULL,
  `loggable_type` varchar(191) NOT NULL,
  `loggable_id` bigint(20) unsigned NOT NULL,
  `description` text DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(191) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` bigint(20) unsigned DEFAULT NULL,
  `device_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`device_info`)),
  `location_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`location_info`)),
  `shared_with` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shared_with`)),
  `visibility_changed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_company_id_foreign` (`company_id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_loggable_type_loggable_id_index` (`loggable_type`,`loggable_id`),
  KEY `activity_logs_session_id_foreign` (`session_id`),
  CONSTRAINT `activity_logs_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `activity_logs_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `user_sessions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `address` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `manager_name` varchar(191) DEFAULT NULL,
  `manager_phone` varchar(191) DEFAULT NULL,
  `manager_email` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_code_unique` (`code`),
  KEY `branches_company_id_foreign` (`company_id`),
  CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  UNIQUE KEY `cache_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `owner_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_slug_unique` (`slug`),
  UNIQUE KEY `companies_email_unique` (`email`),
  KEY `companies_owner_id_foreign` (`owner_id`),
  CONSTRAINT `companies_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `type` varchar(191) NOT NULL DEFAULT 'prospect',
  `position` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_company_id_foreign` (`company_id`),
  CONSTRAINT `contacts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `manager_name` varchar(191) DEFAULT NULL,
  `manager_phone` varchar(191) DEFAULT NULL,
  `manager_email` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_code_unique` (`code`),
  KEY `departments_company_id_foreign` (`company_id`),
  KEY `departments_parent_id_foreign` (`parent_id`),
  CONSTRAINT `departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `departments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appwrite_id` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` varchar(191) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'scheduled',
  `location` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_appwrite_id_unique` (`appwrite_id`),
  KEY `events_company_id_foreign` (`company_id`),
  KEY `events_user_id_foreign` (`user_id`),
  KEY `events_lead_id_foreign` (`lead_id`),
  CONSTRAINT `events_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `events_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`),
  CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `goals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `territory_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('individual','team','department','company') NOT NULL DEFAULT 'individual',
  `category` enum('sales','revenue','leads','activities','performance','quality') NOT NULL DEFAULT 'sales',
  `metric_type` varchar(191) NOT NULL,
  `target_value` decimal(15,2) NOT NULL,
  `current_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(191) NOT NULL,
  `achievement_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `period_type` enum('daily','weekly','monthly','quarterly','yearly','custom') NOT NULL DEFAULT 'monthly',
  `status` enum('draft','active','paused','completed','cancelled','overdue') NOT NULL DEFAULT 'draft',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `progress_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_updated_date` date DEFAULT NULL,
  `reward_amount` decimal(15,2) DEFAULT NULL,
  `reward_type` varchar(191) DEFAULT NULL,
  `reward_description` text DEFAULT NULL,
  `auto_update` tinyint(1) NOT NULL DEFAULT 0,
  `update_rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`update_rules`)),
  `milestone_checkpoints` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`milestone_checkpoints`)),
  `send_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `notification_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_settings`)),
  `manager_notes` text DEFAULT NULL,
  `employee_notes` text DEFAULT NULL,
  `review_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`review_history`)),
  `next_review_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goals_created_by_foreign` (`created_by`),
  KEY `goals_territory_id_foreign` (`territory_id`),
  KEY `goals_company_id_status_start_date_index` (`company_id`,`status`,`start_date`),
  KEY `goals_user_id_type_status_index` (`user_id`,`type`,`status`),
  KEY `goals_team_id_category_status_index` (`team_id`,`category`,`status`),
  KEY `goals_end_date_status_index` (`end_date`,`status`),
  CONSTRAINT `goals_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goals_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goals_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `goals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `lead_classifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lead_classifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lead_classifications_company_id_code_unique` (`company_id`,`code`),
  CONSTRAINT `lead_classifications_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `lead_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lead_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `event_type` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` timestamp NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'scheduled',
  `outcome` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_events_lead_id_foreign` (`lead_id`),
  KEY `lead_events_user_id_foreign` (`user_id`),
  CONSTRAINT `lead_events_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lead_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appwrite_id` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'new',
  `source` varchar(191) DEFAULT NULL,
  `lead_source` varchar(191) DEFAULT NULL,
  `lead_type` varchar(191) DEFAULT NULL,
  `lead_classification_id` bigint(20) unsigned DEFAULT NULL,
  `budget` decimal(12,2) DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `last_contact` timestamp NULL DEFAULT NULL,
  `next_follow_up` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `property_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leads_appwrite_id_unique` (`appwrite_id`),
  KEY `leads_company_id_foreign` (`company_id`),
  KEY `leads_assigned_to_foreign` (`assigned_to`),
  KEY `leads_lead_classification_id_foreign` (`lead_classification_id`),
  KEY `leads_property_id_foreign` (`property_id`),
  CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `leads_lead_classification_id_foreign` FOREIGN KEY (`lead_classification_id`) REFERENCES `lead_classifications` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `opportunities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `opportunities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `lead_id` bigint(20) unsigned NOT NULL,
  `property_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `status` enum('open','qualified','proposal','negotiation','won','lost') NOT NULL DEFAULT 'open',
  `stage` enum('initial','meeting','proposal','negotiation','closing') NOT NULL DEFAULT 'initial',
  `value` decimal(12,2) DEFAULT NULL,
  `probability` int(11) NOT NULL DEFAULT 0,
  `expected_close_date` timestamp NULL DEFAULT NULL,
  `actual_close_date` timestamp NULL DEFAULT NULL,
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `territory_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `opportunities_company_id_foreign` (`company_id`),
  KEY `opportunities_assigned_to_foreign` (`assigned_to`),
  KEY `opportunities_lead_id_foreign` (`lead_id`),
  KEY `opportunities_property_id_foreign` (`property_id`),
  KEY `opportunities_territory_id_foreign` (`territory_id`),
  CONSTRAINT `opportunities_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `opportunities_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `opportunities_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `opportunities_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  CONSTRAINT `opportunities_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `module` varchar(191) NOT NULL,
  `field_name` varchar(191) DEFAULT NULL,
  `actions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`actions`)),
  `company_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`),
  KEY `permissions_company_id_foreign` (`company_id`),
  KEY `permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `permissions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profile_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_permission` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile_permission_profile_id_permission_id_unique` (`profile_id`,`permission_id`),
  KEY `profile_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `profile_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `profile_permission_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profile_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_rule` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `display_name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `level` enum('administration','manager','team_leader','employee') NOT NULL,
  `privileges` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`privileges`)),
  `authentication_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`authentication_settings`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appwrite_id` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `location` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `developer` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `total_area` decimal(12,2) DEFAULT NULL,
  `total_units` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_code_unique` (`code`),
  UNIQUE KEY `projects_appwrite_id_unique` (`appwrite_id`),
  KEY `projects_company_id_foreign` (`company_id`),
  CONSTRAINT `projects_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `properties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appwrite_id` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned DEFAULT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `handler_id` bigint(20) unsigned DEFAULT NULL,
  `sales_person_id` bigint(20) unsigned DEFAULT NULL,
  `handler` varchar(191) DEFAULT NULL,
  `property_name` varchar(191) DEFAULT NULL,
  `compound_name` varchar(191) DEFAULT NULL,
  `property_number` varchar(191) DEFAULT NULL,
  `unit_no` varchar(191) DEFAULT NULL,
  `unit_for` enum('sale','rent','both') NOT NULL DEFAULT 'sale',
  `type` varchar(191) NOT NULL,
  `phase` varchar(191) DEFAULT NULL,
  `building` varchar(191) DEFAULT NULL,
  `floor` varchar(191) DEFAULT NULL,
  `category` enum('residential','commercial','administrative') NOT NULL DEFAULT 'residential',
  `finished` tinyint(1) NOT NULL DEFAULT 1,
  `location_type` enum('inside','outside') NOT NULL DEFAULT 'inside',
  `total_area` decimal(10,2) DEFAULT NULL,
  `land_area` decimal(10,2) DEFAULT NULL,
  `space_earth` decimal(10,2) DEFAULT NULL,
  `space_guard` decimal(10,2) DEFAULT NULL,
  `space_unit` decimal(10,2) DEFAULT NULL,
  `unit_area` decimal(10,2) DEFAULT NULL,
  `building_area` decimal(10,2) DEFAULT NULL,
  `garden_area` decimal(10,2) DEFAULT NULL,
  `area_text` varchar(100) DEFAULT NULL,
  `rooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `total_price` decimal(12,2) DEFAULT NULL,
  `price_per_meter` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'EGP',
  `rent_from` date DEFAULT NULL,
  `rent_to` date DEFAULT NULL,
  `owner_name` varchar(191) DEFAULT NULL,
  `owner_mobile` varchar(191) DEFAULT NULL,
  `owner_tel` varchar(191) DEFAULT NULL,
  `contact_status` enum('active','inactive','pending') NOT NULL DEFAULT 'active',
  `owner_phone` varchar(191) DEFAULT NULL,
  `owner_email` varchar(191) DEFAULT NULL,
  `owner_contact_status` varchar(191) DEFAULT NULL,
  `sales_category` varchar(191) DEFAULT NULL,
  `property_offered_by` varchar(191) DEFAULT NULL,
  `for_update` tinyint(1) NOT NULL DEFAULT 0,
  `last_call_update` timestamp NULL DEFAULT NULL,
  `sales_notes` text DEFAULT NULL,
  `status` enum('available','sold','rented','reserved') NOT NULL DEFAULT 'available',
  `activity` varchar(191) DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `last_follow_up` timestamp NULL DEFAULT NULL,
  `is_shared` tinyint(1) NOT NULL DEFAULT 0,
  `sharing_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sharing_settings`)),
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_in_home` tinyint(1) NOT NULL DEFAULT 0,
  `is_liked` tinyint(1) NOT NULL DEFAULT 0,
  `purpose` varchar(191) DEFAULT NULL,
  `unit_number` varchar(191) DEFAULT NULL,
  `built_area` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(191) DEFAULT NULL,
  `down_payment` decimal(12,2) DEFAULT NULL,
  `installment_years` int(11) DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `the_floors` varchar(191) DEFAULT NULL,
  `finished_type` varchar(191) DEFAULT NULL,
  `unit_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`unit_features`)),
  `in_or_outside_compound` varchar(191) NOT NULL DEFAULT 'inside',
  `last_follow_in` timestamp NULL DEFAULT NULL,
  `call_update` text DEFAULT NULL,
  `sales` varchar(191) DEFAULT NULL,
  `modified_time` timestamp NULL DEFAULT NULL,
  `mobile_no` varchar(191) DEFAULT NULL,
  `tel` varchar(191) DEFAULT NULL,
  `project` varchar(191) DEFAULT NULL,
  `installment` varchar(191) DEFAULT NULL,
  `payed_every` varchar(191) DEFAULT NULL,
  `monthly` decimal(12,2) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `properties_property_number_unique` (`property_number`),
  UNIQUE KEY `properties_appwrite_id_unique` (`appwrite_id`),
  KEY `properties_company_id_foreign` (`company_id`),
  KEY `properties_project_id_foreign` (`project_id`),
  KEY `properties_team_id_foreign` (`team_id`),
  KEY `properties_handler_id_foreign` (`handler_id`),
  KEY `properties_sales_person_id_foreign` (`sales_person_id`),
  CONSTRAINT `properties_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `properties_handler_id_foreign` FOREIGN KEY (`handler_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `properties_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `properties_sales_person_id_foreign` FOREIGN KEY (`sales_person_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `properties_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `property_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `property_media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `property_id` bigint(20) unsigned NOT NULL,
  `type` enum('image','video','document','3d_tour') NOT NULL,
  `file_path` varchar(191) NOT NULL,
  `appwrite_file_id` varchar(191) DEFAULT NULL,
  `thumbnail_path` varchar(191) DEFAULT NULL,
  `title` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_media_property_id_foreign` (`property_id`),
  CONSTRAINT `property_media_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `filters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`filters`)),
  `columns` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`columns`)),
  `description` text DEFAULT NULL,
  `schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedule`)),
  `last_run_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reports_company_id_foreign` (`company_id`),
  KEY `reports_created_by_foreign` (`created_by`),
  CONSTRAINT `reports_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reports_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permission` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permission_role_id_permission_id_unique` (`role_id`,`permission_id`),
  KEY `role_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `display_name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `display_name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `module` varchar(191) NOT NULL,
  `rule_type` varchar(191) NOT NULL,
  `conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`conditions`)),
  `actions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`actions`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `priority` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `team_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `related_user_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `activity_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`activity_data`)),
  `related_type` varchar(191) DEFAULT NULL,
  `related_id` bigint(20) unsigned DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `visibility` enum('public','team','private') NOT NULL DEFAULT 'team',
  `is_system_generated` tinyint(1) NOT NULL DEFAULT 0,
  `requires_action` tinyint(1) NOT NULL DEFAULT 0,
  `participants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`participants`)),
  `mentions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`mentions`)),
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `comments_count` int(11) NOT NULL DEFAULT 0,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `occurred_at` timestamp NULL DEFAULT NULL,
  `due_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `status_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`status_history`)),
  `completion_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_activities_team_id_foreign` (`team_id`),
  KEY `team_activities_related_user_id_foreign` (`related_user_id`),
  KEY `team_activities_related_type_related_id_index` (`related_type`,`related_id`),
  KEY `team_activities_company_id_team_id_created_at_index` (`company_id`,`team_id`,`created_at`),
  KEY `team_activities_user_id_type_created_at_index` (`user_id`,`type`,`created_at`),
  KEY `team_activities_type_status_occurred_at_index` (`type`,`status`,`occurred_at`),
  KEY `team_activities_requires_action_status_index` (`requires_action`,`status`),
  CONSTRAINT `team_activities_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_activities_related_user_id_foreign` FOREIGN KEY (`related_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `team_activities_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `team_company_shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_company_shares` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `shared_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shared_items`)),
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_company_shares_team_id_company_id_unique` (`team_id`,`company_id`),
  KEY `team_company_shares_company_id_foreign` (`company_id`),
  CONSTRAINT `team_company_shares_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_company_shares_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `team_performance_metrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_performance_metrics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `metric_type` varchar(191) NOT NULL,
  `period_type` varchar(191) NOT NULL DEFAULT 'monthly',
  `target_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `actual_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `achievement_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `leads_generated` int(11) NOT NULL DEFAULT 0,
  `leads_converted` int(11) NOT NULL DEFAULT 0,
  `conversion_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `revenue_generated` decimal(15,2) NOT NULL DEFAULT 0.00,
  `deals_closed` int(11) NOT NULL DEFAULT 0,
  `average_deal_size` decimal(15,2) NOT NULL DEFAULT 0.00,
  `calls_made` int(11) NOT NULL DEFAULT 0,
  `emails_sent` int(11) NOT NULL DEFAULT 0,
  `meetings_held` int(11) NOT NULL DEFAULT 0,
  `properties_shown` int(11) NOT NULL DEFAULT 0,
  `follow_ups_completed` int(11) NOT NULL DEFAULT 0,
  `working_hours` int(11) NOT NULL DEFAULT 0,
  `productive_hours` int(11) NOT NULL DEFAULT 0,
  `productivity_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `team_rank` int(11) DEFAULT NULL,
  `company_rank` int(11) DEFAULT NULL,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `badges_earned` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`badges_earned`)),
  `notes` text DEFAULT NULL,
  `status` enum('active','review','completed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_performance_metrics_team_id_foreign` (`team_id`),
  KEY `tpm_company_team_period` (`company_id`,`team_id`,`period_start`),
  KEY `tpm_user_metric_period` (`user_id`,`metric_type`,`period_start`),
  KEY `tpm_period_range` (`period_type`,`period_start`,`period_end`),
  CONSTRAINT `team_performance_metrics_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_performance_metrics_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_performance_metrics_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_user_team_id_foreign` (`team_id`),
  KEY `team_user_user_id_foreign` (`user_id`),
  CONSTRAINT `team_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `leader_id` bigint(20) unsigned DEFAULT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `can_share_externally` tinyint(1) NOT NULL DEFAULT 0,
  `shared_companies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shared_companies`)),
  `visibility_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`visibility_settings`)),
  `public_listing_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teams_code_unique` (`code`),
  KEY `teams_company_id_foreign` (`company_id`),
  CONSTRAINT `teams_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `territories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `territories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('geographic','demographic','industry','product') NOT NULL DEFAULT 'geographic',
  `geographic_boundaries` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`geographic_boundaries`)),
  `postal_codes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`postal_codes`)),
  `cities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cities`)),
  `regions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`regions`)),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `radius_km` int(11) DEFAULT NULL,
  `target_demographics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_demographics`)),
  `customer_segments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`customer_segments`)),
  `total_leads` int(11) NOT NULL DEFAULT 0,
  `active_opportunities` int(11) NOT NULL DEFAULT 0,
  `total_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `target_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `properties_count` int(11) NOT NULL DEFAULT 0,
  `assignment_rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assignment_rules`)),
  `auto_assign_leads` tinyint(1) NOT NULL DEFAULT 1,
  `exclusive_territory` tinyint(1) NOT NULL DEFAULT 0,
  `priority_level` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `effective_from` date DEFAULT NULL,
  `effective_until` date DEFAULT NULL,
  `working_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`working_hours`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `territories_code_unique` (`code`),
  KEY `territories_manager_id_foreign` (`manager_id`),
  KEY `territories_company_id_is_active_index` (`company_id`,`is_active`),
  KEY `territories_team_id_manager_id_index` (`team_id`,`manager_id`),
  KEY `territories_type_is_active_index` (`type`,`is_active`),
  CONSTRAINT `territories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `territories_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `territories_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `territory_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `territory_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `territory_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` enum('manager','agent','coordinator') NOT NULL DEFAULT 'agent',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `territory_user_territory_id_user_id_unique` (`territory_id`,`user_id`),
  KEY `territory_user_territory_id_role_index` (`territory_id`,`role`),
  KEY `territory_user_user_id_is_primary_index` (`user_id`,`is_primary`),
  CONSTRAINT `territory_user_territory_id_foreign` FOREIGN KEY (`territory_id`) REFERENCES `territories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `territory_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `ip_address` varchar(191) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `device_name` varchar(191) DEFAULT NULL,
  `location` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`location`)),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `login_time` timestamp NOT NULL,
  `session_duration` int(11) DEFAULT NULL,
  `last_activity` timestamp NOT NULL,
  `logged_out_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_sessions_user_id_foreign` (`user_id`),
  CONSTRAINT `user_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `appwrite_id` varchar(191) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `role` enum('admin','manager','team_leader','agent','employee') NOT NULL DEFAULT 'employee',
  `phone` varchar(191) DEFAULT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `position` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_company_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `company_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `profile_id` bigint(20) unsigned DEFAULT NULL,
  `hierarchy_level` int(11) NOT NULL DEFAULT 4,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_appwrite_id_unique` (`appwrite_id`),
  KEY `users_company_id_foreign` (`company_id`),
  KEY `users_team_id_foreign` (`team_id`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_manager_id_foreign` (`manager_id`),
  KEY `users_profile_id_foreign` (`profile_id`),
  CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

/*M!999999\- enable the sandbox mode */ 
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2024_01_01_000000_create_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2024_01_01_000001_create_companies_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2024_01_01_000002_create_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2024_01_01_000003_create_teams_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2024_01_01_000004_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2024_01_01_000005_create_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2024_01_01_000006_create_user_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2024_01_01_000007_create_activity_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2024_01_01_000008_create_projects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2024_01_01_000008_create_team_company_shares_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2024_01_01_000009_create_properties_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2024_01_01_000010_create_property_media_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2024_01_01_000011_create_leads_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2024_01_01_000012_create_lead_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2024_01_01_000013_create_branches_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2024_01_01_000014_create_departments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2024_01_01_000015_create_opportunities_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2024_01_01_000016_create_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2024_01_15_000001_create_lead_classifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2024_01_15_000002_add_classification_to_leads',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2024_01_15_000003_add_role_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2024_02_20_000000_create_team_user_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2024_03_28_000000_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2024_03_28_000001_update_leads_table_add_assigned_to',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2024_03_28_000002_add_property_id_to_leads_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2024_03_28_000003_create_reports_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2024_03_28_000004_rename_event_date_column',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2024_03_28_000007_update_activity_logs_add_morphs',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_03_28_094846_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2024_01_01_000020_create_contacts_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2024_05_24_000001_update_properties_table_for_appwrite',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2024_05_24_000002_update_properties_schema_appwrite',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2024_05_24_000002_update_properties_table_with_appwrite_fields',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2024_05_24_000003_sync_properties_with_appwrite',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_06_07_102443_add_appwrite_id_to_tables',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_06_09_064056_add_area_text_column_to_properties_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_06_13_085312_create_profiles_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_06_13_085322_create_rules_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_06_13_085332_create_profile_permission_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_06_13_085400_create_profile_rule_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_06_13_085408_update_users_table_for_hierarchy',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_06_13_085745_create_profiles_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_06_13_085753_create_role_permission_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_06_13_085806_add_hierarchical_fields_to_users_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_06_13_094132_add_foreign_keys_to_profile_permission_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_06_13_135856_create_team_performance_metrics_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_06_13_135910_create_territories_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_06_13_135922_create_goals_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_06_13_135933_create_team_activities_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_06_13_141650_add_territory_id_to_opportunities_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_06_13_162712_create_territory_user_table',13);
