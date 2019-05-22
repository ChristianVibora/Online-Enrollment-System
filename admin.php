<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Admin</title>
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
				   <li class="active"><a href='admin.php'><span>Admin</span></a></li>
				   <li><a href='classlist.php'><span>Class Lists</span></a></li>
				   <li><a href='search.php'><span>Search</span></a></li>
				   <li><a href='fees.php'><span>Fees</span></a></li>
				   <li><a href='creditsdebitsrefunds.php'><span>Discounts | Debits | Refunds</span></a></li>
				   <li><a>Logs</a>
				   <ul class="cssdropdownmenu">
				   <li><a href='paymentlog.php'><span>Payment Log</span></a></li>
				   <li><a href='chequepaymentlog.php'><span>Cheque Payment Log</span></a></li>
				   <li><a href='creditslog.php'><span>Discounts Log</span></a></li>
				   <li><a href='refundslog.php'><span>Refunds Log</span></a></li>
				   </ul>
				   </li>
				   <li><a>Utilities</a>
				   <ul class="cssdropdownmenu">
				   <li><a href='sectionmanagement.php'><span>Section Management</span></a></li>
				   <li><a href='curriculumeditor.php'><span>Curriculum Editor</span></a></li>
				   <li><a href='feeseditor.php'><span>Fees Editor</span></a></li>
				   </ul>
				   </li>
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
							<li><a href="admin.php">Admin</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
							<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Admin</h1>
						</div>
						</div>
					</div>
<center>
<?php

