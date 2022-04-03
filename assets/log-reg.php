<div class='background'></div>
<div class="login">
  <style>
    .wraper {
      white-space: nowrap;
      width: 100%;
      overflow-x: hidden;
      overflow-y: hidden;
      scroll-snap-type: x mandatory;
    }

    .wraper::-webkit-scrollbar {
      width: 0;
    }

    .option {
      display: inline-block;
      width: 100%;
      vertical-align: top;
    }
  </style>
  <div class="form">
    <div style='display: flex; gap: 2.5ch; justify-content: center;'><a href='#login'>login</a><a href='#register'>register</a></div>
    <hr>
    <div class="wraper">
      <div class="option" id="login">
        <form class="login-form" method="post">
          <input type="text" placeholder="Login" name="login" required title="Your email or nickname" /><br>
          <div class="icon" id="icon-login">
            <input type="password" placeholder="Password" name="password" id="password-login" required />
            <a> <span class="material-icons-outlined">visibility</span> </a>
          </div>
          <div style="padding: 15px; margin: 6px 0 15px;">
            <a href="#forgot">Forgot password?</a>
          </div>
          <hr>
          <button type="submit">login</button>
          <div id="err">
            <?php
            if (!empty($_POST['login']) && !empty($_POST['password'])) {
              $que = $con->query("SELECT * FROM `users` WHERE (`email` = '" . $_POST['login'] . "' OR `nick` = '" . $_POST['login'] . "') AND `password` = '" . hash($hash, $_POST['password']) . "';");
              if ($que->fetch()) {
                $login = $_POST['login'];
                $_SESSION['logged'] = ($con->query("SELECT `id` FROM `users` WHERE `nick` = '$login' OR `email` = '$login'"))->fetch()[0];
                header('Refresh:0');
              } else {
                echo "<hr>Wrong login or password!";
              }
            }
            ?>
          </div>
          <script>
            let pass_l = document.querySelector('#password-login')
            let btn_l = document.querySelector('#icon-login a span')

            btn_l.addEventListener('click', () => {
              if (pass_l.type === "text") {
                pass_l.type = "password";
                btn_l.innerHTML = "visibility";
              } else {
                pass_l.type = "text";
                btn_l.innerHTML = "visibility_off";

              }
            })
          </script>
        </form>
      </div>

      <div class="option" id="register">
        <form class="login-form" method="post">
          <input type="text" placeholder="e-mail" name="mail" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Pattern: any@sitename.xxx" /><br>
          <input autocomplete="off" type="text" placeholder="nickname" name="nick" required />
          <div class="icon" id="icon-register">
            <input type="password" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="password" name="password" id="password-register" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />
            <a> <span class="material-icons-outlined">visibility</span> </a>
          </div>
          <hr>
          <button type="submit" id="btn">register</button>
          <div id="err">
            <?php
            if (!empty($_POST['mail']) && !empty($_POST['nick']) && !empty($_POST['password'])) {
              $mail = $_POST['mail'];
              $nick = $_POST['nick'];
              $password = hash($hash, $_POST['password']);

              $is_mail = $con->query("SELECT * FROM `users` WHERE `email` = '$mail'");
              $is_nick = $con->query("SELECT * FROM `users` WHERE `nick` = '$nick'");
              if ($is_mail->fetch()) echo "<hr>There is already a user with this email";
              else if ($is_nick->fetch()) echo "<hr>There is already a user with this nickname";
              else {
                $con->query("INSERT INTO `users` (`id`, `email`, `password`, `nick`) VALUES (NULL, '$mail', '$password', '$nick');");
                header("Refresh:0");
              }
            }
            ?>
          </div>
          <script>
            let pass_r = document.querySelector('#password-register')
            let btn_r = document.querySelector('#icon-register a span')

            btn_r.addEventListener('click', () => {
              if (pass_r.type === "text") {
                pass_r.type = "password";
                btn_r.innerHTML = "visibility";
              } else {
                pass_r.type = "text";
                btn_r.innerHTML = "visibility_off";

              }
            })
          </script>
        </form>
      </div>
    </div>
  </div>
</div>