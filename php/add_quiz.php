<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>addTest</title>
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
    <h1>Add Test</h1>
    <?php
    $authorid = getAuthorID($_GET['username']);
    if (empty($_POST)) {
        echo showQuizForm();
    }
    else {
        global $quiz;
        $quiz = getQuizCredentials($authorid);
        $index = 1;
        echo goAddQ($quiz['quizid'],$quiz['quizname'],$quiz['authorid'],$quiz['duration'],$quiz['astatus'],$index);
    }
    ?>
</body>
</html>

<?php

function getQuizCredentials($authorid) {
    $quiz = array();
    $quiz['quizid'] = rand(100000, 999999);
    $quiz['quizname'] = $_POST['quizname'];
    $quiz['authorid'] = $authorid;
    $quiz['duration'] = $_POST['duration'];
    $quiz['astatus'] = $_POST['astatus'];
    return $quiz;
}

function goAddQ($quizid,$quizname,$authorid,$duration,$astatus,$index) {
    return "
    <a href='add_Question.php?quizid=$quizid&quizname=$quizname&authorid=$authorid&duration=$duration&astatus=$astatus&index=$index'>
        <button>Add Question</button>
    </a>
    ";
}

function showQuizForm(){
    return '
        <form method="POST">

			<label class="form-label" for="quizname">Quiz Name</label>
			<input class="form-control" type="text" name="quizname">

			<label class="form-label" for="astatus">Available Status</label>
		    <input type="radio" class="form-control" name="astatus" value="yes">Available
		    <input type="radio" class="form-control" name="astatus" value="no">Not Available
            <br>
            <label class="form-label" for="duration">Estimate duration(Unit: hour)</label>
		    <input class="form-control" type="text" name="duration">

			<input type="submit" value="Submit"><br>

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
?>