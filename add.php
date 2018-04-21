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
define('MAXSIZE', 400000);
 if (!isset($_SESSION['user_id'])) {
		$enter_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/enter.php';
		header('Location: ' . $enter_url);
  }
	else if (isset($_POST['submit'])){
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		mysqli_set_charset($dbc, "utf8");
		$nev = mysqli_real_escape_string($dbc, $_POST['nev']);
		$tipus = mysqli_real_escape_string($dbc, $_POST['tipus']);
		$ar = mysqli_real_escape_string($dbc, $_POST['ar']);
		$img = $_FILES['img']['name'];
		$img_type = $_FILES['img']['type'];
		$img_size = $_FILES['img']['size']; 
		
		if (!empty($nev) && !empty($tipus) && !empty($ar) && !empty($img)){
			if ((($img_type == 'image/gif') || ($img_type == 'image/jpeg') || ($img_type == 'image/pjpeg') || ($img_type == 'image/png'))
				&& ($img_size > 0) && ($img_size <= MAXSIZE)) {
				if ($_FILES['img']['error'] == 0) {
					$target = 'images/' . $img;
					if (move_uploaded_file($_FILES['img']['tmp_name'], $target)) {
						copy("images/$img", "../etterem/images/$img");
						$query = "INSERT INTO etelek (nev, tipus_id, ar, kep) VALUES ('$nev', '$tipus', '$ar', '$img')";
						mysqli_query($dbc, $query) or die(mysqli_error($dbc));
						mysqli_close($dbc);
						echo '<div class="ok">Hozzáadás megtörtént.</div><script>setTimeout(function(){document.location.href = "frontadd.php";}, 1800);</script>';
					}
					else echo '<div class="error">Hiba történt a kép feltöltésekor<br><br><a href="javascript:history.back()"><button class="btn btn-default">Vissza</button></a></div>';
				}
			}
			else echo '<div class="error">A kép PNG, JPEG és GIF formátumú lehet, ill. nem lehet nagyobb, mint ' .(MAXSIZE/1024). ' KB!<br><br><a href="javascript:history.back()"><button class="btn btn-default">Vissza</button></a></div>';
		}
		else echo '<div class="error">Minden mezőt tölts ki!<br><br><a href="javascript:history.back()"><button class="btn btn-default">Vissza</button></a></div>';
	}
?>

</body>
</html>