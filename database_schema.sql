-- MySQL dump 10.13  Distrib 5.7.42, for Linux (x86_64)
--
-- Host: 103.174.51.100    Database: eskuul_x4_new
-- ------------------------------------------------------
-- Server version	11.4.4-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `balance` double(18,2) NOT NULL DEFAULT 0.00,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accounts_rms_1` (`branch_id`),
  CONSTRAINT `accounts_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `addon`
--

DROP TABLE IF EXISTS `addon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `version` varchar(100) NOT NULL,
  `purchase_code` varchar(255) DEFAULT NULL,
  `items_code` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `advance_salary`
--

DROP TABLE IF EXISTS `advance_salary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advance_salary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `deduct_month` varchar(20) DEFAULT NULL,
  `year` varchar(20) NOT NULL,
  `reason` text CHARACTER SET utf32 COLLATE utf32_unicode_ci DEFAULT NULL,
  `request_date` datetime DEFAULT NULL,
  `paid_date` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=pending,2=paid,3=rejected',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `issued_by` varchar(200) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `advance_salary_rms_1` (`branch_id`),
  KEY `advance_salary_rms_2` (`staff_id`),
  CONSTRAINT `advance_salary_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `advance_salary_rms_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alumni_events`
--

DROP TABLE IF EXISTS `alumni_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `audience` varchar(100) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `selected_list` longtext NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `note` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `show_web` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  KEY `alumni_events_rms_1` (`branch_id`),
  CONSTRAINT `alumni_events_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alumni_students`
--

DROP TABLE IF EXISTS `alumni_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alumni_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enroll_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `profession` varchar(255) NOT NULL,
  `photo` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `alumni_students_rms_1` (`enroll_id`),
  CONSTRAINT `alumni_students_rms_1` FOREIGN KEY (`enroll_id`) REFERENCES `enroll` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `type_id` int(11) NOT NULL,
  `uploader_id` varchar(20) NOT NULL,
  `class_id` varchar(20) DEFAULT 'unfiltered',
  `file_name` varchar(255) NOT NULL,
  `enc_name` varchar(255) NOT NULL,
  `subject_id` varchar(200) DEFAULT 'unfiltered',
  `session_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_rms_1` (`session_id`),
  KEY `attachments_rms_2` (`branch_id`),
  CONSTRAINT `attachments_rms_1` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attachments_rms_2` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachments_type`
--

DROP TABLE IF EXISTS `attachments_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_type_rms_1` (`branch_id`),
  CONSTRAINT `attachments_type_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `award`
--

DROP TABLE IF EXISTS `award`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `gift_item` varchar(255) NOT NULL,
  `award_amount` decimal(18,2) NOT NULL,
  `award_reason` text NOT NULL,
  `given_date` date NOT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `award_rms_1` (`branch_id`),
  KEY `award_rms_2` (`session_id`),
  CONSTRAINT `award_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `award_rms_2` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `author` varchar(255) NOT NULL,
  `isbn_no` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `edition` varchar(255) NOT NULL,
  `purchase_date` date NOT NULL,
  `description` text NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `total_stock` varchar(20) NOT NULL,
  `issued_copies` varchar(20) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `book_rms_1` (`branch_id`),
  CONSTRAINT `book_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `book_category`
--

DROP TABLE IF EXISTS `book_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `book_category_rms_1` (`branch_id`),
  CONSTRAINT `book_category_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `book_issues`
--

