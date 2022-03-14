
<?php if(isset($_SESSION['logged'])) :?>
  <form method="post">
    <input type="hidden" name="logged" value="<?php echo $_SESSION['logged']; ?>">
    <input type="submit" value="Wyloguj">
    <?php
    if (isset($_POST['logged'])) {
      unset($_SESSION['logged']);
      header('refresh:0');
    }
    ?>
  </form>
<?php endif; ?>