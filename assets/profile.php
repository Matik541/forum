<?php 
$que = $con->query("SELECT * FROM `users` WHERE `nick` LIKE '$request[1]'");
$fetch = $que->fetch();
if($fetch) : ?>
<div id="profile">
  <img src="<?= ($fetch[4])?$fetch[4]:$mainHref."/../images/profile-placeholder.png" ?>" alt="<?= $fetch[3] ?>'s profile picture">
  <div>
    <h3><?= $fetch[3] ?></h3>
    <?= $fetch[1] ?>
  </div>
</div>
<?php endif; ?> 