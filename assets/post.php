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
	$post = $_POST['deletr'];
	echo "
	<div class='background'></div>
	<div class='login'>
		<div class='form'>
		<form class='' method='post'>
		
			<div class='group'>
			<button type='reset' name='delete'>Cancle</button>
			<button type='submit' name='confirm' value='$post'>Confirm</button>
			</div>
	</form>
		</div>
	</div>
	";
}

if (!empty($_POST['confirm'])) {
	$post = $_POST['confirm'];
	$con->query("DELETE FROM `posts` WHERE `posts`.`id` = $post");
}

?>

<?php if (!empty($_GET['post']) && empty($_GET['category']))
	if ($rec = ($con->query("SELECT `nick`, `date`, `category`, `title`, `posts`.`id` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`id` = " . $_GET['post']))->fetch()) : ?>
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
	<h2><?= $rec[3] ?>
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
		$que = "SELECT `nick`, `date`, `posts`.`id`, `title` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`rot` = " . $_GET['post'];
		$que = $con->query($que);
		if ($row = $que->fetchAll()) {
			foreach ($row as $record) {
				echo "<div>
          <div style='font-size: 0.75em;'><span title='author'>$record[0]</span> - <span title='publication date'>$record[1]</span></div>
          <div>
            <a title='go to this post' href='./index.php?post=$record[2]' class='post'>$record[3]</a>
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
		$que = "SELECT `nick`, `date`, `posts`.`id`, `title`, `rot` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`category` = '" . $_GET['category'] . "';";
		$que = $con->query($que);
		if ($row = $que->fetchAll()) {
			foreach ($row as $record) {
				echo "<div class='post'>
            <div style='font-size: 0.75em;'><span title='author'>$record[0]</span> - <span title='publication date'>$record[1]</span></div>
            <div>
							<div class='post-content'>
								<a title='go to this post' href='./index.php?post=$record[2]' class='post'>$record[3]</a>
								<form method='post'>
							";
							
							echo "<button type='submit' name='delete' value='$record[2]'>
								<span title='delete post' class='trash material-icons-outlined'>
											delete_forever
										</span></button>";

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
	<hr>
	<?php if (isset($_SESSION['logged'])) : ?>
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
	<?php endif; ?>
<?php endif; ?>