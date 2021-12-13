<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
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
    <div id='wrapper'>

    <?php
    createDatabse();
	createTable();
    ?>

    <h1>Welcome to the quiz system!</h1>
    <a href="login.php">
        <button>Sign in</button>
    </a><br>
    <a href="register.php">
        <button>Sign up</button>
    </a><br>
    </div>

    

</body>
</html>

<?php

function createDatabse() {
	$sql = "CREATE DATABASE IF NOT EXISTS m33394mr";

        $pdo = new pdo('mysql:localhost', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $pdo->query($sql);
}

function createTable() {
	$sql = "
			CREATE TABLE IF NOT EXISTS `Student` (
				`Student_ID` int NOT NULL,
				`Student_Forename` varchar(20) NOT NULL,
				`Student_Surname` varchar(20) NOT NULL,
				`Username` varchar(15) NOT NULL,
				PRIMARY KEY (`Student_ID`, `Username`),
				INDEX (`Username`),
				UNIQUE(`Student_ID`, `Username`)
			) DEFAULT CHARSET=utf8;  ";
	$pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$pdo->query($sql);
	$sql = "
			CREATE TABLE IF NOT EXISTS `Author` (
				`Author_ID` int NOT NULL,
				`Author_name` varchar(30) NOT NULL,
				`Username` varchar(15) NOT NULL,
				PRIMARY KEY (`Author_ID`, `Username`),
				INDEX (`Username`),
				UNIQUE (`Author_ID`, `Username`)
			) DEFAULT CHARSET=utf8;  ";
	$pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$pdo->query($sql);
	$sql = "
			CREATE TABLE IF NOT EXISTS `User` (
				`Username` varchar(15) NOT NULL,
				`Password` int unsigned NOT NULL,
				`Email` varchar(30) NOT NULL,
				`Status` varchar(10) NOT NULL,
				PRIMARY KEY (`Username`),
				UNIQUE (`Username`, `Email`)
			) DEFAULT CHARSET=utf8;    ";
	$pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$pdo->query($sql);
}



?>