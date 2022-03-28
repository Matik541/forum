<?php if (isset($_GET['add'])) : ?>
  <style>
    .form.create {
      padding: 0;
    }

    .create .group {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 5px;
    }

    .form.create .group .select {
      width: 50%;
    }

    .form.create .group button {
      width: 50%;
    }
  </style>
  <form method="post" class="form create">
    <input type="text" placeholder="title" name="title" required>
    <div class="group">
      <input autocomplete="off" type="text" placeholder="category" name="category" list="category">
      <datalist id="category" required>
        <?php
        $que = $con->query("SELECT `category`, COUNT(*) FROM `posts` GROUP BY `category`");
        while ($rec = $que->fetch()) {
          echo "<option value='$rec[0]'>Number of posts: $rec[1]</option>";
        }
        ?>
      </datalist>
      <button type="submit">Post</button>
    </div>
    <?php
    if (!empty($_POST["title"]) && !empty($_POST["category"])) {
      $id = 1 + ($con->query("SELECT `id` FROM `posts` ORDER BY `id` DESC LIMIT 1;"))->fetch()[0];
      $title = $_POST["title"];
      $category = $_POST["category"];
      $author = $_SESSION['logged'];
      $author = ($con->query("SELECT `id` FROM `users` WHERE `nick` = '$author' OR `email` = '$author'"))->fetch()[0];
      $con->query("INSERT INTO `posts` (`id`, `title`, `author`, `category`, `date`, `rot`) VALUES ('$id', '$title', '$author', '$category', current_timestamp(), NULL);");
      $_POST = array();
      header("./index.php?post=$id");
    }
    ?>
  </form>
<?php endif; ?>