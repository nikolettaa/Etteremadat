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
require_once('connectvars.php');
session_start();
  
  $error = "";
  
  if (!isset($_SESSION['user_id'])){
	if (isset($_POST['submit'])){
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$name = mysqli_real_escape_string($dbc, $_POST['name']);
		$password = hash('sha256', mysqli_real_escape_string($dbc, $_POST['pwd']));
		if (!empty($name) && !empty($password)){
			$query = "SELECT id FROM user WHERE username = '$name' AND password = '$password'";
			$result = mysqli_query($dbc, $query);
			mysqli_close($dbc);
			if (mysqli_num_rows($result) == 1){
				$row = mysqli_fetch_array($result);
				$_SESSION['user_id'] = $row['id'];
				$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/frontadd.php';
				header('Location: ' . $home_url);
			}
			else $error = '<div class="err">A felhasználónév vagy a jelszó helytelen.</div>';
		}
		else $error = '<div class="err">A belépéshez meg kell adnod a felhasználóneved és a jelszavad.</div>';
	}
  }
  else 	{$enter_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/frontadd.php';
		header('Location: ' . $enter_url);
		}

  if (empty($_SESSION['user_id'])){
	echo $error;
  ?> 
	<div class="container form-cont">
	<h1>Belépés</h1>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div class="form-group">
		  <label for="usr">Név:</label>
		  <input type="text" class="form-control" placeholder="Írd be a felhasználóneved" id="usr" name="name">
		</div>
		<div class="form-group">
		  <label for="pwd">Jelszó:</label>
		  <input type="password" placeholder="Írd be a jelszavad" class="form-control" id="pwd" name="pwd">
		</div>
		<button type="submit" name="submit" class="btn btn-default">Belépés</button>
	</form>
	</div>

<?php } ?>
</body>
</html>