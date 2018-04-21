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
				<h1>Étlap dátum szerint</h1>
<?php
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	mysqli_set_charset($dbc, "utf8");
	$query0 = "SELECT menu.menu_szam FROM etel_tipus, etelek, menu WHERE etel_tipus.id = etelek.tipus_id AND menu.nev_id = etelek.id GROUP BY menu_szam;";
	$query1 = "SELECT etlap.datum, etel_tipus.tipus, etelek.nev, etelek.ar FROM etel_tipus, etelek, menu, etlap WHERE etel_tipus.id = etelek.tipus_id AND menu.nev_id = etelek.id AND etlap.menu_szam = menu.menu_szam ORDER BY etlap.datum;";
	$query2 = "SELECT etlap.datum, etlap.menu_szam FROM etel_tipus, etelek, menu, etlap WHERE etel_tipus.id = etelek.tipus_id AND menu.nev_id = etelek.id AND etlap.menu_szam = menu.menu_szam GROUP BY etlap.datum ORDER BY etlap.datum DESC;";
	$query3 = "SELECT etel_tipus.tipus, etelek.tipus_id FROM etel_tipus, etelek WHERE etel_tipus.id = etelek.tipus_id Group BY etel_tipus.tipus ORDER BY `etelek`.`tipus_id` ASC;";
	$result0 = mysqli_query($dbc, $query0) or die(mysqli_error($dbc));
	$result1 = mysqli_query($dbc, $query1) or die(mysqli_error($dbc));
	$result2 = mysqli_query($dbc, $query2) or die(mysqli_error($dbc));
	$result3 = mysqli_query($dbc, $query3) or die(mysqli_error($dbc));
	mysqli_close($dbc);
	$data0 = array();
	$data = array();
	$date = array();
	$tipusok = array();
	while($row0 = mysqli_fetch_array($result0)){
		array_push($data0, $row0);
	}
	while($row = mysqli_fetch_array($result1)){
		array_push($data, $row);	
	}
	while($rows = mysqli_fetch_array($result2)){
		array_push($date, $rows);	
	}
	while($row3 = mysqli_fetch_array($result3)){
		array_push($tipusok, $row3);	
	}
	$tip = "";
	$num = 1;
	for($i = 0; $i < count($date); $i++){
		echo '<div class="toggletitle" data-toggle="collapse" data-target="#collapse'.$num.'"><div>[' . $date[$i]['datum'] . '] ' . $date[$i]['menu_szam'] . '. menü</div></div><div id="collapse'.$num.'" class="collapse"><a href="/etteremadat/del3.php?num='.$date[$i]['datum'].'" title="'.$date[$i]['datum'].' menü törlése" onclick="return confirm(\'Biztosan törli?\');"><button type="button" name="delete" class="btn btn-danger btn-s2">Menü törlése</button></a>';
		$num++;
		for($t=0;$t<count($tipusok); $t++){
			foreach($data as $tomb){
				if ($tomb['datum'] == $date[$i]['datum'] && $tipusok[$t]['tipus'] == $tomb['tipus']){
					if ($tip !== $tipusok[$t]['tipus']){
						echo '<div class="toggletip">' . $tipusok[$t]['tipus'] . '</div>';
						$tip = $tipusok[$t]['tipus'];
					}
				echo '<div class="toggleitem">' . $tomb['nev'] . ' --- ' . $tomb['ar'] . ' Ft</div>';
				}
			}
		}
	echo '</div>';
	}
  }
?>
			</div>
			<div class="col-sm-3 right">
				<h1>Menü kiválasztása</h1>
				<form action="datemenu.php" method="post">
					<div class="form-group">
					  <label for="date">Dátum:</label>
					  <input type="date" class="form-control" name="date" id="date">
					 </div>
					  <?php
						echo '<div class="form-group">
						<label for="select">Menüszám:</label><br>
						<select class="form-control" id="menunum" name="menunum">';
						foreach($data0 as $menunum){
						echo '<option value="'. $menunum['menu_szam'] .'">' .$menunum['menu_szam']. '</option>';
						}	
					  ?>
					</select></div>
					<button type="submit" name="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</div>
			
	</div>