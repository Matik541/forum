<?php
require_once("./server.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="log-reg.css">
</head>

<body>

  <div class="login">
    <div class="form">
      <form class="login-form" method="post" id="form">
        <input type="text" placeholder="e-mail" name="mail" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Pattern: any@sitename.xxx" />
        <input type="text" placeholder="nickname" name="nick" required />
        <div id="icon">
          <input type="password" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="password" name="password" id="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />
          <a> <span id="visiblity-toggle" class="material-icons-outlined">visibility</span> </a>
        </div>

        <input type="password" placeholder="repeat password" name="check_password" required />
        <button type="submit" id="btn">register</button>
        <div id="err">
          <?php
          if (!empty($_POST)) {
            $mail = $_POST['mail'];
            $nick = $_POST['nick'];
            $password = hash($hash, $_POST['password']);
            $check = $_POST['check_password'];

            $is_mail = $con->query("SELECT * FROM `users` WHERE `email` = '$mail'");
            $is_nick = $con->query("SELECT * FROM `users` WHERE `nick` = '$nick'");
            if ($is_mail->fetch()) echo "<hr>There is already a user with this email";
            else if ($is_nick->fetch()) echo "<hr>There is already a user with this nickname";
            else {
              $con->query("INSERT INTO `users` (`id`, `email`, `password`, `nick`) VALUES (NULL, '$mail', '$password', '$nick');");
              header("Location:index.php");
            }
          }
          ?>
        </div>
        <script>
          let pass = document.querySelector('#password')
          let btn = document.querySelector('#icon a span')

          btn.addEventListener('click', () => {
            if (pass.type === "text") {
              pass.type = "password";
              btn.innerHTML = "visibility";
            } else {
              pass.type = "text";
              btn.innerHTML = "visibility_off";

            }
          })
        </script>
      </form>
      <hr>
      <a href="./login.php">Sign in</a>
    </div>
  </div>
</body>

</html>