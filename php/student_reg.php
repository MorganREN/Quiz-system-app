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
			width: 40em;
		}
	</style>
</head>
<body>
    <div id="wrapper">
        <h2>Complete your information</h2>

        <?php  
        if (empty($_POST)) {
            echo showStudentForm();
        }
        else{
            $student = getStudentInfo();
            addStudenttoDB($student);
            goLogin();
        }
        ?>
    </div>
</body>
</html>

<?php

function goLogin() {
    echo "
    <a href='login.php'>
        <button>Go To Login</button>
    </a>
    ";
}

function getStudentInfo() {
	$student = array();
	$student['studentid'] = $_POST['studentid'];
	$student['forename'] = $_POST['forename'];
	$student['surname'] = $_POST['surname'];
    $student['username'] = $_GET['username'];
	return $student;
}

function showStudentForm() {
	return '
		<form method="POST">

			<label class="form-label" for="studentid">Student ID</label>
			<input class="form-control" type="text" name="studentid">

			<label class="form-label"  for="forename">Forename</label>
			<input class="form-control"  type="text" name="forename">

			<label class="form-label"  for="surname">Surname</label>
			<input class="form-control"  type="text" name="surname">

			<input type="submit" value="Submit"><br>

		</form>
	';
}

function addStudenttoDB($student){

	$pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$studentid = $student['studentid'];
    $forename = $student['forename'];
    $surname = $student['surname'];
    $username = $student['username'];

	$sql = "INSERT INTO Student(Student_ID, Student_Forename, Student_Surname, Username)
		 		VALUE (:studentid, :forename, :surname, :username)";

	$pdo = $pdo->prepare($sql);

	$pdo->execute([
        ':studentid' => $studentid,
        ':forename' => $forename,
        ':surname' => $surname,
        ':username' => $username
    ]);

    echo "$studentid is added!";
}
?>