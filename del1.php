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
  else if(isset($_GET['num'])){
	  $delete = $_GET['num'];
	  if(!empty($delete)){
		  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		  $query = "DELETE FROM etelek WHERE id = '$delete';";
		  mysqli_query($dbc, $query) or die(mysqli_error($dbc));
		  mysqli_close($dbc);
		  echo '<div class="ok">Elem törölve.</div>';
		}
  }
?>
</body>
<script>setTimeout(function(){document.location.href = "frontadd.php";}, 2000);</script>
</html>