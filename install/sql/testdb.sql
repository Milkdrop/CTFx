-- MySQL dump 10.17  Distrib 10.3.22-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mellivora
-- ------------------------------------------------------
-- Server version	10.3.22-MariaDB-0+deb10u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

USE mellivora;

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `added_by` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `exposed` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `challenges`
--

DROP TABLE IF EXISTS `challenges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `challenges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `added_by` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` smallint(5) unsigned NOT NULL,
  `description` text NOT NULL,
  `exposed` tinyint(1) NOT NULL DEFAULT 1,
  `available_from` int(10) unsigned NOT NULL DEFAULT 0,
  `available_until` int(10) unsigned NOT NULL DEFAULT 0,
  `flag` text NOT NULL,
  `case_insensitive` tinyint(1) NOT NULL DEFAULT 0,
  `automark` tinyint(1) NOT NULL DEFAULT 1,
  `points` int(10) unsigned NOT NULL DEFAULT 500,
  `initial_points` int(10) unsigned NOT NULL DEFAULT 500,
  `minimum_points` int(10) unsigned NOT NULL DEFAULT 50,
  `solve_decay` int(10) unsigned NOT NULL DEFAULT 100,
  `solves` int(10) unsigned NOT NULL DEFAULT 0,
  `num_attempts_allowed` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `min_seconds_between_submissions` smallint(5) unsigned NOT NULL DEFAULT 0,
  `relies_on` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `challenges`
--

LOCK TABLES `challenges` WRITE;
/*!40000 ALTER TABLE `challenges` DISABLE KEYS */;
/*!40000 ALTER TABLE `challenges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cookie_tokens`
--

DROP TABLE IF EXISTS `cookie_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cookie_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `token_series` char(16) NOT NULL,
  `token` char(64) NOT NULL,
  `ip_created` int(10) unsigned NOT NULL,
  `ip_last` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_t_ts` (`user_id`,`token`,`token_series`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cookie_tokens`
--

