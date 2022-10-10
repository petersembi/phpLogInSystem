<?php
if (isset($_POST["reset-password-submit"])) {
	$selector = $_POST["selector"];
	$validator = $_POST["validator"];
	$password = $_POST['pwd'];
	$passwordRepeat = $_POST["pwd-repeat"];

	if (empty($password) || empty($passwordRepeat)) {
		header("Location: ../signup.php?newpwd=empty");
		exit();
	} elseif ($password != $passwordRepeat) {
		header("Location: ../create-new-password.php?newpwd=pwdnotsame");
		exit();
	}

	$currentDate = date("U");

	require 'dbh.inc.php';

	$sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >=?";
	$stmt = mysqli_stmt_init($conn); //initialize connection to database
	if(!mysqli_stmt_prepare($stmt, $sql)){
		echo "There was an error!";
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate ); //bind parameters to placeholders
		mysqli_stmt_execute($stmt); //execute statement
	}

	$result = mysqli_stmt_get_result($stmt);
	if (!$row = mysqli_fetch_assoc($result)) {
		echo "You need to re-submit your reset request.";
		exit();
	} else {
		$tokenBin = hex2bin($validator);
		$tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);


	if ($tokenCheck === false) {
		echo "You need to resubmit your request.";
		exit();
	} elseif ($tokenCheck===true) {
		$tokenEmail = $row['pwdRestEmail'];

		$sql = "SELECT * From users WHERE emailUsers=?;";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
		echo "There was an error!";
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "s", $tokenEmail ); //bind parameters to placeholders
		mysqli_stmt_execute($stmt); //execute statement
		$result = mysqli_stmt_get_result($stmt);

		if (!$row  =  mysqli_fetch_assoc($result)) {
			echo "There was an error!";
			exit();
		} else {

			$sql = "UPDATE users SET pwdUsers=? WHERE emailUsers=?";
			$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
		echo "There was an error!";
		exit();
	} else {

		$newPwdHash = password_hash($password, PASSWORD_DEFAULT);
		mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail ); //bind parameters to placeholders
		mysqli_stmt_execute($stmt); //execute statement
		
		$sql = "DELETE FROM pwdReset WHERE pwdRestEmail=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
		echo "There was an error!";
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "s", $tokenEmail ); //bind parameters to placeholders
		mysqli_stmt_execute($stmt); //execute statemeent
		header("Location: ../index.php?newpwd=passwordupdated");
	}



		}

	}
	}
}


	}

	


} else {
	header("Location: ../index.php");
}


?>