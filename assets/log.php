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
  <?php $profile = ($con->query("SELECT `nick` FROM `users` WHERE `id` = ".$_SESSION['logged']))->fetch()[0] ?>
  <a class="profile" title="logged as" href="<?= $mainHref."/profile/".str_replace(' ', '+', $profile)?>"> 
    <?= (strlen($profile) > 20)?(substr($profile, 0, 17)."..."):$profile ?>
  </a>
<?php endif;

if(!isset($_SESSION['logged'])) :?>
  <form method="post" title="login">
    <button type="submit" name="log">login</button>
  </form>
<?php endif; ?>