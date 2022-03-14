<?php
require_once("./server.php");
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
  <meta name="keywords" content="">
  <meta name="description" content="">
  <title>Forum</title>
  <link rel="stylesheet" href="main.css">
</head>

<body>
  <div id="content">

  </div>

  <?php 
    if(isset($_SESSION['logged'])){
      include('./logout.php');
    }
    else {
      echo "<a class='login' href='./login.php'>Zaloguj</a>";
    }
  ?>

</body>

</html>