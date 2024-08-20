<?php
include 'db_connect.php';
session_start();

# ヌル入力と、ユーザー名に重複がないかチェック
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
	$sql = 'SELECT id FROM users WHERE name=:name';
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(':name', $name, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if (count($row)) {
		$_SESSION['error_message'] = "同じユーザー名は既に使用されています。別のユーザー名を入力してください。";
		return false;
	}

	return true;
}

# 新ユーザー登録
# Params		$name 入力の名前、$password 入力のパスワード
function createUser($name, $password) {
	$conn = dbConnect();
	$sql = 'INSERT INTO users(`name`, `password`) VALUES (:name, :password);';
	$stmt = $conn->prepare($sql);
	$stmt->bindValue(':name', $name, PDO::PARAM_STR);
	$stmt->bindValue(':password', md5($password), PDO::PARAM_STR);

	if ($stmt->execute()) {
		header("Location: login.php");
		exit();
	}
}

# 登録ボタンクリック
if (isset($_POST['btnSignUp'])) {

	$name = trim(htmlspecialchars($_POST['txtName']));
	$password = htmlspecialchars($_POST['txtPassword']);

	if (validateInput($name, $password)) {
		createUser($name, $password);
	} else {
		header("Location: index.php");
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat App. | 会員登録</title>
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
			<h1>Chat App.会員登録</h1>

			<form action="index.php" method="post">

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
					<button type="submit" class="btn-primary" name="btnSignUp">登録</button>
				</div>
			</form>

			<p>ログインは<a href="login.php">こちら</a></p>
		</div>
	</div>

	<!-- fontawesome -->
	<script src="https://kit.fontawesome.com/9670cd3151.js" crossorigin="anonymous"></script>
	<!-- js -->
	<script src="js/custom-show-hide-btn.js"></script>
</body>

</html>