LOCK TABLES `cookie_tokens` WRITE;
/*!40000 ALTER TABLE `cookie_tokens` DISABLE KEYS */;
INSERT INTO `cookie_tokens` VALUES (2,1584021028,1,'CaO+jdVYrEMaXZTe','JCWJ0dQtB95FwbdmEKCiXEzirycP/DU0dXdQrBznDJSWtJBWuB49W82klpX8h9Fz',2886795265,2886795265);
/*!40000 ALTER TABLE `cookie_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `country_name` varchar(50) NOT NULL DEFAULT '',
  `country_code` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `short` (`country_code`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Afghanistan','af'),(2,'Aland Islands','ax'),(3,'Albania','al'),(4,'Algeria','dz'),(5,'American Samoa','as'),(6,'Andorra','ad'),(7,'Angola','ao'),(8,'Anguilla','ai'),(9,'Antarctica','aq'),(10,'Antigua and Barbuda','ag'),(11,'Argentina','ar'),(12,'Armenia','am'),(13,'Aruba','aw'),(14,'Australia','au'),(15,'Austria','at'),(16,'Azerbaijan','az'),(17,'Bahamas','bs'),(18,'Bahrain','bh'),(19,'Bangladesh','bd'),(20,'Barbados','bb'),(21,'Belarus','by'),(22,'Belgium','be'),(23,'Belize','bz'),(24,'Benin','bj'),(25,'Bermuda','bm'),(26,'Bhutan','bt'),(27,'Bolivia, Plurinational State of','bo'),(28,'Bonaire, Sint Eustatius and Saba','bq'),(29,'Bosnia and Herzegovina','ba'),(30,'Botswana','bw'),(31,'Bouvet Island','bv'),(32,'Brazil','br'),(33,'British Indian Ocean Territory','io'),(34,'Brunei Darussalam','bn'),(35,'Bulgaria','bg'),(36,'Burkina Faso','bf'),(37,'Burundi','bi'),(38,'Cambodia','kh'),(39,'Cameroon','cm'),(40,'Canada','ca'),(41,'Cape Verde','cv'),(42,'Cayman Islands','ky'),(43,'Central African Republic','cf'),(44,'Chad','td'),(45,'Chile','cl'),(46,'China','cn'),(47,'Christmas Island','cx'),(48,'Cocos (Keeling) Islands','cc'),(49,'Colombia','co'),(50,'Comoros','km'),(51,'Congo','cg'),(52,'Congo, The Democratic Republic of the','cd'),(53,'Cook Islands','ck'),(54,'Costa Rica','cr'),(55,'Cote d\'Ivoire','ci'),(56,'Croatia','hr'),(57,'Cuba','cu'),(58,'Curacao','cw'),(59,'Cyprus','cy'),(60,'Czech Republic','cz'),(61,'Denmark','dk'),(62,'Djibouti','dj'),(63,'Dominica','dm'),(64,'Dominican Republic','do'),(65,'Ecuador','ec'),(66,'Egypt','eg'),(67,'El Salvador','sv'),(68,'Equatorial Guinea','gq'),(69,'Eritrea','er'),(70,'Estonia','ee'),(71,'Ethiopia','et'),(72,'Falkland Islands (Malvinas)','fk'),(73,'Faroe Islands','fo'),(74,'Fiji','fj'),(75,'Finland','fi'),(76,'France','fr'),(77,'French Guiana','gf'),(78,'French Polynesia','pf'),(79,'French Southern Territories','tf'),(80,'Gabon','ga'),(81,'Gambia','gm'),(82,'Georgia','ge'),(83,'Germany','de'),(84,'Ghana','gh'),(85,'Gibraltar','gi'),(86,'Greece','gr'),(87,'Greenland','gl'),(88,'Grenada','gd'),(89,'Guadeloupe','gp'),(90,'Guam','gu'),(91,'Guatemala','gt'),(92,'Guernsey','gg'),(93,'Guinea','gn'),(94,'Guinea-Bissau','gw'),(95,'Guyana','gy'),(96,'Haiti','ht'),(97,'Heard Island and McDonald Islands','hm'),(98,'Holy See (Vatican City State)','va'),(99,'Honduras','hn'),(100,'Hong Kong','hk'),(101,'Hungary','hu'),(102,'Iceland','is'),(103,'India','in'),(104,'Indonesia','id'),(105,'Iran, Islamic Republic of','ir'),(106,'Iraq','iq'),(107,'Ireland','ie'),(108,'Isle of Man','im'),(109,'Israel','il'),(110,'Italy','it'),(111,'Jamaica','jm'),(112,'Japan','jp'),(113,'Jersey','je'),(114,'Jordan','jo'),(115,'Kazakhstan','kz'),(116,'Kenya','ke'),(117,'Kiribati','ki'),(118,'Korea, Democratic People\'s Republic of','kp'),(119,'Korea, Republic of','kr'),(120,'Kuwait','kw'),(121,'Kyrgyzstan','kg'),(122,'Lao People\'s Democratic Republic','la'),(123,'Latvia','lv'),(124,'Lebanon','lb'),(125,'Lesotho','ls'),(126,'Liberia','lr'),(127,'Libyan Arab Jamahiriya','ly'),(128,'Liechtenstein','li'),(129,'Lithuania','lt'),(130,'Luxembourg','lu'),(131,'Macao','mo'),(132,'Macedonia, The former Yugoslav Republic of','mk'),(133,'Madagascar','mg'),(134,'Malawi','mw'),(135,'Malaysia','my'),(136,'Maldives','mv'),(137,'Mali','ml'),(138,'Malta','mt'),(139,'Marshall Islands','mh'),(140,'Martinique','mq'),(141,'Mauritania','mr'),(142,'Mauritius','mu'),(143,'Mayotte','yt'),(144,'Mexico','mx'),(145,'Micronesia, Federated States of','fm'),(146,'Moldova, Republic of','md'),(147,'Monaco','mc'),(148,'Mongolia','mn'),(149,'Montenegro','me'),(150,'Montserrat','ms'),(151,'Morocco','ma'),(152,'Mozambique','mz'),(153,'Myanmar','mm'),(154,'Namibia','na'),(155,'Nauru','nr'),(156,'Nepal','np'),(157,'Netherlands','nl'),(158,'New Caledonia','nc'),(159,'New Zealand','nz'),(160,'Nicaragua','ni'),(161,'Niger','ne'),(162,'Nigeria','ng'),(163,'Niue','nu'),(164,'Norfolk Island','nf'),(165,'Northern Mariana Islands','mp'),(166,'Norway','no'),(167,'Oman','om'),(168,'Pakistan','pk'),(169,'Palau','pw'),(170,'Palestinian Territory, Occupied','ps'),(171,'Panama','pa'),(172,'Papua New Guinea','pg'),(173,'Paraguay','py'),(174,'Peru','pe'),(175,'Philippines','ph'),(176,'Pitcairn','pn'),(177,'Poland','pl'),(178,'Portugal','pt'),(179,'Puerto Rico','pr'),(180,'Qatar','qa'),(181,'Reunion','re'),(182,'Romania','ro'),(183,'Russian Federation','ru'),(184,'Rwanda','rw'),(185,'Saint Barthelemy','bl'),(186,'Saint Helena, Ascension and Tristan Da Cunha','sh'),(187,'Saint Kitts and Nevis','kn'),(188,'Saint Lucia','lc'),(189,'Saint Martin (French Part)','mf'),(190,'Saint Pierre and Miquelon','pm'),(191,'Saint Vincent and The Grenadines','vc'),(192,'Samoa','ws'),(193,'San Marino','sm'),(194,'Sao Tome and Principe','st'),(195,'Saudi Arabia','sa'),(196,'Senegal','sn'),(197,'Serbia','rs'),(198,'Seychelles','sc'),(199,'Sierra Leone','sl'),(200,'Singapore','sg'),(201,'Sint Maarten (Dutch Part)','sx'),(202,'Slovakia','sk'),(203,'Slovenia','si'),(204,'Solomon Islands','sb'),(205,'Somalia','so'),(206,'South Africa','za'),(207,'South Georgia and The South Sandwich Islands','gs'),(208,'South Sudan','ss'),(209,'Spain','es'),(210,'Sri Lanka','lk'),(211,'Sudan','sd'),(212,'Suriname','sr'),(213,'Svalbard and Jan Mayen','sj'),(214,'Swaziland','sz'),(215,'Sweden','se'),(216,'Switzerland','ch'),(217,'Syrian Arab Republic','sy'),(218,'Taiwan','tw'),(219,'Tajikistan','tj'),(220,'Tanzania, United Republic of','tz'),(221,'Thailand','th'),(222,'Timor-Leste','tl'),(223,'Togo','tg'),(224,'Tokelau','tk'),(225,'Tonga','to'),(226,'Trinidad and Tobago','tt'),(227,'Tunisia','tn'),(228,'Turkey','tr'),(229,'Turkmenistan','tm'),(230,'Turks and Caicos Islands','tc'),(231,'Tuvalu','tv'),(232,'Uganda','ug'),(233,'Ukraine','ua'),(234,'United Arab Emirates','ae'),(235,'United Kingdom','gb'),(236,'United States','us'),(237,'United States Minor Outlying Islands','um'),(238,'Uruguay','uy'),(239,'Uzbekistan','uz'),(240,'Vanuatu','vu'),(241,'Venezuela, Bolivarian Republic of','ve'),(242,'Viet Nam','vn'),(243,'Virgin Islands, British','vg'),(244,'Virgin Islands, U.S.','vi'),(245,'Wallis and Futuna','wf'),(246,'Western Sahara','eh'),(247,'Yemen','ye'),(248,'Zambia','zm'),(249,'Zimbabwe','zw'),(250,'Multiple countries','wo');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dynamic_menu`
--

DROP TABLE IF EXISTS `dynamic_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dynamic_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `internal_page` int(10) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `visibility` enum('public','private','both') NOT NULL,
  `min_user_class` tinyint(4) NOT NULL DEFAULT 0,
  `priority` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permalink` (`permalink`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dynamic_menu`
--

LOCK TABLES `dynamic_menu` WRITE;
/*!40000 ALTER TABLE `dynamic_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `dynamic_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dynamic_pages`
--

DROP TABLE IF EXISTS `dynamic_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dynamic_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `visibility` enum('public','private','both') NOT NULL DEFAULT 'public',
  `min_user_class` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dynamic_pages`
--

LOCK TABLES `dynamic_pages` WRITE;
/*!40000 ALTER TABLE `dynamic_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `dynamic_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exceptions`
--

DROP TABLE IF EXISTS `exceptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exceptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `added_by` int(10) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `trace` text NOT NULL,
  `file` varchar(255) NOT NULL,
  `line` int(10) unsigned NOT NULL,
  `user_ip` int(10) unsigned NOT NULL,
  `user_agent` text NOT NULL,
  `unread` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exceptions`
--

LOCK TABLES `exceptions` WRITE;
/*!40000 ALTER TABLE `exceptions` DISABLE KEYS */;
INSERT INTO `exceptions` VALUES (1,1584020948,0,'Could not send email: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','0','#0 /var/www/ctfx/include/session.inc.php(518): send_email(Array, \'X-MAS CTF accou...\', \'admin, your reg...\')\n#1 /var/www/ctfx/htdocs/actions/register.php(31): register_account(\'admin@admin.com\', \'admin\', \'admin\', \'11\', NULL)\n#2 {main}','/var/www/ctfx/include/email.inc.php',105,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1),(2,1584021238,0,'Could not send email: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','0','#0 /var/www/ctfx/include/session.inc.php(518): send_email(Array, \'X-MAS CTF accou...\', \'pepe, your regi...\')\n#1 /var/www/ctfx/htdocs/actions/register.php(31): register_account(\'pepe@pepe.com\', \'pepe\', \'pepe\', \'8\', NULL)\n#2 {main}','/var/www/ctfx/include/email.inc.php',105,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1);
/*!40000 ALTER TABLE `exceptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `added_by` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `md5` char(32) NOT NULL,
  `download_key` char(64) NOT NULL,
  `challenge` int(10) unsigned NOT NULL,
  `file_type` enum('local','remote') NOT NULL DEFAULT 'local',
  PRIMARY KEY (`id`),
  UNIQUE KEY `download_key` (`download_key`),
  KEY `challenge` (`challenge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hints`
--

DROP TABLE IF EXISTS `hints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `challenge` int(10) unsigned NOT NULL,
  `added` int(10) unsigned NOT NULL,
  `added_by` int(10) unsigned NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 0,
  `body` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `challenge` (`challenge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hints`
--

LOCK TABLES `hints` WRITE;
/*!40000 ALTER TABLE `hints` DISABLE KEYS */;
/*!40000 ALTER TABLE `hints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip_log`
--

DROP TABLE IF EXISTS `ip_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `added` int(10) unsigned NOT NULL,
  `last_used` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `times_used` int(10) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_ip` (`user_id`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_log`
--

LOCK TABLES `ip_log` WRITE;
/*!40000 ALTER TABLE `ip_log` DISABLE KEYS */;
INSERT INTO `ip_log` VALUES (1,1,1584020817,1584021028,2886795265,3),(2,2,1584021108,1584021847,2886795265,2),(3,3,1584022014,1584022014,2886795265,1);
/*!40000 ALTER TABLE `ip_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `added_by` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_password`
--

DROP TABLE IF EXISTS `reset_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reset_password` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `auth_key` char(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_key` (`user_id`,`auth_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_password`
--

LOCK TABLES `reset_password` WRITE;
/*!40000 ALTER TABLE `reset_password` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_password` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restrict_email`
--

DROP TABLE IF EXISTS `restrict_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restrict_email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `added_by` int(11) NOT NULL,
  `rule` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `white` tinyint(1) NOT NULL DEFAULT 1,
  `priority` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restrict_email`
--

LOCK TABLES `restrict_email` WRITE;
/*!40000 ALTER TABLE `restrict_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `restrict_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `submissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(10) unsigned NOT NULL,
  `challenge` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `flag` text NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT 0,
  `marked` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `challenge` (`challenge`),
  KEY `user_id` (`user_id`),
  KEY `challenge_user_id` (`challenge`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `two_factor_auth`
--

DROP TABLE IF EXISTS `two_factor_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `two_factor_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `secret` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `two_factor_auth`
--

LOCK TABLES `two_factor_auth` WRITE;
/*!40000 ALTER TABLE `two_factor_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `two_factor_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  `last_active` int(10) unsigned NOT NULL DEFAULT 0,
  `passhash` varchar(255) NOT NULL,
  `download_key` char(64) NOT NULL,
  `class` tinyint(4) NOT NULL DEFAULT 0,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `user_type` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `competing` tinyint(1) NOT NULL DEFAULT 1,
  `country_id` smallint(5) unsigned NOT NULL,
  `2fa_status` enum('disabled','generated','enabled') NOT NULL DEFAULT 'disabled',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `team_name` (`team_name`),
  UNIQUE KEY `download_key` (`download_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@admin.com','admin',1584020817,1584021028,'$2y$10$owJvYOJpwrEDDfzPSU85YuBb4c/wpUQqEtheouoM.6RaQuDhIpfBK','3bc08c9093a4e64d130975a3f211fd3a3c291cc43c7a6c48a7c9e4b0047cf604',100,1,0,1,11,'disabled'),(2,'pepe@pepe.com','pepe',1584021108,1584021846,'$2y$10$KQyztNfIvj3QJ9HjCsoi5Ozno3mdl1o3J5rx0PevGG/TKDGdL1Q3q','0bfe7109343b5aa2c90aa6ea73cb4c1804521dea02e6907b657099d05448081e',0,1,0,1,8,'disabled'),(3,'alo@alo.com','alo',1584022014,0,'$2y$10$WB7f242IEpZk1ozJs51Vh.T9E/13BDfId/3IKJTEFdZ4so76WWJTu','6fc3a46013ba0ac9183e1ec97ed1777030adb458d3d10b25accb431819d31a2a',0,1,0,1,10,'disabled');
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

-- Dump completed on 2020-03-12 14:08:30
