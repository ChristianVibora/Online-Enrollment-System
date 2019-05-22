<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Register</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/print.css">
	<link rel="stylesheet" href="css/zerogrid.css">
	<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/menu.css">
	<script src="js/jquery-1.12.2.min.js" type="text/javascript"></script>
	<script src="js/script.js"></script>
</head>

<body class="single-page">
		<div class="wrap-body">
		<div class='printignore'>
		<div class="header">
			<div id='cssmenu'>
				<ul>
				   <li><a href='home.php'><span>Home</span></a></li>
				   <li><a href='curriculum.php'><span>Curriculum</span></a></li>
				   <li class="active"><a href='register.php'><span>Register</span></a></li>
				   <li class='last'><a href='login.php'><span>Log-In</span></a></li>
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
							<li><a href="home.php">Home</a></li>
							<li><a href="register.php">Register</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Registration</h1>
						</div>
						</div>
					</div>
<center>
<?php

$firstname = $middlename = $lastname = $mobilenumber = $gender = $dateofbirth = $address = $emailaddress = $guardianname = $guardianmobilenumber = $username = $displayusername = $password = $displaypassword = $confirmpassword = $securityquestion = $securityanswer = $registrationresult = "";

$firstnameerror = $middlenameerror = $lastnameerror = $mobilenumbererror = $gendererror = $dateofbirtherror = $addresserror = $emailaddresserror = $guardiannameerror = $guardianmobilenumbererror = $usernameerror = $passworderror = $confirmpassworderror = $securityquestionerror = $securityanswererror = "";

