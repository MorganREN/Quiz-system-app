<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    <h2>Grade:</h2>

    <?php
    $username = $_GET['username'];
    echo "<br>Hello, " . $username . ", here is your grade:<br>";
    $studentid = getStudenID($username);
    $takes = getTakes($studentid);
    showGrade($takes);
    echo "<br><a href='home.php?username=$username'>
                <button>Home</button>
                </a><br>";
    ?>
    </div>
</body>
</html>

<?php

function showGrade($takes){
    echo "<table border='1'>
            <tr>
                <th>Student ID</th><th>Quiz ID</th><th>Attempt Date</th><th>Grade</th>
            </tr>";
    foreach ($takes as $row) {
        $sid = $row['Student_ID'];
        $qid = $row['Quiz_ID'];
        $da = $row['Date_of_Attempt'];
        $gr = $row['Grade'];
        echo "<tr>
                <td>$sid</td><td>$qid</td><td>$da</td><td>$gr</td>
            <tr>";
    }
    echo "</table>";
}

function getTakes($studentid) {
    $sql = "SELECT * FROM Takes WHERE Student_ID=:sid";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
	$stmt->execute([
	'sid' => $studentid
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $takes = array();
    while ($row = $stmt->fetch()) {
        $ID = $row['Take_No'];
        $takes[$ID] = $row;
    }
    return $takes;
}

function getStudenID($username){
    $sql = "SELECT Student_ID FROM Student WHERE Username=:un";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'rooot');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
	$stmt->execute([
	'un' => $username
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
        return $row['Student_ID'];
    }
}
?>