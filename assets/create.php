<style>
  
</style>
<h3>Dodaj nowy post</h3>
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
    <button type="submit">CREATE POST</button> 
  </div>
  <?php
  if (!empty($_POST["title"]) && !empty($_POST["category"])) {
    $id = 1 + ($con->query("SELECT `id` FROM `posts` ORDER BY `id` DESC LIMIT 1;"))->fetch()[0];
    $title = trim($_POST["title"]);
    $category = $_POST["category"];
    $author = $_SESSION['logged'];
    $con->query("INSERT INTO `posts` (`id`, `title`, `author`, `category`, `date`, `rot`) VALUES ('$id', '$title', '$author', '$category', current_timestamp(), NULL);");
    $_POST = array();
    header("Location:$mainHref/post/".base_convert($id, 10, 36));
  }
  ?>
</form>