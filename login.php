<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
	<title>Log-In</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css" />
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
			<div id='cssmenu' >
				<ul>
				   <li><a href='home.php'><span>Home</span></a></li>
				   <li><a href='curriculum.php'><span>Curriculum</span></a></li>
				   <li><a href='register.php'><span>Register</span></a></li>
				   <li class="last active"><a href='login.php'><span>Log-In</span></a></li>
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
							<li><a href="login.php">Log-In</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing:2px;font-weight:500;">Log-In</h1>	
						</div>
						</div>
					</div>
<center>
<?php

	$username = $password = $md5username = $md5password = $matchedusername = $matchedpassword = $userid = $firstname = $lastname = "";
	$usernameerror = $passworderror = $loginerror = $loginresult = "";
	$errorcount = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (isset($_POST["log-in"])) {
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	if (empty($username)) {
		$usernameerror = "* Username is Required!";
		$errorcount++;
	}
	else 
	{
		$md5username = md5($username, false);
	}
	
	if (empty($password)) {
		$passworderror = "* Password is Required!";
		$errorcount++;
	}
	else {
		$md5password = md5($password, false);
	}
	
	if ($errorcount == 0) {
	  $sql = "SELECT * FROM users WHERE username = '$md5username' AND password = '$md5password'";
	  $result = $connection->query($sql);

		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
				$matchedusername = $row->username;
				$matchedpassword = $row->password;
				$userid = $row->userid;
				$userlevel = $row->userlevel;
				$firstname = $row->firstname;
				$lastname = $row->lastname;
			}
		}
			if ($md5username == $matchedusername && $md5password == $matchedpassword) {
				$loginresult = "Log-In Sucessful $userlevel: $firstname $lastname!";
			}
		else {
			$loginerror = "Username and Password Does Not Matched! Please Try Again. <br/>
							<a href='resetpassword.php?username=$username'>Forgot Password?</a><br/>";
		}
	}
}
	else if (isset($_POST["log-out"])) {
		session_unset();
		session_destroy();
	}
}

if (!empty($_SESSION["userid"]) ) { // displays when the user accessed login.php without logging-out
	$firstname = $lastname = $backpage = "";
	
	$userlevel = $_SESSION["userlevel"];
	$firstname = $_SESSION["firstname"];
	$lastname = $_SESSION["lastname"];
	
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
	
	echo "<br/>Logged-In As <b> $userlevel: </b> $firstname $lastname <br/>
			<a href='$backpage'>Back</a>.<br/> <br/>
			<form method='post' action='#'>
			<input type='submit' name='log-out' value='Log-Out'>
			</form> <br/> <br/>"; // the user have the option to return or to log-out
}
else {

?>
<br/>
<form method="post" action="login.php">
<table>
<tr>
<td class="label">Username:</td>
<td><input type="text" name="username" placeholder="Enter Username" value="<?php echo $username; ?>"></td>
<td><span class="error"> <?php echo $usernameerror; ?> </span> </td>
</tr>
<tr>
<td class="label">Password:</td> 
<td><input type='password' name='password' placeholder='Enter Password'></td>
<td><span class="error"> <?php echo $passworderror; ?> </span> </td>
</tr>
</table>
<br/>
<input type='submit' name='log-in' value='Log-In'>
<br/><br/>
<span class="error"> <?php echo $loginerror; ?> </span>
</form>


<?php
}

$academicyear = $semester = $period = $displayenddate = $enddate = $datetoday = "";

$datetoday = date("Y-m-d");
// $datetoday = date("F d, Y h:i:s A"); datetoday with time stamp soon

