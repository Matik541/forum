<?php
require_once("./server.php");
session_start();
ob_start();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <title>
    <?php
    echo $forumName;
    if (!isset($_GET['category']) && isset($_GET['post']))
      echo " - " . (($con->query("SELECT `title` FROM `posts` WHERE `posts`.`id` = '" . $_GET['post'] . "';"))->fetch()[0]);
    if (isset($_GET['category']) && !isset($_GET['post']))
      echo " : " . $_GET['category'];
    ?>
  </title>
  <link rel="stylesheet" href="main.css">
</head>



<body>
  <div class="content">
    <?php
    include("./assets/header.php");
    if (isset($_GET['post']) || isset($_GET['category'])) {
      include("./assets/post.php");
    } else {
      include("./assets/home.php");
      if (isset($_SESSION['logged'])) {
        echo "<hr>";
        include("./assets/create.php");
      }
    }

    ?>
  </div>

</body>

</html>