$errorcount = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$firstname = $_POST["firstname"];
	$middlename = $_POST["middlename"];
	$lastname = $_POST["lastname"];
	$mobilenumber = $_POST["mobilenumber"];
	$dateofbirth = $_POST["dateofbirth"];
	$address = $_POST["address"];
	$emailaddress = $_POST["emailaddress"];
	$guardianname = $_POST["guardianname"];
	$guardianmobilenumber = $_POST["guardianmobilenumber"];
	$username = $_POST["username"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
	$securityanswer = $_POST["securityanswer"];
	
	// data valition for the next if-else blocks
	
	if (empty($firstname)) {
		$firstnameerror = "* First Name is Required!";
		$errorcount++;
	} 
	else {
    $firstname = validateinput($firstname);
		if (!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
		  $firstnameerror = "* Only Letters and White Space Allowed!"; 
		  $errorcount++;
		}
	}

	if (empty($middlename)) {
		$middlenameerror = "* Middle Name is Required!";
		$errorcount++;
	} 
	else {
    $middlename = validateinput($middlename);
		if (!preg_match("/^[a-zA-Z ]*$/", $middlename)) {
		  $middlenameerror = "* Only Letters and White Space Allowed!";
		  $errorcount++;
		}
	}
	
	if (empty($lastname)) {
		$lastnameerror = "* Last Name is Required!";
		$errorcount++;
	} 
	else {
    $lastname = validateinput($lastname);
		if (!preg_match("/^[a-zA-Z ]*$/", $lastname)) {
		  $lastnameerror = "* Only Letters and White Space Allowed!";
		  $errorcount++;
		}
	}
	
	if ($firstnameerror == "" && $middlenameerror == "" && $lastnameerror == "") {
		if (checkfullname($firstname, $middlename, $lastname) === true) {
			$firstnameerror = "* First Name is Not Available!";
			$middlenameerror = "* Middle Name is Not Available!";
			$lastnameerror = "* Last Name is Not Available!";
			$errorcount++;
		}
	}
	
	if (empty($mobilenumber)) {
		$mobilenumbererror = "* Mobile Number is Required!";
		$errorcount++;
	} 
	else {
    $mobilenumber = validateinput($mobilenumber);
		if (!preg_match("/^[0-9]*$/", $mobilenumber) || strlen($mobilenumber) != 11) { 
		  $mobilenumbererror = "* Only 11 Digits Are Allowed!";
		  $errorcount++;
		}
		else if (checkmobilenumber($mobilenumber) === true) {
			$mobilenumbererror = "* Mobile Number is Not Available!";
			$errorcount++;
		}
	}
	
	if (empty($_POST["gender"])) {
		$gendererror = "* Gender is Required!";
		$errorcount++;
	} 
	else {
		$gender = $_POST["gender"];
		$gender = validateinput($gender);
	}
	
	if (empty($dateofbirth)) {
		$dateofbirtherror = "* Date of Birth is Required!";
		$errorcount++;
	} 
	else {
		$dateofbirth = validateinput($dateofbirth);
	}
	
	if (empty($address)) {
		$addresserror = "* Address is Required!";
		$errorcount++;
	} 
	else {
		$address = validateinput($address);
		$address = addslashes($address);
	}

	if (empty($emailaddress)) {
		$emailaddresserror = "* Email Address is Required!";
	} 
	else {
		$emailaddress = validateinput($emailaddress);
		if (!filter_var($emailaddress, FILTER_VALIDATE_EMAIL)) {
			$emailaddresserror = "* Invalid Email Address Format!"; 
			$errorcount++;
		}
		else {
			$emailaddress = addslashes($emailaddress);
			if (checkemailaddress($emailaddress) === true) {
				$emailaddresserror = "* Email Address is Not Available!"; 
				$errorcount++;
			}
		}
	}
	
	if (empty($guardianname)) {
		$guardiannameerror = "* Guardian's Name is Required!";
		$errorcount++;
	} 
	else {
    $guardianname = validateinput($guardianname);
		if (!preg_match("/^[a-zA-Z .]*$/", $guardianname)) {
		  $guardiannameerror = "* Only Letters and White Space Allowed!"; 
		  $errorcount++;
		}
	}
	
	if (empty($guardianmobilenumber)) {
		$guardianmobilenumbererror = "* Guardian's Mobile Number is Required!";
		$errorcount++;
	} 
	else {
    $guardianmobilenumber = validateinput($guardianmobilenumber);
		if (!preg_match("/^[0-9]*$/", $guardianmobilenumber) || strlen($guardianmobilenumber) != 11) { 
		  $guardianmobilenumbererror = "* Only 11 Digits Are Allowed!";
		  $errorcount++;
		}
		else if (checkmobilenumber($guardianmobilenumber) === true) {
			$guardianmobilenumbererror = "* Mobile Number is Not Available!";
			$errorcount++;
		}
	}
	
	if (empty($username)) {
		$usernameerror = "* Username is Required!";
		$errorcount++;
	} 
	else {
		$username = validateinput($username);
		$displayusername = $username;
		if (strlen($username) < 8) {
			$usernameerror = "* Username Should Contain At Least 8 Characters!"; 
			$errorcount++;
		}
		else {
			$username = md5($username, false);
			if (checkusername($username) === true) {
			$usernameerror = "* Username is Not Available!"; 
			$errorcount++;
		}
		}
	}
	
	if (empty($password)) {
		$passworderror = "* Password is Required!";
		$errorcount++;
	} 
	else {
		$password = validateinput($password);
		$displaypassword = $password;
			if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password) || strlen($password) < 8) { // TODO: validate letters and numbers combination
				$passworderror = "* Password Should Contain At Least 8 Letters and Numbers!"; 
				$errorcount++;
		}
	}
	
	if (empty($confirmpassword)) {
		$confirmpassworderror = "* Password Confirmation is Required!";
		$errorcount++;
	} 
	else {
		$confirmpassword = validateinput($confirmpassword);
			if ($confirmpassword != $password) {
				$confirmpassworderror = "* Passwords Should Match!"; 
				$errorcount++;
		}
		else {
			$displaypassword = $password;
			$password = md5($password, false);
		}
	}
	
	if (empty($_POST["securityquestion"])) {
		$securityquestionerror = "* Security Question is Required!";
		$errorcount++;
	} 
	else {
		$securityquestion = $_POST["securityquestion"];
		$securityquestion = validateinput($securityquestion);
			if (empty($securityanswer)) {
				$securityquestion = $_POST["securityquestion"];
				$securityquestion = validateinput($securityquestion);
				$securityquestionerror = "** Security Answer is Required!";
				$errorcount++;
			}
	}
	
	if (empty($securityanswer)) {
			$securityanswererror = "* Security Answer is Required!";
			$errorcount++;
	} 
	else {
		if (empty($_POST["securityquestion"])) {
			$securityanswer = validateinput($securityanswer);
			$securityanswererror = "** Security Question is Required!";
			$errorcount++;
		}
		else {
			$securityanswer = validateinput($securityanswer);
			$securityanswer = addslashes($securityanswer);
		}
	}
	// data validation
	
if ($errorcount == 0) { 
	
	$emailaddresscode = mt_rand(100000, 999999);

	$sql = "INSERT INTO users (firstname, middlename, lastname, mobilenumber, gender, dateofbirth, address, emailaddress, emailaddresscode, guardianname, guardianmobilenumber, username, password, userlevel, securityquestion, securityanswer) VALUES ('$firstname', '$middlename', '$lastname', '$mobilenumber', '$gender', '$dateofbirth', '$address', '$emailaddress', '$emailaddresscode', '$guardianname', '$guardianmobilenumber', '$username', '$password', 'Student', '$securityquestion', '$securityanswer')";
	$result = $connection->query($sql);
	
	if ($result === true) {
		$registrationresult = "Registration Successful! You May Now Log-In With Your Username and Password.";
		
		$firstname = $middlename = $lastname = $mobilenumber = $gender = $dateofbirth = $address = $emailaddress = $guardianname = $guardianmobilenumber = $username = $password = $confirmpassword = $securityquestion = $securityanswer = "";
	}
	else {
		 $registrationresult = "Registration Unsuccessful! Please Try Again Later.";
	}
}
}

