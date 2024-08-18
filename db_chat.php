<?php
	include('db_connect.php');

	function getUserList() {
		$conn = dbConnect();
		$sql = 'SELECT * FROM users WHERE id != :id';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':id', (int)$_COOKIE['user_id'], PDO::PARAM_INT);
		$stmt->execute();
		$userList = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $userList;
	}

	// function getRoomList() {
	// 	$conn = dbConnect();

	// }

	function createFirstMessage($sendToID, $message) {
		$conn = dbConnect();

		$sql = 'INSERT INTO rooms () VALUES ();';
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		$roomId = $conn->lastInsertId();

		$sql = 'INSERT INTO messages (`text`, `sender_id`, `room_id`) VALUES (:message, :sender_id, :room_id);';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':message', $message, PDO::PARAM_STR);
		$stmt->bindValue(':sender_id', (int)$_COOKIE['user_id'], PDO::PARAM_STR);
		$stmt->bindValue(':room_id',(int)$roomId, PDO::PARAM_INT);
		$stmt->execute();

		$sql = 'INSERT INTO participants (`room_id`, `user_id`) VALUES (:room_id, :user_id);';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':room_id',(int)$roomId, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', (int)$_COOKIE['user_id'], PDO::PARAM_STR);
		$stmt->execute();
		
		$sql = 'INSERT INTO participants (`room_id`, `user_id`) VALUES (:room_id, :user_id);';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':room_id',(int)$roomId, PDO::PARAM_INT);
		$stmt->bindValue(':user_id',(int)$sendToID, PDO::PARAM_STR);
		$stmt->execute();

		return $roomId;
	}

	if (isset($_POST['btnSend'])) {
		
		$sendToID = $_POST['slUserList'];
		$message = htmlspecialchars($_POST['taMessage']);
		
		$roomId = createFirstMessage($sendToID, $message);
		header("Location: chat.php?r_id=".$roomId);
	}



?>