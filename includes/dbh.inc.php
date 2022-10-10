<?php

$servername = "localhost"; //name of server, in this case local host. if using an online server, put the name here.
$dBUsername = "root"; //applying for xampp
$dBPassword = "";
$dBName = "loginsystemtut";

//run a connection 
$conn = mysqli_connect($servername, $dBUsername, $dBPassword,$dBName);

//check if connection was successful

if (!$conn) {
	die("connection failed: ".mysqli_connect_error());//to show the connection error
}

 