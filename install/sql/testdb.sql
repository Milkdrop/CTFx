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
-- Current Database: `mellivora`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mellivora` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `mellivora`;

--
-- Table structure for table `categories`
--

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
  `available_from` int(10) unsigned NOT NULL DEFAULT 0,
  `available_until` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,1583175697,1,'Binary Exploitation','salut exploatare de binare TM\r\n\r\nexploitte',1,1576263600,1892487600);
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
  `case_insensitive` tinyint(1) NOT NULL DEFAULT 1,
  `automark` tinyint(1) NOT NULL DEFAULT 1,
  `points` int(10) unsigned NOT NULL DEFAULT 0,
  `initial_points` int(10) unsigned NOT NULL DEFAULT 0,
  `minimum_points` int(10) unsigned NOT NULL DEFAULT 0,
  `solve_decay` int(10) unsigned NOT NULL DEFAULT 0,
  `solves` int(10) unsigned NOT NULL DEFAULT 0,
  `num_attempts_allowed` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `min_seconds_between_submissions` smallint(5) unsigned NOT NULL DEFAULT 0,
  `relies_on` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `challenges`
--

LOCK TABLES `challenges` WRITE;
/*!40000 ALTER TABLE `challenges` DISABLE KEYS */;
INSERT INTO `challenges` VALUES (1,1583175721,1,'problema pwn1',1,'problema 1 saidj asd jasdad asd asqwd problema 1 saidj asd jasdad asd asqwd problema 1 saidj asd jasdad asd asqwd ',1,1576263600,1892487600,'flag',0,1,500,500,50,100,2,0,5,0),(2,1583175757,1,'problema pwn2',1,'pepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepepe\r\n\r\npepepepepepepepepepe\r\n\r\npepepepe',1,1576263600,1576868400,'flag',0,1,500,500,50,100,0,0,5,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cookie_tokens`
--

LOCK TABLES `cookie_tokens` WRITE;
/*!40000 ALTER TABLE `cookie_tokens` DISABLE KEYS */;
INSERT INTO `cookie_tokens` VALUES (4,1583188910,1,'YAOf+DWzqXyZbOzX','5bGg8O7xNSeMLLOuTdd+9Se+uDDb5VDwdV5S8u4HPGml91bkrkSFRm4cpoczCr4U',2886795265,2886795265),(5,1583189747,6,'pU9WFlrPrQb8usVm','6Vc/ceHx8VAOQdX7U4ed3xYtJSibaLvszTR5K3kX+d/y/ECB+JIUXjkZafv4vWje',2886795265,2886795265);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dynamic_menu`
--

LOCK TABLES `dynamic_menu` WRITE;
/*!40000 ALTER TABLE `dynamic_menu` DISABLE KEYS */;
INSERT INTO `dynamic_menu` VALUES (1,'patreon','https://github.com/AlexAltea/orbital',0,'https://github.com/AlexAltea/orbital','both',0,114);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dynamic_pages`
--

