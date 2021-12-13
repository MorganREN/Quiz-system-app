<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Time</title>
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
    <h2>Quiz Time...</h2>
    <?php
    creatTakeTable();
    $quiz = getQuiz($_GET['quizid']);
    $username = $_GET['username'];
    $status = "Student";
    $student = getStudent($username);
    $questions = getQuestions($_GET['quizid']);
    echo "<br><a href='home.php?username=$username'>
                <button>Home</button>
                </a><br>";
    if ($quiz['Quiz_Available'] == "no") {
        echo "Sorry, the quiz is now unavailable, please choose another one";
        echo "
        <br>
			<a href='view_quiz.php?username=$username&status=$status'>
				<button>View Quiz</button>
			</a>
        ";
    }
    else {
        if (empty($_POST)) {
            showQuestion($questions);
        }
        else {
            $answers = getStudentAnswer($questions);
            $co_answers = getAnswer($_GET['quizid']);
            $grade = getGrade($answers, $co_answers, $questions);
            if ($grade == 100) {
                echo "Congrates, you got " . $grade . " on this quiz!"; 
            }
            else {
                echo "The mark you got is: " . $grade;
            }
            addTakesToDB($student, $_GET['quizid'], $grade);
        }
    }
    ?>
    
</body>
</html>

<?php

function addTakesToDB($student, $quizid, $grade){
    $date = date("d/m/Y");
    $sql = "INSERT INTO Takes (Student_ID, Student_Forename, Student_Surname, Quiz_ID, Date_of_Attempt, Grade)
    VALUES (:sid, :sf, :ss, :qid, :date, :grade)";

    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
	$stmt->execute([
    'sid' => $student['Student_ID'],
    'sf' => $student['Student_Forename'],
    'ss' => $student['Student_Surname'],
	'qid' => $quizid,
    'date' => $date,
    'grade' => $grade
	]);
}

function getGrade($answers, $co_answers, $questions) {
    $total = count($co_answers);
    $correct = 0;
    foreach ($questions as $row) {
        $questionid = $row['Question_ID'];
        if ($answers[$questionid] == $co_answers[$questionid]) {
            $correct += 1;
        }
    }
    $grade = (int)(100 * ($correct / $total));
    return $grade;
}

function getAnswer($quizid) {
    $sql = "SELECT * FROM Answer WHERE Quiz_ID = :qid";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
	$stmt->execute([
	'qid' => $quizid
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $answers = array();
    while ($row = $stmt->fetch()) {
        $questionid = $row['Question_ID'];
        $answers[$questionid] = $row['Answer'];
    }
    return $answers;
}

function getStudentAnswer($questions) {
    $answers = array();
    foreach($questions as $row) {
        $questionid = $row['Question_ID'];
        $answers[$questionid] = $_POST[$questionid];
    }
    return $answers;
}

function showQuestion($questions) {
    echo "hint: Please put the correct answer in the input<br>";
    echo 'Total question number: ' . count($questions);
    echo '<br>Questions: <br><form method="POST">';
    foreach($questions as $row) {
        $questionid = $row['Question_ID'];
        $question = $row['Question'];
        $quizid = $row['Quiz_ID'];
        $option1 = $row['Option_1'];
        $option2 = $row['Option_2'];
        $option3 = $row['Option_3'];
        $option4 = $row['Option_4'];
        echo '<br>' . $questionid;
        echo '. ' . $question;
        echo '<br>A. ' . $option1;
        echo '<br>B. ' . $option2;
        echo '<br>C. ' . $option3;
        echo '<br>D. ' . $option4;
        echo '<br><input type="text" name=' . $questionid;
        echo '><br>';

    }
    echo '<input type="submit" value="Submit"></form>';
}

function getStudent($username) {
    $sql = "SELECT * FROM Student WHERE Username = :un";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
	$stmt->execute([
	'un' => $username
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
        return $row;
    }
}


function getQuestions($quizid) {
    $sql = "SELECT * FROM Question WHERE Quiz_ID = :qid";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
	$stmt->execute([
	'qid' => $quizid
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $question = array();
    while ($row = $stmt->fetch()) {
        $questionid = $row['Question_ID'];
        $question[$questionid] = $row;
    }
    return $question;
}

function getQuiz($qid) {
    $sql = "SELECT * FROM Quiz WHERE Quiz_ID = :qid";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $stmt = $pdo->prepare($sql);
	$stmt->execute([
	'qid' => $qid
	]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $stmt->fetch()) {
        return $row;
    }
}

function creatTakeTable() {
    $sql = "
    CREATE TABLE IF NOT EXISTS `Takes` (
		`Take_No` MEDIUMINT NOT NULL AUTO_INCREMENT,
        `Student_ID` int NOT NULL,
        `Student_Forename` varchar(20) NOT NULL,
        `Student_Surname` varchar(20) NOT NULL,
        `Quiz_ID` int NOT NULL,
        `Date_of_Attempt` varchar(20),
        `Grade` int NOT NULL,
        PRIMARY KEY (`Take_No`),
        FOREIGN KEY (`Quiz_ID`) REFERENCES Quiz(`Quiz_ID`),
        FOREIGN KEY (`Student_ID`) REFERENCES Student(`Student_ID`)
    ) DEFAULT CHARSET=utf8;    
    ";
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$pdo->query($sql);
}
?>
