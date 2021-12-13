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
    <div id="wrapper">

    <?php
    echo ("<h2>Add Question: " . $_GET['index']);
    echo "</h2>";

    if (empty($_POST)) {
        echo AddQuestionForm();
    }
    else {
        $question = getQuestionInfo($_GET['index']);
        addAnswerToDB($question, $_GET['quizid']);
        addQuestionToDB($question, $_GET['quizid']);
        checkQuizInDB($_GET['quizid']);
        echo "This question has been added!";
        echo "<h5>Add another one or not?</h5><br>";
        $username = getUsername($_GET['authorid']);
        echo "<a href='home.php?username=$username'><button>Home</button></a>";
        echo addAnotherQ($_GET['quizid'],$_GET['quizname'],$_GET['authorid'],$_GET['duration'],$_GET['astatus'],$_GET['index']+1);
    }
    ?>
    </div>
</body>
</html>

<?php

function addAnotherQ($quizid,$quizname,$authorid,$duration,$astatus,$index) {
    return "
    <a href='add_Question.php?quizid=$quizid&quizname=$quizname&authorid=$authorid&duration=$duration&astatus=$astatus&index=$index'>
        <button>Add Question</button>
    </a>
    ";
}

function addQuizToDB() {
    $qid = $_GET['quizid'];
    $qn = $_GET['quizname'];
    $aid = $_GET['authorid'];
    $qa = $_GET['astatus'];
    $qd = $_GET['duration'];

    $sql = "INSERT INTO Quiz (Quiz_ID, Quiz_name, Author_ID, Quiz_Available, Quiz_Duration)
            VALUES (:qid, :qn, :aid, :qa, :qd)";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $pdo = $pdo->prepare($sql);

    $pdo->execute([
        ':qid' => $qid,
        ':qn' => $qn,
        ':aid' => $aid,
        ':qa' => $qa,
        ':qd' => $qd
    ]);
}

function checkQuizInDB($quizid) {
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $sql = "SELECT Quiz_ID, Quiz_name FROM Quiz 
        WHERE Quiz_ID=:quizid";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
    'quizid' => $quizid
    ]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    if (empty($stmt->fetch())) {
        addQuizToDB();
    }
}

function addAnswerToDB($question, $quizid) {
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $id = $question['questionid'];
    $answer = $question['answer'];

    $sql = "INSERT INTO Answer (Question_ID, Quiz_ID, Answer)
			VALUES (:id, :quizid, :answer)";

    $pdo = $pdo->prepare($sql);

    $pdo->execute([
        ':id' => $id,
        ':quizid' => $quizid,
        ':answer' => $answer
    ]);
}

function addQuestionToDB($question, $quizid) {
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'Rroot');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $id = $question['questionid'];
    $q = $question['question'];
    $option1 = $question['option1'];
    $option2 = $question['option2'];
    $option3 = $question['option3'];
    $option4 = $question['option4'];

    $sql = "INSERT INTO Question (Question_ID, Quiz_ID, Question, Option_1, Option_2, Option_3, Option_4) VALUES (:questionid, :quizid, :q, :op1, :op2, :op3, :op4)";

    $pdo = $pdo->prepare($sql);

    $pdo->execute([
        ':questionid' => $id,
        ':quizid' => $quizid,
        ':q' => $q,
        ':op1' => $option1,
        ':op2' => $option2,
        ':op3' => $option3,
        ':op4' => $option4
    ]);

}

function getQuestionInfo($index) {
    $question = array();
    $question['questionid'] = $index;
    $question['question'] = $_POST['question'];
    $question['option1'] = $_POST['option1'];
    $question['option2'] = $_POST['option2'];
    $question['option3'] = $_POST['option3'];
    $question['option4'] = $_POST['option4'];
    $question['answer'] = $_POST['answer'];
    return $question;
}

function AddQuestionForm() {
    return '
    <form method="POST">

		<label class="form-label" for="question">Question</label>
		<input class="form-control" type="text" name="question"><br>

        <label class="form-label" for="option1">Option 1</label>
		<input class="form-control" type="text" name="option1"><br>

        <label class="form-label" for="option2">Option 2</label>
		<input class="form-control" type="text" name="option2"><br>

        <label class="form-label" for="option3">Option 3</label>
		<input class="form-control" type="text" name="option3"><br>

        <label class="form-label" for="option4">Option 4</label>
		<input class="form-control" type="text" name="option4"><br>

        <label class="form-label" for="answer">Answer</label>
		<input class="form-control" type="text" name="answer"><br>

		<input type="submit" value="Submit">

	</form>
    ';
}

function getUsername($authorid) {
    $sql = "SELECT Username FROM Author
	WHERE Author_ID = :authorid";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$stmt = $pdo->prepare($sql);
	$stmt->execute([
	'authorid' => $authorid
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
        return $row['Username'];
    }
}
?>