$connection->close();

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	echo " <img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
	Welcome to Tanauan Institute, Inc.<br/>
	Log-Out First To Your Account <a href='login.php'>Here</a>.";
}
else {
?>
<br/>

<form method="post" action="register.php">
<table class="tbl">
<tr><td class="label">First Name:</td> <td><input type="text" name="firstname" value="<?php echo $firstname; ?>"></td> <td><span class="error"> <?php echo $firstnameerror; ?> </span></td></tr>
<tr><td class="label">Middle Name:</td> <td><input type="text" name="middlename" value="<?php echo $middlename; ?>"></td> <td><span class="error"> <?php echo $middlenameerror; ?> </span></td></tr>
<tr><td class="label">Last Name:</td> <td><input type="text" name="lastname" value="<?php echo $lastname; ?>"></td> <td><span class="error"> <?php echo $lastnameerror; ?> </span></td></tr>
<tr><td class="label">Gender:</td> <td><input type="radio" name="gender" <?php if (isset($gender) && $gender == "Female") { echo "checked"; } ?> value="Female">Female</input> <input type="radio" name="gender" <?php if (isset($gender) && $gender == "Male") { echo "checked"; } ?> value="Male">Male</input></td> <td><span class="error"> <?php echo $gendererror; ?> </span></td></tr>
<tr><td class="label">Mobile Number:</td> <td><input type="text" name="mobilenumber" value="<?php echo $mobilenumber; ?>"></td> <td><span class="error"> <?php echo $mobilenumbererror; ?> </span></td></tr>
<tr><td class="label">Date of Birth:</td> <td><input type="date" name="dateofbirth" class="dateofbirth" value="<?php echo $dateofbirth; ?>"></td> <td><span class="error"> <?php echo $dateofbirtherror; ?> </span></td></tr>
<tr><td class="label">Address:</td><td><input type ="text"  name="address" value="<?php echo $address; ?>"></td> <td><span class="error"> <?php echo $addresserror; ?> </span></td></tr>
<tr><td class="label">Email:</td><td><input type ="text"  name="emailaddress" value="<?php echo $emailaddress; ?>"></td> <td><span class="error"> <?php echo $emailaddresserror; ?> </span></td></tr>
<tr><td class="label">Guardian's Name:</td><td><input type ="text"  name="guardianname" value="<?php echo $guardianname; ?>"></td> <td><span class="error"> <?php echo $guardiannameerror; ?> </span></td></tr>
<tr><td class="label">Guardian's Mobile Number:</td><td><input type ="text"  name="guardianmobilenumber" value="<?php echo $guardianmobilenumber; ?>"></td> <td><span class="error"> <?php echo $guardianmobilenumbererror; ?> </span></td></tr>
<tr><td class="label">*Username: </td><td><input type="text" name="username" value="<?php echo $displayusername; ?>"></td> <td><span class="error"> <?php echo $usernameerror; ?> </span></td></tr>
<tr><td class="label">**Password: </td><td><input type ="password" name="password" value="<?php echo $displaypassword; ?>"></td> <td><span class="error"> <?php echo $passworderror; ?> </span></td></tr>
<tr><td class="label">Confirm Password:</td><td><input type ="password" name="confirmpassword"></td> <td/><span class="error"> <?php echo $confirmpassworderror; ?> </span></td></tr>
<tr><td class="label">Security Question:</td><td><select name="securityquestion" class="securityquestion">
<option <?php if ($securityquestion == "") { echo "selected"; } ?> disabled value="">Select Security Question</option>
<option <?php if ($securityquestion == "Who is your favorite childhood superhero?") { echo "selected"; } ?> value="Who is your favorite childhood superhero?">Who is your favorite childhood superhero?</option>
<option <?php if ($securityquestion == "Where do you want to live in the next 10 years?") { echo "selected"; } ?> value="Where do you want to live in the next 10 years?">Where do you want to live in the next 10 years?</option>
<option <?php if ($securityquestion == "How old are you when you got your first cellphone?") { echo "selected"; } ?> value="How old are you when you got your first cellphone?">How old are you when you got your first cellphone?</option>
<option <?php if ($securityquestion == "What is the maiden name of your mother?") { echo "selected"; } ?> value="What is the maiden name of your mother?">What is the maiden name of your mother?</option>
<option <?php if ($securityquestion == "Who said your favorite quotation?") { echo "selected"; } ?> value="Who said your favorite quotation?">Who said your favorite quotation?</option>
</select></td> <td> <span class="error"> <?php echo $securityquestionerror; ?> </span></td></tr>
<tr><td class="label">Security Answer:</td><td><input type="text" name="securityanswer" value="<?php echo $securityanswer; ?>"></td> <td> <span class="error"> <?php echo $securityanswererror; ?> </span></td></tr>
</table>
<br/><br/>

<table class="tbl">
<tr>
<td><b>* Username</b> Should Contain At Least 8 Characters.<br/></td>
</tr>
<tr>
<td><b>** Password</b> Password Should Contain At Least 8 Letters and Numbers.</td>
</tr>
</table>

<br/><br/>
<input type="submit" name="submit" value="Register">
</form>
<br/>
<?php 	
}
echo "<script> 
		var x = messagealert('$registrationresult'); 
		if (x == true) {
			window.location = 'login.php';
		}
	</script>"; 
	$registrationresult = "";			
?>
</div>
</div>
</section>
<table class='footer printignore'><tr><td align='center'>© 2016-2017 →rEVOLution← Studios</td></tr></table>
</body>
</html>