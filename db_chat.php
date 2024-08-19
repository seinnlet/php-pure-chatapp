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

	function getRoomList() {
		$conn = dbConnect();
		$sql =	'SELECT r.id AS room_id,
									u.name AS sender_name,
									m.text AS last_message,
									m.created_at AS last_message_time,
									SUM(CASE WHEN m.read_status = 0 AND m.sender_id != :loggedin_user_id THEN 1 ELSE 0 END) AS unread_messages
						FROM rooms r
						JOIN participants p ON r.id = p.room_id
						JOIN messages m ON r.id = m.room_id
						JOIN users u ON m.sender_id = u.id
						WHERE p.user_id = :loggedin_user_id
						GROUP BY r.id
						HAVING m.created_at = (SELECT MAX(m2.created_at)
																	FROM messages m2
																	WHERE m2.room_id = r.id)
						ORDER BY last_message_time DESC;';
		
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':loggedin_user_id', (int)$_COOKIE['user_id'], PDO::PARAM_INT);
		$stmt->execute();
		$roomList = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $roomList;
	}

	function getMessages() {
		$conn = dbConnect();
		$sql = 'SELECT * FROM messages WHERE room_id = :r_id';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':r_id', (int)$_GET['r_id'], PDO::PARAM_INT);
		$stmt->execute();
		$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $messages;
	}

	function getSenderName() {
		$conn = dbConnect();
		$sql = 'SELECT u.name FROM participants p, users u WHERE p.user_id = u.id AND p.room_id = :r_id AND p.user_id != :loggedin_user_id';
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':r_id', (int)$_GET['r_id'], PDO::PARAM_INT);
		$stmt->bindValue(':loggedin_user_id', (int)$_COOKIE['user_id'], PDO::PARAM_INT);
		$stmt->execute();
		$senderName = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $senderName[0]['name'];
	}

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