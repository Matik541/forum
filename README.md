in main dir add file `server.php` with this information:
```
<?php
$forumName = "name";
$forumDescription = "description";
$mainDir = "/yourDir";
<<<<<<< HEAD
=======
$mainHref = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']."$mainDir/index.php";
>>>>>>> 909b95c497dcb40d9e4efd45120704778848c1ac

$server = "host";
$user = "";
$password = "";
$basePath = "yourBase";

$hash = "HashAlg"; // eg. sha256

$mainHref = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']."$mainDir/index.php";

try {
  $con = new PDO("mysql:host=$server;dbname=$basePath", $user, $password);
} 
catch (PDOException $e) {
  echo "Error connecting to $server: " . $e->getMessage();
}

```
