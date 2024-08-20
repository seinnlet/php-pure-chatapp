<?php
include 'db_connect.php';
session_start();

# ヌル入力と、ユーザー名とパスワードが合っているかチェック
# Return		true/false
# Params		$name 入力のユーザー名、$password 入力のパスワード
function validateInput($name, $password) {

	if (isset($_SESSION['error_message'])) unset($_SESSION['error_message']);
	
	if (empty($name)) {
		$_SESSION['error_message'] = "ユーザー名を入力てください。";
		return false;
	}
	if (empty($password)) {
		$_SESSION['error_message'] = "パスワードを入力てください。";
		return false;
	}

	$conn = dbConnect();
	$sql = 'SELECT id FROM users WHERE BINARY name=:name AND password=:password'; // BINARY for case sensitive
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(':name', $name, PDO::PARAM_STR);
	$stmt->bindValue(':password', md5($password), PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if (count($row) == 0) {
		$_SESSION['error_message'] = "名前またはパスワードが違います。";
		return false;
	}

	setcookie('user_id', $row[0]['id'], time() + 7 * 24 * 60 * 60);
	setcookie('user_name', $name, time() + 7 * 24 * 60 * 60);
	return true;
}

# ログインボタンクリック
if (isset($_POST['btnLogIn'])) {

	$name = trim(htmlspecialchars($_POST['txtName']));
	$password = htmlspecialchars($_POST['txtPassword']);

	if (validateInput($name, $password)) {
		header("Location: chat.php");
	}
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat App. | ログイン</title>
	<!-- font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
	<!-- css -->
	<link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-secondary-dark">

	<div class="form-wrapper">
		<div class="form-container">
			<h1>Chat App.ログイン</h1>

			<form action="login.php" method="post">

				<?php if (isset($_SESSION['error_message'])): ?>
					<div class="form-group div-error-message"><?= $_SESSION['error_message'] ?></div>
				<?php endif; ?>

				<div class="form-group">
					<label for="name">ユーザー名</label>
					<input type="text" name="txtName" id="name">
				</div>

				<div class="form-group">
					<label for="password">パスワード</label>
					<input type="password" name="txtPassword" id="password">
					<button type="button" id="btnEyeToggle"><i id="eyeIcon" class="fa-solid fa-eye-slash"></i></button>
				</div>

				<div class="form-group">
					<button type="submit" class="btn-primary" name="btnLogIn">ログイン</button>
				</div>
			</form>

			<p>新規ユーザー登録は<a href="index.php">こちら</a></p>
		</div>
	</div>

	<!-- fontawesome -->
	<script src="https://kit.fontawesome.com/9670cd3151.js" crossorigin="anonymous"></script>
	<!-- js -->
	<script src="js/custom-show-hide-btn.js"></script>
</body>

</html>