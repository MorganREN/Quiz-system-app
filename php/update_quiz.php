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
        <h3>Quiz Update</h3>

        <?php
            $authorid = getAuthorID($_GET['username']);
            echo "<br>";
            
            if (empty($_POST)) {
                showQuizForAuthor($authorid);
                echo updateInfo();
            }
            else{
                $update = getUpdate();
                updateToDB($update, $authorid);
                echo "The quiz has been updated!";
                showQuizForAuthor($authorid);
                echo goback($_GET['username']);
            }
        ?>
    </div>
</body>
</html>

<?php

function goback($username) {
    return "
        <a href='home.php?username=$username'>
            <button>Home</button>
        </a>";
}

function updateToDB($update, $authorid) {
    $qid = $update['quizid'];
    $qn = $update['qn'];
    $as = $update['astatus'];
    $dur = $update['duration'];

    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $sql = 'UPDATE Quiz SET Quiz_name=:qn, Quiz_Available=:as, Quiz_Duration=:dur
            WHERE Quiz_ID=:qid AND Author_ID=:aid';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
    'qn' => $qn,
    'as' => $as,
    'dur' => $dur,
    'qid' => $qid,
    'aid' => $authorid
    ]);
}

function getUpdate(){
    $update = array();
    $update['quizid'] = $_POST['quizid'];
    $update['qn'] = $_POST['qn'];
    $update['astatus'] = $_POST['astatus'];
    $update['duration'] = $_POST['duration'];
    return $update;
}

function updateInfo() {
    return '
        <br>
        <form method="POST">
            <label for="quizid">Quiz ID</label>
            <input type="text" name="quizid">
            <br>
            <label for="qn">New Question Name</label>
            <input type="text" name="qn">
            <br>
            <label for="astatus">Available</label>
            <input type="radio" name="astatus" value="yes">Available
            <input type="radio" name="astatus" value="no">Not Available
            <br>
            <label for="duration">New Duration/h</label>
            <input type="text" name="duration">
            <br>
    
            <input type="submit" value="Submit">
        </form>';
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

function getAuthorQuiz($authorid) {
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

function showQuizForAuthor($authorid) {
    $quiz = getAuthorQuiz($authorid);
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
?>