<?php if(isset($_SESSION['logged'])) :?>
  <form method="post" title="logout">
    <input type="hidden" name="logout" value="<?= $_SESSION['logged']; ?>">
    <button type="submit">logout</button>
    <?php
    if (isset($_POST['logout'])) {
      unset($_SESSION['logged']);
      header('refresh:0');
    }
    ?>
  </form>
  |
  <!-- Zrobić jako link do konta użytkownika :DDD -->
  <div title="logged as"> 
    <?= ($con->query("SELECT `nick` FROM `users` WHERE `id` = ".$_SESSION['logged']))->fetch()[0] ?>
  </div>
<?php endif;

if(!isset($_SESSION['logged'])) :?>
  <form method="post" title="login">
    <button type="submit" name="login">login</button>
    <?php
    if (isset($_POST['login'])) {
      include('./assets/log-reg.php');
    }
    ?>
  </form>
<?php endif; ?>