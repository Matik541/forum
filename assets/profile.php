<?php
$que = $con->query("SELECT * FROM `users` WHERE `nick` LIKE '$request[1]'");
$fetch = $que->fetch();
if ($fetch) : ?>
	<div id="profile">
		<img src="<?= ($fetch[4]) ? $fetch[4] : $mainHref . "/../images/profile-placeholder.png" ?>" alt="<?= $fetch[3] ?>'s profile picture">
		<div>
			<h3><?= $fetch[3] ?></h3>
			<?= $fetch[1] ?>
		</div>
	</div>

	<span class="hr_label">Friends</span>
	<hr>
	<div>

	</div>
	<span class="hr_label">Posts</span>
	<hr>
	<div>
		<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, dicta!</p>
		<p>Ipsa illo omnis quo, iusto ea culpa tenetur nisi provident!</p>
		<p>Error tempore eaque fuga itaque nisi porro. Minus, sit libero!</p>
	</div>
	<span class="hr_label">Liked posts</span>
	<hr>
	<div>
		<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vel, id.</p>
		<p>Laborum reiciendis natus dignissimos, in officia adipisci sit. Cum, fugiat?</p>
		<p>Quos quo earum temporibus ullam iste dolorum. Iste, modi vel.</p>
	</div>

<?php endif; ?>