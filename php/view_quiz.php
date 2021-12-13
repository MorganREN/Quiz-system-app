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
    <div id=#wrapper>
    <h3>Quiz: </h3>
    <?php
    $status = $_GET['status'];
    $username = $_GET['username'];
    
    if ($status == "Staff") {
        showQuizForAuthor($username);
    }
    else if ($status == "Student") {
        showQuizForStu($username);
    }
    echo "<br><a href='home.php?username=$username'>
                <button>Home</button>
                </a><br>";
    
    ?>
    </div>
</body>
</html>

<?php

function getAllQuiz() {
    $sql = "SELECT * FROM Quiz";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
	$stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $quiz = array();
    while ($row = $stmt->fetch()) {
        $quizid = $row['Quiz_ID'];
        $quiz[$quizid] = $row;
    }
    return $quiz;
}

function showQuizForStu($username) {
    $quiz = getAllQuiz();

    echo "<table border='1'>
            <tr>
                <th>Quiz ID</th><th>Quiz Name</th><th>Author ID</th><th>Availabel</th><th>Duration/h</th>
            </tr>";
    foreach ($quiz as $row) {
        $qid = $row['Quiz_ID'];
        $qn = $row['Quiz_name'];
        $aid = $row['Author_ID'];
        $qa = $row['Quiz_Available'];
        $qd = $row['Quiz_Duration'];
        echo "<tr>
                <td>$qid</td>
                <td><a href='do_quiz.php?username=$username&quizid=$qid'>$qn</a></td>
                <td>$aid</td><td>$qa</td><td>$qd</td>
            <tr>";
    }
    echo "</table>";
}



function getAuthorQuiz($username) {
    $authorid = getAuthorID($username);
    $sql = "SELECT Quiz_ID, Quiz_name, Author_ID, Quiz_Available, Quiz_Duration FROM Quiz
    WHERE Author_ID = :authorid";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$stmt = $pdo->prepare($sql);
	$stmt->execute([
	'authorid' => $authorid
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $quiz = array();
    while ($row = $stmt->fetch()) {
        $quizid = $row['Quiz_ID'];
        $quiz[$quizid] = $row;
    }
    return $quiz;
}

function showQuizForAuthor($username) {
    $quiz = getAuthorQuiz($username);
    echo "<table border='1'>
            <tr>
                <th>Quiz ID</th><th>Quiz Name</th><th>Author ID</th><th>Availabel</th><th>Duration/h</th>
            </tr>";
    foreach ($quiz as $row) {
        $qid = $row['Quiz_ID'];
        $qn = $row['Quiz_name'];
        $aid = $row['Author_ID'];
        $qa = $row['Quiz_Available'];
        $qd = $row['Quiz_Duration'];
        echo "<tr>
                <td>$qid</td><td>$qn</td><td>$aid</td><td>$qa</td><td>$qd</td>
            <tr>";
    }
    echo "</table>";
}

function getAuthorID($username) {
    $sql = "SELECT Author_ID FROM Author
	WHERE Username = :username";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$stmt = $pdo->prepare($sql);
	$stmt->execute([
	'username' => $username
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
        echo "<br>Your ID: " . $row['Author_ID'];
        return $row['Author_ID'];
    }
}
?>