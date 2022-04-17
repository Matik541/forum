<?php
require_once("./server.php");
session_start();
ob_start();

$request = explode('/', substr($_SERVER['REQUEST_URI'], 17));
if (count($request) == 2)
  $request[1] = urldecode($request[1]);

function publication($date)
{
  $date = time() - strtotime($date);
  $time = $date % 60 . "s";
  if ($date / 60 % 60 > 0)
    $time = $date / 60 % 60 . "m";
  if ($date / 60 / 60 % 24 > 0)
    $time = $date / 60 / 60 % 24 . "h";
  if (intval($date / 60 / 60 / 24) > 0)
    $time = intval($date / 60 / 60 / 24) . "d";
  return "$time ago";
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="description" content='<?= $forumDescription ?>'>
  <title>
    <?php
    echo $forumName;
    if (!isset($_GET['category']) && isset($_GET['post']))
      echo " - " . (($con->query("SELECT `title` FROM `posts` WHERE `posts`.`id` = '" . $_GET['post'] . "';"))->fetch()[0]);
    if (isset($_GET['category']) && !isset($_GET['post']))
      echo " : " . $_GET['category'];
    ?>
  </title>
  <link rel="stylesheet" href="<?= $mainHref ?>/../main.css">
</head>



<body>
  <div class="content">
    <?php
    include("./assets/header.php");
    if (isset($_POST['reg']) || isset($_POST['log'])) {
      include("./assets/log-reg.php");
    }
    if (isset($_GET['post']) || isset($_GET['category'])) {
      include("./assets/posts.php");
    } else if (count($request) >= 2) {
      if ($request[0] == 'profile')
        include("./assets/profile.php");
      else if ($request[0] == 'post' || $request[0] == 'category')
        include("./assets/posts.php");
      else
        echo "404 - Not Found";
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