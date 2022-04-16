<?php
if (!empty($_POST['like'])) {
	$post = $_POST['like'];
	$user = $_SESSION['logged'];
	$query_like = $con->query("SELECT * FROM `likes` WHERE `user_id` = '$user' AND `post_id` = '$post'");
	if (!($query_like)->fetch())
		$con->query("INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES (NULL, '$user', '$post');");
	else
		$con->query("DELETE FROM `likes` WHERE `user_id` = '$user' AND `post_id` = '$post'");
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
?>

<div class="comments">
	<h2>Most popular posts:</h2>
	<?php
	$que = "SELECT DISTINCT `nick`, `date`, `posts`.`id`, `title`, `rot`, `users`.`id`, `category` FROM `posts` LEFT JOIN `users` ON `author` = `users`.`id` LEFT JOIN `likes` ON `posts`.`id` = `post_id`";
	if (!empty($_GET['search'])) {
		$search = explode(" ", trim($_GET['search']));
		$que .= " WHERE `title` LIKE '%" . $search[0] . "%'";
		unset($search[0]);
		foreach ($search as $word) {
			$que .= " OR `title` LIKE '%" . $word . "%'";
		}
	}
	$que .= "GROUP BY `posts`.`id` ORDER BY COUNT(`likes`.`id`) DESC;";
	$que = $con->query($que);
	if ($row = $que->fetchAll()) {
		foreach ($row as $record) {
			echo "<div class='post'>
          <div style='font-size: 0.75em;'><a title='author' href='".$mainHref."/profile/".str_replace(' ', '+', $record[0])."'>" . ((strlen($record[0]) > 20) ? (substr($record[0], 0, 17) . "...") : $record[0]) . "</a> - $record[1]</div>
          <div class='post-title'>
						<div class='post-content'>
            <a href='$mainHref/post/" . base_convert($record[2], 10, 36) . "' class='post'>$record[3]</a><form method='post'>
						";
			if (isset($_SESSION['logged'])) {
				if ($record[5] == $_SESSION['logged']) {
					echo "<button type='submit' name='delete' value='$record[2]'>
									<span title='delete post' class='trash material-icons-outlined'>
										delete_forever
									</span>
								</button>";
				}
			}
			echo "</form>
						</div>
						<form method='post' class='like'>";
			echo (($con->query("SELECT COUNT(*) FROM `likes` WHERE `post_id` = '" . $record[2] . "';"))->fetch()[0]);
			if (!isset($_SESSION['logged'])) : ?>
				<button type='submit' name="login">
					<span class='material-icons-outlined'>favorite</span>
				</button>
			<?php endif;
			if (isset($_SESSION['logged'])) : ?>
				<input type='hidden' name='like' value='<?= $record[2] ?>'>
				<button type='submit' <?= ((($con->query("SELECT * FROM `likes` WHERE `user_id` = '" . $_SESSION['logged'] . "' AND `post_id` = '" . $record[2] . "';"))->fetch()) ? "class='liked'" : "") ?>>
					<span class='material-icons-outlined'>favorite</span>
				</button>

	<?php endif;

			echo "</form>
					</div>
        </div>";
		}
	} else {
		echo "No post yet!";
	}
	?>
</div>