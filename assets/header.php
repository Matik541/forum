<header>
  <h3><?= $forumName ?></h3>
  <div>
    <a title="go to homepage" href="<?= $mainHref ?>">Home</a>
    <?php
    include('./assets/log.php');
    ?>
    |
    <div>
      <form method="get" action="<?= $mainHref ?>" class="form search">
        <input type="text" name="search" value="<?= (isset($_GET['search']) ? $_GET['search'] : '') ?>">
        <button type="submit">
          <span class="material-icons-outlined">search</span>
        </button>
      </form>
    </div>
  </div>

</header>