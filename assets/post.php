<?php if (!empty($_GET['post']) && empty($_GET['category']))
  if ($rec = ($con->query("SELECT `nick`, `date`, `title` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`id` = " . $_GET['post']))->fetch()) : ?>
  <div><?= $rec[0]." - ".$rec[1] ?></div>
  <h2><?= $rec[2] ?></h2>
  <hr>
  <div class="comments">
    <?php 
      $que = $con->query("SELECT `nick`, `date`, `title` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`rot` = ".$_GET['post'].";");

      while($row = $que->fetch()){
        echo "<div class='comment'><div style='font-size: 0.7em;'>$row[0] - $row[1]</div><a href='./index.php?post=2'>$row[2]</a></div>";
      }
    ?>
  </div>
  <hr>


<?php endif; ?>

<?php if (!empty($_GET['category']) && empty($_GET['post'])) : ?>
  <?= $_GET['category'] ?>
<?php endif; ?>