# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.15)
# Database: slim-cms
# Generation Time: 2014-07-16 18:50:32 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table articles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `dat` date NOT NULL,
  `tit` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `utt` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tag` varchar(255) CHARACTER SET latin1 DEFAULT '',
  `txt` text CHARACTER SET latin1,
  `pid` smallint(5) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  `pub` int(11) DEFAULT NULL,
  `typ` mediumtext,
  `views` int(11) DEFAULT NULL,
  `ytb` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `images`;

CREATE TABLE `images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) DEFAULT NULL,
  `ptb` int(11) DEFAULT NULL,
  `typ` tinytext CHARACTER SET latin1,
  `seq` int(11) DEFAULT NULL,
  `fnm` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pub` int(11) DEFAULT NULL,
  `tit` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;

INSERT INTO `images` (`id`, `pid`, `ptb`, `typ`, `seq`, `fnm`, `pub`, `tit`)
VALUES
	(72,43,NULL,'cnt',2,'P3089345.jpg',1,NULL),
	(73,42,NULL,'cnt',1,'MILO_VoorkantA.jpg',1,NULL);

/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `txt` varchar(255) DEFAULT NULL,
  `tit` varchar(255) DEFAULT NULL,
  `seq` int(11) DEFAULT NULL,
  `adr` text,
  `ctt` text,
  `soc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`id`, `txt`, `tit`, `seq`, `adr`, `ctt`, `soc`)
VALUES
	(3,'Dit is een Slim CMS demo site','Demo',NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nam` tinytext CHARACTER SET latin1,
  `psw` varchar(32) CHARACTER SET latin1 DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `nam`, `psw`)
VALUES
	(3,'martijnbac','8e1a9c06d6b5e1656188e9b8cf8a1770');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
