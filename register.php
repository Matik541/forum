<?php
$con = mysqli_connect('localhost', 'root', '', 'baza');
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
    Rejestracja
    <form action="" method="post">
      <input type="text" name="new_login" placeholder="login"><br>
      <input type="password" name="new_password" placeholder="hasło"><br>
      <input type="password" name="check_password" placeholder="powtórz hasło"><br>
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
          $que = mysqli_query($con, "SELECT * FROM `users` WHERE `login` = '" . $_POST['new_login'] . "';");
          if ($check = mysqli_fetch_array($que)) {
            echo "<span style='color: red;'>już jest użytkownik o takim loginie!</span>";
          } else {
            mysqli_query($con, "INSERT INTO `users` (`id`, `login`, `password`) VALUES (NULL, '" . $_POST['new_login'] . "', '" . hash('whirlpool', $_POST['new_password']) . "')");
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
<?php
mysqli_close($con);
?>