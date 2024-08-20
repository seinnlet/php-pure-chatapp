<?php

function dbConnect() {
	$dns = 'mysql:host=localhost; dbname=chat_db;';
	$user = 'root';
	$password = '';

	try {
		$conn = new PDO($dns, $user, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	} catch (PDOException $e) {
		die("Connection failed: " . $e->getMessage());
	}
}

session_start();