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
  <meta name="keywords" content="session, login, register">
  <meta name="description" content="zarejestruj i zaloguj się na stronę!">
  <title>Login/Register</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <div id="container">
    <h3>Rejestracja</h3>
    <form action="" method="post">
      <input type="text" name="new_login" placeholder="login"><br>
      <input type="password" class="password" name="new_password" placeholder="hasło"><br>
      <input type="password" class="password" name="check_password" placeholder="powtórz hasło"><br>
      <input type="submit" value="Stwórz konto">
      <hr>
      <?php
      if (!empty($_POST['new_login']) && !empty($_POST['new_password'])) {
        if(strlen($_POST['new_password']) < 8) {
          echo "Hasło musi mieć długość przynajmniej 8 znaków";
        }
        else if(!preg_match('/\d/', $_POST['new_password'])) {
          echo "Hasło nie zawiera żadnej cyfry!";
        }
        else if ($_POST['new_password'] != $_POST['check_password']) {
          echo "Hasła nie są takie same, spróbuj ponownie!";
        } 
        else {
          $que = $con->query("SELECT * FROM `users` WHERE `login` = '" . $_POST['new_login'] . "';");
          if ($que->fetch()) {
            echo "<span style='color: red;'>już jest użytkownik o takim loginie!</span>";
          } else {
            $con->query("INSERT INTO `users` (`id`, `login`, `password`) VALUES (NULL, '" . $_POST['new_login'] . "', '" . hash('whirlpool', $_POST['new_password']) . "')");
            header("Location:index.php");
          }
        }
      }
      ?>
    </form>
    <a href="login.php">Wróć do logowania</a>
  </div>

</body>

</html>