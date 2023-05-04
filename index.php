<?php
require("./server.php");
session_start();
ob_start();

$request = explode('/', substr($_SERVER['REQUEST_URI'], 17));
if (count($request) == 2)
  $request[1] = urldecode($request[1]);

function publication($date)
{
  $date = time() - strtotime($date);
  $time = $date % 60 . " s";
  if ($date / 60 % 60 > 0)
    $time = $date / 60 % 60 . " m";
  if ($date / 60 / 60 % 24 > 0)
    $time = $date / 60 / 60 % 24 . " h";
  if (intval($date / 60 / 60 / 24) > 0)
    $time = intval($date / 60 / 60 / 24) . " d";
  if (intval($date / 60 / 60 / 24 / 30) > 0)
    $time = intval($date / 60 / 60 / 24 / 30) . " mo";
  if (intval($date / 60 / 60 / 24 / 365) > 0)
    $time = intval($date / 60 / 60 / 24 / 365) . " y";
  return "$time ago";
}
function post($type, $id, $title, $category, $date, $author, $author_img, $nick, $root, $con, $mainHref)
{
  echo "<div class='" . (($type == 'single') ? "post" : "main-post") . "'>";
  if ($type == 'comments') : ?>
    <div class="header" style='margin: 1em 0 0.5em;'>
      <a title='author' href="<?= "$mainHref/profile/" . (str_replace(' ', '+', $nick)) ?>"><img src="<?= ($author_img) ? $author_img : $mainHref . "/../images/profile-placeholder.png" ?>" alt="<?= $nick ?>'s profile picture"><?= ((strlen($nick) > 20) ? (substr($nick, 0, 17) . "...") : $nick) ?></a> -
      <span title='publication date | <?= $date ?>'> <?= publication($date) ?></span> |
      <a title='category' href="<?= "$mainHref/category/" . str_replace(' ', '+', $category) ?>"> <?= $category ?></a>
    </div>
    <h2 class="post-title post">
      <div class="post-content"><?= $title ?>
        <form method='post'>
          <?php if (isset($_SESSION['logged']))
            if ($author == $_SESSION['logged']) : ?>
            <button type='submit' name='delete' value='<?= $id ?>'>
              <span title='delete post' class='trash material-icons-outlined'>
                delete_forever
              </span>
            </button>
          <?php endif; ?>
        </form>
      </div>

      <form method='post' class='like'>
        <?php
        echo (($con->query("SELECT COUNT(*) FROM `likes` WHERE `post_id` = '" . $id . "';"))->fetch()[0]);
        if (!isset($_SESSION['logged'])) : ?>
          <button type='submit' name="login">
            <span class='material-icons-outlined'>
              favorite
            </span>
          </button>
        <?php endif;
        if (isset($_SESSION['logged'])) : ?>
          <input type='hidden' name='like' value='<?= $id ?>'>
          <button type='submit' <?= ((($con->query("SELECT * FROM `likes` WHERE `user_id` = '" . $_SESSION['logged'] . "' AND `post_id` = '" . $id . "';"))->fetch()) ? "class='liked'" : "") ?>>
            <span class='material-icons-outlined'>
              favorite
            </span>
          </button>
        <?php endif; ?>
      </form>
    </h2>
    <span class="hr-label">Comments</span>
    <hr>
    <div class="comments">
      <?php
      $que = $con->query("SELECT `nick`, `picture`, `date`, `posts`.`id` AS 'post', `category`, `title`, `rot`, `author` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`rot` = '$id'");
      if ($row = $que->fetchAll()) {
        foreach ($row as $record) {
          post('single', $record['post'], $record['title'], $record['category'], $record['date'], $record['author'], $record['picture'], $record['nick'], $record['rot'], $con, $mainHref);
        }
      } else {
        echo "No comments yet!";
      }
      ?>
    </div>
  <?php endif;
  if ($type == 'single') : ?>
    <div class="header">
      <a title='author' href="<?= "$mainHref/profile/" . (str_replace(' ', '+', $nick)) ?>"><img src="<?= ($author_img) ? $author_img : $mainHref . "/../images/profile-placeholder.png" ?>" alt="<?= $nick ?>'s profile picture"><?= ((strlen($nick) > 20) ? (substr($nick, 0, 17) . "...") : $nick) ?></a> -
      <span title='publication date | <?= $date ?>'> <?= publication($date) ?></span> |
      <a title='category' href="<?= "$mainHref/category/" . str_replace(' ', '+', $category) ?>"> <?= $category ?></a>
    </div>
    <div class='post-title'>
      <div class='post-content'>
        <a href='<?= "$mainHref/post/" . base_convert($id, 10, 36) ?>' class='post'><?= $title ?></a>
        <form method='post'>
          <?php if (isset($_SESSION['logged']))
            if ($author == $_SESSION['logged']) : ?>
            <button type='submit' name='delete' value='<?= $id ?>'>
              <span title='delete post' class='trash material-icons-outlined'>
                delete_forever
              </span>
            </button>
          <?php endif; ?>
        </form>
      </div>
      <form method='post' class='like'>
        <?php
        echo (($con->query("SELECT COUNT(*) FROM `likes` WHERE `post_id` = '" . $id . "';"))->fetch()[0]);
        if (!isset($_SESSION['logged'])) : ?>
          <button type='submit' name="login">
            <span class='material-icons-outlined'>favorite</span>
          </button>
        <?php endif;
        if (isset($_SESSION['logged'])) : ?>
          <input type='hidden' name='like' value='<?= $id ?>'>
          <button type='submit' <?= ((($con->query("SELECT * FROM `likes` WHERE `user_id` = '" . $_SESSION['logged'] . "' AND `post_id` = '" . $id . "';"))->fetch()) ? "class='liked'" : "") ?>>
            <span class='material-icons-outlined'>favorite</span>
          </button>
        <?php endif; ?>
      </form>
    </div>
<?php endif;
  echo "</div>";
}

?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="description" content='<?= $forumDescription ?>'>
  <title>
    <?php
    echo $forumName;
    if (!isset($_GET['category']) && isset($_GET['post']))
      echo " - " . (($con->query("SELECT `title` FROM `posts` WHERE `posts`.`id` = '" . $_GET['post'] . "';"))->fetch()[0]);
    if (isset($_GET['category']) && !isset($_GET['post']))
      echo " : " . $_GET['category'];
    ?>
  </title>
  <link rel="stylesheet" href="<?= $mainHref ?>/../main.css">
</head>



<body>
  <div class="content">
    <?php
    if (isset($_POST['code']))
      if ($_POST['code'] == $_POST['cod']) : ?>
      <div class='background'></div>
      <p class='sorry'>
        Sorry, we can't log you to this account, it's just a system that will be work if PHP allow to send emails on localhost. Anyway, enjoy your day and login to your account with an login and password, or if you realy don't remmember your password then create a new one and continue exploring our website :D
      </p>
    <?php
        header('Refresh:15');
      endif;
    include("./assets/header.php");
    if (isset($_POST['reg']) || isset($_POST['log'])) {
      include("./assets/log-reg.php");
    }
    if (isset($_GET['post']) || isset($_GET['category'])) {
      include("./assets/posts.php");
    } else if (count($request) >= 2) {
      if ($request[0] == 'profile')
        include("./assets/profile.php");
      else if ($request[0] == 'post' || $request[0] == 'category')
        include("./assets/posts.php");
      else
        echo "404 - Not Found";
    } else {
      include("./assets/home.php");
      if (isset($_SESSION['logged'])) {
        echo "<hr>";
        include("./assets/create.php");
      }
    }

    ?>
  </div>

</body>

</html>