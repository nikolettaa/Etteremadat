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
			<div class="row addmenu">
				<div class="col-sm-5 col-sm-offset-2 left">
				<h1>Menüösszeállítás</h1>
<?php
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	mysqli_set_charset($dbc, "utf8");
	$query0 = "SELECT menu.menu_szam FROM etel_tipus, etelek, menu WHERE etel_tipus.id = etelek.tipus_id AND menu.nev_id = etelek.id GROUP BY menu_szam;";
	$query1 = "SELECT menu.menu_szam, menu.id,  etel_tipus.tipus, etelek.nev, etelek.ar FROM etel_tipus, etelek, menu WHERE etel_tipus.id = etelek.tipus_id AND menu.nev_id = etelek.id ORDER BY menu_szam;";
	$query2 = "SELECT etel_tipus.tipus, etelek.tipus_id FROM etel_tipus, etelek WHERE etel_tipus.id = etelek.tipus_id Group BY etel_tipus.tipus ORDER BY `etelek`.`tipus_id` ASC;";
	$result0 = mysqli_query($dbc, $query0) or die(mysqli_error($dbc));
	$result1 = mysqli_query($dbc, $query1) or die(mysqli_error($dbc));
	$result2 = mysqli_query($dbc, $query2) or die(mysqli_error($dbc));
	$tipusok = array();
	$tip = '';
	$num = 1;
	$data0 = array();
	$data1 = array();
	while($row0 = mysqli_fetch_array($result0)){
		array_push($data0, $row0);
	}
	while($row1 = mysqli_fetch_array($result1)){
		array_push($data1, $row1);
	}
	while($row2 = mysqli_fetch_array($result2)){
		array_push($tipusok, $row2);
	}
	foreach($data0 as $menunum){
		echo '<div class="toggletitle" data-toggle="collapse" data-target="#collapse'.$num.'"><div>' .$menunum['menu_szam'].'. menü </div></div><div id="collapse'.$num.'" class="collapse"><a href="/etteremadat/del2.php?num='.$menunum['menu_szam'].'" title="'.$menunum['menu_szam'].'. menü törlése" onclick="return confirm(\'Biztosan törli?\');"><button type="button" name="delete" class="btn btn-danger btn-s2">Menü törlése</button></a>';
		$num++;
		for($i=0;$i<count($tipusok); $i++){
			foreach($data1 as $tomb1){
				if ($tomb1['menu_szam'] == $menunum['menu_szam'] && $tipusok[$i]['tipus'] == $tomb1['tipus']){
					if ($tip !== $tipusok[$i]['tipus']){
						echo '<div class="toggletip">' . $tipusok[$i]['tipus'] . '</div>';
						$tip = $tipusok[$i]['tipus'];
					}
					echo  '<div class="toggleitem">' . $tomb1['nev'] . ' --- ' . $tomb1['ar'] . ' Ft <a href="/etteremadat/del2.php?num2='.$tomb1['id'].'" title="Elem törlése" onclick="return confirm(\'Biztosan törli?\');"><button type="button" name="delete" class="btn btn-danger btn-s">X</button></a></div>';
				}
			}
		}
	echo '</div>';
	}
	echo '</div><div class="col-sm-3 col-sm-offset-2 right">
			<form method="post" action="check.php"><h1>Menü hozzáadása</h1>
			<div class="form-group"><label for="check[]">Menü száma</label><br><input type="number" class="form-control" name="check[] min="1" max="99""></div>';
	$query3 = "SELECT etelek.id, etel_tipus.tipus, etelek.nev FROM etelek INNER JOIN etel_tipus ON etel_tipus.id = etelek.tipus_id;";
	$result3 = mysqli_query($dbc, $query3) or die(mysqli_error($dbc));
	mysqli_close($dbc);
	$data2 = array();
	$tip = '';
	while($row3 = mysqli_fetch_array($result3)){
		array_push($data2, $row3);
	}	
	for($i=0;$i<count($tipusok); $i++){
		foreach($data2 as $tomb2){
			if ($tipusok[$i]['tipus'] == $tomb2['tipus']){
				if ($tip !== $tipusok[$i]['tipus']){
					echo '<h2>' . $tipusok[$i]['tipus'] . '</h2><div class="checkbox">';
					$tip = $tipusok[$i]['tipus'];
				}
				echo '<label><input type="checkbox" name="check[]" value="'. $tomb2['id'] .'">' . $tomb2['nev'] . '</label><br>';
			}				
		}
	}
	echo'</div><button type="submit" name="submit" class="btn btn-default">Submit</button></form>
				</div>
			</div>
		</div>';
	}
?>
</body>
</html>