$userid = $firstname = $lastname = $password = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION
	
	if ($userlevel == "Admin") { // if the user is admin
	$sql = "SELECT * FROM users WHERE userid = '$userid'";
	$result = $connection->query($sql);

if ($result->num_rows == 1) {
	while ($row = $result->fetch_object()) {
		$firstname = $row->firstname;
		$lastname = $row->lastname;
		$password = $row->password;
		$_SESSION["firstname"] = $firstname;
		$_SESSION["lastname"] = $lastname;
}
}
		echo " Welcome to Tanauan Institute, Inc. <b>Admin:</b> $firstname $lastname. <br/>
			<form method='post' action='editprofile.php'>
			<input type='submit' name='editprofile' value='Edit Profile'>
			</form>
			<br/>";
		
$currentacademicyear = $currentsemester = $currentperiod = $startdate = $enddate = "";

$sql = "SELECT * FROM academic";
$result = $connection->query($sql);

if ($result->num_rows == 1) {
	while ($row = $result->fetch_object()) {
		$currentacademicyear = $row->academicyear;
		$currentsemester = $row->semester;
		$currentperiod = $row->period;
		$startdate = date('F d, Y h:i:s A', strtotime($row->startdate));
		$enddate = date('F d, Y', strtotime($row->enddate)); // todo: update current academic details when the current date hits $enddate
		// $enddate = date('F d, Y h:i:s A', strtotime($row->enddate)); end date displayed with timestamp soon to implement;
				echo " <h3> Current Academic Year/Semester/Period </h3>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Academic Year </th> 
						<th> Semester </th>
						<th> Period </th>
						<th> Date Started </th>
						<th> End Date </th>
					</tr>
					<tr align='center'>
						<td> $currentacademicyear </td>
						<td> $currentsemester </td>
						<td> $currentperiod </td>
						<td> $startdate </td>
						<td> $enddate </td>
					</tr>
					</table> "; // displays the current academic details: Academic Year, Semester, Period, Start Date, and End Date
	}
}
		echo "
			<br/><br/>
			<form method='post' action='admin.php'>
			<input type='submit' name='update' value='Update Academic Year/Semester/Period'>
			</form>
			<br/><br/><hr/>"; // this form is only displayed when the user logged-in correctly

	
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$academicyear = $academicyearstatus = $semester = $semesterstatus = $period = $periodstatus = "";
		
		if(isset($_POST["update"])) {
			
			$sqlacademicyear = "SELECT * FROM sequenceacademicyear";
			$sqlsemester = "SELECT * FROM sequencesemester";
			$sqlperiod = "SELECT * FROM sequenceperiod";
			
			$resultacademicyear = $connection->query($sqlacademicyear);
			$resultsemester = $connection->query($sqlsemester);
			$resultperiod = $connection->query($sqlperiod);
			
			$valuedate = date('Y-m-d', strtotime($enddate));
			
			echo "<br/><br/>
			<form method='post' action='admin.php'>
			<table>
			<tr ><td class='label'>Academic Year: </td><td class='label1'><select name='academicyear'>";
?>
			<option <?php if ($currentacademicyear == "-") { echo "selected"; } ?> disabled value="">Select Academic Year:</option>
<?php
			if ($resultacademicyear->num_rows > 0) {
				while ($rowacademicyear = $resultacademicyear->fetch_object()) {
					$academicyear = $rowacademicyear->academicyear;
					$academicyearstatus = $rowacademicyear->status;
?>
			<option <?php if ($academicyearstatus == 0) { echo "disabled"; } else if ($academicyearstatus == 1) { echo "selected"; } if ($currentacademicyear == $academicyear) { echo "selected"; } ?> value="<?php echo $academicyear; ?>"><?php echo $academicyear; ?></option>
<?php 
				}
			echo "</select></td></tr>"; 
			}
			echo "<tr><td class='label'>Semester: </td><td class='label1'> <select name='semester'>";
?>
			<option <?php if ($currentsemester == "-") { echo "selected"; } ?> disabled value="">Select Semester:</option>
<?php
			if ($resultsemester->num_rows > 0) {
				while ($rowsemester = $resultsemester->fetch_object()) {
					$semester = $rowsemester->semester;
					$semesterstatus = $rowsemester->status;
?>
			<option <?php if ($semesterstatus == 0) { echo "disabled"; } else if ($semesterstatus == 1) { echo "selected"; } if ($currentsemester == $semester) { echo "selected"; } ?> value="<?php echo $semester; ?>"><?php echo $semester; ?></option>
<?php
				}
				echo "</select></td></tr>"; 
			}
			echo "<tr><td class='label'>Period:</td><td class='label1'> <select name='period'>";
?>
			<option <?php if ($currentperiod == "-") { echo "selected"; } ?> disabled value="">Select Period:</option>
<?php
			if ($resultperiod->num_rows > 0) {
				while ($rowperiod = $resultperiod->fetch_object()) {
					$periodid = $rowperiod->recordid;
					$period = $rowperiod->period;
					$periodstatus = $rowperiod->status;
?>
			<option <?php if ($periodstatus == 0) { echo "disabled"; } else if ($periodstatus == 1) { echo "selected"; } if ($currentperiod == $period) { echo "selected"; $_SESSION["periodid"] = $periodid; } ?> value="<?php echo $period; ?>"><?php echo $period; ?></option>
<?php
				}
				echo "</select></td></tr>"; 
			}
?>
			<tr><td class="label">End Date: </td><td class="label1"><input type="date" name="enddate" value="<?php echo $valuedate; ?>"></td></tr>
			<tr><td></td><td></td></tr>
			<tr><td></td><td></td></tr>
			<tr><td></td><td></td></tr>
			<tr><td class="label">Enter Your Password:</td><td class="label1"> <input type="password" name="confirmpassword"></td></tr>
			</table>
			<br/>
			<input type="submit" name="submit" value="Submit">
			</form> <!-- displays the form when Update Academic Year/Semester/Period button is clicked -->
			<br/>
			
<?php
		}
		else if (isset($_POST["submit"])) {
			
			$confirmpassword = $updateacademicyear = $updatesemester = $updateperiod = $now = $updateenddate = $timenow = "";
			$updateacademicyearerror = $updatesemestererror = $updateperioderror = $updateenddateerror = $updateenddateerror1 = $passworderror = $updateresult = "";
			$errorcount = 0;
			
			//	$timenow = date("H:i:s"); get the current time stamp to add to the value from input 'date' type
			$now = date("Y-m-d"); // look for 24hour date format
			$confirmpassword = $_POST["confirmpassword"];
			//	$updateenddate = date("Y-m-d $timenow", strtotime($_POST["enddate"])); end date with timestamp soon to implement
			
			// validation blocks
			if (empty($_POST["academicyear"])) {
				$updateacademicyearerror = "<b>Error: </b> Academic Year is Required!";
				$errorcount++;
			}
			else {
				$updateacademicyear = $_POST["academicyear"];
			}
			
			if (empty($_POST["semester"])) {
				$updatesemestererror = "<b>Error: </b>Semester is Required!";
				$errorcount++;
			}
			else {
				$updatesemester = $_POST["semester"];
			}
			
			if (empty($_POST["period"])) {
				$updateperioderror = "<b>Error: </b>Period is Required!";
				$errorcount++;
			}
			else {
				$updateperiod = $_POST["period"];
			}
			
			if (empty($_POST["enddate"])) {
				$updateenddateerror = "<b>Error: </b> End Date is Required!";
				$errorcount++;
			}
			else {
				$updateenddate = $_POST["enddate"];
			}
			
			if ($updateenddate <= $now) {
				$updateenddateerror1 = "<b>Error: </b> Please Provide an End Date That is Later Than Today!";
				// $errorcount++;
			}
		
			if (empty($confirmpassword)) {
				$passworderror = "<b>Error: </b> Password is Required!";
				$errorcount++;
			}
			else {
				if (md5($confirmpassword, false) != $password) {
					$passworderror = "<b>Error: </b> Password is Incorrect!";
					$errorcount++;
				}
			}
			
			if ($errorcount > 0) {
				
				echo "<br/><form method='post' action='admin.php'>
					<table class='tbl'>
					<tr><td> <span class='error'>$updateacademicyearerror</span> </td></tr>
					<tr><td> <span class='error'>$updatesemestererror</span> </td></tr>
					<tr><td> <span class='error'>$updateperioderror</span> </td></tr>
					<tr><td> <span class='error'>$updateenddateerror</span> </td></tr>
					<tr><td> <span class='error'>$updateenddateerror1</span> </td></tr>
					<tr><td> <span class='error'>$passworderror</span> </td></tr>
					</table>
					<br/><br/>
					<input type='submit' name='update' value='Try Again'>
					</form>
					<br/>
					";
				
			}
			else {
				$updatependingpayment = $pastfee = $message = "";
				$emailenddate = date('F d, Y', strtotime($updateenddate));
				
				if ($updateperiod == "Enrollment") { 
					$updatependingpayment = "downpaymentfee"; 
					$message = "You can now enroll for your next subjects until $emailenddate."; 
				}
				else if ($updateperiod == "Payment") { 
					$updatependingpayment = "downpaymentfee"; 
					$message = "Enrollment is closed, and you can now settle your Down-Payment Fee until $emailenddate.. Disregard this message if you have already settled your fee."; 
				}
				else if ($updateperiod == "Prelims") { 
					$pastfee = "downpaymentfee"; 
					$updatependingpayment = "prelimsfee"; 
					$message = "You can now settle your Prelims Fee for Prelim Examinations until $emailenddate. Disregard this message if you have already settled your fee.";
				}
				else if ($updateperiod == "Midterms") { 
					$pastfee = "prelimsfee"; 
					$updatependingpayment = "midtermsfee"; 
					$message = "You can now settle your Midterms Fee for Midterm Examinations until $emailenddate. Disregard this message if you have already settled your fee.";
				}
				else if ($updateperiod == "Pre-Finals") { 
					$pastfee = "midtermsfee"; 
					$updatependingpayment = "prefinalsfee";
					$message = "You can now settle your Pre-Finals Fee for Pre-Final Examinations until $emailenddate. Disregard this message if you have already settled your fee.";					
				}
				else if ($updateperiod == "Finals") { 
					$pastfee = "prefinalsfee"; 
					$updatependingpayment = "finalsfee"; 
					$message = "You can now settle your Finals Fee for Final Examinations until $emailenddate. Disregard this message if you have already settled your fee.";
				}
				else if ($updateperiod == "Clearance") { 
					$pastfee = "finalsfee"; 
					$message = "You can now get clearance after you settle your fees and subjects until $emailenddate. Disregard this message if you have already settled your fees and subjects.";
				}
				else if ($updateperiod == "Vacation") { 
					$message = "You can now enjoy vacation until $emailenddate. See you next enrollment period!";
				}
				else {}
				
				if ($updateperiod == "Clearance") {
					
					if ($updatesemester == "Summer") {
						$sqluser = "UPDATE users INNER JOIN enrolledstudents ON enrolledstudents.studentnumber = users.userid SET userstatus = 2 WHERE studentstatus = 'Pending'";
						$resultuser = $connection->query($sqluser);
						
						$sql7 = "DELETE FROM enrolledstudents WHERE studentstatus = 'Pending'";
						$result7 = $connection->query($sql7);
							
						$sql8 = "DELETE FROM enrolledsubjects WHERE subjectstatus = 'Pending'";
						$result8 = $connection->query($sql8);
							
						$sql9 = "DELETE FROM creditedsubjects WHERE subjectstatus = 'Pending'";
						$result9 = $connection->query($sql9);
							
						$sql10 = "DELETE FROM enrolledfees WHERE balancestatus = 'Pending'";
						$result10 = $connection->query($sql10);
					}
					else {
						$resultuser = $result7 = $result8 = $result9 = $result10 = true;
					}
					
					$sql1 = "UPDATE academic SET academicyear = '$updateacademicyear', semester = '$updatesemester', period = '$updateperiod', enddate = '$updateenddate' WHERE recordid = 1";
					$result1 = $connection->query($sql1);
						
					$sql2 = "INSERT INTO studentdebits (studentnumber, debitvalue, debitname) SELECT studentnumber, $pastfee, pendingpayment FROM enrolledfees WHERE pendingpayment = '$pastfee'";
					$result2 = $connection->query($sql2);
					
					$sql3 = "UPDATE enrolledfees SET $pastfee = -2 WHERE pendingpayment = '$pastfee'";
					$result3 = $connection->query($sql3);
					
					$sql6 = "UPDATE enrolledfees SET pendingpayment = '' WHERE pendingpayment = '$pastfee' AND $pastfee = -2";
					$result6 = $connection->query($sql6);
					
					$sql4 = "UPDATE enrolledstudents INNER JOIN enrolledfees ON enrolledfees.studentnumber = enrolledstudents.studentnumber INNER JOIN enrolledsubjects ON enrolledsubjects.studentnumber = enrolledfees.studentnumber SET enrolledstudents.studentstatus = 'Cleared', enrolledsubjects.subjectstatus = 'Passed' WHERE enrolledfees.balancestatus = 'Fully Paid'";
					$result4 = $connection->query($sql4);
					
					$sql5 = "UPDATE enrolledstudents INNER JOIN enrolledfees ON enrolledfees.studentnumber = enrolledstudents.studentnumber INNER JOIN enrolledsubjects ON enrolledsubjects.studentnumber = enrolledfees.studentnumber SET enrolledstudents.studentstatus = 'Pending', enrolledsubjects.subjectstatus = 'Pending' WHERE enrolledfees.balancestatus = 'Partially Paid'";
					$result5 = $connection->query($sql5);
					
					if ($result1 === true && $result2 === true && $result3 === true && $result4 === true && $result5 === true && $result6 === true && $resultuser === true && $result7 === true && $result8 === true && $result9 === true && $result10 === true) {
					$updateresult = "Academic Year/Semester/Period Update Successful!";
					
						$sqlemail = "SELECT * FROM users WHERE emailaddresscode = 0 AND userstatus = 1 AND userlevel = 'Student'";
						$resultemail = $connection->query($sqlemail);
						
						if ($resultemail->num_rows > 0) {
							while ($row = $resultemail->fetch_object()) {
								
								$emailaddress = $row->emailaddress;
								$firstname = $row->firstname;
								$lastname = $row->lastname;
								
								$recipient = $emailaddress;
								$name = "$firstname $lastname";
								$subject = "$updateperiod Period";
								$body = "Hello $firstname $lastname!\n\n$updateperiod Period has begun for Academic Year $updateacademicyear: $updatesemester\n$message\n\nThank You! Have a good day!";
								
								// sendemail($recipient, $name, $subject, $body);
							}
						}
					
					}
					else {
					$updateresult = "Academic Year/Semester/Period Update Unsuccessful. Please Try Again.";
					}
				}
				else if ($updateperiod == "Enrollment" || $updateperiod == "Payment" || $updateperiod == "Vacation") {
					$sql = "UPDATE academic SET academicyear = '$updateacademicyear', semester = '$updatesemester', period = '$updateperiod', enddate = '$updateenddate' WHERE recordid = 1";
					$result = $connection->query($sql);
					
					if ($result === true) {
					$updateresult = "Academic Year/Semester/Period Update Successful!";
					
					$sqlemail = "SELECT * FROM users WHERE emailaddresscode = 0 AND userlevel = 'Student'";
					$resultemail = $connection->query($sqlemail);
						
						if ($resultemail->num_rows > 0) {
							while ($row = $resultemail->fetch_object()) {
								
								$emailaddress = $row->emailaddress;
								$firstname = $row->firstname;
								$lastname = $row->lastname;
								
								$recipient = $emailaddress;
								$name = "$firstname $lastname";
								$subject = "$updateperiod Period";
								$body = "Hello $firstname $lastname!\n\n$updateperiod Period has begun for Academic Year $updateacademicyear: $updatesemester\n$message\n\nThank You! Have a good day!";
								
								// sendemail($recipient, $name, $subject, $body);
							}
						}
					
					}
					else {
					$updateresult = "Academic Year/Semester/Period Update Unsuccessful. Please Try Again.";
					}
				}
				else {
					
					$sqltrevoke = "UPDATE enrolledstudents INNER JOIN enrolledfees ON enrolledfees.studentnumber = enrolledstudents.studentnumber INNER JOIN enrolledsubjects ON enrolledsubjects.studentnumber = enrolledfees.studentnumber LEFT JOIN creditedsubjects ON creditedsubjects.studentnumber = enrolledsubjects.studentnumber SET enrolledstudents.studentstatus = 'Revoked', enrolledsubjects.subjectstatus = 'Revoked', creditedsubjects.subjectstatus = 'Revoked', enrolledfees.balancestatus = 'Revoked' WHERE enrolledfees.balancestatus = 'Pending' AND enrolledstudents.academicyear = '$currentacademicyear' AND enrolledstudents.semester = '$currentsemester'";
					$resultrevoke = $connection->query($sqltrevoke);
					
					$sqluser = "UPDATE users INNER JOIN enrolledstudents ON enrolledstudents.studentnumber = users.userid SET userstatus = 2 WHERE studentstatus = 'Revoked'";
					$resultuser = $connection->query($sqluser);
					
					$sqlenrolledstudents = "INSERT INTO historyenrolledstudents (referencenumber, studentnumber, academicyear, semester, studentcourse, studentyear, studentsection, enrollmenttype, studentstatus, enrollmentdate) SELECT referencenumber, studentnumber, academicyear, semester, studentcourse, studentyear, studentsection, enrollmenttype, studentstatus, enrollmentdate FROM enrolledstudents WHERE studentstatus = 'Revoked'";
					$resultenrolledstudents = $connection->query($sqlenrolledstudents);
							
					$sqlenrolledsubjects = "INSERT INTO historyenrolledsubjects (referencenumber, studentnumber, subjectid, subjectstatus) SELECT referencenumber, studentnumber, subjectid, subjectstatus FROM enrolledsubjects WHERE subjectstatus = 'Revoked'";
					$resultenrolledsubjects = $connection->query($sqlenrolledsubjects);
							
					$sqlcreditedsubjects = "INSERT INTO historycreditedsubjects (referencenumber, studentnumber, subjectid, subjectstatus) SELECT referencenumber, studentnumber, subjectid, subjectstatus FROM creditedsubjects WHERE subjectstatus = 'Revoked'";
					$resultcreditedsubjects = $connection->query($sqlcreditedsubjects);
							
					$sqlenrolledfees = "INSERT INTO historyenrolledfees (referencenumber, studentnumber, modeofpayment, tuitionfee, scholarshipdiscount, miscellaneousfee, overloadfee, id, graduationfee, studentteaching, firingfee, totalfee, remainingbalance, balancestatus) SELECT referencenumber, studentnumber, modeofpayment, tuitionfee, scholarshipdiscount, miscellaneousfee, overloadfee, id, graduationfee, studentteaching, firingfee, totalfee, remainingbalance, balancestatus FROM enrolledfees WHERE balancestatus = 'Revoked'";
					$resultenrolledfees = $connection->query($sqlenrolledfees);
					
					$sqlpayments = "INSERT INTO historypayments (transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, cashpayment, creditpayment, totalpayment,  paymentchange, paymentdate) SELECT transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, cashpayment, creditpayment, totalpayment, paymentchange, paymentdate FROM payments WHERE studentnumber = '$userid'";
					$resultpayments = $connection->query($sqlpayments);
					
					$sql1 = "DELETE FROM enrolledstudents WHERE studentstatus = 'Revoked'";
					$result1 = $connection->query($sql1);
						
					$sql2 = "DELETE FROM enrolledsubjects WHERE subjectstatus = 'Revoked'";
					$result2 = $connection->query($sql2);
						
					$sql3 = "DELETE FROM creditedsubjects WHERE subjectstatus = 'Revoked'";
					$result3 = $connection->query($sql3);
						
					$sql4 = "DELETE FROM enrolledfees WHERE balancestatus = 'Revoked'";
					$result4 = $connection->query($sql4);

					$sql5 = "UPDATE academic SET academicyear = '$updateacademicyear', semester = '$updatesemester', period = '$updateperiod', enddate = '$updateenddate' WHERE recordid = 1";
					$result5 = $connection->query($sql5);
					
					$sql6 = "INSERT INTO studentdebits (studentnumber, debitvalue, debitname) SELECT studentnumber, $pastfee, pendingpayment FROM enrolledfees WHERE pendingpayment = '$pastfee'";
					$result6 = $connection->query($sql6);
					
					$sql7 = "UPDATE enrolledfees SET $pastfee = -2 WHERE pendingpayment = '$pastfee'";
					$result7 = $connection->query($sql7);
					
					$sql8 = "UPDATE enrolledfees SET pendingpayment = '$updatependingpayment' WHERE $updatependingpayment != 0 AND $updatependingpayment != -1 AND $updatependingpayment != -2";
					$result8 = $connection->query($sql8);
					
					if ($resultrevoke === true && $resultuser === true && $resultenrolledstudents === true && $resultenrolledsubjects === true && $resultcreditedsubjects === true && $resultenrolledfees === true && $result1 === true && $result2 === true && $result3 === true && $result4 === true && $result5 === true && $result6 === true && $result7 === true && $result8 === true) {
						
					$updateresult = "Academic Year/Semester/Period Update Successful!";
					
					$sqlemaildrop = "SELECT * FROM users WHERE emailaddresscode = 0 AND userstatus = 2 AND userlevel = 'Student'";
					$resultemaildrop = $connection->query($sqlemaildrop);
					
					if ($resultemaildrop->num_rows > 0) {
						while ($row = $resultemaildrop->fetch_object()) {
							
							$emailaddress = $row->emailaddress;
							$firstname = $row->firstname;
							$lastname = $row->lastname;
								
							$recipient = $emailaddress;
							$name = "$firstname $lastname";
							$subject = "Void Enrollment";
							$body = "Hello $firstname $lastname!\n\nYour enrollment for Academic Year $academicyear: $semester has been void\nSee you again next enrollment period.\n\nThank You! Have a good day!";
								
							// sendemail($recipient, $name, $subject, $body);
							
						}
					}
					
					$sqlemail = "SELECT * FROM users WHERE emailaddresscode = 0 AND userstatus = 1 AND userlevel = 'Student'";
					$resultemail = $connection->query($sqlemail);
						
						if ($resultemail->num_rows > 0) {
							while ($row = $resultemail->fetch_object()) {
								
								$emailaddress = $row->emailaddress;
								$firstname = $row->firstname;
								$lastname = $row->lastname;
								
								$recipient = $emailaddress;
								$name = "$firstname $lastname";
								$subject = "$updateperiod Period";
								$body = "Hello $firstname $lastname!\n\n$updateperiod Period has begun for Academic Year $updateacademicyear: $updatesemester\n$message\n\nThank You! Have a good day!";
								
								// sendemail($recipient, $name, $subject, $body);
							}
						}
					
					}
					else {
					$updateresult = "Academic Year/Semester/Period Update Unsuccessful. Please Try Again.";
					}
				}
			}
			echo "<script> 
					var x = messagealert('$updateresult'); 
					if (x == true) {
						window.location = 'admin.php';
					}
				</script>"; 
				$updateresult = "";
		}
	}
	
	echo "<br/><br/><form method='post' action='login.php'>
			<input type='submit' name='log-out' value='Log-Out' >
			</form>
			<br/>";
	
 $connection->close();
 }
	else if ($userlevel == "Student"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Students! <br/> <a href='profile.php'>Back</a>"; // if the user tries to access cashier.php with student userlevel
	}
	else if ($userlevel == "Registrar Staff"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Registrar Staffs! <br/> <a href='registrar.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
	}
	else if ($userlevel == "Cashier"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Cashiers! <br/> <a href='payment.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
	}
}
	else { // if the user tries to access admin.php without logging-in
		echo " <img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
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