<?php

if ($userlevel == "Student") {
	if (isset($_SESSION["lastactivity"]) && time() - $_SESSION["lastactivity"] > 180) {
		echo "<script> window.alert('Your Session Has Expired Due To 3 Minutes of Inactivity! Please Log-In Again.'); </script>";
		session_unset();
		session_destroy();
		echo "<script> window.location = 'login.php'; </script>";
	}
	else {
		$_SESSION["lastactivity"] = time();
	}
}

	if (!isset($_SESSION["created"])) {
		$_SESSION["created"] = time();
	}
	else if (time() - $_SESSION["created"] > 180) {
		session_regenerate_id(true);
		$_SESSION["created"] = time();
	}
?>