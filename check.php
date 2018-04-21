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
require_once('connectvars.php');
session_start();
 if (!isset($_SESSION['user_id'])) {
		$enter_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/enter.php';
		header('Location: ' . $enter_url);
  }
else if(isset($_POST['submit'])){
	$check = $_POST['check'];
	if(!empty($check[0]) && !empty($check[1])){
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		mysqli_set_charset($dbc, "utf8");
		for($i = 1; $i<count($check); $i++){
			$query = "INSERT INTO menu (menu_szam, nev_id) VALUES ('$check[0]', '$check[$i]')";
			mysqli_query($dbc, $query) or die(mysqli_error($dbc));
		}
		mysqli_close($dbc);
		echo '<div class="ok">Hozzáadás megtörtént.</div><script>setTimeout(function(){document.location.href = "addmenu.php";}, 1800);</script>';
	}
	else echo '<div class="error">Hiányzik a menü száma vagy az étel!<br><br><a href="javascript:history.back()"><button class="btn btn-default">Vissza</button></a></div>';
}
?>
</body>
</html>