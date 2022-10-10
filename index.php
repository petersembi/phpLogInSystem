<?php
require "header.php";
require "includes/dbh.inc.php"

?>

<main>
	<?php
		if(isset($_GET['newpwd'])) {
			if ($_GET['newpwd']=="passwoedupdated") {
				echo '<p class="signupsucess">Your  password has been reset!</p>';
			}
		}



	?>

	<?php
		if (isset($_SESSION['userId'])) {
			echo "<p> You are logged in</p>";

		} else {
			echo "<p> You are logged out</p>";
		}

	?>
	<?php 
 $sql = "SELECT * FROM users;";
$result = mysqli_query($conn, $sql);
$resultCheck = mysqli_num_rows($result);



if ($resultCheck > 0) {

while($row = mysqli_fetch_assoc($result)) {
		
		echo $row['uidUsers']."</br>";
	 	echo $row['emailUsers']."</br>";

	
	
	}
}
?>

</main>

<?php

require "footer.php";

?>