<?php
#  © 2016-2017 →rEVOLution← Studios #

session_start();
date_default_timezone_set("Asia/Manila");
set_error_handler("errorhandler");

function errorhandler($errornumber, $errorstring, $errorfile, $errorline) {
	include 'connection.php';
	
	echo "<span class='error'><b>Error:</b> [$errornumber] $errorstring $errorfile line <b>$errorline</b></span><br/>";
	
	$sql = "INSERT INTO errorlog (errornumber, errorstring, errorfile, errorline) VALUES ('$errornumber','$errorstring','$errorfile','$errorline')";
	$result = $connection->query($sql);
	
	$connection->close();
}

$userlevel = "";

if (!empty($_SESSION["userlevel"])) {
	$userlevel = $_SESSION["userlevel"];
}

?>