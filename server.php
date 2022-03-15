<?php
$server = "localhost";
$user = "root";
$password = "";
$basePath = "forum";

$hash = "sha256";

try {
  $con = new PDO("mysql:host=$server;dbname=$basePath", $user, $password);
} catch (PDOException $e) {
  echo "Error connecting to $server: " . $e->getMessage();
}