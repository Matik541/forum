<?php
require_once("./server.php");
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="log-reg.css">
</head>

<body>
  <div id="container">
    <div class="login">
      <div class="form">
        <form class="login-form" method="post">
          <input type="text" placeholder="Login" name="login" required />
          <div id="icon">
            <input type="password" placeholder="Password" name="password" id="password" required />
            <a> <span id="visiblity-toggle" class="material-icons-outlined">visibility</span> </a>
          </div>
          <button type="submit">login</button>
          <?php
          if (!empty($_POST['login']) && !empty($_POST['password'])) {
            $que = $con->query("SELECT * FROM `users` WHERE (`email` = '" . $_POST['login'] . "' OR `nick` = '" . $_POST['login'] . "') AND `password` = '" . hash('whirlpool', $_POST['password']) . "';");
            if ($que->fetch()) {
              $_SESSION['logged'] = $_POST['login'];
              header('Location:index.php');
            }
          }
          ?>
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
        <a href="./register.php">Sign up</a>
        <!-- <a href="./forgot.php">Forgot password?</a> -->
      </div>
    </div>
  </div>

</body>

</html>