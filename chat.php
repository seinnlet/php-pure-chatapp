<?php
	include('db_chat.php');

	# ログインしないと、このページは表示できません
	if (!isset($_COOKIE['user_id']) &&  !isset($_COOKIE['user_name'])) {
		header("Location: login.php");
	}

	$userList = getUserList();

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
		<aside>
			<a href="">
				<div class="pre-message-div active">
					<div class="profile-circle">S</div>
					<div class="message">
						<div class="name">Seinn</div>
						<div class="preview">Hi!</div>
					</div>
					<div class="count-msg"></div>
				</div>
			</a>

			<a href="">
				<div class="pre-message-div">
					<div class="profile-circle">T</div>
					<div class="message">
						<div class="name">Test</div>
						<div class="preview">Hello</div>
					</div>
					<div class="count-msg"><span>2</span></div>
				</div>
				</a>
		</aside>

		<main id="chat-main">
			
			<form action="db_chat.php" method="post" <?php if (!isset($_GET['r_id'])) echo 'onsubmit="return validateSelect()"'; ?>>
				
				<div class="chat-wrapper">

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
						<div class="name">Seinn</div>
						<div class="chat right">
							<div class="time-read">
								<span class="read">Read</span>
								<span class="time">11:29</span>
							</div>
							<div class="message">Hello</div>
						</div>

						<div class="chat left">
							<div class="time-read">
								<span class="time">11:29</span>
							</div>
							<div class="message">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit mollitia architecto tempora quibusdam praesentium excepturi ipsa, saepe laudantium consectetur veritatis ab voluptatibus aut, alias blanditiis veniam autem voluptatum dignissimos earum?</div>
						</div>
					<?php endif; ?>

				</div> 
				
				<div class="chat-message-wrapper">
					<textarea name="taMessage" id="taMessage" placeholder="Message..." required></textarea>
					<button type="<?php echo (isset($_GET['r_id'])) ? 'button' : 'submit';  ?>" class="btn-primary" name="btnSend">送る</button>
				</div>

			</form>

		</main>

	</div>

	<!-- fontawesome -->
	<script src="https://kit.fontawesome.com/9670cd3151.js" crossorigin="anonymous"></script>
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/custom-select.js"></script>
</body>
</html>