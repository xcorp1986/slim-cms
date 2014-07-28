<?php

function getBaseUrl() {
	$baseUrl = "http://slim-cms.dev";
	return $baseUrl;
}
function getDb() {

$config_file = file_get_contents('config.json');
$config = json_decode($config_file, true); // decode the JSON into an associative array

$host = $config[0];
$dbname = $config[1];
$user = $config[2];
$pass = $config[3];


	try {
	  $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		return $dbh;
	 }
	catch(PDOException $e) {
	    echo $e->getMessage();
	}

}

// CREATE TABLE `users` (
//   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `nam` tinytext,
//   `psw` varchar(32) DEFAULT '',
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

// CREATE TABLE `settings` (
//   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `txt` varchar(255) DEFAULT NULL,
//   `tit` varchar(255) DEFAULT NULL,
//   `seq` int(11) DEFAULT NULL,
//   `adr` text,
//   `ctt` text,
//   `soc` text,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

// CREATE TABLE `images` (
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

// CREATE TABLE `articles` (
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