DROP TABLE IF EXISTS `book_issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `book_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `date_of_issue` date DEFAULT NULL,
  `date_of_expiry` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `fine_amount` decimal(18,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 = accepted, 2 = rejected, 3 = returned',
  `issued_by` varchar(255) DEFAULT NULL,
  `return_by` int(11) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `book_issues_rms1` (`branch_id`),
  KEY `book_issues_rms2` (`book_id`),
  KEY `book_issues_rms3` (`session_id`),
  CONSTRAINT `book_issues_rms1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `book_issues_rms2` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE,
  CONSTRAINT `book_issues_rms3` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `school_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobileno` varchar(100) NOT NULL,
  `currency` varchar(100) NOT NULL,
  `symbol` varchar(25) NOT NULL,
  `currency_formats` tinyint(4) NOT NULL DEFAULT 1,
  `symbol_position` tinyint(4) NOT NULL DEFAULT 1,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `stu_generate` tinyint(4) NOT NULL DEFAULT 0,
  `stu_username_prefix` varchar(255) NOT NULL,
  `stu_default_password` varchar(255) NOT NULL,
  `grd_generate` tinyint(4) NOT NULL DEFAULT 0,
  `grd_username_prefix` varchar(255) NOT NULL,
  `grd_default_password` varchar(255) NOT NULL,
  `teacher_restricted` tinyint(1) DEFAULT 1,
  `due_days` float NOT NULL DEFAULT 30,
  `due_with_fine` tinyint(4) NOT NULL DEFAULT 1,
  `translation` varchar(255) NOT NULL DEFAULT 'english',
  `timezone` varchar(255) NOT NULL,
  `weekends` varchar(255) NOT NULL DEFAULT '1',
  `reg_prefix_enable` tinyint(1) NOT NULL DEFAULT 0,
  `student_login` tinyint(4) NOT NULL DEFAULT 1,
  `parent_login` tinyint(4) NOT NULL DEFAULT 1,
  `teacher_mobile_visible` tinyint(4) NOT NULL DEFAULT 1,
  `teacher_email_visible` tinyint(4) NOT NULL DEFAULT 1,
  `reg_start_from` tinyint(4) NOT NULL DEFAULT 1,
  `institution_code` varchar(100) DEFAULT NULL,
  `reg_prefix_digit` int(11) NOT NULL,
  `offline_payments` tinyint(1) NOT NULL DEFAULT 1,
  `attendance_type` tinyint(1) NOT NULL DEFAULT 0,
  `show_own_question` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `unique_roll` tinyint(4) NOT NULL DEFAULT 1,
  `default_admitcard_temp` int(11) NOT NULL DEFAULT 0,
  `default_marksheet_temp` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bulk_msg_category`
--

DROP TABLE IF EXISTS `bulk_msg_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulk_msg_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT 'sms=1, email=2',
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bulk_msg_category_rms_1` (`branch_id`),
  CONSTRAINT `bulk_msg_category_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bulk_sms_email`
--

DROP TABLE IF EXISTS `bulk_sms_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulk_sms_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_name` varchar(255) DEFAULT NULL,
  `sms_gateway` varchar(55) DEFAULT '0',
  `message` text DEFAULT NULL,
  `email_subject` varchar(255) DEFAULT NULL,
  `message_type` tinyint(3) DEFAULT 0 COMMENT 'sms=1, email=2',
  `recipient_type` tinyint(3) NOT NULL COMMENT 'group=1, individual=2, class=3',
  `recipients_details` longtext DEFAULT NULL,
  `additional` longtext DEFAULT NULL,
  `schedule_time` datetime DEFAULT NULL,
  `posting_status` tinyint(3) NOT NULL COMMENT 'schedule=1,competed=2',
  `total_thread` int(11) NOT NULL,
  `successfully_sent` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `bulk_sms_email_rms_1` (`branch_id`),
  CONSTRAINT `bulk_sms_email_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `call_log`
--

DROP TABLE IF EXISTS `call_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `purpose_id` int(11) DEFAULT NULL,
  `call_type` tinyint(1) DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `follow_up` date DEFAULT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `call_log_rms_1` (`branch_id`),
  CONSTRAINT `call_log_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `call_purpose`
--

DROP TABLE IF EXISTS `call_purpose`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_purpose` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `call_purpose_rms_1` (`branch_id`),
  CONSTRAINT `call_purpose_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `card_templete`
--

DROP TABLE IF EXISTS `card_templete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_templete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_type` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `user_type` tinyint(1) NOT NULL,
  `background` varchar(355) DEFAULT NULL,
  `logo` varchar(355) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `layout_width` varchar(11) NOT NULL DEFAULT '54',
  `layout_height` varchar(11) NOT NULL DEFAULT '86',
  `photo_style` tinyint(1) NOT NULL,
  `photo_size` varchar(25) NOT NULL,
  `top_space` varchar(25) NOT NULL,
  `bottom_space` varchar(25) NOT NULL,
  `right_space` varchar(25) NOT NULL,
  `left_space` varchar(25) NOT NULL,
  `qr_code` varchar(25) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `card_templete_rms_1` (`branch_id`),
  CONSTRAINT `card_templete_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `certificates_templete`
--

DROP TABLE IF EXISTS `certificates_templete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certificates_templete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_type` tinyint(1) NOT NULL,
  `background` varchar(355) DEFAULT NULL,
  `logo` varchar(355) DEFAULT NULL,
  `signature` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `page_layout` tinyint(1) NOT NULL,
  `photo_style` tinyint(1) NOT NULL,
  `photo_size` varchar(25) NOT NULL,
  `top_space` varchar(25) NOT NULL,
  `bottom_space` varchar(25) NOT NULL,
  `right_space` varchar(25) NOT NULL,
  `left_space` varchar(25) NOT NULL,
  `qr_code` varchar(25) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `certificates_templete_rms_1` (`branch_id`),
  CONSTRAINT `certificates_templete_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_numeric` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_rms_1` (`branch_id`),
  CONSTRAINT `class_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaint`
--

DROP TABLE IF EXISTS `complaint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `assigned_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `date_of_solution` date DEFAULT NULL,
  `file` varchar(500) NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_rms_1` (`branch_id`),
  CONSTRAINT `complaint_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `complaint_type`
--

DROP TABLE IF EXISTS `complaint_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `complaint_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `complaint_type_rms_1` (`branch_id`),
  CONSTRAINT `complaint_type_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_field`
--

DROP TABLE IF EXISTS `custom_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_to` varchar(50) DEFAULT NULL,
  `field_label` varchar(100) NOT NULL,
  `default_value` text DEFAULT NULL,
  `field_type` enum('text','textarea','dropdown','date','checkbox','number','url','email') NOT NULL,
  `required` varchar(5) NOT NULL DEFAULT 'false',
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `show_on_table` varchar(5) DEFAULT NULL,
  `field_order` int(11) NOT NULL,
  `bs_column` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_fields_online_values`
--

DROP TABLE IF EXISTS `custom_fields_online_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_fields_online_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relid` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `relid` (`relid`),
  KEY `fieldid` (`field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custom_fields_values`
--

DROP TABLE IF EXISTS `custom_fields_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_fields_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `relid` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `relid` (`relid`),
  KEY `fieldid` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disable_reason`
--

DROP TABLE IF EXISTS `disable_reason`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disable_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `disable_reason_rms_1` (`branch_id`),
  CONSTRAINT `disable_reason_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disable_reason_details`
--

DROP TABLE IF EXISTS `disable_reason_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disable_reason_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `reason_id` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_config`
--

DROP TABLE IF EXISTS `email_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `protocol` varchar(255) NOT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_user` varchar(255) DEFAULT NULL,
  `smtp_pass` varchar(255) DEFAULT NULL,
  `smtp_port` varchar(100) DEFAULT NULL,
  `smtp_encryption` varchar(10) DEFAULT NULL,
  `smtp_auth` varchar(10) NOT NULL DEFAULT 'true',
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tags` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_templates_details`
--

DROP TABLE IF EXISTS `email_templates_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `template_body` text NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enquiry`
--

DROP TABLE IF EXISTS `enquiry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` tinyint(1) DEFAULT 0,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `previous_school` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `reference_id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `response` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `note` varchar(255) NOT NULL,
  `assigned_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `no_of_child` float NOT NULL,
  `class_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiry_rms_1` (`branch_id`),
  CONSTRAINT `enquiry_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enquiry_follow_up`
--

DROP TABLE IF EXISTS `enquiry_follow_up`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiry_follow_up` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enquiry_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `next_date` date NOT NULL,
  `response` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `note` varchar(255) NOT NULL,
  `follow_up_by` int(11) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiry_follow_up_rms_1` (`enquiry_id`),
  CONSTRAINT `enquiry_follow_up_rms_1` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiry` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enquiry_reference`
--

DROP TABLE IF EXISTS `enquiry_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiry_reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiry_reference_rms_1` (`branch_id`),
  CONSTRAINT `enquiry_reference_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enquiry_response`
--

DROP TABLE IF EXISTS `enquiry_response`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiry_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiry_response_rms_1` (`branch_id`),
  CONSTRAINT `enquiry_response_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enroll`
--

DROP TABLE IF EXISTS `enroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `roll` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `default_login` tinyint(4) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `is_alumni` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enroll_rms_1` (`student_id`),
  KEY `enroll_rms_2` (`session_id`),
  KEY `enroll_rms_3` (`class_id`),
  KEY `enroll_rms_4` (`section_id`),
  KEY `enroll_rms_5` (`branch_id`),
  CONSTRAINT `enroll_rms_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enroll_rms_2` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enroll_rms_3` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enroll_rms_4` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enroll_rms_5` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `remark` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `type` text NOT NULL,
  `audition` longtext NOT NULL,
  `selected_list` longtext NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `show_web` tinyint(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `event_rms_1` (`branch_id`),
  KEY `event_rms_2` (`session_id`),
  CONSTRAINT `event_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_rms_2` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_types`
--

DROP TABLE IF EXISTS `event_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_types_rms_1` (`branch_id`),
  CONSTRAINT `event_types_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam`
--

DROP TABLE IF EXISTS `exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `term_id` int(11) DEFAULT NULL,
  `type_id` tinyint(4) NOT NULL COMMENT '1=mark,2=gpa,3=both',
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `remark` text NOT NULL,
  `mark_distribution` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `publish_result` tinyint(1) NOT NULL DEFAULT 1,
  `rank_generated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_rms_1` (`branch_id`),
  KEY `exam_rms_2` (`session_id`),
  CONSTRAINT `exam_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_rms_2` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_attendance`
--

DROP TABLE IF EXISTS `exam_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `status` varchar(4) DEFAULT NULL COMMENT 'P=Present, A=Absent, L=Late',
  `remark` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_attendance_rms_1` (`branch_id`),
  KEY `exam_attendance_rms_2` (`exam_id`),
  KEY `exam_attendance_rms_3` (`student_id`),
  CONSTRAINT `exam_attendance_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_attendance_rms_2` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_attendance_rms_3` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_hall`
--

DROP TABLE IF EXISTS `exam_hall`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_hall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hall_no` longtext NOT NULL,
  `seats` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_hall_rms_1` (`branch_id`),
  CONSTRAINT `exam_hall_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_mark_distribution`
--

DROP TABLE IF EXISTS `exam_mark_distribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_mark_distribution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_mark_distribution_rms_1` (`branch_id`),
  CONSTRAINT `exam_mark_distribution_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_rank`
--

DROP TABLE IF EXISTS `exam_rank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `enroll_id` int(11) NOT NULL,
  `principal_comments` text DEFAULT NULL,
  `teacher_comments` text DEFAULT NULL,
  `rank` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_group_class_batch_exam_id` (`exam_id`),
  KEY `student_id` (`enroll_id`),
  CONSTRAINT `exam_rank_rms_1` FOREIGN KEY (`enroll_id`) REFERENCES `enroll` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_rank_rms_2` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exam_term`
--

DROP TABLE IF EXISTS `exam_term`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_allocation`
--

DROP TABLE IF EXISTS `fee_allocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_allocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `prev_due` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fee_allocation_rsm_1` (`student_id`),
  KEY `fee_allocation_rsm_2` (`branch_id`),
  KEY `fee_allocation_rsm_3` (`session_id`),
  CONSTRAINT `fee_allocation_rsm_1` FOREIGN KEY (`student_id`) REFERENCES `enroll` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_allocation_rsm_2` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_allocation_rsm_3` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_fine`
--

DROP TABLE IF EXISTS `fee_fine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_fine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `fine_value` varchar(20) NOT NULL,
  `fine_type` varchar(20) NOT NULL,
  `fee_frequency` varchar(20) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fee_fine_rms_2` (`group_id`),
  KEY `fee_fine_rms_3` (`session_id`),
  KEY `fee_fine_rms_4` (`branch_id`),
  CONSTRAINT `fee_fine_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_fine_rms_2` FOREIGN KEY (`group_id`) REFERENCES `fee_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_fine_rms_3` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_fine_rms_4` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_groups`
--

DROP TABLE IF EXISTS `fee_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `system` tinyint(4) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fee_groups_rms_1` (`branch_id`),
  KEY `fee_groups_rms_2` (`session_id`),
  CONSTRAINT `fee_groups_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_groups_rms_2` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_groups_details`
--

DROP TABLE IF EXISTS `fee_groups_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_groups_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_groups_id` int(11) NOT NULL,
  `fee_type_id` int(11) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fee_groups_details_rms_1` (`fee_groups_id`),
  KEY `fee_groups_details_rms_2` (`fee_type_id`),
  CONSTRAINT `fee_groups_details_rms_1` FOREIGN KEY (`fee_groups_id`) REFERENCES `fee_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_groups_details_rms_2` FOREIGN KEY (`fee_type_id`) REFERENCES `fees_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_payment_history`
--

DROP TABLE IF EXISTS `fee_payment_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `allocation_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `transport_fee_details_id` int(11) DEFAULT NULL,
  `collect_by` varchar(20) DEFAULT NULL,
  `amount` decimal(18,2) NOT NULL,
  `discount` decimal(18,2) NOT NULL,
  `fine` decimal(18,2) NOT NULL,
  `pay_via` varchar(20) NOT NULL,
  `remarks` longtext NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fee_payment_history_rms_1` (`allocation_id`),
  KEY `fee_payment_history_rms_2` (`type_id`),
  CONSTRAINT `fee_payment_history_rms_1` FOREIGN KEY (`allocation_id`) REFERENCES `fee_allocation` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_payment_history_rms_2` FOREIGN KEY (`type_id`) REFERENCES `fees_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fees_reminder`
--

DROP TABLE IF EXISTS `fees_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_reminder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frequency` varchar(255) NOT NULL,
  `days` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `dlt_template_id` varchar(255) DEFAULT NULL,
  `student` tinyint(3) NOT NULL,
  `guardian` tinyint(3) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fees_reminder_rms_1` (`branch_id`),
  CONSTRAINT `fees_reminder_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fees_type`
--

DROP TABLE IF EXISTS `fees_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `fee_code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `branch_id` int(11) NOT NULL DEFAULT 0,
  `system` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_about`
--

DROP TABLE IF EXISTS `front_cms_about`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `page_title` varchar(255) NOT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `about_image` varchar(255) NOT NULL,
  `elements` mediumtext NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_admission`
--

DROP TABLE IF EXISTS `front_cms_admission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_admission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `terms_conditions_title` varchar(255) DEFAULT NULL,
  `terms_conditions_description` text NOT NULL,
  `fee_elements` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `application_form_name` varchar(255) DEFAULT NULL,
  `application_form_file` varchar(255) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_admitcard`
--

DROP TABLE IF EXISTS `front_cms_admitcard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_admitcard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `templete_id` int(11) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_certificates`
--

DROP TABLE IF EXISTS `front_cms_certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_contact`
--

DROP TABLE IF EXISTS `front_cms_contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `box_title` varchar(255) DEFAULT NULL,
  `box_description` varchar(500) DEFAULT NULL,
  `box_image` varchar(255) DEFAULT NULL,
  `form_title` varchar(355) DEFAULT NULL,
  `address` varchar(355) DEFAULT NULL,
  `phone` varchar(355) DEFAULT NULL,
  `email` varchar(355) DEFAULT NULL,
  `submit_text` varchar(355) NOT NULL,
  `map_iframe` text DEFAULT NULL,
  `page_title` varchar(255) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_events`
--

DROP TABLE IF EXISTS `front_cms_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_exam_results`
--

DROP TABLE IF EXISTS `front_cms_exam_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_exam_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `grade_scale` tinyint(1) NOT NULL,
  `attendance` tinyint(1) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_faq`
--

DROP TABLE IF EXISTS `front_cms_faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_faq_list`
--

DROP TABLE IF EXISTS `front_cms_faq_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_faq_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_gallery`
--

DROP TABLE IF EXISTS `front_cms_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_gallery_category`
--

DROP TABLE IF EXISTS `front_cms_gallery_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_gallery_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_gallery_content`
--

DROP TABLE IF EXISTS `front_cms_gallery_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_gallery_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb_image` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `category_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `elements` longtext NOT NULL,
  `show_web` tinyint(4) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_home`
--

DROP TABLE IF EXISTS `front_cms_home`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_home` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `item_type` varchar(20) NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `elements` mediumtext NOT NULL,
  `color1` varchar(100) DEFAULT NULL,
  `color2` varchar(100) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `active` tinyint(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_home_seo`
--

DROP TABLE IF EXISTS `front_cms_home_seo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_home_seo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `meta_keyword` text NOT NULL,
  `meta_description` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_menu`
--

DROP TABLE IF EXISTS `front_cms_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `ordering` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT 0,
  `open_new_tab` int(11) NOT NULL DEFAULT 0,
  `ext_url` tinyint(3) NOT NULL DEFAULT 0,
  `ext_url_address` text DEFAULT NULL,
  `publish` tinyint(3) NOT NULL,
  `system` tinyint(3) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_menu_visible`
--

DROP TABLE IF EXISTS `front_cms_menu_visible`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_menu_visible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `parent_id` varchar(11) DEFAULT NULL,
  `ordering` varchar(20) DEFAULT NULL,
  `invisible` tinyint(2) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_news`
--

DROP TABLE IF EXISTS `front_cms_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_news_list`
--

DROP TABLE IF EXISTS `front_cms_news_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_news_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `alias` varchar(500) NOT NULL,
  `date` date NOT NULL,
  `show_web` tinyint(4) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `front_cms_news_list_rms_1` (`branch_id`),
  CONSTRAINT `front_cms_news_list_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_pages`
--

DROP TABLE IF EXISTS `front_cms_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `content` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `menu_id` int(11) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_services`
--

DROP TABLE IF EXISTS `front_cms_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `parallax_image` varchar(255) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_services_list`
--

DROP TABLE IF EXISTS `front_cms_services_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_services_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_setting`
--

DROP TABLE IF EXISTS `front_cms_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_title` varchar(255) NOT NULL,
  `url_alias` varchar(255) DEFAULT NULL,
  `cms_active` tinyint(4) NOT NULL DEFAULT 0,
  `online_admission` tinyint(4) NOT NULL DEFAULT 0,
  `theme` varchar(255) NOT NULL,
  `captcha_status` varchar(20) NOT NULL,
  `recaptcha_site_key` varchar(255) NOT NULL,
  `recaptcha_secret_key` varchar(255) NOT NULL,
  `address` varchar(350) NOT NULL,
  `mobile_no` varchar(60) NOT NULL,
  `fax` varchar(60) NOT NULL,
  `receive_contact_email` varchar(255) NOT NULL,
  `email` varchar(60) NOT NULL,
  `copyright_text` varchar(255) NOT NULL,
  `fav_icon` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `footer_about_text` varchar(300) NOT NULL,
  `working_hours` varchar(300) NOT NULL,
  `google_analytics` text DEFAULT NULL,
  `primary_color` varchar(100) NOT NULL DEFAULT '#ff685c',
  `menu_color` varchar(100) NOT NULL DEFAULT '#fff',
  `hover_color` varchar(100) NOT NULL DEFAULT '#f04133',
  `text_color` varchar(100) NOT NULL DEFAULT '#232323',
  `text_secondary_color` varchar(100) NOT NULL DEFAULT '#383838',
  `footer_background_color` varchar(100) NOT NULL DEFAULT '#383838',
  `footer_text_color` varchar(100) NOT NULL DEFAULT '#8d8d8d',
  `copyright_bg_color` varchar(100) NOT NULL DEFAULT '#262626',
  `copyright_text_color` varchar(100) NOT NULL DEFAULT '#8d8d8d',
  `border_radius` varchar(100) NOT NULL DEFAULT '0',
  `facebook_url` varchar(100) NOT NULL,
  `twitter_url` varchar(100) NOT NULL,
  `youtube_url` varchar(100) NOT NULL,
  `google_plus` varchar(100) NOT NULL,
  `linkedin_url` varchar(100) NOT NULL,
  `pinterest_url` varchar(100) NOT NULL,
  `instagram_url` varchar(100) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_teachers`
--

DROP TABLE IF EXISTS `front_cms_teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keyword` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `front_cms_testimonial`
--

DROP TABLE IF EXISTS `front_cms_testimonial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `front_cms_testimonial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(355) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rank` int(5) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `global_settings`
--

DROP TABLE IF EXISTS `global_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institute_name` varchar(255) NOT NULL,
  `institution_code` varchar(255) NOT NULL,
  `reg_prefix` varchar(255) NOT NULL,
  `institute_email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `mobileno` varchar(100) NOT NULL,
  `currency` varchar(100) NOT NULL,
  `currency_symbol` varchar(100) NOT NULL,
  `currency_formats` tinyint(4) NOT NULL DEFAULT 1,
  `symbol_position` tinyint(4) NOT NULL DEFAULT 1,
  `sms_service_provider` varchar(100) NOT NULL,
  `session_id` int(11) NOT NULL,
  `translation` varchar(100) NOT NULL,
  `footer_text` varchar(255) NOT NULL,
  `animations` varchar(100) NOT NULL,
  `timezone` varchar(100) NOT NULL,
  `date_format` varchar(100) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `facebook_url` varchar(255) NOT NULL,
  `twitter_url` varchar(255) NOT NULL,
  `linkedin_url` varchar(255) NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `cron_secret_key` varchar(255) DEFAULT NULL,
  `preloader_backend` tinyint(1) NOT NULL DEFAULT 1,
  `footer_branch_switcher` tinyint(1) NOT NULL DEFAULT 1,
  `cms_default_branch` int(11) NOT NULL,
  `cache_store` tinyint(1) NOT NULL DEFAULT 0,
  `image_extension` text DEFAULT NULL,
  `image_size` float NOT NULL DEFAULT 1024,
  `file_extension` text DEFAULT NULL,
  `pid` varchar(255) DEFAULT NULL,
  `file_size` float DEFAULT 1024,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grade`
--

DROP TABLE IF EXISTS `grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `grade_point` varchar(255) NOT NULL,
  `lower_mark` int(11) NOT NULL,
  `upper_mark` int(11) NOT NULL,
  `remark` text NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grade_rms_1` (`branch_id`),
  CONSTRAINT `grade_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hall_allocation`
--

DROP TABLE IF EXISTS `hall_allocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hall_allocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `hall_no` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `homework`
--

DROP TABLE IF EXISTS `homework`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homework` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `date_of_homework` date NOT NULL,
  `date_of_submission` date NOT NULL,
  `description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `status` varchar(10) NOT NULL,
  `sms_notification` tinyint(2) NOT NULL,
  `schedule_date` date DEFAULT NULL,
  `document` varchar(255) NOT NULL,
  `evaluation_date` date DEFAULT NULL,
  `evaluated_by` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `homework_rms_1` (`branch_id`),
  KEY `homework_rms_2` (`class_id`),
  KEY `homework_rms_3` (`section_id`),
  KEY `homework_rms_4` (`session_id`),
  KEY `homework_rms_5` (`subject_id`),
  CONSTRAINT `homework_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `homework_rms_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `homework_rms_3` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE,
  CONSTRAINT `homework_rms_4` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE,
  CONSTRAINT `homework_rms_5` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `homework_evaluation`
--

DROP TABLE IF EXISTS `homework_evaluation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homework_evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `homework_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `remark` text NOT NULL,
  `rank` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `homework_evaluation_rms_1` (`homework_id`),
  CONSTRAINT `homework_evaluation_rms_1` FOREIGN KEY (`homework_id`) REFERENCES `homework` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `homework_submit`
--

DROP TABLE IF EXISTS `homework_submit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homework_submit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `homework_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `message` varchar(355) NOT NULL,
  `enc_name` varchar(355) DEFAULT NULL,
  `file_name` varchar(355) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `homework_submit_rms_1` (`homework_id`),
  KEY `homework_submit_rms_2` (`student_id`),
  CONSTRAINT `homework_submit_rms_1` FOREIGN KEY (`homework_id`) REFERENCES `homework` (`id`) ON DELETE CASCADE,
  CONSTRAINT `homework_submit_rms_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hostel`
--

DROP TABLE IF EXISTS `hostel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hostel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `category_id` int(11) NOT NULL,
  `address` longtext NOT NULL,
  `watchman` longtext NOT NULL,
  `remarks` longtext DEFAULT NULL,
  `branch_id` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hostel_rms_1` (`branch_id`),
  CONSTRAINT `hostel_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hostel_category`
--

DROP TABLE IF EXISTS `hostel_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hostel_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `description` longtext DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hostel_room`
--

DROP TABLE IF EXISTS `hostel_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hostel_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `hostel_id` int(11) NOT NULL,
  `no_beds` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `bed_fee` decimal(18,2) NOT NULL,
  `remarks` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `language_list`
--

DROP TABLE IF EXISTS `language_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(600) NOT NULL,
  `lang_field` varchar(600) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `rtl` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL,
  `english` varchar(255) NOT NULL,
  `bengali` varchar(255) NOT NULL,
  `arabic` varchar(255) NOT NULL,
  `french` varchar(255) NOT NULL,
  `hindi` varchar(255) NOT NULL,
  `indonesian` varchar(255) NOT NULL,
  `italian` varchar(255) NOT NULL,
  `japanese` varchar(255) NOT NULL,
  `korean` varchar(255) NOT NULL,
  `dutch` varchar(255) NOT NULL,
  `portuguese` varchar(255) NOT NULL,
  `thai` varchar(255) NOT NULL,
  `turkish` varchar(255) NOT NULL,
  `urdu` varchar(255) NOT NULL,
  `chinese` varchar(255) NOT NULL,
  `afrikaans` varchar(255) NOT NULL,
  `german` varchar(255) NOT NULL,
  `greek` varchar(255) NOT NULL,
  `spanish` varchar(255) NOT NULL,
  `nepali` varchar(255) NOT NULL,
  `russian` varchar(255) NOT NULL,
  `danish` varchar(255) NOT NULL,
  `armenian` varchar(255) NOT NULL,
  `georgian` varchar(255) NOT NULL,
  `marathi` varchar(255) NOT NULL,
  `malay` varchar(255) NOT NULL,
  `punjabi` varchar(255) NOT NULL,
  `tamil` varchar(255) NOT NULL,
  `telugu` varchar(255) NOT NULL,
  `swedish` varchar(255) NOT NULL,
  `filipino` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1225 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leave_application`
--

DROP TABLE IF EXISTS `leave_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `category_id` int(2) NOT NULL,
  `reason` longtext CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `leave_days` varchar(20) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT 1 COMMENT '1=pending,2=accepted 3=rejected',
  `apply_date` date DEFAULT NULL,
  `approved_by` int(11) NOT NULL,
  `orig_file_name` varchar(255) NOT NULL,
  `enc_file_name` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leave_category`
--

DROP TABLE IF EXISTS `leave_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_category` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `role_id` tinyint(1) NOT NULL,
  `days` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `live_class`
--

DROP TABLE IF EXISTS `live_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `live_class_method` tinyint(1) NOT NULL DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `meeting_id` varchar(255) NOT NULL,
  `meeting_password` varchar(255) NOT NULL,
  `own_api_key` tinyint(1) NOT NULL DEFAULT 0,
  `duration` int(11) NOT NULL,
  `bbb` longtext NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` text NOT NULL,
  `remarks` text NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `live_class_config`
--

DROP TABLE IF EXISTS `live_class_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_class_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zoom_api_key` varchar(255) DEFAULT NULL,
  `zoom_api_secret` varchar(255) DEFAULT NULL,
  `bbb_salt_key` varchar(355) DEFAULT NULL,
  `bbb_server_base_url` varchar(355) DEFAULT NULL,
  `staff_api_credential` tinyint(1) NOT NULL DEFAULT 0,
  `student_api_credential` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `live_class_reports`
--

DROP TABLE IF EXISTS `live_class_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_class_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `live_class_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_credential`
--

DROP TABLE IF EXISTS `login_credential`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_credential` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` tinyint(2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1(active) 0(deactivate)',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_log`
--

DROP TABLE IF EXISTS `login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `browser` varchar(255) NOT NULL,
  `platform` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_log_rms_1` (`branch_id`),
  CONSTRAINT `login_log_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mark`
--

DROP TABLE IF EXISTS `mark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `mark` text DEFAULT NULL,
  `absent` varchar(4) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `marksheet_template`
--

DROP TABLE IF EXISTS `marksheet_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marksheet_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `background` varchar(355) DEFAULT NULL,
  `logo` varchar(355) DEFAULT NULL,
  `left_signature` varchar(255) NOT NULL,
  `middle_signature` varchar(255) DEFAULT NULL,
  `right_signature` varchar(255) DEFAULT NULL,
  `attendance_percentage` tinyint(4) NOT NULL DEFAULT 1,
  `grading_scale` tinyint(4) NOT NULL DEFAULT 1,
  `position` tinyint(4) NOT NULL DEFAULT 1,
  `cumulative_average` tinyint(4) NOT NULL DEFAULT 1,
  `class_average` tinyint(4) NOT NULL DEFAULT 1,
  `result` tinyint(4) NOT NULL DEFAULT 1,
  `subject_position` tinyint(4) NOT NULL DEFAULT 1,
  `remark` tinyint(4) NOT NULL DEFAULT 1,
  `header_content` text DEFAULT NULL,
  `footer_content` text DEFAULT NULL,
  `page_layout` tinyint(1) NOT NULL,
  `photo_style` tinyint(1) NOT NULL,
  `photo_size` float NOT NULL DEFAULT 120,
  `top_space` varchar(25) NOT NULL DEFAULT '0',
  `bottom_space` varchar(25) NOT NULL DEFAULT '0',
  `right_space` varchar(25) NOT NULL DEFAULT '0',
  `left_space` varchar(25) NOT NULL DEFAULT '0',
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `certificates_templete_rms_1` (`branch_id`),
  CONSTRAINT `marksheet_template_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `file_name` text DEFAULT NULL,
  `enc_name` text DEFAULT NULL,
  `trash_sent` tinyint(1) NOT NULL,
  `trash_inbox` int(11) NOT NULL,
  `fav_inbox` tinyint(1) NOT NULL,
  `fav_sent` tinyint(1) NOT NULL,
  `reciever` varchar(100) NOT NULL,
  `sender` varchar(100) NOT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unread 1 read',
  `reply_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 unread 1 read',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_reply`
--

DROP TABLE IF EXISTS `message_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `file_name` text NOT NULL,
  `enc_name` text NOT NULL,
  `identity` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modules_manage`
--

DROP TABLE IF EXISTS `modules_manage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modules_id` int(11) NOT NULL,
  `isEnabled` tinyint(1) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offline_fees_payments`
--

DROP TABLE IF EXISTS `offline_fees_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offline_fees_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method` int(11) NOT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `student_enroll_id` int(11) DEFAULT NULL,
  `fees_allocation_id` int(11) DEFAULT NULL,
  `fees_type_id` int(11) DEFAULT NULL,
  `transport_fee_details_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `reference` varchar(200) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `fine` float(10,2) DEFAULT NULL,
  `submit_date` datetime DEFAULT NULL,
  `approve_date` datetime DEFAULT NULL,
  `orig_file_name` varchar(255) DEFAULT NULL,
  `enc_file_name` text DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_fees_master_id` (`fees_allocation_id`),
  KEY `fee_groups_feetype_id` (`fees_type_id`),
  KEY `offline_fees_payments_ibfk_4` (`approved_by`),
  KEY `student_session_id` (`student_enroll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offline_payment_types`
--

DROP TABLE IF EXISTS `offline_payment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offline_payment_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `note` varchar(500) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_admission`
--

DROP TABLE IF EXISTS `online_admission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_admission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` varchar(25) DEFAULT NULL,
  `birthday` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `caste` varchar(100) DEFAULT NULL,
  `blood_group` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(50) DEFAULT NULL,
  `mother_tongue` varchar(100) DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `admission_date` varchar(100) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `student_photo` varchar(255) DEFAULT NULL,
  `category_id` varchar(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `previous_school_details` text DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relation` varchar(50) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `grd_occupation` varchar(255) DEFAULT NULL,
  `grd_income` varchar(25) DEFAULT NULL,
  `grd_education` varchar(255) DEFAULT NULL,
  `grd_email` varchar(255) DEFAULT NULL,
  `grd_mobile_no` varchar(50) DEFAULT NULL,
  `grd_address` text DEFAULT NULL,
  `grd_city` varchar(255) DEFAULT NULL,
  `grd_state` varchar(255) DEFAULT NULL,
  `grd_photo` varchar(255) DEFAULT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 1,
  `payment_status` tinyint(1) NOT NULL DEFAULT 0,
  `payment_amount` decimal(18,2) NOT NULL,
  `payment_details` longtext NOT NULL,
  `branch_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` varchar(11) DEFAULT NULL,
  `apply_date` datetime NOT NULL,
  `doc` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `online_admission_rms_1` (`branch_id`),
  CONSTRAINT `online_admission_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_admission_fields`
--

DROP TABLE IF EXISTS `online_admission_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_admission_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fields_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `required` tinyint(4) NOT NULL DEFAULT 0,
  `system` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_exam`
--

DROP TABLE IF EXISTS `online_exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` text NOT NULL,
  `subject_id` text NOT NULL,
  `limits_participation` int(11) NOT NULL,
  `exam_start` datetime DEFAULT NULL,
  `exam_end` datetime DEFAULT NULL,
  `duration` time NOT NULL,
  `mark_type` tinyint(1) NOT NULL DEFAULT 1,
  `passing_mark` float NOT NULL DEFAULT 0,
  `instruction` text DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `publish_result` tinyint(1) NOT NULL DEFAULT 0,
  `marks_display` tinyint(1) NOT NULL DEFAULT 1,
  `neg_mark` tinyint(1) NOT NULL DEFAULT 0,
  `question_type` tinyint(1) NOT NULL DEFAULT 0,
  `publish_status` tinyint(1) NOT NULL DEFAULT 0,
  `exam_type` tinyint(1) NOT NULL DEFAULT 0,
  `fee` float NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `position_generated` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_exam_answer`
--

DROP TABLE IF EXISTS `online_exam_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `online_exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_exam_attempts`
--

DROP TABLE IF EXISTS `online_exam_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `online_exam_id` int(11) NOT NULL,
  `count` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_exam_payment`
--

DROP TABLE IF EXISTS `online_exam_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `payment_method` tinyint(4) NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `transaction_id` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online_exam_submitted`
--

DROP TABLE IF EXISTS `online_exam_submitted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_exam_submitted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `online_exam_id` int(11) NOT NULL,
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parent`
--

DROP TABLE IF EXISTS `parent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `income` varchar(100) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobileno` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `active` tinyint(2) NOT NULL DEFAULT 0 COMMENT '0(active) 1(deactivate)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_config`
--

DROP TABLE IF EXISTS `payment_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_username` varchar(255) DEFAULT NULL,
  `paypal_password` varchar(255) DEFAULT NULL,
  `paypal_signature` varchar(255) DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `paypal_sandbox` tinyint(4) DEFAULT NULL,
  `paypal_status` tinyint(4) DEFAULT NULL,
  `stripe_secret` varchar(255) DEFAULT NULL,
  `stripe_publishiable` varchar(255) NOT NULL,
  `stripe_demo` varchar(255) DEFAULT NULL,
  `stripe_status` tinyint(4) DEFAULT NULL,
  `payumoney_key` varchar(255) DEFAULT NULL,
  `payumoney_salt` varchar(255) DEFAULT NULL,
  `payumoney_demo` tinyint(4) DEFAULT NULL,
  `payumoney_status` tinyint(4) DEFAULT NULL,
  `paystack_secret_key` varchar(255) NOT NULL,
  `paystack_status` tinyint(4) NOT NULL,
  `razorpay_key_id` varchar(255) NOT NULL,
  `razorpay_key_secret` varchar(255) NOT NULL,
  `razorpay_demo` tinyint(4) NOT NULL,
  `razorpay_status` tinyint(4) NOT NULL,
  `sslcz_store_id` varchar(255) NOT NULL,
  `sslcz_store_passwd` varchar(255) NOT NULL,
  `sslcommerz_sandbox` tinyint(1) NOT NULL,
  `sslcommerz_status` tinyint(1) NOT NULL,
  `jazzcash_merchant_id` varchar(255) NOT NULL,
  `jazzcash_passwd` varchar(255) NOT NULL,
  `jazzcash_integerity_salt` varchar(255) NOT NULL,
  `jazzcash_sandbox` tinyint(1) NOT NULL,
  `jazzcash_status` tinyint(1) NOT NULL,
  `midtrans_client_key` varchar(255) NOT NULL,
  `midtrans_server_key` varchar(255) NOT NULL,
  `midtrans_sandbox` tinyint(1) NOT NULL,
  `midtrans_status` tinyint(1) NOT NULL,
  `flutterwave_public_key` varchar(255) DEFAULT NULL,
  `flutterwave_secret_key` varchar(255) DEFAULT NULL,
  `flutterwave_sandbox` tinyint(4) NOT NULL DEFAULT 0,
  `flutterwave_status` tinyint(4) NOT NULL DEFAULT 0,
  `paytm_merchantmid` varchar(255) DEFAULT NULL,
  `paytm_merchantkey` varchar(255) DEFAULT NULL,
  `paytm_merchant_website` varchar(255) DEFAULT NULL,
  `paytm_industry_type` varchar(255) DEFAULT NULL,
  `paytm_status` tinyint(1) NOT NULL DEFAULT 0,
  `toyyibpay_secretkey` varchar(255) DEFAULT NULL,
  `toyyibpay_categorycode` varchar(255) DEFAULT NULL,
  `toyyibpay_status` tinyint(1) NOT NULL DEFAULT 0,
  `payhere_merchant_id` varchar(255) DEFAULT NULL,
  `payhere_merchant_secret` varchar(255) DEFAULT NULL,
  `payhere_status` tinyint(1) NOT NULL DEFAULT 0,
  `nepalste_public_key` varchar(255) DEFAULT NULL,
  `nepalste_secret_key` varchar(255) DEFAULT NULL,
  `nepalste_status` tinyint(1) NOT NULL DEFAULT 0,
  `bkash_app_key` varchar(255) DEFAULT NULL,
  `bkash_app_secret` varchar(255) DEFAULT NULL,
  `bkash_username` varchar(255) DEFAULT NULL,
  `bkash_password` varchar(255) DEFAULT NULL,
  `bkash_sandbox` tinyint(1) NOT NULL DEFAULT 0,
  `bkash_status` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_salary_stipend`
--

DROP TABLE IF EXISTS `payment_salary_stipend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_salary_stipend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payslip_id` int(11) NOT NULL,
  `name` longtext NOT NULL,
  `amount` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_types`
--

DROP TABLE IF EXISTS `payment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL DEFAULT 0,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payslip`
--

DROP TABLE IF EXISTS `payslip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `month` varchar(200) DEFAULT NULL,
  `year` varchar(20) NOT NULL,
  `basic_salary` decimal(18,2) NOT NULL,
  `total_allowance` decimal(18,2) NOT NULL,
  `total_deduction` decimal(18,2) NOT NULL,
  `net_salary` decimal(18,2) NOT NULL,
  `bill_no` varchar(25) NOT NULL,
  `remarks` text NOT NULL,
  `pay_via` tinyint(1) NOT NULL,
  `hash` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_by` varchar(200) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payslip_details`
--

DROP TABLE IF EXISTS `payslip_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payslip_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payslip_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `show_view` tinyint(1) DEFAULT 1,
  `show_add` tinyint(1) DEFAULT 1,
  `show_edit` tinyint(1) DEFAULT 1,
  `show_delete` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission_modules`
--

DROP TABLE IF EXISTS `permission_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `system` tinyint(1) NOT NULL,
  `sorted` tinyint(10) NOT NULL,
  `in_module` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `postal_record`
--

DROP TABLE IF EXISTS `postal_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postal_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_title` varchar(255) DEFAULT NULL,
  `receiver_title` varchar(255) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `date` date NOT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `file` varchar(250) NOT NULL,
  `confidential` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `purchase_unit_id` int(11) NOT NULL,
  `sales_unit_id` int(11) NOT NULL,
  `unit_ratio` varchar(20) DEFAULT '1',
  `purchase_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `sales_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `available_stock` varchar(11) NOT NULL DEFAULT '0',
  `photo` varchar(100) DEFAULT NULL,
  `remarks` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_rms_1` (`branch_id`),
  CONSTRAINT `product_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_category_rms_1` (`branch_id`),
  CONSTRAINT `product_category_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_issues`
--

DROP TABLE IF EXISTS `product_issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `remarks` text NOT NULL,
  `prepared_by` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_issues_rms_1` (`branch_id`),
  CONSTRAINT `product_issues_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_issues_details`
--

DROP TABLE IF EXISTS `product_issues_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_issues_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issues_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_issues_details_rms_1` (`product_id`),
  CONSTRAINT `product_issues_details_rms_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_store`
--

DROP TABLE IF EXISTS `product_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `mobileno` varchar(255) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_supplier`
--

DROP TABLE IF EXISTS `product_supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `mobileno` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `product_list` mediumtext NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_supplier_rms_1` (`branch_id`),
  CONSTRAINT `product_supplier_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_unit`
--

DROP TABLE IF EXISTS `product_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_unit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `promotion_history`
--

DROP TABLE IF EXISTS `promotion_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotion_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `pre_class` int(11) NOT NULL,
  `pre_section` int(11) NOT NULL,
  `pre_session` int(11) NOT NULL,
  `pro_class` int(11) NOT NULL,
  `pro_section` int(11) NOT NULL,
  `pro_session` int(11) NOT NULL,
  `prev_due` float NOT NULL DEFAULT 0,
  `is_leave` tinyint(4) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `promotion_history_rms_1` (`student_id`),
  CONSTRAINT `promotion_history_rms_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase_bill`
--

DROP TABLE IF EXISTS `purchase_bill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_no` varchar(200) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `paid` decimal(18,2) NOT NULL DEFAULT 0.00,
  `due` decimal(18,2) NOT NULL DEFAULT 0.00,
  `payment_status` int(11) NOT NULL,
  `purchase_status` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `prepared_by` int(11) DEFAULT NULL,
  `modifier_id` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_bill_rms_1` (`branch_id`),
  CONSTRAINT `purchase_bill_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase_bill_details`
--

DROP TABLE IF EXISTS `purchase_bill_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_bill_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_bill_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `quantity` varchar(20) NOT NULL,
  `discount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `purchase_bill_details_rms_1` (`product_id`),
  CONSTRAINT `purchase_bill_details_rms_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase_payment_history`
--

DROP TABLE IF EXISTS `purchase_payment_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_bill_id` varchar(11) NOT NULL,
  `payment_by` int(11) DEFAULT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `pay_via` varchar(25) NOT NULL,
  `remarks` text NOT NULL,
  `attach_orig_name` varchar(255) DEFAULT NULL,
  `attach_file_name` varchar(255) DEFAULT NULL,
  `paid_on` date DEFAULT NULL,
  `coll_type` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `question_group`
--

DROP TABLE IF EXISTS `question_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT 0,
  `subject_id` int(11) NOT NULL DEFAULT 0,
  `group_id` int(11) NOT NULL,
  `question` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `opt_1` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `opt_2` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `opt_3` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `opt_4` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mark` float(10,2) NOT NULL DEFAULT 0.00,
  `branch_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `questions_manage`
--

DROP TABLE IF EXISTS `questions_manage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `onlineexam_id` int(11) DEFAULT NULL,
  `marks` float(10,2) NOT NULL DEFAULT 0.00,
  `neg_marks` float(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `onlineexam_id` (`onlineexam_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reset_password`
--

DROP TABLE IF EXISTS `reset_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reset_password` (
  `key` longtext NOT NULL,
  `username` varchar(100) NOT NULL,
  `login_credential_id` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rm_sessions`
--

DROP TABLE IF EXISTS `rm_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rm_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `is_system` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salary_template`
--

DROP TABLE IF EXISTS `salary_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `basic_salary` decimal(18,2) NOT NULL,
  `overtime_salary` varchar(100) NOT NULL DEFAULT '0',
  `branch_id` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salary_template_details`
--

DROP TABLE IF EXISTS `salary_template_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_template_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_template_id` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `type` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_bill`
--

DROP TABLE IF EXISTS `sales_bill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_no` varchar(200) NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `paid` decimal(18,2) NOT NULL DEFAULT 0.00,
  `due` decimal(18,2) NOT NULL DEFAULT 0.00,
  `payment_status` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `prepared_by` int(11) DEFAULT NULL,
  `modifier_id` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_bill_rms_1` (`branch_id`),
  CONSTRAINT `sales_bill_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_bill_details`
--

DROP TABLE IF EXISTS `sales_bill_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_bill_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_bill_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `quantity` varchar(20) NOT NULL,
  `discount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `sales_bill_details_rms_1` (`product_id`),
  CONSTRAINT `sales_bill_details_rms_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sales_payment_history`
--

DROP TABLE IF EXISTS `sales_payment_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_bill_id` varchar(11) NOT NULL,
  `payment_by` int(11) DEFAULT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `pay_via` varchar(25) NOT NULL,
  `remarks` text NOT NULL,
  `attach_orig_name` varchar(255) DEFAULT NULL,
  `attach_file_name` varchar(255) DEFAULT NULL,
  `paid_on` date DEFAULT NULL,
  `coll_type` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schoolyear`
--

DROP TABLE IF EXISTS `schoolyear`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schoolyear` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_year` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `capacity` varchar(20) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sections_allocation`
--

DROP TABLE IF EXISTS `sections_allocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections_allocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sections_allocation_rms_1` (`class_id`),
  KEY `sections_allocation_rms_2` (`section_id`),
  CONSTRAINT `sections_allocation_rms_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sections_allocation_rms_2` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_api`
--

DROP TABLE IF EXISTS `sms_api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_credential`
--

DROP TABLE IF EXISTS `sms_credential`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_credential` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_api_id` int(11) NOT NULL,
  `field_one` varchar(300) NOT NULL,
  `field_two` varchar(300) NOT NULL,
  `field_three` varchar(300) NOT NULL,
  `field_four` varchar(300) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_template`
--

DROP TABLE IF EXISTS `sms_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tags` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_template_details`
--

DROP TABLE IF EXISTS `sms_template_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_template_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `dlt_template_id` varchar(255) DEFAULT NULL,
  `notify_student` tinyint(3) NOT NULL DEFAULT 1,
  `notify_parent` tinyint(3) NOT NULL DEFAULT 1,
  `template_body` longtext NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(25) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` int(11) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `experience_details` varchar(255) DEFAULT NULL,
  `total_experience` varchar(255) DEFAULT NULL,
  `designation` int(11) NOT NULL,
  `joining_date` varchar(100) NOT NULL,
  `birthday` varchar(100) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `blood_group` varchar(20) NOT NULL,
  `present_address` text NOT NULL,
  `permanent_address` text NOT NULL,
  `mobileno` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `salary_template_id` int(11) DEFAULT 0,
  `branch_id` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_attendance`
--

DROP TABLE IF EXISTS `staff_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `status` varchar(11) DEFAULT NULL COMMENT 'P=Present, A=Absent, H=Holiday, L=Late',
  `remark` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_attendance_rms_1` (`branch_id`),
  KEY `staff_attendance_rms_2` (`staff_id`),
  CONSTRAINT `staff_attendance_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_attendance_rms_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_bank_account`
--

DROP TABLE IF EXISTS `staff_bank_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_bank_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `holder_name` varchar(255) NOT NULL,
  `bank_branch` varchar(255) NOT NULL,
  `bank_address` varchar(255) NOT NULL,
  `ifsc_code` varchar(200) NOT NULL,
  `account_no` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_department`
--

DROP TABLE IF EXISTS `staff_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_designation`
--

DROP TABLE IF EXISTS `staff_designation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_designation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_documents`
--

DROP TABLE IF EXISTS `staff_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` varchar(20) NOT NULL,
  `remarks` text NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `enc_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_privileges`
--

DROP TABLE IF EXISTS `staff_privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `is_add` tinyint(1) NOT NULL,
  `is_edit` tinyint(1) NOT NULL,
  `is_view` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=813 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `register_no` varchar(100) DEFAULT NULL,
  `admission_date` varchar(100) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birthday` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `caste` varchar(100) DEFAULT NULL,
  `blood_group` varchar(100) DEFAULT NULL,
  `mother_tongue` varchar(100) DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `mobileno` varchar(100) DEFAULT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `email` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `route_id` int(11) NOT NULL DEFAULT 0,
  `stoppage_point_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `hostel_id` int(11) NOT NULL DEFAULT 0,
  `room_id` int(11) NOT NULL DEFAULT 0,
  `previous_details` text DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_admission_fields`
--

DROP TABLE IF EXISTS `student_admission_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_admission_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fields_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `required` tinyint(4) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_attendance`
--

DROP TABLE IF EXISTS `student_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enroll_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(4) DEFAULT NULL COMMENT 'P=Present, A=Absent, H=Holiday, L=Late',
  `remark` text DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_attendance_rms_1` (`branch_id`),
  KEY `student_attendance_rms_2` (`enroll_id`),
  CONSTRAINT `student_attendance_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_attendance_rms_2` FOREIGN KEY (`enroll_id`) REFERENCES `enroll` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_category`
--

DROP TABLE IF EXISTS `student_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_documents`
--

DROP TABLE IF EXISTS `student_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `remarks` text NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `enc_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_fields`
--

DROP TABLE IF EXISTS `student_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(255) NOT NULL,
  `default_status` tinyint(1) NOT NULL DEFAULT 1,
  `default_required` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_profile_fields`
--

DROP TABLE IF EXISTS `student_profile_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_profile_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fields_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `required` tinyint(4) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `student_subject_attendance`
--

DROP TABLE IF EXISTS `student_subject_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_subject_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enroll_id` int(11) DEFAULT NULL,
  `subject_timetable_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `attendence_type_id` (`status`),
  KEY `student_session_id` (`enroll_id`),
  KEY `subject_timetable_id` (`subject_timetable_id`),
  KEY `student_subject_attendance_rms_1` (`branch_id`),
  CONSTRAINT `student_subject_attendance_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_subject_attendance_rms_2` FOREIGN KEY (`enroll_id`) REFERENCES `enroll` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `subject_code` varchar(200) NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `subject_author` varchar(255) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subject_assign`
--

DROP TABLE IF EXISTS `subject_assign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_assign_rms_1` (`branch_id`),
  KEY `subject_assign_rms_2` (`session_id`),
  KEY `subject_assign_rms_3` (`class_id`),
  KEY `subject_assign_rms_4` (`section_id`),
  KEY `subject_assign_rms_5` (`subject_id`),
  CONSTRAINT `subject_assign_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_assign_rms_2` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_assign_rms_3` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_assign_rms_4` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_assign_rms_5` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teacher_allocation`
--

DROP TABLE IF EXISTS `teacher_allocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_allocation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teacher_note`
--

DROP TABLE IF EXISTS `teacher_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher_note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` longtext NOT NULL,
  `description` longtext NOT NULL,
  `file_name` longtext NOT NULL,
  `enc_name` longtext NOT NULL,
  `type_id` int(11) NOT NULL,
  `class_id` longtext NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `theme_settings`
--

DROP TABLE IF EXISTS `theme_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `theme_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `border_mode` varchar(200) NOT NULL,
  `dark_skin` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `timetable_class`
--

DROP TABLE IF EXISTS `timetable_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `break` varchar(11) DEFAULT 'false',
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_room` varchar(100) DEFAULT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `day` varchar(20) NOT NULL,
  `session_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timetable_class_rms_1` (`branch_id`),
  KEY `timetable_class_rms_2` (`class_id`),
  KEY `timetable_class_rms_3` (`section_id`),
  KEY `timetable_class_rms_4` (`session_id`),
  CONSTRAINT `timetable_class_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetable_class_rms_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetable_class_rms_3` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetable_class_rms_4` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `timetable_exam`
--

DROP TABLE IF EXISTS `timetable_exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable_exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `time_start` varchar(20) NOT NULL,
  `time_end` varchar(20) NOT NULL,
  `mark_distribution` text NOT NULL,
  `hall_id` int(11) NOT NULL,
  `exam_date` date NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timetable_exam_rms_1` (`branch_id`),
  KEY `timetable_exam_rms_2` (`exam_id`),
  KEY `timetable_exam_rms_3` (`class_id`),
  KEY `timetable_exam_rms_4` (`section_id`),
  KEY `timetable_exam_rms_5` (`session_id`),
  KEY `timetable_exam_rms_6` (`subject_id`),
  CONSTRAINT `timetable_exam_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetable_exam_rms_2` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetable_exam_rms_3` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetable_exam_rms_4` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetable_exam_rms_5` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetable_exam_rms_6` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(20) NOT NULL,
  `voucher_head_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `dr` decimal(18,2) NOT NULL DEFAULT 0.00,
  `cr` decimal(18,2) NOT NULL DEFAULT 0.00,
  `bal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `date` date NOT NULL,
  `pay_via` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `attachments` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_rms_1` (`branch_id`),
  CONSTRAINT `transactions_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transactions_links`
--

DROP TABLE IF EXISTS `transactions_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) DEFAULT NULL,
  `deposit` tinyint(3) DEFAULT NULL,
  `expense` tinyint(3) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_links_rms_1` (`branch_id`),
  CONSTRAINT `transactions_links_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transactions_links_details`
--

DROP TABLE IF EXISTS `transactions_links_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions_links_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) NOT NULL,
  `transactions_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transport_assign`
--

DROP TABLE IF EXISTS `transport_assign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transport_assign_rms_1` (`branch_id`),
  CONSTRAINT `transport_assign_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transport_fee_details`
--

DROP TABLE IF EXISTS `transport_fee_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport_fee_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(2) DEFAULT NULL,
  `transport_fee_fine_id` int(11) DEFAULT NULL,
  `enroll_id` int(11) NOT NULL,
  `stoppage_point_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transport_fee_details_rms_1` (`stoppage_point_id`),
  KEY `transport_fee_details_rms_2` (`enroll_id`),
  CONSTRAINT `transport_fee_details_rms_2` FOREIGN KEY (`enroll_id`) REFERENCES `enroll` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transport_fee_fine`
--

DROP TABLE IF EXISTS `transport_fee_fine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport_fee_fine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(2) NOT NULL,
  `due_date` date NOT NULL,
  `fine_value` varchar(20) DEFAULT NULL,
  `fine_type` varchar(20) DEFAULT NULL,
  `fee_frequency` varchar(20) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fee_fine_rms_2` (`month`),
  KEY `fee_fine_rms_3` (`session_id`),
  KEY `fee_fine_rms_4` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transport_route`
--

DROP TABLE IF EXISTS `transport_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `start_place` longtext NOT NULL,
  `remarks` longtext NOT NULL,
  `stop_place` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transport_route_rms_1` (`branch_id`),
  CONSTRAINT `transport_route_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transport_stoppage`
--

DROP TABLE IF EXISTS `transport_stoppage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport_stoppage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stop_position` varchar(255) NOT NULL,
  `stop_time` time NOT NULL,
  `route_fare` decimal(18,2) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transport_stoppage_rms_1` (`branch_id`),
  CONSTRAINT `transport_stoppage_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transport_stoppage_point`
--

DROP TABLE IF EXISTS `transport_stoppage_point`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport_stoppage_point` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) NOT NULL,
  `stoppage_id` int(11) NOT NULL,
  `route_fare` decimal(18,2) DEFAULT NULL,
  `stop_time` time DEFAULT NULL,
  `order_no` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transport_assign_rms_1` (`branch_id`),
  KEY `transport_stoppage_point_rms_1` (`route_id`),
  KEY `transport_stoppage_point_rms_3` (`session_id`),
  CONSTRAINT `transport_stoppage_point_rms_1` FOREIGN KEY (`route_id`) REFERENCES `transport_route` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `transport_stoppage_point_rms_2` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `transport_stoppage_point_rms_3` FOREIGN KEY (`session_id`) REFERENCES `schoolyear` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transport_vehicle`
--

DROP TABLE IF EXISTS `transport_vehicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport_vehicle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_no` longtext NOT NULL,
  `capacity` longtext NOT NULL,
  `insurance_renewal` longtext NOT NULL,
  `driver_name` longtext NOT NULL,
  `driver_phone` longtext NOT NULL,
  `driver_license` longtext NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transport_vehicle_rms_1` (`branch_id`),
  CONSTRAINT `transport_vehicle_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visitor_log`
--

DROP TABLE IF EXISTS `visitor_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitor_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `purpose_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `entry_time` time DEFAULT NULL,
  `exit_time` time DEFAULT NULL,
  `number_of_visitor` float DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `token_pass` varchar(255) DEFAULT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitor_log_rms_1` (`branch_id`),
  CONSTRAINT `visitor_log_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visitor_purpose`
--

DROP TABLE IF EXISTS `visitor_purpose`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitor_purpose` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `visitor_purpose_rms_1` (`branch_id`),
  CONSTRAINT `visitor_purpose_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `voucher_head`
--

DROP TABLE IF EXISTS `voucher_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher_head` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `system` tinyint(1) DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `voucher_head_rms_1` (`branch_id`),
  CONSTRAINT `voucher_head_rms_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `whatsapp_agent`
--

DROP TABLE IF EXISTS `whatsapp_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `whatsapp_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_name` varchar(255) NOT NULL,
  `agent_image` varchar(255) NOT NULL,
  `agent_designation` varchar(255) NOT NULL,
  `whataspp_number` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `weekend` varchar(20) DEFAULT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `whatsapp_chat`
--

DROP TABLE IF EXISTS `whatsapp_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `whatsapp_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header_title` varchar(255) NOT NULL,
  `subtitle` varchar(355) DEFAULT NULL,
  `footer_text` varchar(255) DEFAULT NULL,
  `popup_message` varchar(255) DEFAULT NULL,
  `frontend_enable_chat` tinyint(1) NOT NULL DEFAULT 0,
  `backend_enable_chat` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `zoom_own_api`
--

DROP TABLE IF EXISTS `zoom_own_api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zoom_own_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `zoom_api_key` varchar(255) NOT NULL,
  `zoom_api_secret` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-09 20:35:58
