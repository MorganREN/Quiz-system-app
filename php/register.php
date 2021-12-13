<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register User</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<style type="text/css">
		#wrapper
		{
			margin: auto;
			width: 40em;
		}
	</style>
</head>
<body>
	<div id="wrapper">
		<h1>Register</h1>
	<?php
	if(empty($_POST)){
		echo(showRegisterForm());
	}
	else{
		$user = getUserCredentials();

		if (isset($user['badun'])) {
			echo ("Username exists, please try another one");
			$_POST = array();
			echo "<a href='register.php'>
			<button> Try again </button></a>";
		}

		else if(isset($user['badinput'])){
			echo ("Something went wrong: Passwords don't match");
			$_POST = array();
			echo "<a href='register.php'>
			<button> Try again </button></a>";
		}
		else if (isset($user['badpwd'])) {
			echo ("Something went wrong: Passwords should be numeric and longer than 3");
			$_POST = array();
			echo "<br><a href='register.php'>
			<button> Try again </button></a>";
		}
		else{
			addUserToDatabase($user);
		}
	}
	?>
	<?php
		if (empty($_POST)) {

		}
		else {
			if ($_POST['status'] == "Staff") {
				echo goAuthor($user['username']);
			}
			else if ($_POST['status'] == "Student") {
				echo goStudent($user['username']);
			}
		}
	?>
	</div>
</body>
</html>

<?php

function goAuthor($username) {
	return "
	<p>Hello staff $username!</p>
	<a href='author_reg.php?username=$username'>
        <button>Finish my information</button>
    </a>
	";
}

function goStudent($username) {
	return "
	<a href='student_reg.php?username=$username'>
        <button>Finish my information</button>
    </a>
	";
}

function addUserToDatabase($user)
{
	$pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$username = $user['username'];
	$password = $user['password'];
	$email = $user['email'];
	$status = $user['status'];

	$sql = "INSERT INTO User (Username, Password, Email, Status)
			VALUES (:username, :password, :email, :status)";

	$pdo = $pdo->prepare($sql);

	$pdo->execute([
		':username' => $username,
		':password' => $password,
		':email' => $email,
		':status' => $status
	]);
}

function showRegisterForm()
{
	return '
	<form method="POST" action="register.php">

		<label class="form-label" for="username">Username</label>
		<input class="form-control" type="text" name="username">

		<label class="form-label"  for="email">Email</label>
		<input class="form-control"  type="email" name="email">

		<label class="form-label"  for="password">Password</label>
		<input class="form-control"  type="password" name="password">

		<label class="form-label"  for="cpassword">Confirm Password</label>
		<input class="form-control"  type="password" name="cpassword">

		<label class="form-label" for="status">Status</label>
		<input type="radio" class="form-control" name="status" value="Staff">Staff
		<input type="radio" class="form-control" name="status" value="Student">Student
		<br>

		<input type="submit" value="Register">

	</form>';
}

function getuserFromDB($userin) {
	$sql = "SELECT * FROM User WHERE Username = :username";
	$pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$stmt = $pdo->prepare($sql);
	$stmt->execute([
		':username' => $userin
	]);

	$stmt->setFetchMode(PDO::FETCH_ASSOC);

	while ($row = $stmt->fetch()){
		return $row;
	}
}

function getUserCredentials(){
	$user = array();
	$userin = getuserFromDB($_POST['username']);
	if (!empty($userin)) {
		$user['badun'] = "Username exists";
		return $user;
	}
	if ($_POST['password'] != $_POST['cpassword'])
	{
		$user['badinput'] = "Passwords don't match";
		return $user;
	}
	if ((!is_numeric($_POST['password'])) or (strlen($_POST['password']) < 3)) {
		$user['badpwd'] = "Bad password";
	}
	$user['username'] = $_POST['username'];
	$user['email'] = $_POST['email'];
	$user['password'] = $_POST['password'];
	$user['status'] = $_POST['status'];
	return $user;
}
?>