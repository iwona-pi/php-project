<?php
  session_start();
  
  
  function check($user, $pass) {
	  if(!$fd = fopen("C:/xampp/htdocs/zad/users/users.txt", "a+")) {
		  
		 return false;
	  } 
		$result = false;
		$users = file("C:/xampp/htdocs/zad/users/users.txt", FILE_IGNORE_NEW_LINES);
		$liczUz = count($users);
		$maxLiczUz = 5;
		//$users = array("PPPP");
		if ($pass == "a") {
				if (in_array($user, $users, true)) {
					$result =true;
					
				} else {
					if ($liczUz<=$maxLiczUz){
					$newUser = trim($user);
					$newUser .= "\r\n";
				//file_put_contents($fd, $newUser, FILE_APPEND);
					fwrite($fd, $newUser);
				//fwrite($fd, $l);
				$result = true;
					}}
			}
		 else {
			$result = false;
		}			
	  
	  fclose($fd);
	  return $result;
  }
  
  if(isset($_SESSION['zalogowany'])){
    header("Location: http://localhost/zad/glowna.php");
    exit();
  }
  else if(isset($_POST['user']) && isset($_POST['haslo'])){
    if(check($_POST['user'], $_POST['haslo'])){
      $_SESSION['zalogowany'] = 'user1';
      header("Location: http://localhost/zad/glowna.php");
      exit();
    }
    else{
      $_SESSION['komunikat'] = "Niepoprawne dane logowania.";
      header("Location: http://localhost/zad/index.php");
      exit();
    }
  }
  if(isset($_SESSION['komunikat'])){
    $komunikat = $_SESSION['komunikat'];
    unset($_SESSION['komunikat']);
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Moja strona WWW</title>
  </head>
  <body>
    <div>
      <form action = "http://localhost/zad/index.php"
            method = "post">
      <?php 
        if(isset($komunikat)):
      ?>
      <div><?=$komunikat?></div>
      <?php
        endif;
		$users = file("C:/xampp/htdocs/zad/users/users.txt", FILE_IGNORE_NEW_LINES);	
		var_dump($users);	
      ?>
        <table>
          <tr>
            <td>Użytkownik:</td>
            <td>
              <input type="text" name="user">
            </td>
          </tr><tr>
            <td>Hasło:</td>
            <td>
              <input type="password" name="haslo">
            </td>
          </tr><tr>
            <td colspan="2" style="text-align:center">
              <input type="submit" value="Wejdź">
            </td>
          </tr>
        </table>
      </form>
    </div>
  </body>
</html>
