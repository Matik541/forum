<?php if (!empty($_GET['post']) && empty($_GET['category']))
  if ($rec = ($con->query("SELECT `nick`, `date`, `category`, `title` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`id` = " . $_GET['post']))->fetch()) : ?>
  <style>
    .comments {
      display: flex;
      gap: 1em;
      flex-direction: column;
      flex-wrap: nowrap;
    }

    .form.comment {
      padding: 0;
    }

    .comment.group {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 5px;
    }

    .form.comment.group .select {
      width: 50%;
    }

    .form.comment.group button {
      width: 50%;
    }
  </style>
  <div><?= $rec[0] . " - " . $rec[1] ?> | <a href='./index.php?category=<?= $rec[2] ?>'><?= $rec[2] ?></a></div>
  <h2><?= $rec[3] ?></h2>
  <hr>
  <div class="comments">
    <?php
    $que = "SELECT `nick`, `date`, `posts`.`id`, `title` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`rot` = " . $_GET['post'];
    $que = $con->query($que);
    if ($row = $que->fetchAll()) {
      foreach ($row as $record) {
        echo "<div><div style='font-size: 0.7em;'>$record[0] - $record[1]</div><a href='./index.php?post=$record[2]'>$record[3]</a></div>";
      }
    } else {
      echo "No comments yet!";
    }

    ?>
  </div>
  <hr>
  <form method="post" class="form comment group">
    <input type="text" placeholder="title" name="title" required>
    <button type="submit">Add comment</button>
    <?php
    if (!empty($_POST["title"])) {
      $id = 1 + ($con->query("SELECT `id` FROM `posts` ORDER BY `id` DESC LIMIT 1;"))->fetch()[0];
      $title = $_POST["title"];
      $author = $_SESSION['logged'];
      $author = ($con->query("SELECT `id` FROM `users` WHERE `nick` = '$author' OR `email` = '$author'"))->fetch()[0];
      $con->query("INSERT INTO `posts` (`id`, `title`, `author`, `category`, `date`, `rot`) VALUES ('$id', '$title', '$author', '$rec[2]', current_timestamp(), " . $_GET['post'] . ");");
      $_POST = array();
      header("Refresh:0");
    }
    ?>
  </form>

<?php endif; ?>

<?php if (!empty($_GET['category']) && empty($_GET['post'])) : ?>
  <style>
    .comments {
      display: flex;
      gap: 1em;
      flex-direction: column;
      flex-wrap: nowrap;
    }

    .form.comment {
      padding: 0;
    }

    .comment.group {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 5px;
    }

    .form.comment.group .select {
      width: 50%;
    }

    .form.comment.group button {
      width: 50%;
    }
  </style>
  <h3>Posts form category: <a href="./index.php?category=<?= $_GET['category'] ?>"><?= $_GET['category'] ?></a></h3>
  <div class="comments">
    <?php
    $que = "SELECT `nick`, `date`, `posts`.`id`, `title` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`category` = '" . $_GET['category'] . "';";
    $que = $con->query($que);
    if ($row = $que->fetchAll()) {
      foreach ($row as $record) {
        echo "<div><div style='font-size: 0.75em;'>$record[0] - $record[1]</div><a href='./index.php?post=$record[2]' style='font-size: 1.25em; font-weight: bold;'>$record[3]</a></div>";
      }
    } else {
      echo "No comments yet!";
    }
    ?>
  </div>
  <hr>
  <form method="post" class="form comment group">
    <input type="text" placeholder="title" name="title" required>
    <button type="submit">Add comment</button>
    <?php
    if (!empty($_POST["title"])) {
      $id = 1 + ($con->query("SELECT `id` FROM `posts` ORDER BY `id` DESC LIMIT 1;"))->fetch()[0];
      $title = $_POST["title"];
      $author = $_SESSION['logged'];
      $author = ($con->query("SELECT `id` FROM `users` WHERE `nick` = '$author' OR `email` = '$author'"))->fetch()[0];
      $con->query("INSERT INTO `posts` (`id`, `title`, `author`, `category`, `date`, `rot`) VALUES ('$id', '$title', '$author', '" . $_GET['category'] . "', current_timestamp(), NULL);");
      $_POST = array();
      header("Refresh:0");
    }
    ?>
  </form>
<?php endif; ?>