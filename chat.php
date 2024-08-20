<?php
	include('db_chat.php');

	# ログインしないと、このページは表示できません
	if (!isset($_COOKIE['user_id']) &&  !isset($_COOKIE['user_name'])) {
		header("Location: login.php");
	}

	$userList = getUserList();
	$roomList = getRoomList();

	if (isset($_GET['r_id'])) $messages = getMessages();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat App.</title>
	<!-- font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
	<!-- css -->
	<link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-primary-dark" id="chat">
	
	<header>
		<div class="container">
			<nav>
				<span class="site-logo">Chat App.</span>
				<ul>
					<li><span title="自分"><?php if(isset($_COOKIE['user_name'])) echo $_COOKIE['user_name']; ?></span></li>
					<li><a href="chat.php" title="新規メッセージ"><i class="fa-regular fa-square-plus"></i></a></li>
					<li><a href="logout.php" title="ログアウト"><i class="fa-solid fa-right-from-bracket"></i></a></li>
				</ul>
			</nav>
		</div>
	</header>

	<div class="side-main-wrap">
		<aside id="aside-room">

			<?php foreach ($roomList as $room): ?>
				<a href="chat.php?r_id=<?= $room['room_id'] ?>">
					<div class="pre-message-div <?= isset($_GET['r_id']) && $room['room_id'] == $_GET['r_id']  ? 'active' : '' ?>">
						<div class="profile-circle"><?= substr($room['send_to_name'], 0, 1) ?></div>
						<div class="message">
							<div class="name"><?= ($room['sender_name'] == $_COOKIE['user_name']) ? 'You': $room['sender_name'] ?></div>
							<div class="preview"><?= $room['last_message'] ?></div>
						</div>
						<?php if ($room['unread_messages'] != 0): ?>
							<div class="count-msg"><span><?= $room['unread_messages'] ?></span></div>
						<?php endif; ?>
					</div>
				</a>
			<?php endforeach; ?>

		</aside>

		<main id="chat-main">
			
			<form action="db_chat.php" method="post" <?php if (!isset($_GET['r_id'])) echo 'onsubmit="return validateSelect()"'; ?>>
				
				<?php if (isset($_GET['r_id'])): ?>
					<div class="name"><?= getSenderName() ?></div>
				<?php endif; ?>

				<div class="chat-wrapper" id="chat-wrapper">

					<?php if (!isset($_GET['r_id'])): ?>
						<div class="to-message">
							<span class="to-span">To: </span>
							<select name="slUserList" id="slUserList">
								<option value=""></option>
								<?php foreach ($userList as $user): ?>
									<option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endif; ?>

					<?php if (isset($_GET['r_id'])): ?>

						<?php foreach ($messages as $message): ?>
							<div class="chat <?= $message['sender_id'] == $_COOKIE['user_id'] ? 'right': 'left' ?>">
								<div class="time-read">
									<?php if ($message['sender_id'] == $_COOKIE['user_id']): ?>
										<span class="read"><?= $message['read_status'] == 1 ? '既読': '' ?></span>
									<?php endif; ?>
									<span class="time"><?= date( 'G:i', strtotime( $message['created_at'] ) ) ?></span>
								</div>
								<div class="message"><?= $message['text'] ?></div>
							</div>
						<?php endforeach; ?>

					<?php endif; ?>

				</div> 
				
				<div class="chat-message-wrapper">
					<textarea name="taMessage" id="taMessage" placeholder="Message...&#10;(Shift+Enterキーで送る)" required></textarea>
					<button type="<?php echo (isset($_GET['r_id'])) ? 'button' : 'submit';  ?>" class="btn-primary" name="btnSend" id="btnSend">送る</button>
				</div>

			</form>

		</main>

	</div>

	<!-- fontawesome -->
	<script src="https://kit.fontawesome.com/9670cd3151.js" crossorigin="anonymous"></script>
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/custom-select.js"></script>
	<script src="js/ajax_script.js"></script>
</body>
</html>