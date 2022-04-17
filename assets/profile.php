<?php
$que = $con->query("SELECT * FROM `users` WHERE `nick` LIKE '$request[1]'");
$fetch = $que->fetch();
if ($fetch) : ?>
	<div id="profile">
		<img src="<?= ($fetch[4]) ? $fetch[4] : $mainHref . "/../images/profile-placeholder.png" ?>" alt="<?= $fetch[3] ?>'s profile picture">
		<div class="info">
			<h3><?= $fetch[3] ?></h3>
			<?= $fetch[1] ?>
		</div>
		<div>
			<form class="form" style="padding:0;" method="post">
				<?php
				if (isset($_SESSION['logged']))
					if ($_SESSION['logged'] != $fetch[0]) {
						$user = $_SESSION['logged'];
						$query_1 = $con->query("SELECT * FROM `friends` WHERE `user_id_1` = '$fetch[0]' AND `user_id_2` = '$user';");
						$query_2 = $con->query("SELECT * FROM `friends` WHERE `user_id_1` = '$user' AND `user_id_2` = '$fetch[0]';");
						$fetch_1 = $query_1->fetch();
						$fetch_2 = $query_2->fetch();
						if ($fetch_1 && $fetch_2)
							echo "<button disabled>You are friends</button>";
						else if ($fetch_2)
							echo "<button type='submit' name='accept' value='$fetch[0]'></button>";
						else if ($fetch_1)
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
	<div>
		<?php

		?>
	</div>
	<span class="hr-label">Posts</span>
	<hr>
	<div>
		<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, dicta!</p>
		<p>Ipsa illo omnis quo, iusto ea culpa tenetur nisi provident!</p>
		<p>Error tempore eaque fuga itaque nisi porro. Minus, sit libero!</p>
	</div>
	<span class="hr-label">Liked posts</span>
	<hr>
	<div>
		<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vel, id.</p>
		<p>Laborum reiciendis natus dignissimos, in officia adipisci sit. Cum, fugiat?</p>
		<p>Quos quo earum temporibus ullam iste dolorum. Iste, modi vel.</p>
	</div>

<?php endif; ?>