$sql = "SELECT * FROM academic"; // retrieve the stored academic year, semester, and period form 'academic' table
$result = $connection->query($sql);
if ($result->num_rows == 1) {
	while ($row = $result->fetch_object()) {
		$academicyear = $row->academicyear;
		$semester = $row->semester;
		$period = $row->period;
		$displayenddate = date('F d, Y', strtotime($row->enddate));
		// $enddate = date('F d, Y h:i:s A', strtotime($row->enddate)); enddate displayed with time stamp soon
		$enddate = date("Y-m-d", strtotime($row->enddate));
	}
}
	if ($enddate <= $datetoday) { // SOON: compare enddate to datetoday with exact time stamp NOTE: for development purposes, 'Month-Day-Year' format is used for easier testing
	
	$academicyearid = $nextacademicyearid = $semesterid = $nextsemesterid = $periodid = $nextperiodid = 0;
	
		$sqlacademicyear = "SELECT * FROM sequenceacademicyear WHERE academicyear = '$academicyear'";
		$sqlsemester = "SELECT * FROM sequencesemester WHERE semester = '$semester'";
		$sqlperiod = "SELECT * FROM sequenceperiod WHERE period = '$period'";
			
		$resultacademicyear = $connection->query($sqlacademicyear);
		$resultsemester = $connection->query($sqlsemester);
		$resultperiod = $connection->query($sqlperiod);
	
		if ($resultacademicyear->num_rows == 1) {
			while ($rowacademicyear = $resultacademicyear->fetch_object()) {
				$academicyearid = $rowacademicyear->recordid;
				$nextacademicyearid = $academicyearid + 1;
			}
		}
		
		if ($resultsemester->num_rows == 1) {
			while ($rowsemester = $resultsemester->fetch_object()) {
				$semesterid = $rowsemester->recordid;
				$nextsemesterid = $semesterid + 1;
			}
		}
	
		if ($resultperiod->num_rows == 1) {
			while ($rowperiod = $resultperiod->fetch_object()) {
				$periodid = $rowperiod->recordid;
				$nextperiodid = $periodid + 1;
			}
		}
	
		$sql1 = "UPDATE academic SET period = '-' WHERE recordid = 1";
		$result1 = $connection->query($sql1);
		
		if ($periodid == 8) {
			$sql2 = "UPDATE academic SET semester = '-' WHERE recordid = 1";
			$result2 = $connection->query($sql2);
			
			$sql3 = "UPDATE sequenceperiod SET status = 0 WHERE recordid = $periodid";
			$result3 = $connection->query($sql3);

			$sql4 = "UPDATE sequenceperiod SET status = 1 WHERE recordid = 1";
			$result4 = $connection->query($sql4);
			
			if ($semesterid == 3) {
				$sql5 = "UPDATE academic SET academicyear = '-' WHERE recordid = 1";
				$result5 = $connection->query($sql5);
				
				$sql6 = "UPDATE sequencesemester SET status = 0 WHERE recordid = $semesterid";
				$result6 = $connection->query($sql6);
				
				$sql7 = "UPDATE sequencesemester SET status = 1 WHERE recordid = 1";
				$result7 = $connection->query($sql7);
				
				$sql8 = "UPDATE sequenceacademicyear SET status = 0 WHERE recordid = $academicyearid";
				$result8 = $connection->query($sql8);
				
				$sql9 = "UPDATE sequenceacademicyear SET status = 1 WHERE recordid = $nextacademicyearid";
				$result9 = $connection->query($sql9);
			}
			else {
				$sql5 = "UPDATE sequencesemester SET status = 0 WHERE recordid = $semesterid";
				$result5 = $connection->query($sql5);
				
				$sql6 = "UPDATE sequencesemester SET status = 1 WHERE recordid = $nextsemesterid";
				$result6 = $connection->query($sql6);
			}
		}
		else {
			$sql2 = "UPDATE sequenceperiod SET status = 0 WHERE recordid = $periodid";
			$result2 = $connection->query($sql2);

				if ($periodid == 2 && $semesterid == 3) {
					$sql3 = "UPDATE sequenceperiod SET status = 1 WHERE recordid = 7";
					$result3 = $connection->query($sql3);
				}
				else {
					$sql3 = "UPDATE sequenceperiod SET status = 1 WHERE recordid = $nextperiodid";
					$result3 = $connection->query($sql3);
				}
		}
		
		$sql = "SELECT * FROM academic"; // repeat to get the updated details
		$result = $connection->query($sql);
		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
				$academicyear = $row->academicyear;
				$semester = $row->semester;
				$period = $row->period;
				$displayenddate = date('F d, Y', strtotime($row->enddate));
				// $enddate = date('F d, Y h:i:s A', strtotime($row->enddate)); enddate displayed with time stamp soon
			}
		}
	}
	echo "<br/><br/> <h4> $period Period is Ongoing for <br/>
		Academic Year $academicyear: $semester <br/>
		Until $displayenddate!</h4>  <br/><br/>"; // displays the current academic year, semester, period, and end date
		
$connection->close();

if ($loginresult != "") {
	$_SESSION['userid'] = $userid; // set userid in $_SESSION for later use
	$_SESSION['userlevel'] = $userlevel;
	
echo "<script> 
	var x = messagealert('$loginresult'); 
	if (x == true) {
		var userlevel = '$userlevel';
			if (userlevel == 'Admin') { // if the user is admin
				window.location = 'admin.php?userid=$userid&userlevel=$userlevel'; // userid and userlevel are displayed on the URL
			}
			else if (userlevel == 'Registrar Staff'){ // if the user is cashier
				window.location = 'registrar.php?userid=$userid&userlevel=$userlevel'; // userid and userlevel are displayed on the URL
			}
			else if (userlevel == 'Cashier'){ // if the user is cashier
				window.location = 'payment.php?userid=$userid&userlevel=$userlevel'; // userid and userlevel are displayed on the URL
			}
			else if (userlevel == 'Student'){ // if the user is student
				window.location = 'profile.php?userid=$userid&userlevel=$userlevel'; // userid and userlevel are displayed on the URL
			}
		}
	</script>";
	$loginresult = "";
}
?>	
</div>
</div>
</section>
<table class='footer printignore'><tr><td align='center'>© 2016-2017 →rEVOLution← Studios</td></tr></table>
</body>
</html>