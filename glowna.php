<?php
  session_start();
  if(!isset($_SESSION['zalogowany'])){
    header("Location: http://localhost/zad/index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
  <head>
  <style>
 
td{        
padding-right: 2em;
}
 
</style>
    <meta charset="utf-8">
    <title>Moja strona WWW</title>
  </head>
  <body>
    <p>Witamy zalogowanego użytkownika.</p>
    <p>Pamiętaj o wylogowaniu przed opuszczeniem strony.</p>
    <p><a href="http://localhost/zad/logout.php">Wylogowanie</a></p>
	<div>
	 <form  action=" ">
			<label for="start">Wybierz zakres od:</label>
			<input type="date" id="start" name="start"
				value=<?php echo isset($_GET["start"]) ? $_GET["start"] : ""?>
				min="2018-01-01" max=<?php echo date("Y-m-d")?>>
				
			<label for="end">do:</label>
			<input type="date" id="end" name="end"
				value=<?php echo isset($_GET["end"]) ? $_GET["end"] : ""?>
				min="2018-01-01" max=<?php echo date("Y-m-d")?>>
					
			
			<input type="submit" value="OK">
		</form>
	</div>
	
	<?php
  if (!$db = mysqli_connect("localhost", "root", "bazka")){
    exit('Wystąpił błąd podczas próby połączenia z serwerem MySQL...<br>');
  }

  if(!mysqli_select_db($db, 'zamowienia')){
    echo 'Wystąpił błąd podczas wyboru bazy danych: zamówiena<br>';
  }

	
	if (isset($_GET["start"]) && isset($_GET["end"])) {
		$t = strtotime($_GET["start"]);
		//echo $t;
		//echo strtotime($_GET["start"]);
		$e = strtotime($_GET["end"]);
		
		$m = date("m", $t);
		//echo $m;
		$r = date("Y", $t);
		$d = date("d", $t);
		
		$m1 = date("m", $e);
		//echo $m1;
		$r1 = date("Y", $e);
		$d1 = date("d", $e);
		$date1 = date("Y-m", $t);

		
		$wynik =" ";
		if (($r ==$r1) && ($m == $m1)) {
		$query = "SELECT firma FROM {$m}_{$r} WHERE data>={$d} AND data<={$d1} GROUP BY firma";

		} else {
		for ($date = strtotime("+1 month",  mktime(0,0,0, $m, 1, $r)); $date <= $e; $date = strtotime("+1 month ",  $date)) {

		
		 $mies = date("m", $date);
		 $rok = date("Y", $date);
		 $wynik .= "UNION SELECT firma FROM {$mies}_{$rok} ";
		}

		$query = "SELECT firma FROM {$m}_{$r} WHERE data>={$d} {$wynik}  WHERE data<={$d1} GROUP BY firma ORDER BY firma";

		}
		if(!$result = mysqli_query($db, $query)){
			mysqli_close($db);
			echo 'Wystąpił błąd: nieprawidłowe zapytanie...<br>';
			echo '</body></html>';
			exit();
		}

	
	};
	if (isset($_POST["firma"])) {
		$f = $_POST["firma"];
		$wynik1 = " ";
		for ($date = strtotime("+1 month",  mktime(0,0,0, $m, 1, $r)); $date <= $e; $date = strtotime("+1 month",  $date)) {
		//echo date("Y-m-d", $date)."<br />";
		 $mies1 = date("m", $date);
		 $rok1 = date("Y", $date);
		 $wynik1 .= "UNION ALL SELECT * FROM {$mies1}_{$rok1} WHERE firma='$f' ";

		}
		
		$query1 = "SELECT * FROM {$m}_{$r} WHERE firma='$f' AND data>={$d} {$wynik1} AND data<={$d1}";
		$klery= "SELECT sum(N) from {$m}_{$r}";

		
		if(!$result1 = mysqli_query($db, $query1)){
			mysqli_close($db);
			echo 'Wystąpił błąd: nieprawidłowe zapytanie...<br>';
			echo '</body></html>';
			exit();
    }
	if(!$result2 = mysqli_query($db, $klery)){
			mysqli_close($db);
			echo 'Wystąpił błąd: nieprawidłowe zapytanie...<br>';
			echo '</body></html>';
			exit();
    }
		
	};
?>
<div>
	
		<form method="post" action=" ">
  <label for="firma">Wybierz firmę:</label>
  <select name="firma" id="firma" style='width:30em;'>
  <?php
  while($row = mysqli_fetch_row($result)){
        echo "<option value='$row[0]'";
		echo (isset($_POST["firma"]) && ($_POST["firma"] ==$row[0]) ) ? "selected>$row[0]" : ">$row[0]";
		echo "</option>";
		echo $row[0];
		}
	
	//if(!mysqli_close($db)){
    //echo 'Wystąpił błąd podczas zamykania połączenia z serwerem MySQL...<br>';
  //}
	?>
  </select>
  <input type="submit" value="Wyszukaj">
</form>

	</div>


 <?php
	if (isset($_POST["firma"])) {
		$_SESSION['firma'] = $_POST["firma"];
		echo "<div><p><a href='http://localhost/zad/kontakty.php' target='_blank'>Dane kontaktowe</a></p></div>";
		echo $_POST["firma"];
		echo "<table>
	<tr style='font-weight:bold;'>
		<td>Data</td>
		<td>Faktura</td>";
		echo ($r==2018 || $r1 ==2018) ? " " : "<td>Status</td>";
		echo "<td>Firma</td>
		<td>Produkt</td>
		<td>Opis produktu</td>
		<td>Sprzedaż netto</td>
		<td>Koszt netto</td>
		<td>Wykonawca</td>
		
	</tr>";

		$sum = 0;
		$sum1 = 0.00;
	 	while($row1 = mysqli_fetch_row($result1)){
        echo "<tr>";
		echo "<td>$row1[0]</td>";
		echo "<td>$row1[1]</td>";
		echo "<td>$row1[2]</td>";
		echo "<td>$row1[3]</td>";
		echo "<td>$row1[4]</td>";
		echo "<td>$row1[5]</td>";
		$row1[6] = str_replace(',' , '.' , $row1[6]);
		echo "<td>$row1[6]</td>";
		$row1[7] = str_replace(',' , '.' , $row1[7]);
		echo "<td>$row1[7] </td>";
		echo "<td>$row1[8]</td>";
		echo (isset($row1[9])) ? "<td>$row1[9]</td>" : " ";
		echo "<td>";
		echo is_numeric($row1[9]);
		echo "</td>";
		echo "</tr>";
		 
		$sum += floatval($row1[6]);
		//$sum1 +=str_replace(',' , '.' , $row1[7]);	
		$sum1 += floatval($row1[7]);
	}
	echo "<tr>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td style='font-weight:bold; text-align: right; padding-right: 4em; ' >Suma</td>";
		echo "<td>$sum</td>";
		echo "<td>$sum1</td>";
		echo "<td>";
		//echo is_numeric($sum);
		echo "</td>";
	echo "<tr>";	
	echo "</table>";
	}
?>

<?php		
	 
	 if(!mysqli_close($db)){
    echo 'Wystąpił błąd podczas zamykania połączenia z serwerem MySQL...<br>';
  }
 ?>
 
	
  </body>
</html>
