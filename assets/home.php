<?php
if (!empty($_POST['like'])) {
	$post = $_POST['like'];
	$user = $_SESSION['logged'];
	$query_like = $con->query("SELECT * FROM `likes` WHERE `user_id` = '$user' AND `post_id` = '$post'");
	if (!($query_like)->fetch())
		$con->query("INSERT INTO `likes` (`user_id`, `post_id`) VALUES ('$user', '$post');");
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

<div class="comments" style="font-size: 1em;">
	<h3>Most popular posts:</h3>
	<?php
	$que = "SELECT DISTINCT `nick`, `date`, `posts`.`id` AS 'post', `title`, `rot`, `author`, `category` FROM `posts` LEFT JOIN `users` ON `author` = `users`.`id` LEFT JOIN `likes` ON `posts`.`id` = `post_id`";
	if (!empty($_GET['search'])) {
		$search = explode(" ", trim($_GET['search']));
		$que .= " WHERE `title` LIKE '%" . $search[0] . "%'";
		unset($search[0]);
		foreach ($search as $word) {
			$que .= " OR `title` LIKE '%" . $word . "%'";
		}
	}
	$que .= "GROUP BY `posts`.`id` ORDER BY COUNT(`likes`.`post_id`) DESC, `date`;";
	$que = $con->query($que);
	if ($row = $que->fetchAll()) {
		foreach ($row as $record) {
			post('single', $record['post'], $record['title'], $record['category'], $record['date'], $record['author'], $record['nick'], $record['rot'], $con, $mainHref);
		}
	} else {
		echo "No post yet!";
	}
	?>
</div>