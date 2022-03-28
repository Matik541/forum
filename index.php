<?php
require_once("./server.php");
session_start();

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
  <div class="content">
    <?php 
      include("./assets/post.php");
      if(isset($_SESSION['logged'])){
        include("./assets/create.php");
      }
    ?>
  </div>

  <?php
  if (isset($_SESSION['logged'])) {
    include('./assets/logout.php');
  }
  else {
    include('./assets/log-reg.php');
  }
  ?>

</body>

</html>