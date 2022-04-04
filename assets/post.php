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

<?php if (!empty($_GET['post']) && empty($_GET['category'])) {
	$que = $con->query("SELECT `nick`, `date`, `category`, `title`, `posts`.`id`, `users`.`id` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`id` = " . $_GET['post']);
	$rec = $que->fetch();
	if (!$rec) {
		header("Location:index.php");
	}
	if ($rec = ($con->query("SELECT `nick`, `date`, `category`, `title`, `posts`.`id`, `users`.`id` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`id` = " . $_GET['post']))->fetch()) : ?>
		<style>
			.form.comment {
				padding: 0;
			}

			.comment.group {
				display: flex;
				justify-content: space-between;
				align-items: flex-start;
				gap: 1ch;
			}

			.form.comment.group .select {
				width: 50%;
			}

			.form.comment.group button {
				width: 50%;
			}
		</style>

		<div><span title="author"><?= $rec[0] . "</span> - <span title='publication date'>" . $rec[1] ?><span> | <a title='category' href='./index.php?category=<?= $rec[2] ?>'><?= $rec[2] ?></a></div>
		<h2 class="post-title post">
			<div class="post-content"><?= $rec[3] ?>
				<form method='post'>
					<?php
					if (isset($_SESSION['logged'])) {
						if ($rec[5] == $_SESSION['logged']) {
							echo "<button type='submit' name='delete' value='$rec[4]'>
						<span title='delete post' class='trash material-icons-outlined'>
							delete_forever
						</span>
					</button>";
						}
					}
					?>
				</form>
			</div>
			<form method='post' class='like' title='likes'>
				<?= (($con->query("SELECT COUNT(*) FROM `likes` WHERE `post_id` = '" . $rec[4] . "';"))->fetch()[0]) ?>
				<?php if (!isset($_SESSION['logged'])) : ?>
					<button type='submit' name="login">
						<span class='material-icons-outlined'>favorite</span>
					</button>
				<?php endif; ?>
				<?php if (isset($_SESSION['logged'])) : ?>
					<button name='like' value='<?= $rec[4] ?>' type='submit' <?= ((($con->query(" SELECT * FROM `likes` WHERE `user_id`='" . $_SESSION['logged'] . "' AND `post_id` = '" . $rec[4] . "';"))->fetch()) ? "class='liked'" : "") ?>>
						<span class='material-icons-outlined'>favorite</span>
					</button>
				<?php endif; ?>
			</form>
		</h2>
		<hr>
		<div class="comments">
			<?php
			$que = "SELECT `nick`, `date`, `posts`.`id`, `title`, `users`.`id` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`rot` = " . $_GET['post'];
			$que = $con->query($que);
			if ($row = $que->fetchAll()) {
				foreach ($row as $record) {
					echo "<div class='post'>
          <div style='font-size: 0.75em;'><span title='author'>$record[0]</span> - <span title='publication date'>$record[1]</span></div>
          <div class='post-title'>
						<div class='post-content'>
            	<a title='go to this post' href='./index.php?post=$record[2]' class='post'>$record[3]</a>
							<form method='post'>
							";
					if (isset($_SESSION['logged'])) {
						if ($record[4] == $_SESSION['logged']) {
							echo "<button type='submit' name='delete' value='$record[2]'>
													<span title='delete post' class='trash material-icons-outlined'>
														delete_forever
													</span>
												</button>";
						}
					}
					echo "</form>
						</div>
            <form method='post' class='like' title='likes'>";
					echo (($con->query("SELECT COUNT(*) FROM `likes` WHERE `post_id` = '" . $record[2] . "';"))->fetch()[0]);
					if (!isset($_SESSION['logged'])) : ?>
						<button type='submit' name="login">
							<span class='material-icons-outlined'>favorite</span>
						</button>
					<?php endif;
					if (isset($_SESSION['logged'])) : ?>
						<button name='like' value='<?= $record[2] ?>' type='submit' <?= ((($con->query("SELECT * FROM `likes` WHERE `user_id` = '" . $_SESSION['logged'] . "' AND `post_id` = '" . $record[2] . "';"))->fetch()) ? "class='liked'" : "") ?>>
							<span class='material-icons-outlined'>favorite</span>
						</button>
			<?php endif;
					echo "</form>
          </div>
        </div>";
				}
			} else {
				echo "No comments yet!";
			}

			?>
		</div>
		<?php if (isset($_SESSION['logged'])) : ?>
			<hr>
			<form method="post" class="form comment group">
				<input type="text" placeholder="title" name="title" required>
				<button type="submit">Add comment</button>
				<?php
				if (!empty($_POST["title"])) {
					$id = 1 + ($con->query("SELECT `id` FROM `posts` ORDER BY `id` DESC LIMIT 1;"))->fetch()[0];
					$title = $_POST["title"];
					$author = $_SESSION['logged'];
					$con->query("INSERT INTO `posts` (`id`, `title`, `author`, `category`, `date`, `rot`) VALUES ('$id', '$title', '$author', '$rec[2]', current_timestamp(), " . $_GET['post'] . ");");
					$_POST = array();
					header("Refresh:0");
				}
				?>
			</form>

<?php endif;
	endif;
}
?>

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
		$query = "SELECT `nick`, `date`, `posts`.`id`, `title`, `rot`, `users`.`id` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`category` = '" . $_GET['category'] . "';";
		$query = $con->query($query);
		if ($row = $query->fetchAll()) {
			foreach ($row as $record) {
				echo "<div class='post'>
            <div style='font-size: 0.75em;'><span title='author'>$record[0]</span> - <span title='publication date'>$record[1]</span></div>
            <div class='post-title'>
							<div class='post-content'>
								<a title='go to this post' href='./index.php?post=$record[2]' class='post'>$record[3]</a>
								<form method='post'>
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
							<form method='post' class='like' title='likes'>";
				echo (($con->query("SELECT COUNT(*) FROM `likes` WHERE `post_id` = '" . $record[2] . "';"))->fetch()[0]);
				if (!isset($_SESSION['logged'])) : ?>
					<button type='submit' name="login">
						<span class='material-icons-outlined'>favorite</span>
					</button>
				<?php endif;
				if (isset($_SESSION['logged'])) : ?>
					<button name='like' value='<?= $record[2] ?>' type='submit' <?= ((($con->query("SELECT * FROM `likes` WHERE `user_id` = '" . $_SESSION['logged'] . "' AND `post_id` = '" . $record[2] . "';"))->fetch()) ? "class='liked'" : "") ?>>
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
	<?php if (isset($_SESSION['logged'])) : ?>
		<hr>
		<form method="post" class="form comment group">
			<input type="text" placeholder="title" name="title" required>
			<button type="submit">Add post</button>
			<?php
			if (!empty($_POST["title"])) {
				$id = 1 + ($con->query("SELECT `id` FROM `posts` ORDER BY `id` DESC LIMIT 1;"))->fetch()[0];
				$title = $_POST["title"];
				$author = $_SESSION['logged'];
				$con->query("INSERT INTO `posts` (`id`, `title`, `author`, `category`, `date`, `rot`) VALUES ('$id', '$title', '$author', '" . $_GET['category'] . "', current_timestamp(), NULL);");
				$_POST = array();
				header("Refresh:0");
			}
			?>
		</form>
<?php endif;
endif; ?>