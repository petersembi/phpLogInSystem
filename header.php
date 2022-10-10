<?php 
session_start();

 ?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<header> 
		<nav>
			<a href="">
				<img src="img/logo.png" alt="logo">

			</a>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="portf.php">Portfolio</a></li>
				<li><a href="abtme.php">About me</a></li>
				<li><a href="contact.php">Contact</a></li>
			</ul>
			<div>

				<?php
				if (isset($_SESSION['userId'])) {
					print_r($_SESSION);
					echo '
				<form action="includes/logout.inc.php" method="post">
					<button type="submit" name="logout-submit">Logout</button>
				</form>';

				} else {
					echo '
					<form action="includes/login.inc.php" method="post">
					<input type="text" name="mailuid" placeholder="Username/E-mail...">
					<input type="password" name="pwd" placeholder="Password">
					<button type="submit" name="login-submit">Login</button>
				</form><a href="reset-password.php">Forgot your password?</a>
				';
				}

				?>
				<a href="signup.php">Signup</a>
				
			</div>
		</nav>

	</header>
   