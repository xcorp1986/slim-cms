<?php

function getBaseUrl() {

	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from settings ORDER BY id ASC");
	$sth->execute();
	$rows = $sth->fetch(PDO::FETCH_ASSOC);
	$baseUrl = $rows["url"];

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
