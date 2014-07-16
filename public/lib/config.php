<?php

function getBaseUrl() {
	$baseUrl = "http://slim-cms.dev";
	return $baseUrl;
}
function getDb() {
	$host = 'localhost';
	$dbname = 'slim-cms';
	$user = 'root';
	$pass = 'Lego8200!';

	try {
	  $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
	 }
	catch(PDOException $e) {
	    echo $e->getMessage();
	}

	return $dbh;
}

// CREATE TABLE `site_usr` (
//   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `nam` tinytext,
//   `psw` varchar(32) DEFAULT '',
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

// CREATE TABLE `site_stt` (
//   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `txt` varchar(255) DEFAULT NULL,
//   `tit` varchar(255) DEFAULT NULL,
//   `seq` int(11) DEFAULT NULL,
//   `adr` text,
//   `ctt` text,
//   `soc` text,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

// CREATE TABLE `site_img` (
//   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `pid` smallint(5) DEFAULT NULL,
//   `ptb` int(11) DEFAULT NULL,
//   `typ` tinytext CHARACTER SET latin1,
//   `seq` int(11) DEFAULT NULL,
//   `fnm` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
//   `pub` int(11) DEFAULT NULL,
//   `tit` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

// CREATE TABLE `site_cnt` (
//   `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
//   `dat` date NOT NULL,
//   `tit` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
//   `utt` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
//   `tag` varchar(255) CHARACTER SET latin1 DEFAULT '',
//   `txt` text CHARACTER SET latin1,
//   `pid` smallint(5) DEFAULT NULL,
//   `seq` int(11) DEFAULT NULL,
//   `pub` int(11) DEFAULT NULL,
//   `typ` mediumtext,
//   `views` int(11) DEFAULT NULL,
//   `ytb` mediumtext,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

