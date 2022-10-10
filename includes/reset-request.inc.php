<?php

//check if user tries to access this  page by clicking submit button in reset password page
if (isset($_POST["reset-request-submit"])) {
	//generate two tokens
	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);
	//create url
	$url="www.mmtuts.net/forgottenpwd/create-new-password.php?selector=".$selector."&validator=".bin2hex($token);
	//set time for the link
	$expires = date("U")+1800; //date('u') gives present time. adding 1800 seconds  means link should expire after one hour
	//include database connection
	require 'dbh.inc.php';


	$userEmail = $_POST['email']; //store email entered by user in a variable.
	$sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;"; //sql statement using placeholders. deletes any row from database where pwdResetEmail is the same as the email entered by the user. the reason behind this is to avoid same user having two tokens at the same time. 
	$stmt = mysqli_stmt_init($conn); //initialize connection to database
	if(!mysqli_stmt_prepare($stmt, $sql)){
		echo "There was an error!";
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "s", $userEmail); //bind parameters to placeholders
		mysqli_stmt_execute($stmt); //execute statement
	}

	$sql = "INSERT INTO pwdReset(pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);"; 
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt, $sql)){
		echo "There was an error!";
		exit();
	} else {

		$hashedToken = password_hash($token, PASSWORD_DEFAULT); //token should be hashed for security
		mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
		mysqli_stmt_execute($stmt);
	}

	mysqli_stmt_close($stmt);
	mysqli_close();


	$to = $userEmail;
	$subject = 'Reset your password for mmtuts';
	$message = '<p>We recieved a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email. The link expires after one hour</p>';
	$message .= '<p>Here is your password reset link: </br>';
	$message .=  '<a href="'.$url. ' ">'. $url.'</a></p>';

	$headers = "From: mmtuts <petersembi@gmail.com\r\n";
	$headers .= "Reply-To: petersembi@gmail.com\r\n";

	mail($to, $subject, $message, $headers);

	header("Location: ../reset-password.php?reset=success");






} else {
	header("Location: ../index.php");

}