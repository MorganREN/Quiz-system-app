<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style type="text/css">
  		#wrapper
  		{
  			margin: auto;
  			width: 25em;
  		}
  	</style>
</head>
<body>
    <h1>Login</h1>
	<div>no account? </div>
	<a href="register.php">
		<button>sign up</button>
	</a>
	<br>
    <?php
		createQuestion();

		if (empty($_POST)) {
			echo(loginForm());
		} 
		else {
			$user = loginUser ();
			if (isset($user['wrongpwd'])) {
				$_POST = array();
				echo "
				<br>
				<a href='login.php'>
					<button>Try Again</button>
				</a>
				";
			}
			
			else if (empty($user)) {
				$_POST = array();
				echo "Username does not exist, please try again";
				echo "
				<br>
				<a href='login.php'>
					<button>Try Again</button>
				</a>
				";
			}

			else {
				$username = $user['Username'];
				$status = $user['Status'];
				echo "
				<br>
				<a href='view_quiz.php?username=$username&status=$status'>
					<button>View Quiz</button>
				</a>
				";
				if ($user['Status'] == "Staff") {
					echo addQuizBtn($username);
				}
				else if ($user['Status'] == "Student") {
					echo studentbtn($username);
				}
			}
		} 
	?> 
</body>
</html>

<?php

function studentbtn($username) {
	return "
	<br>
	<a href='grade.php?username=$username'>
        <button>See Grade</button>
    </a><br>
	<a href='home.php?username=$username'>
        <button>Home</button>
    </a>
	";
}

function addQuizBtn($username) {
	return "
	<br>
	<a href='add_quiz.php?username=$username'>
        <button>Add Quiz</button>
    </a>
	<br>
	<a href='update_quiz.php?username=$username'>
        <button>Update Quiz</button>
    </a>
	<br>
	<a href='delete_quiz.php?username=$username'>
        <button>Delete Quiz</button>
    </a>
	<br>
	<a href='home.php?username=$username'>
        <button>Home</button>
    </a>
	";
}

function loginUser() {
	$un = $_POST['username'];
	$pw = $_POST['password'];

	$sql = "SELECT Username, Password, Status FROM User
	WHERE Username = :username";

	$pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$stmt = $pdo->prepare($sql);
	$stmt->execute([
	'username' => $un
	]);

	$stmt->setFetchMode(PDO::FETCH_ASSOC);

	$user = array();

	while ($row = $stmt->fetch()){
		if ($pw == $row['Password']){
			echo("<br>Password OK - User logged in.");
			echo("<br>You are " . $row['Status'] . "<br>");
			return $row;
		}
		else {
			echo("<br>Invalid Credentials");
			echo("<br>User: " . $row['Username']);
			echo("<br>Correct Password: " . $row['Password']);
			$row['wrongpwd'] = "Wrong password";
			return $row;
		}	
	}
}

function loginForm()
{
return '
	<form method="POST">
		<label for="username">Username</label>
		<input type="text" id="username" name="username">
		<label for="password">Password</label>
		<input type="password" id="password" name="password">
		<input type="submit" value="Login">
	</form>
';
}

function createQuestion() {
    $sql = "
    CREATE TABLE IF NOT EXISTS `Answer` (
		`ID` MEDIUMINT NOT NULL AUTO_INCREMENT,
        `Question_ID` int NOT NULL,
        `Quiz_ID` int NOT NULL,
        `Answer` varchar(80) NOT NULL,
        PRIMARY KEY (`ID`)
    ) DEFAULT CHARSET=utf8;    
    ";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$pdo->query($sql);

    $sql = "
    CREATE TABLE IF NOT EXISTS `Question` (
		`ID` MEDIUMINT NOT NULL AUTO_INCREMENT,
        `Question_ID` int NOT NULL,
        `Quiz_ID` int NOT NULL,
        `Question` varchar(80),
        `Option_1` varchar(80),
        `Option_2` varchar(80),
        `Option_3` varchar(80),
        `Option_4` varchar(80),
        PRIMARY KEY (`ID`),
        UNIQUE (`ID`)
    ) DEFAULT CHARSET=utf8;    
    ";
    $pdo->query($sql);

    $sql = "
    CREATE TABLE IF NOT EXISTS `Quiz` (
        `Quiz_ID` int NOT NULL,
        `Quiz_name` varchar(20),
        `Author_ID` int NOT NULL,
        `Quiz_Available` varchar(20) NOT NULL,
        `Quiz_Duration` varchar(20),
        PRIMARY KEY (`Quiz_ID`),
        UNIQUE (`Quiz_ID`),
        FOREIGN KEY (`Author_ID`) REFERENCES Author(`Author_ID`)
    ) DEFAULT CHARSET=utf8;  
    ";
    $pdo->query($sql);
}

 ?>