<?php
  session_start();
  if(isset($_SESSION['zalogowany'])){
    unset($_SESSION['zalogowany']);
  }
  else{
    header("Location: index.php");
    exit();
  }
  //Jeżeli chcemy unieważnić bieżącą sesję
  if(isset($_COOKIE[session_name()])){
    setcookie(session_name(), '', time() - 360);
  }
  session_destroy();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Moja strona WWW</title>
  </head>
  <body>
    <p>Wylogowanie prawidłowe</p>
    <p><a href='http://localhost/zad/index.php'>Powrót do strony logowania</a></p>
  </body>
</html>
