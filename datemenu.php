<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"> 
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('connectvars.php');

 if (!isset($_SESSION['user_id'])) {
		$enter_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/enter.php';
		header('Location: ' . $enter_url);
	}
	else if (isset($_POST['submit'])){
		$date = $_POST['date'];
		$menunum = $_POST['menunum'];
		if(!empty($menunum) && !empty($date)){
			$query = "INSERT INTO etlap (datum, menu_szam) VALUES ('$date', '$menunum')";
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			mysqli_query($dbc, $query) or die(mysqli_error($dbc));
			mysqli_close($dbc);
			echo '<div class="ok">Hozzáadás megtörtént.</div>';
		}
		else echo '<div class="error">Minden mezőt tölts ki!</div>';
	}
?>
<script>setTimeout(function(){document.location.href = "menucomp.php";}, 1800);</script>
</body>
</html>