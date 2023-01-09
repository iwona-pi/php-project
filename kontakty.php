<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Moja strona WWW</title>
  </head>
   <style>
 
td{        
padding-right: 2em;
}
 
</style>
  <body>
   <?php
	if (!$db = mysqli_connect("localhost", "root", "bazka")){
    exit('Wystąpił błąd podczas próby połączenia z serwerem MySQL...<br>');
  }

  if(!mysqli_select_db($db, 'zamowienia')){
    echo 'Wystąpił błąd podczas wyboru bazy danych: zamówiena<br>';
  }
	$f = $_SESSION["firma"];
	$check = "SELECT * FROM kontakty WHERE Firma='$f' ";
	
	if(!$result5 = mysqli_query($db, $check)){
			mysqli_close($db);
			exit ('Wystąpił błąd: nieprawidłowe zapytanie...<br>');
			
	}
	$fi = false;
	$os = false;
	$te = false;
	$em = false;
	while($row = mysqli_fetch_row($result5)){
        
		echo $row[0];
		$fi = $row[0];
		$os =$row[1];
		$te = $row[2];
		$em = $row[3];
		} 
	
		
		
	
?> 
  
	
<div><table>
	<form method="post" action=" ">
	<tr><td>Nazwa firmy:</td>
		<td><?php echo $_SESSION['firma'];?></td>
	</tr>
	<tr><td><label for="osoba">Osoba do kontaktu:</label></td>
		<td>
  <input type="text" id="osoba" name="osoba" value=<?php echo ( (isset ($_POST["osoba"]) ? $_POST["osoba"] :( ($os)? $os : ""))); ?>></td>
	</tr>
	<tr><td><label for="telefon">Telefon:</label></td>
	<td><input type="text" id="telefon" name="telefon" value=<?php echo ((isset ($_POST["telefon"]) ? $_POST["telefon"] :( ($te)? $te : ""))); ?>></td></tr>
	<tr><td><label for="email">E-mail:</label></td>
	<td><input type="text" id="email" name="email" value=<?php echo ((isset ($_POST["email"]) ? $_POST["email"] :( ($em)? $em : ""))); ?>></td></tr>
	<tr><td></td><td style='text-align: right;'><input type="submit"  value="Zapisz"></td></tr>
	</form>
	</table>
	</div>
<?php
if ($fi || $os || $te || $em){
		if (isset ($_POST["osoba"]) || isset ($_POST["telefon"]) || isset ($_POST["email"])){
		$o = $_POST["osoba"];
		$t = $_POST["telefon"];
		$e = $_POST["email"];
		$uaktualnij = "UPDATE kontakty SET `Osoba do kontaktu`='$o', Telefon = '$t', `e-mail` = '$e' 
					   WHERE Firma = '$f' ";
					   
		if(!$result5 = mysqli_query($db, $uaktualnij)){
			mysqli_close($db);
			exit ('Wystąpił błąd: nieprawidłowe zapytanie...<br>');
			
	}
		
	}}	
	 else {
		 if (isset ($_POST["firma"]) || isset ($_POST["osoba"]) || isset ($_POST["telefon"]) || isset ($_POST["email"])){
			$f = $_SESSION["firma"];
			$o = $_POST["osoba"];
			$t = $_POST["telefon"];
			$e = $_POST["email"] ;
		$zapytanie = "INSERT INTO kontakty (Firma, `Osoba do kontaktu`, Telefon, `e-mail`) 
					VALUES ('$f', '$o', '$t', '$e')";
					
	if(!$result4 = mysqli_query($db, $zapytanie)){
			mysqli_close($db);
			exit ('Wystąpił błąd: nieprawidłowe zapytanie...<br>');
		
	 }}
	
	
	}	
	
	
	
?>
	
  </body>
</html>