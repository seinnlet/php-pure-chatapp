<?php 
	
	if (isset($_COOKIE['user_id']) &&  isset($_COOKIE['user_name'])) {
		setcookie('user_id', $row[0]['id'], time() - 7 * 24 * 60 * 60);
		setcookie('user_name', $name, time() - 7 * 24 * 60 * 60);
	}

	header("Location: login.php");

?>