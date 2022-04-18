<?php
if (!empty($_POST['like'])) {
	$post = $_POST['like'];
	$user = $_SESSION['logged'];
	$query_like = $con->query("SELECT * FROM `likes` WHERE `user_id` = '$user' AND `post_id` = '$post'");
	if (!($query_like)->fetch()) {
		$con->query("INSERT INTO `likes` (`user_id`, `post_id`) VALUES ('$user', '$post');");
	} else {
		$con->query("DELETE FROM `likes` WHERE `user_id` = '$user' AND `post_id` = '$post'");
	}
}
if (!empty($_POST['delete'])) {
	$post = $_POST['delete'];
	echo "
	<div class='background'></div>
	<div class='login'>
		<div class='form'>
		<form class='' method='post'>";
	$row = $con->query("SELECT `category`, `title` FROM `posts` WHERE `posts`.`id` = " . $post)->fetch();
	echo "<h3>Do you want to delete post: $row[0] / \"$row[1]\"</h3>
			<div class='group'>
			<button type='submit' name='confirm'>Cancle</button>
			<button type='submit' name='confirm' value='$post'>Confirm</button>
			</div>
			</form>
		</div>
	</div>
	";
}

if (!empty($_POST['confirm'])) {
	$post = $_POST['confirm'];
	$row = $con->query("SELECT `category`, `title` FROM `posts` WHERE `posts`.`id` = " . $post)->fetch();
	if ($row) {
		echo "<h4>Delete post: $row[0] / \"$row[1]\" </h4>";
		$con->query("DELETE FROM `posts` WHERE `posts`.`id` = $post");
	}
	$_POST = array();
}

$que = $con->query("SELECT * FROM `users` WHERE `nick` LIKE '$request[1]'");
$fetch = $que->fetch();
if ($fetch) : ?>
	<div id="profile">
		<img src="<?= ($fetch[4]) ? $fetch[4] : $mainHref . "/../images/profile-placeholder.png" ?>" alt="<?= $fetch[3] ?>'s profile picture">
		<div class="info">
			<h3><?= $fetch[3] ?></h3>
			<?= $fetch[1] ?>
		</div>
		<div class="action">
			<form class="form" style="padding:0;" method="post">
				<?php
				if (isset($_POST['accept']) && isset($_SESSION['logged'])) {
					$user = $_SESSION['logged'];
					$friend = $_POST['accept'];
					if (!$con->query("SELECT * FROM `friends` WHERE `user_id_1` = $user AND`user_id_2` = $friend")->fetch())
						$con->query("INSERT INTO `friends` (`user_id_1`, `user_id_2`) VALUES ('$user', '$friend');");
				}
				if (isset($_POST['add']) && isset($_SESSION['logged'])) {
					$user = $_SESSION['logged'];
					$friend = $_POST['add'];
					if (!$con->query("SELECT * FROM `friends` WHERE `user_id_1` = $user AND `user_id_2` = $friend")->fetch())
						$con->query("INSERT INTO `friends` (`user_id_1`, `user_id_2`) VALUES ('$user', '$friend');");
				}

				if (isset($_SESSION['logged']))
					if ($_SESSION['logged'] != $fetch[0]) {
						$user = $_SESSION['logged'];
						$query_1 = $con->query("SELECT * FROM `friends` WHERE `user_id_1` = '$fetch[0]' AND `user_id_2` = '$user';");
						$query_2 = $con->query("SELECT * FROM `friends` WHERE `user_id_1` = '$user' AND `user_id_2` = '$fetch[0]';");
						$fetch_1 = $query_1->fetch();
						$fetch_2 = $query_2->fetch();
						if ($fetch_1 && $fetch_2)
							echo "<button disabled>You are friends</button>";
						else if ($fetch_1)
							echo "<button type='submit' name='accept' value='$fetch[0]'>Accept</button>";
						else if ($fetch_2)
							echo "<button disabled>Request sent</button>";
						else
							echo "<button type='submit' name='add' value='$fetch[0]'>Add friend</button>";
					}
				?>
			</form>
		</div>
	</div>

	<span class="hr-label">Friends</span>
	<hr>
	<div class="scrolling-wrapper-flexbox">
		<?php
		$que = $con->query("SELECT `id`, `nick`, `picture` FROM `users` WHERE `id` IN (SELECT `user_id_2` AS 'friends' FROM `friends` WHERE `user_id_1` = $fetch[0] INTERSECT SELECT `user_id_1` AS 'friends' FROM `friends` WHERE `user_id_2` = $fetch[0]);");
		while ($rec = $que->fetch()) {
			echo "<div class='card'>
								<a href=" . $mainHref . "/profile/" . str_replace(' ', '+', $rec[1]) . ">
									<img width='50' height='50' src='" . (($rec[2]) ? $rec[2] : $mainHref . "/../images/profile-placeholder.png") . "' alt=\"$rec[1]'s profile picture\">" .
				((strlen($rec[1]) > 20) ? (substr($rec[1], 0, 17) . "...") : $rec[1])
				. "</a>
							</div>";
		}
		?>

	</div>
	<span class="hr-label">Posts</span>
	<hr>
	<div class="posts">
		<?php
		$que = $con->query("SELECT `nick`, `date`, `category`, `title`, `posts`.`id` AS 'post', `author`, `rot` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `author` = '$fetch[0]' LIMIT 3");
		while ($record = $que->fetch()) {
			post('single', $record['post'], $record['title'], $record['category'], $record['date'], $record['author'], $record['nick'], $record['rot'], $con, $mainHref);
		}
		?>
	</div>
	<span class="hr-label">Liked posts</span>
	<hr>
	<div class="posts">
	<?php
		$que = $con->query("SELECT `nick`, `date`, `category`, `title`, `posts`.`id` AS 'post', `author`, `rot` FROM `posts` LEFT JOIN `users` ON `author` = `users`.`id` LEFT JOIN `likes` ON `posts`.`id` = `post_id` WHERE `likes`.`user_id` = $fetch[0] GROUP BY `posts`.`id` LIMIT 3;");
		while ($record = $que->fetch()) {
			post('single', $record['post'], $record['title'], $record['category'], $record['date'], $record['author'], $record['nick'], $record['rot'], $con, $mainHref);
		}
		?>
	</div>

<?php endif; ?>