<?php
$getcategory = ($request[0] == 'category') ? $request[1] : ((isset($_GET['category'])) ? $_GET['category'] : NULL);
$getpost = ($request[0] == 'post') ? base_convert($request[1], 36, 10) : ((isset($_GET['post'])) ? $_GET['post'] : NULL);


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

?>

<?php if (!empty($getpost) && empty($getcategory)) {
	$que = "SELECT `nick`, `date`, `category`, `title`, `posts`.`id` AS 'post', `author`, `rot` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`id` = $getpost";
	
	$rec = $con->query($que)->fetch();
	if (!$rec) {
		header("Location:index.php");
	}
	if ($rec = $con->query($que)->fetch()) : ?>
		<style>
			.form.comment.group .select {
				width: 50%;
			}

			.form.comment.group button {
				width: 50%;
			}
		</style>
			<?php
				post('comments', $rec['post'], $rec['title'], $rec['category'], $rec['date'], $rec['author'], $rec['nick'], $rec['rot'], $con, $mainHref);

			?>
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
					$con->query("INSERT INTO `posts` (`id`, `title`, `author`, `category`, `date`, `rot`) VALUES ('$id', '$title', '$author', '$rec[2]', current_timestamp(), $getpost);");
					$_POST = array();
					header("Refresh:0");
				}
				?>
			</form>

<?php endif;
	endif;
}
?>

<?php if (!empty($getcategory) && empty($getpost)) : ?>
	<style>



	</style>
	<h3>Posts form category: <a href="<?= $mainHref ?>/category/<?= str_replace(' ', '+', $getcategory) ?>"><?= $getcategory ?></a></h3>
	<div class="comments">
		<?php
		$query = "SELECT `nick`, `date`, `posts`.`id`, `title`, `rot`, `users`.`id` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`category` = '" . $getcategory . "';";
		$query = $con->query($query);
		if ($row = $query->fetchAll()) {
			foreach ($row as $record) {
				echo "<div class='post'>
            <div style='font-size: 0.75em;'><a title='author' href=" . $mainHref . "/profile/" . str_replace(' ', '+', $record[0]) . ">" . ((strlen($record[0]) > 20) ? (substr($record[0], 0, 17) . "...") : $record[0]) . "</a> - <span title='publication date | $record[1]'>".publication($record[1])."</span></div>
            <div class='post-title'>
							<div class='post-content'>
								<a title='go to this post' href='$mainHref/post/" . base_convert($record[2], 10, 36) . "' class='post'>$record[3]</a>
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
				$con->query("INSERT INTO `posts` (`id`, `title`, `author`, `category`, `date`, `rot`) VALUES ('$id', '$title', '$author', '" . $getcategory . "', current_timestamp(), NULL);");
				$_POST = array();
				header("Refresh:0");
			}
			?>
		</form>
<?php endif;
endif; ?>