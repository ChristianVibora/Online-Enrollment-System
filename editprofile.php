<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Edit Profile</title>
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
				   <li><a href='home.php'><span>Home</span></a></li>
				   <li class="active"><a href='editprofile.php'><span>Edit Profile</span></a></li>
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
							<li><a href="editprofile.php">Edit Profile</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing:2px;font-weight: 500;l"> Edit Profile</h1>
						</div>
						</div>
					</div>
<center>
<?php

$userid = $firstname = $middlename = $lastname = $mobilenumber = $gender = $dateofbirth = $address = $guardianname = $guardianmobilenumber = $emailaddress = $emailaddresscode = $userstatus  = $period = $profilepicture = $username = $password = $backpage = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"];
	
	if ($userlevel == "Admin") {
		$backpage = "admin.php";
	}
	else if ($userlevel == "Registrar Staff") {
		$backpage = "registrar.php";
	}
	else if ($userlevel == "Cashier") {
		$backpage = "payment.php";
	}
	else if ($userlevel == "Student") {
		$backpage = "profile.php";
	}
	
	$sql = "SELECT * FROM users WHERE userid = '$userid'";
	$result = $connection->query($sql);
	
	if ($result->num_rows == 1) {
	while ($row = $result->fetch_object()) {
		$firstname = $row->firstname;
		$middlename = $row->middlename;
		$lastname = $row->lastname;
		$mobilenumber = $row->mobilenumber;
		$gender = $row->gender;
		$dateofbirth = date('F d, Y', strtotime($row->dateofbirth));
		$address = $row->address;
		$guardianname = $row->guardianname;
		$guardianmobilenumber = $row->guardianmobilenumber;
		$emailaddress = $row->emailaddress;
		$emailaddresscode = $row->emailaddresscode;
		$userstatus = $row->userstatus;
		$username = $row->username;
		$password = $row->password;
		$profilepicture = $row->profilepicture;
	}
}

