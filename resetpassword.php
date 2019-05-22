<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Reset Password</title>
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
				   <li class="active"><a href='resetpassword.php'><span>Reset Password</span></a></li>
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
						<li><a href="login.php">Log-In</a></li>
						<li><a href="resetpassword.php">Reset Password</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Reset Password</h1>
							</div>
						</div>
					</div>
<center>					
<?php

$displayusername = $username = $firstname = $lastname = $emailaddress = $emailaddresscode = $securityquestion = $securityanswer = $answererror = "";
$emailaddressverification = $emailaddressverificationerror = $verificationresult = "";	
$sendemail = true;

$errorcount = 0;
if (!empty($_GET["username"])) {
	
	$displayusername = validateinput($_GET["username"]);
	$username = md5($displayusername, false);
	
	$sql = "SELECT * FROM users WHERE username = '$username'";
	$result = $connection->query($sql);
	
		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
				$firstname = $row->firstname;
				$lastname = $row->lastname;
				$emailaddress = $row->emailaddress;
				$emailaddresscode = $row->emailaddresscode;
				$securityquestion = $row->securityquestion;
				$securityanswer = $row->securityanswer;
			}
		
		if (empty($_SESSION["answered"])) {
			$_SESSION["securityquestion"] = $securityquestion;	
			$_SESSION["securityanswer"] = $securityanswer;	
		}
		
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
					<h3>Verify Your Email Before Password Reset</h3><br/>
					<h3>An Email That Contains The Verification Code Was Sent To: <br/>
					$emailaddress<h3/><br/>
					<h4>Enter The Verification Code Here: </h4><br/>
					<form method='post' action='resetpassword.php?username=$displayusername'>
					<input type='text' name='emailaddressverification' size='6' maxlength='6'><br/><br/>
					<span class='error'>$emailaddressverificationerror</span><br/>
					<input type='submit' name='verify' value='Verify'>
					</form>";
				}
				else {
				
					$emailaddresscode = 0;
					
					$sql = "UPDATE users SET emailaddresscode = 0 WHERE username = '$username'";
					$result = $connection->query($sql);
					
					if ($result === true) {
						$verificationresult = "Email Verification Successful!";
						
							echo "<script> 
									var x = messagealert('$verificationresult'); 
									if (x == true) {
										window.location = 'resetpassword.php?username=$displayusername';
									}
								</script>"; 
								$verificationresult = "";
					}
					
				}
			}
			else if (isset($_POST["answer"])) {
				
				$answer = "";
				
				$answer = $_POST["securityanswer"];
				$answer = validateinput($answer);
				
				if ($answer == "") {
					$answererror = "* Please Enter The Security Answer! <br/>";
				}
				else 
					if (strtolower($answer) != strtolower($securityanswer)) {
						$answererror = "* Security Answer is Incorrect! <br/>";
					}
					else {
						$_SESSION["answered"] = "true";
						$_SESSION["securityquestion"] = "";	
						$_SESSION["securityanswer"] = "";
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
					<h3>Verify Your Email Before Password Reset</h3><br/>
					<h3>An Email That Contains The Verification Code Was Sent To: <br/>
					$emailaddress<h3/><br/>
					<h4>Enter The Verification Code Here: </h4><br/>
					<form method='post' action='resetpassword.php?username=$displayusername'>
					<input type='text' name='emailaddressverification' size='6' maxlength='6'><br/><br/>
					<span class='error'>$emailaddressverificationerror</span><br/>
					<input type='submit' name='verify' value='Verify'>
					</form>";
			}
			else {
				echo "<script> messagealert('The Email That Contains Your Verification Code Was Not Sent Successfully! Please Try Again Later.');
					window.location = 'login.php';
					</script>";
				}
			}
		}
		else if (!empty($_SESSION["securityquestion"]) && !empty($_SESSION["securityanswer"])) {
			
			echo "<br/><hr/><form method='post' action='resetpassword.php?username=$displayusername'>
				<h3>Answer The Security Question Before Password Reset</h3><br/>
				<h4>$securityquestion</h4><br/>
				<input type='text' name='securityanswer'>
				<br/><br/>
				<span class='error'>$answererror</span><br/>
				<input type='submit' name='answer' value='Answer'>
				</form>";
		}
		else {
				
				$emailpassword = mt_rand(100000, 999999);
				$newpassword = md5($emailpassword, false);
			
				$recipient = $emailaddress;
				$name = "$firstname $lastname";
				$subject = "Password Reset";
				$body = "Hello $firstname $lastname!\n\nThis is your new temporary password: $emailpassword\n\nThank You! Have a good day!";
				
			if (sendemail($recipient, $name, $subject, $body) === true) {
				
				$sql = "UPDATE users SET password = '$newpassword' WHERE username = '$username'";
				$result = $connection->query($sql);
				
				if ($result === true) {
					echo "<br/><hr/>
					<h3>An Email That Contains Your New Password Was Sent To: <br/>
					$emailaddress</h3><br/>
					<h4>You Can Now Log-In With Your New Temporary Password.<br/>
					Be Sure To Change It After You Logged-In. Thank You!</h4>";
				}
			}
			else {
				echo "<script> messagealert('The Email That Contains Your New Password Was Not Sent Successfully! Please Try Again Later.');
					window.location = 'login.php';
					</script>";
			}
		}

$connection->close();	
	}
		else {
			echo "<br/><img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
			No Password to Reset.<br/>
			Try To Log-In First To Your Account <a href='login.php'>Here</a>.";
		}
}
else {
		echo "<br/><img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		No Password to Reset.<br/>
		Try To Log-In First To Your Account <a href='login.php'>Here</a>.";
}
?>
</div>
</div>
</section>
<table class='footer printignore'><tr><td align='center'>© 2016-2017 →rEVOLution← Studios</td></tr></table>
</body>
</html>