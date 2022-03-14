<?php
$con = mysqli_connect('localhost', 'root', '', 'baza');
session_start();
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
  Logowanie
  <form action="" method="post">
    <input type="text" name="login" placeholder="login"><br>
    <input type="password" name="password" placeholder="hasło"><br>
    <input type="submit" value="Zaloguj">
    <?php
    if(!empty($_POST['login']) && !empty($_POST['password'])){
      $que = mysqli_query($con, "SELECT * FROM `users` WHERE `login` = '".$_POST['login']."' AND `password` = '".hash('whirlpool', $_POST['password'])."';");
      if(mysqli_fetch_array($que)){
        $_SESSION['logged'] = $_POST['login'];
        header('Location:index.php');
      }
      else{
        echo "<br><span style='color: red;'>błędny login lub hasło!</span>";
      }
    }
    ?>
  </form> 
  <a href="register.php">Załuż konto</a>
  </div>
  
</body>
</html>
<?php
mysqli_close($con);
?>