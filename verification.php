<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Verification</title>
<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/print.css">
	<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/menu.css">
	<script src="js/jquery-1.12.2.min.js" type="text/javascript"></script>
	<script src="js/script.js"></script>
	<link rel="stylesheet" href="css/zerogrid.css">
	</head>
<body class="single-page">
	<div class="wrap-body">
	<div class='printignore'>
		<div class="header">
			<div id='cssmenu' >
				<ul>
					<li><a href="home.php">Home</a></li>
				   <li class="active"><a href='verification.php'><span>Verification</span></a></li>
				</ul>
			</div>
			</div>
		</div>
		</div>
		<section id="container">
			<div class="wrap-container clearfix">
				<div id="main-content">
				<div class='printignore'>
					<div class="crumbs">
						<ul>
						<?php menu($userlevel); ?>
						<li><a href="verification.php">Verification</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Verification</h1>
							</div>
						</div>
					</div>
<center>					
<?php

$emailaddress = $emailaddresscode = $firstname = $lastname = $type = "";
		
if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION

	if ($userlevel == "Student") { // if the user is student

		$sql = "SELECT * FROM users WHERE userid = '$userid'";
		$result = $connection->query($sql);

		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
				$firstname = $row->firstname;
				$lastname = $row->lastname;
				$emailaddress = $row->emailaddress;
				$emailaddresscode = $row->emailaddresscode;
			}
		}
		
	$emailaddressverification = $emailaddressverificationerror = $verificationresult = "";	
	$sendemail = true;
	$errorcount = 0;
	
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
			if (isset($_POST["verify"])) {
				$sendemail = false;
				$emailaddressverification = $_POST["emailaddressverification"];
				
				if (empty($emailaddressverification)) {
					$emailaddressverificationerror = "* Please Enter The Verification Code! <br/>";
					$errorcount++;
				} 
				else {
				$emailaddressverification = validateinput($emailaddressverification);
					if (!preg_match("/^[0-9]*$/", $emailaddressverification) || strlen($emailaddressverification) != 6) { 
					  $emailaddressverificationerror = "* Only 6 Digits Are Allowed! <br/>";
					  $errorcount++;
					}
					else if ($emailaddressverification != $emailaddresscode) {
						$emailaddressverificationerror = "* Verification Code is Incorrect! <br/>";
						$errorcount++;
					}
				}
				
				if ($errorcount > 0) {
					
					echo "<br/><hr/>
					<h3>An Email That Contains The Verification Code Was Sent To: <br/>
					$emailaddress<h3/><br/>
					<h4>Enter The Verification Code Here: </h4><br/>
					<form method='post' action='verification.php'>
					<input type='text' name='emailaddressverification' size='6' maxlength='6'><br/><br/>
					<span class='error'>$emailaddressverificationerror</span><br/>
					<input type='submit' name='verify' value='Verify'>
					</form>";
					
				}
				else {
					
					$emailaddresscode = 0;

					$sql = "UPDATE users SET emailaddresscode = 0 WHERE userid = '$userid'";
					$result = $connection->query($sql);
					
					if ($result === true) {
						$verificationresult = "Email Verification Successful!";
						$emailaddresscode = 0;
						
							echo "<script> 
									var x = messagealert('$verificationresult'); 
									if (x == true) {
										window.location = 'profile.php';
									}
								</script>"; 
								$verificationresult = "";
					}
				}
				
				
				
			}
		}
		
		if ($emailaddresscode != 0) {
			
			if ($sendemail === true) {
			
			$recipient = $emailaddress;
			$name = "$firstname $lastname";
			$subject = "Email Verification";
			$body = "Hello $firstname $lastname!\n\nThis is your email verification code: $emailaddresscode\n\nThank You! Have a good day!";
			
			if (sendemail($recipient, $name, $subject, $body) === true) {
				
					echo "<br/><hr/>
					<h3>An Email That Contains The Verification Code Was Sent To: <br/>
					$emailaddress<h3/><br/>
					<h4>Enter The Verification Code Here: </h4><br/>
					<form method='post' action='verification.php'>
					<input type='text' name='emailaddressverification' size='6' maxlength='6'><br/><br/>
					<span class='error'>$emailaddressverificationerror</span><br/>
					<input type='submit' name='verify' value='Verify'>
					</form>";
					
			}
			else {
				echo "<script> messagealert('The Email That Contains Your Verification Code Was Not Sent Successfully! Please Try Again Later.');
					window.location = 'profile.php';
					</script>";
			}
		}
	}	
		$connection->close();

	else {
		echo "<br/><img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		Email Address For This Account is Already Verified! <br/><a href='profile.php'>Back</a>";
	}
}
	else if ($userlevel == "Cashier"){
		echo "<br/><img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Cashiers! <br/> <a href='payment.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
	}
	else if ($userlevel == "Registrar Staff"){
		echo "<br/><img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Registrar Staffs! <br/> <a href='registrar.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
	}
	else if ($userlevel == "Admin"){
		echo "<br/><img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Admins! <br/> <a href='admin.php'>Back</a>"; // if the user tries to access cashier.php with admin userlevel
	}
}
	else { // if the user tries to access a profile.php without logging-in
		echo "<br/><img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		Welcome to Tanauan Institute, Inc.<br/>
		Log-In First To Your Account <a href='login.php'>Here</a>.";
	}
?>
</div>
</div>
</section>
<table class='footer printignore'><tr><td align='center'>© 2016-2017 →rEVOLution← Studios</td></tr></table>
</body>
</html>