LOCK TABLES `dynamic_pages` WRITE;
/*!40000 ALTER TABLE `dynamic_pages` DISABLE KEYS */;
INSERT INTO `dynamic_pages` VALUES (1,'ce e asta','salut','both',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exceptions`
--

LOCK TABLES `exceptions` WRITE;
/*!40000 ALTER TABLE `exceptions` DISABLE KEYS */;
INSERT INTO `exceptions` VALUES (1,1583174252,0,'An invalid cookie token was used. Cookie likely stolen. TS: sAyve6XXmPnfDr6D','0','#0 /var/www/ctfx/include/session.inc.php(72): login_session_create_from_login_cookie()\n#1 /var/www/ctfx/include/session.inc.php(398): login_session_refresh(false)\n#2 /var/www/ctfx/htdocs/challenges.php(5): enforce_authentication()\n#3 {main}','/var/www/ctfx/include/session.inc.php',254,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1),(2,1583174266,0,'Could not send email: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','0','#0 /var/www/ctfx/include/session.inc.php(518): send_email(Array, \'X-MAS CTF accou...\', \'admin, your reg...\')\n#1 /var/www/ctfx/htdocs/actions/register.php(31): register_account(\'admin@admin.com\', \'admin\', \'admin\', \'15\', NULL)\n#2 {main}','/var/www/ctfx/include/email.inc.php',105,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1),(3,1583174277,0,'Could not send email: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','0','#0 /var/www/ctfx/include/session.inc.php(518): send_email(Array, \'X-MAS CTF accou...\', \'user1, your reg...\')\n#1 /var/www/ctfx/htdocs/actions/register.php(31): register_account(\'user1@user1.com\', \'user1\', \'user1\', \'1\', NULL)\n#2 {main}','/var/www/ctfx/include/email.inc.php',105,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1),(4,1583174287,0,'Could not send email: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','0','#0 /var/www/ctfx/include/session.inc.php(518): send_email(Array, \'X-MAS CTF accou...\', \'user2, your reg...\')\n#1 /var/www/ctfx/htdocs/actions/register.php(31): register_account(\'user2@user2.com\', \'user2\', \'user2\', \'5\', NULL)\n#2 {main}','/var/www/ctfx/include/email.inc.php',105,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1),(5,1583174338,0,'Could not send email: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','0','#0 /var/www/ctfx/include/session.inc.php(518): send_email(Array, \'X-MAS CTF accou...\', \'user3, your reg...\')\n#1 /var/www/ctfx/htdocs/actions/register.php(31): register_account(\'user3@user3.com\', \'user3\', \'user3\', \'71\', NULL)\n#2 {main}','/var/www/ctfx/include/email.inc.php',105,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1),(6,1583174350,0,'Could not send email: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','0','#0 /var/www/ctfx/include/session.inc.php(518): send_email(Array, \'X-MAS CTF accou...\', \'user4, your reg...\')\n#1 /var/www/ctfx/htdocs/actions/register.php(31): register_account(\'user4@user4.com\', \'user4\', \'user4\', \'71\', NULL)\n#2 {main}','/var/www/ctfx/include/email.inc.php',105,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1),(7,1583174384,0,'Could not send email: SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting','0','#0 /var/www/ctfx/include/session.inc.php(518): send_email(Array, \'X-MAS CTF accou...\', \'Nume lung salut...\')\n#1 /var/www/ctfx/htdocs/actions/register.php(31): register_account(\'user5@user5.com\', \'user5\', \'Nume lung salut...\', \'8\', NULL)\n#2 {main}','/var/www/ctfx/include/email.inc.php',105,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1),(8,1583189494,1,'SQLSTATE[22003]: Numeric value out of range: 1264 Out of range value for column \'priority\' at row 1','22003','#0 /var/www/ctfx/include/db.inc.php(99): PDOStatement->execute(Array)\n#1 /var/www/ctfx/htdocs/admin/actions/edit_dynamic_menu_item.php(26): db_update(\'dynamic_menu\', Array, Array)\n#2 {main}','/var/www/ctfx/include/db.inc.php',99,2886795265,'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (3,1583177578,1,'fisier1',22,'36433f0f453a58069f92bb0eca5f100e','bf1449d79c6ea975d7c543d705de708f1b6aba9e3d9570f5ff1a3b963de54019',1,'local');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hints`
--

LOCK TABLES `hints` WRITE;
/*!40000 ALTER TABLE `hints` DISABLE KEYS */;
INSERT INTO `hints` VALUES (1,1,1583192572,1,1,'indiciu');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_log`
--

LOCK TABLES `ip_log` WRITE;
/*!40000 ALTER TABLE `ip_log` DISABLE KEYS */;
INSERT INTO `ip_log` VALUES (1,1,1583174265,1583188910,2886795265,4),(2,2,1583174276,1583174276,2886795265,1),(3,3,1583174286,1583174286,2886795265,1),(4,4,1583174337,1583174337,2886795265,1),(5,5,1583174349,1583174349,2886795265,1),(6,6,1583174383,1583189747,2886795265,2);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,1583189237,1,'Stire de ultima ora','iată și firma Philips\r\no firmă a dumneavoastră care acum este la modă în București\r\nse spune că...\r\nviața dumneavoastră se va schimba cu un Philips\r\nacum nu contează daca este un Philips nou sau vechi\r\ndar Philipsu rămâne Philips\r\nnicio casă fără echipament Philips\r\naceasta este lozinca');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
INSERT INTO `submissions` VALUES (1,1583186560,1,1,'test',0,1),(2,1583186584,1,1,'flag',1,1),(3,1583189757,1,6,'testt',0,1),(4,1583189762,1,6,'alo salut',0,1),(5,1583189768,1,6,'<b>ce</b>',0,1),(6,1583189779,1,6,'flag',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@admin.com','admin',1583174265,1583195316,'$2y$10$2FpMUnbALjvFjd.KvVpFDufINsUweOfPmAEUuXXJS3E3lc1RIHY2K','8bae36cc786c70dc31185cdd9e002a44474feb3157cfc905477d89d6d0261b5f',100,1,0,1,15,'disabled'),(2,'user1@user1.com','user1',1583174276,0,'$2y$10$ZxI3.6NMX2/ywxji/o6cJeO2RlUa005OyhJ9TGFWCULIECx3SV2BG','55120cc6b3c831f7f1eb42252a88c9d0fc9f219fdcab3ebfbc3e3f08e2d39df0',0,1,0,1,1,'disabled'),(3,'user2@user2.com','user2',1583174286,0,'$2y$10$56P0Txo4dVi./LQIR5Lq2OWe4I5NnznKy4eY6d1YDQk276lSFerVu','61fa793386448a8d57f5650cf6a2aa96577904dee874f5c5dc2f7ddbde8cfe62',0,1,0,1,5,'disabled'),(4,'user3@user3.com','user3',1583174337,0,'$2y$10$zOcq5FdMRSmoHhtW0qxTZuBRE9V.3ZqZW56BmnvhkSSVmNyeJOZz2','1acae00ca0d78dc95d8ee0a6129102c7d438c8bb051023ed3d744be5d491e831',0,1,0,1,71,'disabled'),(5,'user4@user4.com','user4',1583174349,0,'$2y$10$zjk3g/w7BOUkL0ogzwt1XeaSbIOWFpflaePCvTLywWpvajuDqgs9a','b2f4f6ef64c680026322bd44863a0225929252f57cba74ff68e73aa2ead139ae',0,1,0,1,71,'disabled'),(6,'user5@user5.com','Nume lung salut <b>lol</b>',1583174383,1583189747,'$2y$10$Ei0n4KT08Ea.1GelgJ7vl.bysV38ia/wITTzxqR4ZA207o5aeerK.','b5c4b1c5be92e0604475df422d0e8d93208e330b639d71b0fb5c655df25e7669',0,1,0,1,8,'disabled');
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

-- Dump completed on 2020-03-03  0:34:35
