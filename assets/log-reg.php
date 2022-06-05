<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
?>
<div class='background'></div>
<div class="login">
	<style>
		.wraper {
			white-space: nowrap;
			width: 100%;
			overflow-x: hidden;
			overflow-y: hidden;
			scroll-snap-type: x mandatory;
		}

		.wraper::-webkit-scrollbar {
			width: 0;
		}

		.option {
			display: inline-block;
			width: 100%;
			vertical-align: top;
		}
	</style>
	<div class="form">
		<div class="login-label">
			<a href='#login'>login</a>
			<a href='#register'>register</a></div>
		<hr>
		<div class="wraper">
			<?php
			if (!empty($_POST['forgot'])) {
				$fetch = $con->query("SELECT * FROM `users` WHERE `email` = '" . $_POST['forgot'] . "';")->fetch();
				if ($fetch) :
					$code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
					// 		$mail = new PHPMailer;

					// 		$mail->isSMTP();											// Set mailer to use SMTP 
					// 		$mail->Host = 'smtp.gmail.com';				// Specify main and backup SMTP servers 
					// 		$mail->SMTPAuth = true;								// Enable SMTP authentication 
					// 		$mail->Username = 'matik7146@gmail.com';   // SMTP username 
					// 		$mail->Password = 'M@t!k_12';					// SMTP password 
					// 		$mail->SMTPSecure = "ssl";            // Enable TLS encryption, `ssl` also accepted 
					// 		$mail->Port = 587;                    // TCP port to connect to 

					// 		// Sender info 
					// 		$mail->setFrom('matik7146@gmail.com', $forumName);
					// 		$mail->addReplyTo('matik7146@gmail.com', "reply - $forumName");

					// 		// Add a recipient 
					// 		$mail->addAddress($_POST['forgot']);

					// 		// Set email format to HTML 
					// 		$mail->isHTML(true);

					// 		// Mail subject 
					// 		$mail->Subject = "One time login code - $forumName";

					// 		// Mail body content 
					// 		$mail->Body = "Your one time login code: <kbd>$code</kbd>";

					// 		// Send email 
					// 		if (!$mail->send())
					// 			echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
					// 		else
					// 			echo 'Message has been sent.';
			?>
					<div class="option" id="code">
						<form class="login-form" method="post" action="<?= $mainHref ?>">
							<style>
								input.code {
									width: 5ch;
								}

								#code form {
									display: flex;
									flex-direction: column;
								}

								#code form>input {
									position: relative;
									top: 4.35em;
									opacity: 0;
								}
							</style>
							<canvas id="canvas" width="300" height="50"></canvas>
							<script>
								function r(min, max){
									min = Math.ceil(min);
									max = Math.floor(max);
									return Math.floor(Math.random() * (max - min)) + min;
								}
								var canvas = document.getElementById("canvas");
								var ctx = canvas.getContext("2d");
								let h = canvas.height
								let w = canvas.width
								let m = 500, n = 100
								ctx.font = "60px Brush Script MT, Brush Script Std, cursive";
								ctx.textAlign = "center";
								ctx.moveTo(50, h/(r(n, m)/100));
								ctx.bezierCurveTo(w/4, h/(r(n, m)/100), w/2, h/(r(n, m)/100), w-50, h/(r(n, m)/100));
								ctx.moveTo(50, h/(r(n, m)/100));
								ctx.bezierCurveTo(w/4, h/(r(n, m)/100), w/2, h/(r(n, m)/100), w-50, h/(r(n, m)/100));
								ctx.lineWidth = 5;
								ctx.lineCap = 'round';
								ctx.fillText("<?= $code ?>", w/2, 45);
								ctx.stroke();
							</script>
							<input type="hidden" name="cod" value="<?= $code ?>">
							<input autocomplete="off" type="number" name="code" required id="cod" min="0" max="999999">
							<div>
								<input class="code" disabled>
								<input class="code" disabled>
								<input class="code" disabled>
								<input class="code" disabled>
								<input class="code" disabled>
								<input class="code" disabled>
							</div>
							<script>
								document.getElementById('cod').addEventListener('keyup', function() {
									let code = document.getElementsByClassName('code');
									if (this.value < 0) this.value *= -1;
									if (this.value.length > 6) this.value = this.value.slice(0, 6);
									if (this.value.length == 6) document.getElementById('login-btn').disabled = false;
									else document.getElementById('login-btn').disabled = true;
									for (let i = 0; i < 6; i++) {
										code[i].value = (this.value[i]) ? this.value[i] : "";
									}
								})
							</script>
							<hr>
							<button id="login-btn" type="submit" disabled>login</button>
						</form>
					</div>
			<?php endif;
				if (!$fetch)
					echo "<div class='err'><hr>Wrong email!</div>";
			}
			?>
			<div class="option" id="login">
				<form class="login-form" method="post">
					<input type="hidden" name="log">
					<input type="text" placeholder="Login" name="login" required title="Your email or nickname" /><br>
					<div class="icon" id="icon-login">
						<input type="password" placeholder="Password" name="password" id="password-login" required />
						<a> <span class="material-icons-outlined">visibility</span> </a>
					</div>
					<div style="padding: 15px; margin: 6px 0 15px;">
						<a href="#forgot">Forgot password?</a>
					</div>
					<hr>
					<button type="submit">login</button>
					<div class="err">
						<?php
						if (!empty($_POST['login']) && !empty($_POST['password'])) {
							$que = $con->query("SELECT * FROM `users` WHERE (`email` = '" . $_POST['login'] . "' OR `nick` = '" . $_POST['login'] . "') AND `password` = '" . hash($hash, $_POST['password']) . "';");
							if ($que->fetch()) {
								$login = $_POST['login'];
								$_SESSION['logged'] = ($con->query("SELECT `id` FROM `users` WHERE `nick` = '$login' OR `email` = '$login'"))->fetch()[0];
								header('Refresh:0');
							} else {
								echo "<hr>Wrong login or password!";
							}
						}
						?>
					</div>
					<script>
						let pass_l = document.querySelector('#password-login')
						let btn_l = document.querySelector('#icon-login a span')

						btn_l.addEventListener('click', () => {
							if (pass_l.type === "text") {
								pass_l.type = "password";
								btn_l.innerHTML = "visibility";
							} else {
								pass_l.type = "text";
								btn_l.innerHTML = "visibility_off";

							}
						})
					</script>
				</form>
			</div>

			<div class="option" id="register">
				<form class="login-form" method="post">
					<input type="hidden" name="reg">
					<input type="text" placeholder="e-mail" name="mail" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Pattern: any@sitename.xxx" /><br>
					<input autocomplete="off" type="text" placeholder="nickname" name="nick" required />
					<div class="icon" id="icon-register">
						<input type="password" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="password" name="password" id="password-register" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />
						<a> <span class="material-icons-outlined">visibility</span> </a>
					</div>
					<hr>
					<button type="submit" id="btn">register</button>
					<div class="err">
						<?php
						if (!empty($_POST['mail']) && !empty($_POST['nick']) && !empty($_POST['password'])) {
							$mail = $_POST['mail'];
							$nick = $_POST['nick'];

							$password = hash($hash, $_POST['password']);

							$is_mail = $con->query("SELECT * FROM `users` WHERE `email` = '$mail'");
							$is_nick = $con->query("SELECT * FROM `users` WHERE `nick` = '$nick'");
							if (str_contains($nick, '+')) echo "<hr>Nick can't contain \"+\" sign";
							else if ($is_mail->fetch()) echo "<hr>There is already a user with this email";
							else if ($is_nick->fetch()) echo "<hr>There is already a user with this nickname";
							else {
								$con->query("INSERT INTO `users` (`id`, `email`, `password`, `nick`, `picture`) VALUES (NULL, '$mail', '$password', '$nick', NULL);");
								header("Refresh:0");
							}
						}
						?>
					</div>
					<script>
						let pass_r = document.querySelector('#password-register')
						let btn_r = document.querySelector('#icon-register a span')

						btn_r.addEventListener('click', () => {
							if (pass_r.type === "text") {
								pass_r.type = "password";
								btn_r.innerHTML = "visibility";
							} else {
								pass_r.type = "text";
								btn_r.innerHTML = "visibility_off";

							}
						})
					</script>
				</form>
			</div>
			<div class="option" id="forgot">
				<form class="login-form" method="post">
					<input type="hidden" name="log">
					<input type="text" placeholder="Email" name="forgot" required title="Your email" /><br>
					<hr>
					<button type="submit" id="email">Send email</button>
					<script>
						let email = document.querySelector('#email')

						email.addEventListener('click', () => {
							window.location.replace('#code');
						})
					</script>
				</form>
			</div>

		</div>
	</div>
</div>