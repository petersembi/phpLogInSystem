<?php
 
 if (isset($_POST['signup-submit'])){

 	require 'dbh.inc.php';//to include the connection script in this file;


 	$username = $_POST['uid'];
 	$email = $_POST['mail'];
 	$password = $_POST['pwd'];
 	$passwordRepeat = $_POST['pwd-repeat'];

 	//ERROR HANDLERS

 	//Check if any of the fields is empty

if (empty($username)|| empty($email)||empty($password)||empty($passwordRepeat)){
	header("Location:../signup.php?error=emptyfields&uid=".$username."&mail=".$email);//take the user back to the sign up page, display what he had filled in the url. we avoid refilling the password for security reasons.
	exit(); // stop running the script
}

//check if both the email and username are invalid
else if (!filter_var($email, FILTER_VALIDATE_EMAIL)&&!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
	header("Location:../signup.php?error=invalidmailuid");
	exit();
}

//check if the email was valid

else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	header("Location:../signup.php?error=invalidmail&uid=".$username);
	exit();

 }

 //check if username is valid

 else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
 	header("Location:../signup.php?error=invalidusername&mail=".$email);
 	exit();
 	 }

 //check if both passwords match
 	 else if ($password !== $passwordRepeat) {
 	 	header("Location: ../signup.php?error=passwordcheck&uid=".$username."&mail=".$email);
 	 	exit();
 	 }

 	 else {
 	 	$sql = "INSERT INTO users (uidUsers,emailUsers, pwdUsers) VALUES (?, ?, ?)";//we use placeholders for safety
 	 	$stmt = mysqli_stmt_init($conn);

 	 	if (!mysqli_stmt_prepare($stmt, $sql)) {
 	 		header("Location: ../signup.php?error=sqlerror");
 	 		exit();
 	 	} 
 	 	else {

 	 		$sql = "SELECT uidUsers FROM users WHERE uidUsers=?";
 	 		$stmt = mysqli_stmt_init($conn);
 	 		if (!mysqli_stmt_prepare($stmt,$sql)) {
 	 			header("Location: ../signup.php?error=sqlerror");
 	 			exit();
 	 		}
 	 			else {
 	 				mysqli_stmt_bind_param($stmt, "s", $username);
 	 				mysqli_stmt_execute($stmt);
 	 				mysqli_stmt_store_result($stmt);
 	 				$resultCheck = mysqli_stmt_num_rows($stmt);
 	 				if ($resultCheck>0) {
 	 					header("Location: ../signup.php?error=usertaken&mail=".$email);
 	 					exit();
 	 				} else {
 	 					$sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)";
 	 					$stmt = mysqli_stmt_init($conn);
 	 					if (!mysqli_stmt_prepare($stmt,$sql)) {
		 	 			header("Location: ../signup.php?error=sqlerror2");
		 	 			exit();
		 	 		} else {
		 	 			//use b crypt method to hash passwords
		 	 			$hashedPwd = password_hash($password, PASSWORD_DEFAULT);


		 	 			mysqli_stmt_bind_param($stmt, "sss", $username, $email,$hashedPwd);
		 	 			mysqli_stmt_execute($stmt);
		 	 			
		 	 			header("Location: ../signup.php?signup=success");
		 	 			exit();

		 	 		}

 	 				}

 	 			}
 	 	}
 	 }
 	 	mysqli_stmt_close($stmt);
 	 	mysqli_close($conn);
} else
{
	header("Location: ../signup.php");
	exit();
}

