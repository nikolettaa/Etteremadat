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
  else {
?>
<div class="nav"><a href="out.php" class="out">Kijelentkezés</a>
<ul>
<li><a href="frontadd.php">Étel hozzáadása</a></li>
<li><a href="addmenu.php">Menü hozzáadása</a></li>
<li><a href="menucomp.php">Étlap összeállítása</a></li>
</ul>
</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-5 col-sm-offset-2 left">
				<h1>Teljes kínálat</h1>
				<?php 
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
					mysqli_set_charset($dbc, "utf8");
					$query = "SELECT etelek.id, etel_tipus.tipus, etelek.nev, etelek.ar, etelek.kep FROM etelek INNER JOIN etel_tipus ON etel_tipus.id = etelek.tipus_id;";
					$result = mysqli_query($dbc, $query) or die(mysqli_error($dbc));
					$query2 = "SELECT etel_tipus.tipus, etelek.tipus_id FROM etel_tipus, etelek WHERE etel_tipus.id = etelek.tipus_id Group BY etel_tipus.tipus ORDER BY `etelek`.`tipus_id` ASC;";
					$result2 = mysqli_query($dbc, $query2) or die(mysqli_error($dbc));
					$query3 = "SELECT * FROM etel_tipus;";
					$result3 = mysqli_query($dbc, $query3) or die(mysqli_error($dbc));
					mysqli_close($dbc);
					$data = array();
					$tipusok = array();
					while($row = mysqli_fetch_array($result)){
							array_push($data, $row);	
					}
					while($row2 = mysqli_fetch_array($result2)){
							array_push($tipusok, $row2);	
					}
						$tip = "";
						$num = 1;
						for($i=0;$i<count($tipusok); $i++){
							foreach($data as $tomb){
								if ($tipusok[$i]['tipus'] == $tomb['tipus']){
									if ($tip !== $tipusok[$i]['tipus']){
									echo '<div class="toggletitle" data-toggle="collapse" data-target="#collapse'.$num.'"><div>' . $tipusok[$i]['tipus'] . '</div></div><div id="collapse'.$num.'" class="collapse">';
									$tip = $tipusok[$i]['tipus'];
									}
									echo '<div class="toggleitem">';
									if (is_file('images/'.$tomb['kep']) && filesize('images/'.$tomb['kep']) > 0) {
										echo '<a href="images/'.$tomb['kep'].'" target="_blank"><img class="float-left" width="40px" height="30px" src="images/'.$tomb['kep'].'"></a>';
									}
									else echo '<img width="40px" height="30px" src="images/no-img.jpg"> ';
									echo $tomb['nev'] . ' --- ' . $tomb['ar'] . ' Ft <a href="/etteremadat/del1.php?num='.$tomb['id'].'" title="Elem törlése" onclick="return confirm(\'Biztosan törli?\');"><button type="button" name="delete" class="btn btn-danger btn-s">X</button></a></div>';
								}
							}
							echo '</div>';
							$num++;
						}
 ?>
			</div>
		<div class="col-sm-3 right">
			<h1>Étel/ital hozzáadása</h1>
		<form enctype="multipart/form-data" action="add.php" method="post">
			<div class="form-group">
			  <label for="nev">Megnevezés:</label>
			  <input type="text" class="form-control" name="nev" id="nev">
			</div>
			<div class="form-group">
			  <label for="sel1">Típus:</label>
			  <select class="form-control" id="tipus" name="tipus">
			  <?php $addtip = array();
					while($addtip = mysqli_fetch_array($result3)){
						echo '<option value="'.$addtip['id'].'">'.$addtip['tipus'].'</option>';
					}
				?>
			  </select>
			</div>
			<div class="form-group">
			  <label for="ar">Ár:</label>
			  <input type="number" class="form-control" name="ar" id="ar">
			</div>
			<div class="form-group">
				<label for="img">Kép:</label>
				<input type="file" id="img" name="img" />
			</div>
			<button type="submit" name="submit" class="btn btn-default">Submit</button>
		</form>
			</div>
		</div>
	</div>
<?php	
	} ?>
</body>
</html>