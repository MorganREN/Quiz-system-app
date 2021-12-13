<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
    <div id='wrapper'>
    <h1>Home</h1>
    <?php
    $username = $_GET['username'];
    echo "<h5>Hello " . $username;
    echo "!</h5><br>";

    $status = getStatus($username);
    echo "
        <a href='view_quiz.php?username=$username&status=$status'>
            <button>View Quiz</button>
        </a>
    ";
    if ($status == "Staff") {
        echo "
            <a href='add_quiz.php?username=$username'>
                <button>Add Quiz</button>
            </a>
        ";
        echo "
            <a href='update_quiz.php?username=$username'>
                <button>Update Quiz</button>
            </a>
        ";
        echo "
            <a href='delete_quiz.php?username=$username'>
                <button>Delete Quiz</button>
            </a>
        ";
    }
    else if ($status == "Student") {
        echo "
            <a href='grade.php?username=$username'>
                <button>Grade</button>
            </a>
        ";
    }
    ?>
    </div>
</body>
</html>

<?php

function getStatus($username) {
    $sql = "SELECT Status FROM User
	WHERE Username = :username";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$stmt = $pdo->prepare($sql);
	$stmt->execute([
	'username' => $username
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
        return $row['Status'];
    }
}
?>