<?php
	include('connect.php');

	function getUserList() {
		$conn = dbConnect();
		$sql = 'SELECT * FROM users WHERE id != :id';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':id', (int)$_COOKIE['user_id'], PDO::PARAM_INT);
		$stmt->execute();
		$userList = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $userList;
	}

?>