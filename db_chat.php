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

	if (isset($_POST['btnSend'])) {
		
		$sendToID = $_POST['slUserList'];
		$message = htmlspecialchars($_POST['taMessage']);
		
		$roomId = createMessage($sendToID, $message);
		header("Location: chat.php?r_id=".$roomId);
	}

	function createMessage($sendToID, $message) {
		$conn = dbConnect();

		$sql = 'INSERT INTO rooms (`send_to_id`) VALUES (:send_to_id);';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':send_to_id', (int)$sendToID, PDO::PARAM_INT);
		$stmt->execute();

		$roomId = $conn->lastInsertId();
		$sql = 'INSERT INTO messages (`text`, `send_from_id`, `room_id`) VALUES (:message, :send_from_id, :room_id);';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':message', $message, PDO::PARAM_STR);
		$stmt->bindValue(':send_from_id', (int)$_COOKIE['user_id'], PDO::PARAM_STR);
		$stmt->bindValue(':room_id',(int)$roomId, PDO::PARAM_INT);
		$stmt->execute();

		return $roomId;
	}

?>