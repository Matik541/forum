<?php
$forumName = "\"taki drugi reddit\"";
$forumDescription = "\"taki drugi reddit\", czytaj nie działa, ale jest :D";
$mainHref = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/forum/index.php';

$server = "localhost";
$user = "root";
$password = "";
$basePath = "forum";

$hash = "sha256";

try {
  $con = new PDO("mysql:host=$server;dbname=$basePath", $user, $password);
} 
catch (PDOException $e) {
  echo "Error connecting to $server: " . $e->getMessage();
}
