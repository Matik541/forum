<?php
$forumName = "name";
$forumDescription = "description";
$mainDir = "/yourDir";
$mainHref = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME']."$mainDir/index.php";

$server = "localhost";
$user = "root";
$password = "";
$basePath = "yourBase";

$hash = "chooseHashAlg";

try {
  $con = new PDO("mysql:host=$server;dbname=$basePath", $user, $password);
} 
catch (PDOException $e) {
  echo "Error connecting to $server: " . $e->getMessage();
}
