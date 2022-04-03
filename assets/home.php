<div class="comments">
	<h2>Most popular posts:</h2>
	<?php
	if (!empty($_POST['like'])) {
		$post = $_POST['like'];
		$user = $_SESSION['logged'];
		$query_like = $con->query("SELECT * FROM `likes` WHERE `user_id` = '$user' AND `post_id` = '$post'");
		if (!($query_like)->fetch()) {
			$con->query("INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES (NULL, '$user', '$post');");
		} else {
			$con->query("DELETE FROM `likes` WHERE `user_id` = '$user' AND `post_id` = '$post'");
		}
	}
	$que = "SELECT `nick`, `date`, `posts`.`id`, `title`, `rot` FROM `likes` JOIN `posts` ON `post_id` = `posts`.`id` JOIN `users` ON `user_id` = `users`.`id` GROUP BY `post_id` ORDER BY COUNT(`likes`.`id`) DESC LIMIT 10;";
	$que = $con->query($que);
	if ($row = $que->fetchAll()) {
		foreach ($row as $record) {
			echo "<div>
          <div style='font-size: 0.75em;'>$record[0] - $record[1]</div>
          <div>
            <a href='./index.php?post=$record[2]' class='post'>$record[3]</a>
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