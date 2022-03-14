<?php
require_once("../server.php");
session_start();

try {
  $con = new PDO("mysql:host=$server;dbname=$basePath", $user, $password);
  echo "Connecting to $server";
} catch (PDOException $e) {
  echo "Error connecting to $server: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8">
  <meta name="keywords" content="session, login, register">
  <meta name="description" content="zarejestruj i zaloguj się na stronę!">
  <title>Login/Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  
  <div id="container">
  Logowanie
  <form action="" method="post">
    <input type="text" name="login" placeholder="login"><br>
    <input type="password" name="password" placeholder="hasło"><br>
    <input type="submit" value="Zaloguj">
    <?php
    if(!empty($_POST['login']) && !empty($_POST['password'])){
      $que = $con->query("SELECT * FROM `users` WHERE `login` = '".$_POST['login']."' AND `password` = '".hash('whirlpool', $_POST['password'])."';");
      if($que->fetch()){
        $_SESSION['logged'] = $_POST['login'];
        header('Location:index.php');
      }
      else{
        echo "<br><span style='color: red;'>błędny login lub hasło!</span>";
      }
    }
    ?>
  </form> 
  <a href="register.php">Załuż konto</a>
  </div>
  
</body>
</html>