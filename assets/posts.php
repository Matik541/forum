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
	$que = "SELECT `nick`, `picture`, `date`, `category`, `title`, `posts`.`id` AS 'post', `author`, `rot` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`id` = $getpost";
	
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
				post('comments', $rec['post'], $rec['title'], $rec['category'], $rec['date'], $rec['author'], $rec['picture'], $rec['nick'], $rec['rot'], $con, $mainHref);

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
	<h3>Posts form category: <a href="<?= $mainHref ?>/category/<?= str_replace(' ', '+', $getcategory) ?>"><?= $getcategory ?></a></h3>
	<div class="comments">
		<?php
		$query = "SELECT `nick`, `picture`, `date`, `posts`.`id` AS 'post', `category`, `title`, `rot`, `author` FROM `posts` JOIN `users` ON `author` = `users`.`id` WHERE `posts`.`category` = '" . $getcategory . "';";
		$query = $con->query($query);
		if ($row = $query->fetchAll()) {
			foreach ($row as $record) {
				post('single', $record['post'], $record['title'], $record['category'], $record['date'], $record['author'], $record['picture'], $record['nick'], $record['rot'], $con, $mainHref);
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