$newprofilepicture = $newmobilenumber = $newguardianmobilenumber = $newemailaddress = $newusername = $newpassword = $newconfirmpassword = $currentusername = $currentpassword = $displayusername = $displaypassword = "";
$newprofilepictureerror = $newmobilenumbererror = $newguardianmobilenumbererror = $newemailaddresserror = $newusernameerror = $newpassworderror = $confirmnewpassworderror = $currentusernameerror = $currentpassworderror = $updateresult = "";
$errorcount = $updateerrors = 0;

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if (isset($_POST["upload"])) {
			
			if (!empty($_FILES["newprofilepicture"]["name"])) {
			
			$targetdirectory = $filename = $targetfile = $newprofilepictureerror = $filetype = $checkfiletype = "";
			
			$targetdirectory = "images/profilepictures/";
			$filename = basename($_FILES["newprofilepicture"]["name"]);
			$targetfile = "$targetdirectory$filename";
			$filetype = pathinfo($targetfile,PATHINFO_EXTENSION);
			$file = $_FILES["newprofilepicture"]["tmp_name"];
			
			if (file_exists($file))
			{
				$imagesizedata = getimagesize($file);
			if ($imagesizedata === FALSE)
				{
					$newprofilepictureerror = "* File is Not An Image! Please Select a Valid Image File.";
					$errorcount++;
				}
			}
			else
			{
				$newprofilepictureerror = "* Image Dimensions Too Large! Please Lower The Image Dimension.";
				$errorcount++;
			}
			
			if ($_FILES["newprofilepicture"]["size"] > 500000) {
				$newprofilepictureerror = "* File Size Too Large! Please Select a File Smaller Than 500KB.";
				$errorcount++;
			}
			
			if(strtolower($filetype) != "jpg" && strtolower($filetype) != "jpeg" && strtolower($filetype) != "png") {
				$newprofilepictureerror = "* File Type Is Invalid! Please Select a JPEG or PNG Image File.";
				$errorcount++;
			}
			$newprofilepicture = "$targetdirectory$userid.$filetype";
			}
			
			$newmobilenumber = $_POST["newmobilenumber"];
			$newemailaddress = $_POST["newemailaddress"];
			$newguardianmobilenumber = $_POST["newguardianmobilenumber"];
			$newusername = $_POST["newusername"];
			$newpassword = $_POST["newpassword"];
			$confirmnewpassword = $_POST["confirmnewpassword"];
			$currentusername = $_POST["username"];
			$currentpassword = $_POST["password"];
			
				if (!empty($newmobilenumber)) {
				$newmobilenumber = validateinput($newmobilenumber);
					if (!preg_match("/^[0-9]*$/", $newmobilenumber) || strlen($newmobilenumber) != 11) { 
					  $newmobilenumbererror = "* Only 11 Digits Are Allowed!";
					  $errorcount++;
					}
					else if (checkmobilenumber($newmobilenumber) === true) {
						$newmobilenumbererror = "* Mobile Number is Not Available!";
						$errorcount++;
					}
				}
				
				if (!empty($newemailaddress)) {
					$newemailaddress = validateinput($newemailaddress);
					if (!filter_var($newemailaddress, FILTER_VALIDATE_EMAIL)) {
						$newemailaddresserror = "* Invalid Email Address Format!"; 
						$errorcount++;
					}
					else {
						$newemailaddress = addslashes($newemailaddress);
						if (checkemailaddress($newemailaddress) === true) {
							$newemailaddresserror = "* Email Address is Not Available!"; 
							$errorcount++;
						}
					}
				}
				
				if (!empty($newguardianmobilenumber)) {
				$newguardianmobilenumber = validateinput($newguardianmobilenumber);
					if (!preg_match("/^[0-9]*$/", $newguardianmobilenumber) || strlen($newguardianmobilenumber) != 11) { 
					  $newguardianmobilenumbererror = "* Only 11 Digits Are Allowed!";
					  $errorcount++;
					}
					else if (checkmobilenumber($newguardianmobilenumber) === true) {
						$newguardianmobilenumbererror = "* Mobile Number is Not Available!";
						$errorcount++;
					}
				}
			
					if (!empty($newusername)) {
						$newusername = validateinput($newusername);
						$displayusername = $newusername;
						if (strlen($newusername) < 8) {
							$newusernameerror = "* Username Should Contain At Least 8 Characters!"; 
							$errorcount++;
						}
						else {
							$displayusername = $newusername;
							$newusername = md5($newusername, false);
							if (checkusername($newusername) === true) {
							$newusernameerror = "* Username is Not Available!"; 
							$errorcount++;
						}
						}
					}
					
					if (!empty($newpassword)) {
						$newpassword = validateinput($newpassword);
						$displaypassword = $newpassword;
							if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $newpassword) || strlen($newpassword) < 8) { // TODO: validate letters and numbers combination
								$newpassworderror = "* Password Should Contain At Least 8 Letters and Digits!"; 
								$errorcount++;
						}
						if (empty($confirmnewpassword)) {
							$confirmnewpassworderror = "* Password Confirmation is Required!";
							$errorcount++;
						} 
						else {
							$confirmnewpassword = validateinput($confirmnewpassword);
								if ($confirmnewpassword != $newpassword) {
									$confirmnewpassworderror = "* Passwords Should Match!"; 
									$errorcount++;
							}
							else {
								$displaypassword = $newpassword;
								$newpassword = md5($newpassword, false);
							}
						}
					}
					
								if (empty($currentusername)) {
									$currentusernameerror = "* Current Username is Required!";
									$errorcount++;
								}
								else {
									if (md5($currentusername, false) != $username) {
										$currentusernameerror = "* Username is Incorrect!";
										$errorcount++;
									}
								}
								
								if (empty($currentpassword)) {
									$currentpassworderror = "* Current Password is Required!";
									$errorcount++;
								}
								else {
									if (md5($currentpassword, false) != $password) {
										$currentpassworderror = "* Password is Incorrect!";
										$errorcount++;
									}
								}
								
								if ($errorcount == 0) {
									
									if ($newprofilepicture != "") {
										
										if (move_uploaded_file($_FILES["newprofilepicture"]["tmp_name"], $newprofilepicture)) {
					
										$sql = "UPDATE users SET profilepicture = '$userid.$filetype' WHERE userid = $userid";
										$result = $connection->query($sql);
										
										if ($result === true) {
											
										}
										else {
											$newprofilepictureerror = "* Profile Picture Upload Unsuccessful! Please Try Again.";
											$updateerrors++;
										}
									}
									else {
										$newprofilepictureerror = "* Profile Picture Upload Unsuccessful! Please Try Again.";
										$updateerrors++;
									}
								}
								
								if ($newemailaddress != "") {
										
										$emailaddresscode = mt_rand(100000, 999999);
										
										$sql = "UPDATE users SET emailaddress = '$newemailaddress', emailaddresscode = '$emailaddresscode' WHERE userid = $userid";
										$result = $connection->query($sql);
										
										if ($result === true) {
											
											if ($emailaddresscode == 0) {
				
												$recipient = $emailaddress;
												$name = "$firstname $lastname";
												$subject = "Email Address Change";
												$body = "Hello $firstname $lastname!\n\nYour email address was changed to: $newemailaddress\nYou cannot receive any more updates on this email address\n\nThank You! Have a good day!";
											
											// sendemail($recipient, $name, $subject, $body);
											}
											$emailaddress = $newemailaddress;
										}
										else {
											$newemailaddresserror = "* Email Address Update Unsuccessful! Please Try Again.";
											$updateerrors++;
										}
									}
								
									if ($newmobilenumber != "") {
										$sql = "UPDATE users SET mobilenumber = '$newmobilenumber' WHERE userid = $userid";
										$result = $connection->query($sql);
										
										if ($result === true) 
										
											{if ($emailaddresscode == 0) {
				
												$recipient = $emailaddress;
												$name = "$firstname $lastname";
												$subject = "Mobile Number Change";
												$body = "Hello $firstname $lastname!\n\nYour mobile number was changed to: $newmobilenumber\n\nThank You! Have a good day!";
											
											// sendemail($recipient, $name, $subject, $body);
											}
										}
										else {
											$newmobilenumbererror = "* Mobile Number Update Unsuccessful! Please Try Again.";
											$updateerrors++;
										}
									}
									
									if ($newguardianmobilenumber != "") {
										$sql = "UPDATE users SET guardianmobilenumber = '$newguardianmobilenumber' WHERE userid = $userid";
										$result = $connection->query($sql);
										
										if ($result === true) 
										
											{if ($emailaddresscode == 0) {
				
												$recipient = $emailaddress;
												$name = "$firstname $lastname";
												$subject = "Guardian Mobile Number Change";
												$body = "Hello $firstname $lastname!\n\nYour guardian mobile number was changed to: $newguardianmobilenumber\n\nThank You! Have a good day!";
											
											// sendemail($recipient, $name, $subject, $body);
											}
										}
										else {
											$newmobilenumbererror = "* Guardian Mobile Number Update Unsuccessful! Please Try Again.";
											$updateerrors++;
										}
									}
									
									if ($newusername != "") {
										$sql = "UPDATE users SET username = '$newusername' WHERE userid = $userid";
										$result = $connection->query($sql);
										
										if ($result === true) {
											
											if ($emailaddresscode == 0) {
				
												$recipient = $emailaddress;
												$name = "$firstname $lastname";
												$subject = "Username Change";
												$body = "Hello $firstname $lastname!\n\nYour username was changed to: $displayusername\nYou cannot log-in any more with your old username\n\nThank You! Have a good day!";
											
											// sendemail($recipient, $name, $subject, $body);
											}
										}
										else {
											$newusernameerror = "* Username Update Unsuccessful! Please Try Again.";
											$updateerrors++;
										}
									}
									
									if ($newpassword != "") {
										$sql = "UPDATE users SET password = '$newpassword' WHERE userid = $userid";
										$result = $connection->query($sql);
										
										if ($result === true) {
											
											if ($emailaddresscode == 0) {
				
											$recipient = $emailaddress;
												$name = "$firstname $lastname";
												$subject = "Password Change";
												$body = "Hello $firstname $lastname!\n\nYour password was changed to: $displaypassword\nYou cannot log-in any more with your old password\n\nThank You! Have a good day!";
											
											// sendemail($recipient, $name, $subject, $body);
											}
										}
										else {
											$newpassworderror = "* Password Update Unsuccessful! Please Try Again.";
											$updateerrors++;
										}
									}
									
									if ($updateerrors == 0) {
										$updateresult = "Profile Update Successful!";
									}
									
								}
		}
	}
		echo "<form action='editprofile.php' method='post' enctype='multipart/form-data'>";
		
		if ($userlevel == "Student") {
		
		if ($profilepicture == "") {
				$profilepicture = "logo.png";
		}
		
		
			echo "<br/><img src='images/profilepictures/$profilepicture' style='width:200px;height:200px;border-style: solid;border-width:3px;border-color:black;' alt='Profile Picture Unavailable'>
			<br/><br/>
			<table>
			<tr><td class='label'>Edit Profile Picture:</td> <td class='label1'><input type='file' name='newprofilepicture'></td><td><span class='error'>$newprofilepictureerror</span></td></tr>
			</table>
			<br/>";
		}
			echo "
				<table class='tbl'>
				<tr><td colspan=2><h4>Personal Details</h4></td></tr>
				<tr><td class='label'>Full Name:</td> <td class='label1'>$firstname $middlename $lastname</td></tr>
				<tr><td class='label'>Mobile Number:</td> <td class='label1'>$mobilenumber</td></tr>
				<tr><td class='label'>Gender:</td> <td class='label1'>$gender</td></tr>
				<tr><td class='label'>Date of Birth:</td> <td class='label1'>$dateofbirth</td></tr>
				<tr><td class='label'>Address:</td> <td class='label1'>$address</td></tr>
				<tr><td class='label'>Email Address:</td> <td class='label1'>$emailaddress</td></tr>
				<tr><td class='label'>Guardian Name:</td> <td class='label1'>$guardianname</td></tr>
				<tr><td class='label'>Guardian Mobile Number:</td> <td class='label1'>$guardianmobilenumber</td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Edit Mobile Number:</td> <td class='label1'><input type='text' name='newmobilenumber' value='$newmobilenumber'></td><td><span class='error'>$newmobilenumbererror</span></td></tr>
				<tr><td class='label'>Edit Email Address:</td> <td class='label1'><input type='text' name='newemailaddress' value='$newemailaddress'></td><td><span class='error'>$newemailaddresserror</span></td></tr>
				<tr><td class='label'>Edit Guardian Mobile Number:</td> <td class='label1'><input type='text' name='newguardianmobilenumber' value='$newguardianmobilenumber'></td><td><span class='error'>$newguardianmobilenumbererror</span></td></tr>
				<tr><td class='label'>Edit Username:</td> <td class='label1'><input type='text' name='newusername' value='$displayusername'></td><td><span class='error'>$newusernameerror</span></td></tr>
				<tr><td class='label'>Edit Password:</td> <td class='label1'><input type='password' name='newpassword' value='$displaypassword'></td><td><span class='error'>$newpassworderror</span></td></tr>
				<tr><td class='label'>Confirm New Password:</td> <td class='label1'><input type='password' name='confirmnewpassword'></td><td><span class='error'>$confirmnewpassworderror</span></td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Enter Current Username:</td> <td class='label1'><input type='text' name='username'></td><td><span class='error'>$currentusernameerror</span></td></tr>
				<tr><td class='label'>Enter Current Password:</td> <td class='label1'><input type='password' name='password'></td><td><span class='error'>$currentpassworderror</span></td></tr>
				</table>
				<br/>
				<input type='submit' name='upload' value='Submit'>
				</form>";

				
						echo "<script> 
							var x = messagealert('$updateresult'); 
							if (x == true) {
								window.location = '$backpage';
								}
							</script>"; 
						$updateresult = "";	

 $connection->close();
}
else {
	echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
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