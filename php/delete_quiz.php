<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id='wrapper'>
        <h3>Quiz Delete</h3>

        <?php
            $authorid = getAuthorID($_GET['username']);
            echo "<br>";
            
            if (empty($_POST)) {
                showQuizForAuthor($authorid);
                echo deleteInfo();
            }
            else{
                $delete = getDelete();
                updateToDB($delete);
                echo "The quiz has been deleted!";
                showQuizForAuthor($authorid);
                echo goback($_GET['username']);
            }
        ?>
    </div>
</body>
</html>

<?php

function updateToDB($delete) {

    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $sql = 'DELETE FROM Quiz WHERE Quiz_ID=:qid';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
    'qid' => $delete
    ]);
}

function getDelete(){
    $delete = $_POST['quizid'];
    return $delete;
}

function goback($username) {
    return "<a href='home.php?username=$username'><button>Home</button></a>";
}

function deleteInfo() {
    return '
        <br>
        <form method="POST">
            <label for="quizid">Quiz ID</label>
            <input type="text" name="quizid">
            <br>
    
            <input type="submit" value="Submit">
        </form>
    ';
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