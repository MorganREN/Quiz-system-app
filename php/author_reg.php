<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Form</title>
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
        echo showStaffForm();
    }
    else{
        $author = getAuthorInfo();
        addAuthortoDB($author);
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

function addAuthortoDB($author) {
    $pdo = new pdo('mysql:host=localhost;dbname=m33394mr', 'root', 'root');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $authorid = $author['authorid'];
    $authorname = $author['authorname'];
    $username = $author['username'];

    $sql = "INSERT INTO Author(Author_ID, Author_name, Username)
		 		VALUE (:authorid, :authorname, :username)";

		$pdo = $pdo->prepare($sql);

		$pdo->execute([
			':authorid' => $authorid,
			':authorname' => $authorname,
			':username' => $username
		]);

    echo "$authorname is added!";
}

function showStaffForm() {
	return '
		<form method="POST">

			<label class="form-label" for="authorid">Staff ID</label>
			<input class="form-control" type="text" name="authorid">

			<label class="form-label"  for="authorname">Staff name</label>
			<input class="form-control"  type="text" name="authorname">
			<input type="submit" value="Submit"><br>

		</form>
	';
}

function getAuthorInfo() {
	$author = array();
	$author['authorid'] = $_POST['authorid'];
	$author['authorname'] = $_POST['authorname'];
	$author['username'] = $_GET['username'];
	return $author;
}
?>