<?php

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "semestralka";

$options = [
	\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
	\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
	\PDO::ATTR_EMULATE_PREPARES   => false,
];

$db = new \PDO("mysql:host=$db_host;dbname=$db_name; charset=utf8", $db_user, $db_password, $options);


if (!$db) {
	echo "Connection failed!";
}