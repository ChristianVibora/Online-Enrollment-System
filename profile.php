<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Profile</title>
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
			<div id='cssmenu' >
				<ul>
				   <li><a href='home.php'><span>Home</span></a></li>
				   <li class="active"><a href='profile.php'><span>Profile</span></a></li>
				   <li><a href='classlist.php'><span>Class Lists</span></a></li>
				   <li><a href='search.php'><span>Search</span></a></li>
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
							<li><a href="profile.php">Profile</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing:2px;font-weight: 500;l">Profile</h1>
						</div>
						</div>
					</div>
				
<center>
<?php

$userid = $firstname = $middlename = $lastname = $mobilenumber = $gender = $dateofbirth = $address = $emailaddress = $emailaddresscode = $userstatus  = $period = $profilepicture = "";
$_SESSION["backpage1"] = "Profile";
if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION

	if ($userlevel == "Student") { // if the user is student
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
		$emailaddress = $row->emailaddress;
		$emailaddresscode = $row->emailaddresscode;
		$guardianname = $row->guardianname;
		$guardianmobilenumber = $row->guardianmobilenumber;
		$userstatus = $row->userstatus;
		$profilepicture = $row->profilepicture;
		$_SESSION["firstname"] = $firstname;
		$_SESSION["lastname"] = $lastname;
		$_SESSION["userstatus"] = $userstatus;
	}
}

		$sql = "SELECT * FROM academic";
		$result = $connection->query($sql);

		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
			$period = $row->period;
			}
		}

		$_SESSION["course"] = $_SESSION["year"] = $_SESSION["enrollmenttype"] = "";
		
		echo "<div class='printignore'> Welcome to Tanauan Institute, Inc. <b>Student:</b> $firstname $lastname. <br/>
			<form method='post' action='editprofile.php'>
			<input type='submit' name='editprofile' value='Edit Profile'>
			</form>
			<br/></div>";
		
		
		if ($userstatus == 0) { // if the user is not yet enrolled

		if ($period == "Enrollment") {
		echo "<div class='printignore'><a href='enroll.php' class='enrollnow'><b>Enroll Now!</b></a></div>";
		}
		
			if ($profilepicture != "") {
				echo "<br/ class='printignore'><img class='profilepicture' src='images/profilepictures/$profilepicture' alt='Profile Picture Unavailable'><br/ class='printignore'><br/ class='printignore'>";
			}
			else {
				echo "
				<br/ class='printignore'><img class='profilepicture' src='images/profilepictures/logo.png' alt='Profile Picture Unavailable'>
				<div class='printignore'><br/><form action='profile.php' method='post' enctype='multipart/form-data'>
				<table>
				<tr><td>Change Profile Picture:</td> <td><input type='file' name='profilepicture'> </td></tr>
				</table><br/>
				<input type='submit' name='upload' value='Upload'>
				</form><br/></div>";
			}
		
				// displays the personal details
				echo "
				<table class='profile'>
				<tr><td colspan=2 align='center' class='label2'><h4>Personal Details</h4></td></tr>
				<tr><td class='label'>Full Name:</td> <td class='label1'>$firstname $middlename $lastname</td></tr>
				<tr><td class='label'>Mobile Number:</td> <td class='label1'>$mobilenumber</td></tr>
				<tr><td class='label'>Gender:</td> <td class='label1'>$gender</td></tr>
				<tr><td class='label'>Date of Birth:</td> <td class='label1'>$dateofbirth</td></tr>
				<tr><td class='label'>Address:</td> <td class='label1'>$address</td></tr>
				<tr><td class='label'>Email Address:</td> <td class='label1'>$emailaddress";
				if ($emailaddresscode != 0) { echo "<span class='printignore'> | <a href='verification.php?userid=$userid'> Verify </a> </span>"; }
				echo "</td></tr>
				<tr><td class='label'>Guardian Name:</td> <td class='label1'>$guardianname</td></tr>
				<tr><td class='label'>Guardian Mobile Number:</td> <td class='label1'>$guardianmobilenumber</td></tr>
				</table>
				";
		}
		else if ($userstatus == 1) { // if the user is already enrolled, display the academic details
		
			if ($period == "Clearance" || $period == "Vacation") {
			
			$sql = "UPDATE enrolledstudents INNER JOIN enrolledfees ON enrolledfees.studentnumber = enrolledstudents.studentnumber INNER JOIN enrolledsubjects ON enrolledsubjects.studentnumber = enrolledfees.studentnumber SET enrolledstudents.studentstatus = 'Cleared', enrolledsubjects.subjectstatus = 'Passed' WHERE enrolledstudents.studentnumber = '$userid' AND enrolledfees.balancestatus = 'Fully Paid' AND enrolledstudents.studentstatus != 'Cleared' AND enrolledsubjects.subjectstatus != 'Passed'";
			$result = $connection->query($sql);
		}
		
			if ($profilepicture != "") {
				echo "<br/ class='printignore'><img class='profilepicture' src='images/profilepictures/$profilepicture' alt='Profile Picture Unavailable'><br/ class='printignore'><br/ class='printignore'>";
			}
			else {
				echo "<br/ class='printignore'><img class='profilepicture' src='images/profilepictures/logo.png' alt='Profile Picture Unavailable'>
				<div class='printignore'><br/>
				<form action='profile.php' method='post' enctype='multipart/form-data'>
				<table>
				<tr><td>Change Profile Picture:</td> <td><input type='file' name='profilepicture'> </td></tr>
				</table><br/>
				<input type='submit' name='upload' value='Upload'>
				</form><br/></div>";
			}
		
				// displays the personal details
				echo "
				<table class='profile pagebreak'>
				<tr><td colspan=2 align='center' class='label2'><h4>Personal Details</h4></td></tr>
				<tr><td class='label'>Full Name:</td> <td class='label1'>$firstname $middlename $lastname</td></tr>
				<tr><td class='label'>Mobile Number:</td> <td class='label1'>$mobilenumber</td></tr>
				<tr><td class='label'>Gender:</td> <td class='label1'>$gender</td></tr>
				<tr><td class='label'>Date of Birth:</td> <td class='label1'>$dateofbirth</td></tr>
				<tr><td class='label'>Address:</td> <td class='label1'>$address</td></tr>
				<tr><td class='label'>Email Address:</td> <td class='label1'>$emailaddress";
				if ($emailaddresscode != 0) { echo "<span class='printignore'> | <a href='verification.php?userid=$userid'> Verify </a> </span>"; }
				echo "</td></tr>
				<tr><td class='label'>Guardian Name:</td> <td class='label1'>$guardianname</td></tr>
				<tr><td class='label'>Guardian Mobile Number:</td> <td class='label1'>$guardianmobilenumber</td></tr>
				<tr><td colspan=2 class='label'><div class='printignore'><hr/></div></td></tr>";

			$studentnumber = $studentcourse = $studentyear = $studentsection = $academicyear = $semester = $enrollmenttype = $studentstatus = "";			
			$tuitionfee = $scholarshipdiscount = $totaltuitionfee = $miscellaneousfee = $overloadfee = $id = $graduationfee = $studentteaching = $firingfee = $totalfee = $modeofpayment = $pendingpayment = $downpaymentfee = $prelimsfee = $midtermsfee = $prefinalsfee = $finalsfee = $remainingbalance = "";
			$i = 0;
			$subjectid = $subjectcode = $coursecode = $subjectyear = $subjectdescription = $subjectunits = $subjectstatus = $subjectids = $subjectcodes = $subjectdescriptions = "";
			$totalsubjectunits = 0;
			
			$sql = "SELECT * FROM enrolledstudents WHERE studentnumber = $userid";
			$result = $connection->query($sql);
			
			if ($result->num_rows == 1) {
				while ($row = $result->fetch_object()) {
				$studentnumber = $row->studentnumber;
				$academicyear = $row->academicyear;
				$semester = $row->semester;
				$studentcourse = $row->studentcourse;
				$studentyear = $row->studentyear;
				$studentsection = $row->studentsection;
				$enrollmenttype = $row->enrollmenttype;
				$studentstatus = $row->studentstatus;
				}
			// displays academic details
			echo "
				<tr><td colspan=2 align='center' class='label2'><h4>Academic Details</h4></td></tr>
				<tr><td class='label'>Student Number:</td> <td class='label1'>$studentnumber</td></tr>
				<tr><td class='label'>Academic Year:</td> <td class='label1'>$academicyear</td></tr>
				<tr><td class='label'>Semester:</td> <td class='label1'>$semester</td></tr>
				<tr><td class='label'>Course:</td> <td class='label1'>$studentcourse</td></tr>
				<tr><td class='label'>Year:</td> <td class='label1'>$studentyear</td></tr>
				<tr><td class='label'>Section:</td> <td class='label1'>$studentsection</td></tr>
				<tr><td class='label'>Enrollment Type:</td> <td class='label1'> $enrollmenttype</td></tr>
				<tr><td class='label'>Status:</td> <td class='label1'>$studentstatus</td></tr>
				<tr><td colspan=2 class='label'><div class='printignore'><hr/></div></td></tr>"; 
			
			$sql = "SELECT * FROM enrolledfees WHERE studentnumber = $userid";
			$result = $connection->query($sql);
			
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$tuitionfee = $row->tuitionfee;
						$scholarshipdiscount = $row->scholarshipdiscount;
						$totaltuitionfee = $tuitionfee - $scholarshipdiscount;
						$miscellaneousfee = $row->miscellaneousfee;
						$overloadfee = $row->overloadfee;
						$id = $row->id;
						$graduationfee = $row->graduationfee;
						$studentteaching = $row->studentteaching;
						$firingfee = $row->firingfee;
						$totalfee = $row->totalfee;
						$modeofpayment = $row->modeofpayment;
						$pendingpayment = $row->pendingpayment;
						$downpaymentfee = $row->downpaymentfee;
						$prelimsfee = $row->prelimsfee;
						$midtermsfee = $row->midtermsfee;
						$prefinalsfee = $row->prefinalsfee;
						$finalsfee = $row->finalsfee;
						$remainingbalance = $row->remainingbalance;
					}
				}
					
					if ($pendingpayment == "downpaymentfee") { $pendingpayment = "Down-Payment Fee"; }
					else if ($pendingpayment == "prelimsfee") { $pendingpayment = "Prelims Fee"; }
					else if ($pendingpayment == "midtermsfee") { $pendingpayment = "Midterms Fee"; }
					else if ($pendingpayment == "prefinalsfee") { $pendingpayment = "Pre-Finals Fee"; }
					else if ($pendingpayment == "finalsfee") { $pendingpayment = "Finals Fee"; }
					else { $pendingpayment = "None"; }
					
					if ($downpaymentfee == -1) { $downpaymentfee = "Paid"; } 
					else if ($downpaymentfee == -2) { $downpaymentfee = "Overdue"; }
					else if ($downpaymentfee == -3) { $downpaymentfee = "Pending"; }
					
					if ($prelimsfee == -1) { $prelimsfee = "Paid"; } 
					else if ($prelimsfee == -2) { $prelimsfee = "Overdue"; }
					else if ($prelimsfee == -3) { $prelimsfee = "Pending"; }
						
					if ($midtermsfee == -1) { $midtermsfee = "Paid"; }
					else if ($midtermsfee == -2) { $midtermsfee = "Overdue"; }
					else if ($midtermsfee == -3) { $midtermsfee = "Pending"; }
					
					if ($prefinalsfee == -1) { $prefinalsfee = "Paid"; } 
					else if ($prefinalsfee == -2) { $prefinalsfee = "Overdue"; }
					else if ($prefinalsfee == -3) { $prefinalsfee = "Pending"; }
					
					if ($finalsfee == -1) { $finalsfee = "Paid"; } 
					else if ($finalsfee == -2) { $finalsfee = "Overdue"; }
					else if ($finalsfee == -3) { $finalsfee = "Pending"; }
					
					if ($remainingbalance == -1) { $remainingbalance = "Fully Paid"; }
					
				$studentcredits = $studentdebits = $studentrefunds = 0;
				
				$sql = "SELECT creditvalue FROM studentcredits WHERE studentnumber = $userid";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentcredits = $row->creditvalue;
					}
				}
				
				$sql = "SELECT SUM(debitvalue) AS debitvalue FROM studentdebits WHERE studentnumber = $userid";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentdebits = $row->debitvalue + 0;
					}
						if ($studentdebits > 0) {
							$pendingpayment = "Debits";
						}
				}
				
				$sql = "SELECT SUM(refundvalue) as refundvalue FROM studentrefunds WHERE studentnumber = $userid";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentrefunds = $row->refundvalue;
					}
				}
					
				// display the payment details
				echo "
					<tr><td colspan=2 align='center' class='label2'> <h4> Payment Details </h4> </td></tr>
					<tr><td class='label'>Tuition Fee:</td> <td class='label1'> " , formatcurrency($tuitionfee) , " </td></tr>
					<tr><td class='label'>Scholarship (100%):</td> <td class='label1'> " , formatcurrency($scholarshipdiscount) , " </td></tr>
					<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Total Tuition Fee:</td> <td class='label1'> " , formatcurrency($totaltuitionfee) , " </td></tr>
					<tr><td class='label'>Miscellaneous Fee:</td> <td class='label1'> " , formatcurrency($miscellaneousfee) , " </td></tr>";

					if ($overloadfee != 0) {
						echo "<tr><td class='label'>Overload Fee:</td> <td class='label1'> " , formatcurrency($overloadfee) , " </td></tr>";
					}
					
					if ($id != 0) {
						echo "<tr><td class='label'>ID Fee:</td> <td class='label1'> " , formatcurrency($id) , " </td></tr>";
					}
					
					if ($graduationfee != 0) {
						echo "<tr><td class='label'>Graduation Fee:</td> <td class='label1'> " , formatcurrency($graduationfee) , " </td></tr>";
					}
					
					if ($studentteaching != 0) {
						echo "<tr><td class='label'>Student Teaching Fee:</td> <td class='label1'> " , formatcurrency($studentteaching) , " </td></tr>";
					}
					
					if ($firingfee != 0) {
						echo "<tr><td class='label'>Firing Fee:</td> <td class='label1'> " , formatcurrency($firingfee) , " </td></tr>";
					}
					
					echo "<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Total Fee:</td> <td class='label1'> " , formatcurrency($totalfee) , " </td></tr>
					<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Mode of Payment:</td> <td class='label1'>$modeofpayment</td></tr>
					<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Pending Payment:</td> <td class='label1'>$pendingpayment</td></tr>
					<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Down-Payment Fee:</td> <td class='label1'> " , formatcurrency($downpaymentfee) , " </td></tr>
					<tr><td class='label'>Prelims Fee:</td> <td class='label1'> " , formatcurrency($prelimsfee) , " </td></tr>
					<tr><td class='label'>Midterms Fee:</td> <td class='label1'> " , formatcurrency($midtermsfee) , " </td></tr>
					<tr><td class='label'>Pre-Finals Fee:</td> <td class='label1'> " , formatcurrency($prefinalsfee) , " </td></tr>
					<tr><td class='label'>Finals Fee:</td> <td class='label1'> " , formatcurrency($finalsfee) , " </td></tr>
					<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Remaining Balance:</td> <td class='label1'> " , formatcurrency($remainingbalance) , " </td></tr>
					<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Discounts:</td> <td class='label1'> " , formatcurrency($studentcredits) , " </td></tr>
					<tr><td class='label'>Debits:</td> <td class='label1'> " , formatcurrency($studentdebits) , " </td></tr>";
				
					if ($studentrefunds != 0) {
						echo "<tr><td class='label'>Refunds:</td> <td class='label1'> " , formatcurrency($studentrefunds) , " </td></tr>";
					}
				
					echo "</table>";
				
				$sql = "SELECT * FROM enrolledsubjects INNER JOIN enrolledstudents ON enrolledstudents.studentnumber = enrolledsubjects.studentnumber INNER JOIN curriculum ON curriculum.subjectid = enrolledsubjects.subjectid WHERE enrolledstudents.studentnumber = $userid"; // SQL query to relate different database tables
				$result = $connection->query($sql);
			
				if ($result->num_rows > 0) {
					
					echo "<br/><br/> <h4> Enrolled Subjects </h4><br/ class='printignore'>
					<table border=1 width=1000 class='subjects'>
					<tr class='curriculum-header'>
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Course </th>
						<th> Subject Year </th>
						<th> Subject Status </th>
					</tr>"; // displays the enrolled subjects
					
					while ($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectids[$i] = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectcodes[$i] = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectdescriptions[$i] = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$coursecode = $row->coursecode;
						$subjectyear = $row->subjectyear;
						$subjectstatus = $row->subjectstatus;
						$totalsubjectunits += $row->subjectunits;
						$i++;
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $coursecode </td>
							<td> $subjectyear </td>
							<td> $subjectstatus </td>
						</tr>";
					}
					echo "<tr class='units'> <td colspan=8 align='center'> Total Subject Units: $totalsubjectunits </td> </tr> </table> ";
				}
				
					$totalsubjectunits = 0;
					
					$sql = "SELECT * FROM creditedsubjects INNER JOIN enrolledstudents ON enrolledstudents.studentnumber = creditedsubjects.studentnumber  INNER JOIN curriculum ON curriculum.subjectid = creditedsubjects.subjectid WHERE enrolledstudents.studentnumber = '$userid'"; // SQL query to relate different database tables
					$result = $connection->query($sql);
					
				if ($result->num_rows > 0) {
					
					echo " <br/><br/> <h4> Credited Subjects </h4><br/ class='printignore'>
					<table border=1 width=1000 class='subjects'>
					<tr class='curriculum-header'>
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Course </th>
						<th> Subject Year </th>
						<th> Subject Status </th>
					</tr>";
					
					while ($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$coursecode = $row->coursecode;
						$subjectyear = $row->subjectyear;
						$subjectstatus = $row->subjectstatus;
						$totalsubjectunits += $row->subjectunits;
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $coursecode </td>
							<td> $subjectyear </td>
							<td> $subjectstatus </td>
						</tr>";
					}
					echo "<tr class='units'> <td colspan=7 align='center'> Total Subject Units: $totalsubjectunits </td> </tr> </table> ";
				}
				
			echo "<div class='printignore' id='drop'><br/><br/>
			<form method='post' action='profile.php'>
			<table>
			<tr>
			<td> Select Subject ID to Drop: </td>
			<td><select name='subjectid'>
			<option value='' disabled selected>Select:</option>";
			
			for ($n=0;$n<sizeof($subjectids);$n++) {
				echo "<option value='$subjectids[$n]'>$subjectids[$n]</option>";
			}
			
			echo "</td></tr></table>
			</select><br/>
			<input type='submit' name='dropsubject' value='Drop Subject'>
			</form>
			<br/>Or<br/><br/>
			<form method='post' action='profile.php'>
				<input type='submit' name='drop' value='Drop Enrollment'>
				</form>
			<br/></div>";	
			
			if ($studentdebits == 0) {
			
			echo "<div class='printignore' id='enabledrop'><br/><br/>
				<button name='enabledrop' onclick='enabledrop()'>Enable Dropping</button>
			<br/></div>";	
			
			}
			if ($studentstatus == "Cleared") {
				echo "<div class='printignore'>
						<br/><br/>
						<form method='post' action='profile.php'>
							<input type='submit' name='clear' value='Clear'>
						</form>
						</div>";
			}
		}
	}
	else if ($userstatus == 2) {
			
			$dropresult = "";
			
			$sqluser = "UPDATE users SET userstatus = 0 WHERE userid = $userid AND userstatus = 2";
			$resultuser = $connection->query($sqluser);
			
			if ($resultuser === true) {
			
			$dropresult = "Your Enrollment Has Been Void Because You Did Not Settle Your Down-Payment Fee Before Prelim Period! Please Try Again Next Enrollment Period.";
			
			echo "<script> 
				var x = messagealert('$dropresult'); 
				if (x == true) {
					window.location = 'profile.php';
				}
			</script>"; 
			$dropresult = "";
			}
	}
		
			
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if (isset($_POST["clear"])) {
			
			$clearanceresult = "";
			
			$sqlenrolledstudents = "INSERT INTO historyenrolledstudents (referencenumber, studentnumber, academicyear, semester, studentcourse, studentyear, studentsection, enrollmenttype, studentstatus, enrollmentdate) SELECT referencenumber, studentnumber, academicyear, semester, studentcourse, studentyear, studentsection, enrollmenttype, studentstatus, enrollmentdate FROM enrolledstudents WHERE studentnumber = '$userid'";
			$resultenrolledstudents = $connection->query($sqlenrolledstudents);
					
			$sqlenrolledsubjects = "INSERT INTO historyenrolledsubjects (referencenumber, studentnumber, subjectid, subjectstatus) SELECT referencenumber, studentnumber, subjectid, subjectstatus FROM enrolledsubjects WHERE studentnumber = '$userid'";
			$resultenrolledsubjects = $connection->query($sqlenrolledsubjects);
					
			$sqlcreditedsubjects = "INSERT INTO historycreditedsubjects (referencenumber, studentnumber, subjectid, subjectstatus) SELECT referencenumber, studentnumber, subjectid, subjectstatus FROM creditedsubjects WHERE studentnumber = '$userid'";
			$resultcreditedsubjects = $connection->query($sqlcreditedsubjects);
					
			$sqlenrolledfees = "INSERT INTO historyenrolledfees (referencenumber, studentnumber, modeofpayment, tuitionfee, scholarshipdiscount, miscellaneousfee, overloadfee, id, graduationfee, studentteaching, firingfee, totalfee, remainingbalance, balancestatus) SELECT referencenumber, studentnumber, modeofpayment, tuitionfee, scholarshipdiscount, miscellaneousfee, overloadfee, id, graduationfee, studentteaching, firingfee, totalfee, remainingbalance, balancestatus FROM enrolledfees WHERE studentnumber = '$userid'";
			$resultenrolledfees = $connection->query($sqlenrolledfees);
			
			$sqlpayments = "INSERT INTO historypayments (transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, cashpayment, creditpayment, totalpayment, paymentchange, paymentdate) SELECT transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, cashpayment, creditpayment, totalpayment, paymentchange, paymentdate FROM payments WHERE studentnumber = '$userid'";
			$resultpayments = $connection->query($sqlpayments);
			
			$sqlchequepayments = "INSERT INTO historychequepayments (transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, accountname, accountnumber, chequeamount, paymentchange, paymentdate) SELECT transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, accountname, accountnumber, chequeamount, paymentchange, paymentdate FROM chequepayments WHERE studentnumber = '$userid'";
			$resultchequepayments = $connection->query($sqlchequepayments);
			
			$sqlusers = "UPDATE users SET userstatus = 0 WHERE userid = '$userid' AND userstatus = 1";
			$resultusers = $connection->query($sqlusers);
			
			$sql1 = "DELETE FROM enrolledstudents WHERE studentnumber = '$userid'";
			$result1 = $connection->query($sql1);
			
			$sql2 = "DELETE FROM enrolledsubjects WHERE studentnumber = '$userid'";
			$result2 = $connection->query($sql2);
			
			$sql3 = "DELETE FROM creditedsubjects WHERE studentnumber = '$userid'";
			$result3 = $connection->query($sql3);
			
			$sql4 = "DELETE FROM enrolledfees WHERE studentnumber = '$userid'";
			$result4 = $connection->query($sql4);
			
			$sql5 = "DELETE FROM payments WHERE studentnumber = '$userid'";
			$result5 = $connection->query($sql5);
			
			$sql6 = "DELETE FROM chequepayments WHERE studentnumber = '$userid'";
			$result6 = $connection->query($sql6);
			
			if ($resultenrolledstudents === true && $resultenrolledsubjects === true && $resultcreditedsubjects === true && $resultenrolledfees === true && $resultpayments === true && $resultchequepayments === true && $resultusers === true && $result1 === true && $result2 === true && $result3 === true && $result4 === true && $result5 === true && $result6 === true) {
			
				$clearanceresult = "Clearance Successful! You May Now Re-Enroll Next Enrollment Period.";

					if ($emailaddresscode == 0) {
				
					$recipient = $emailaddress;
					$name = "$firstname $lastname";
					$subject = "Clearance";
					$body = "Hello $firstname $lastname!\n\nYou have successfully cleared your enrollment for Academic Year $academicyear: $semester\n\nThank You! Have a good day!";
					
					// sendemail($recipient, $name, $subject, $body);
						
					}
				
			}
			else {
				$clearanceresult = "Clearance Unsuccessful! Please Try Again.";
			}
			
			echo "<script> 
				var x = messagealert('$clearanceresult'); 
				if (x == true) {
					window.location = 'profile.php';
				}
			</script>"; 
			$clearanceresult = "";
			
		}
		else if (isset($_POST["upload"])) {
		
			$targetdirectory = $filename = $targetfile = $uploaderror = $uploadresult = $filetype = $checkfiletype = "";
			$errorcount = 0;
			
			if (empty($_FILES["profilepicture"]["name"])) {
				$uploaderror = "Please Select an Image File.";
				$errorcount++;
			}
			else {
			$targetdirectory = "images/profilepictures/";
			$filename = basename($_FILES["profilepicture"]["name"]);
			$targetfile = "$targetdirectory$filename";
			$filetype = pathinfo($targetfile,PATHINFO_EXTENSION);
			
			$checkfiletype = getimagesize($_FILES["profilepicture"]["tmp_name"]);
				
			if($checkfiletype !== false) {
				
			} 
			else {
				$uploaderror = "Image Dimensions Too Large! Please Lower The Image Dimension.";
				$errorcount++;
			}
			
			if ($_FILES["profilepicture"]["size"] > 500000) {
				$uploaderror = " File Size Too Large! Please Select a File Smaller Than 500KB.";
				$errorcount++;
			}
			
			if(strtolower($filetype) != "jpg" && strtolower($filetype) != "jpeg" && strtolower($filetype) != "png") {
				$uploaderror = "File Type Is Invalid! Please Select a JPEG or PNG Image File.";
				$errorcount++;
			}
			}
			if ($errorcount > 0) {
				echo "<script> 
					var x = messagealert('$uploaderror'); 
				</script>"; 
				$uploaderror = "";
				$errorcount = 0;
			}
			else {
				$newfilename = "$targetdirectory$userid.$filetype";
			
				if (move_uploaded_file($_FILES["profilepicture"]["tmp_name"], $newfilename)) {
					
					$sql = "UPDATE users SET profilepicture = '$userid.$filetype' WHERE userid = $userid";
					$result = $connection->query($sql);
					
					if ($result === true) {
						$uploadresult = "Profile Picture Upload Successful!";
					}
					else {
						$uploadresult = "Profile Picture Upload Unsuccessful! Please Try Again.";
					}
				}
				else {
					$uploadresult = "Profile Picture Upload Unsuccessful! Please Try Again.";
				}
				
				echo "<script> 
				var x = messagealert('$uploadresult'); 
				if (x == true) {
					window.location = 'profile.php';
				}
				</script>"; 
				$uploadresult = "";
			}
		}
		else if (isset($_POST["drop"])) {

			$dropresult = "";
			
			$sqldrop = "UPDATE enrolledstudents INNER JOIN enrolledfees ON enrolledfees.studentnumber = enrolledstudents.studentnumber INNER JOIN enrolledsubjects ON enrolledsubjects.studentnumber = enrolledfees.studentnumber LEFT JOIN creditedsubjects ON creditedsubjects.studentnumber = enrolledsubjects.studentnumber SET enrolledstudents.studentstatus = 'Dropped', enrolledsubjects.subjectstatus = 'Dropped', creditedsubjects.subjectstatus = 'Dropped', enrolledfees.balancestatus = 'Dropped' WHERE enrolledstudents.studentnumber = '$userid'";
			$resultdrop = $connection->query($sqldrop);
			
			$sqlenrolledstudents = "INSERT INTO historyenrolledstudents (referencenumber, studentnumber, academicyear, semester, studentcourse, studentyear, studentsection, enrollmenttype, studentstatus, enrollmentdate) SELECT referencenumber, studentnumber, academicyear, semester, studentcourse, studentyear, studentsection, enrollmenttype, studentstatus, enrollmentdate FROM enrolledstudents WHERE studentnumber = '$userid'";
			$resultenrolledstudents = $connection->query($sqlenrolledstudents);
					
			$sqlenrolledsubjects = "INSERT INTO historyenrolledsubjects (referencenumber, studentnumber, subjectid, subjectstatus) SELECT referencenumber, studentnumber, subjectid, subjectstatus FROM enrolledsubjects WHERE studentnumber = '$userid'";
			$resultenrolledsubjects = $connection->query($sqlenrolledsubjects);
					
			$sqlcreditedsubjects = "INSERT INTO historycreditedsubjects (referencenumber, studentnumber, subjectid, subjectstatus) SELECT referencenumber, studentnumber, subjectid, subjectstatus FROM creditedsubjects WHERE studentnumber = '$userid'";
			$resultcreditedsubjects = $connection->query($sqlcreditedsubjects);
					
			$sqlenrolledfees = "INSERT INTO historyenrolledfees (referencenumber, studentnumber, modeofpayment, tuitionfee, scholarshipdiscount, miscellaneousfee, overloadfee, id, graduationfee, studentteaching, firingfee, totalfee, remainingbalance, balancestatus) SELECT referencenumber, studentnumber, modeofpayment, tuitionfee, scholarshipdiscount, miscellaneousfee, overloadfee, id, graduationfee, studentteaching, firingfee, totalfee, remainingbalance, balancestatus FROM enrolledfees WHERE studentnumber = '$userid'";
			$resultenrolledfees = $connection->query($sqlenrolledfees);
			
			$sqlpayments = "INSERT INTO historypayments (transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, cashpayment, creditpayment, totalpayment,  paymentchange, paymentdate) SELECT transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, cashpayment, creditpayment, totalpayment, paymentchange, paymentdate FROM payments WHERE studentnumber = '$userid'";
			$resultpayments = $connection->query($sqlpayments);
			
			$sqlchequepayments = "INSERT INTO historychequepayments (transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, accountname, accountnumber, chequeamount, paymentchange, paymentdate) SELECT transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, accountname, accountnumber, chequeamount, paymentchange, paymentdate FROM chequepayments WHERE studentnumber = '$userid'";
			$resultchequepayments = $connection->query($sqlchequepayments);
			
			$sqlusers = "UPDATE users SET userstatus = 0 WHERE userid = '$userid' AND userstatus = 1";
			$resultusers = $connection->query($sqlusers);
			
			$sql1 = "DELETE FROM enrolledstudents WHERE studentnumber = '$userid'";
			$result1 = $connection->query($sql1);
			
			$sql2 = "DELETE FROM enrolledsubjects WHERE studentnumber = '$userid'";
			$result2 = $connection->query($sql2);
			
			$sql3 = "DELETE FROM creditedsubjects WHERE studentnumber = '$userid'";
			$result3 = $connection->query($sql3);
			
			$sql4 = "DELETE FROM enrolledfees WHERE studentnumber = '$userid'";
			$result4 = $connection->query($sql4);
			
			$sql5 = "DELETE FROM payments WHERE studentnumber = '$userid'";
			$result5 = $connection->query($sql5);
			
			$sql6 = "DELETE FROM chequepayments WHERE studentnumber = '$userid'";
			$result6 = $connection->query($sql6);
			
			if ($resultdrop === true && $resultenrolledstudents === true && $resultenrolledsubjects === true && $resultcreditedsubjects === true && $resultenrolledfees === true && $resultpayments === true && $resultchequepayments === true && $resultusers === true && $result1 === true && $result2 === true && $result3 === true && $result4 === true && $result5 === true && $result6 === true) {
			
			$dropresult = "Drop Enrollment Successful! You May Now Re-Enroll Or Wait For the Next Enrollment Period.";

				if ($emailaddresscode == 0) {
				
				$recipient = $emailaddress;
				$name = "$firstname $lastname";
				$subject = "Drop Enrollment";
				$body = "Hello $firstname $lastname!\n\nYou have successfully dropped your enrollment for Academic Year $academicyear: $semester\n\nThank You! Have a good day!";
				
				// sendemail($recipient, $name, $subject, $body);
					
				}
			}
			else {
				$dropresult = "Drop Enrollment Unsuccessful! Please Try Again.";
			}
			
			echo "<script> 
				var x = messagealert('$dropresult'); 
				if (x == true) {
					window.location = 'profile.php';
				}
			</script>"; 
			$dropresult = "";
		}
		
		else if (isset($_POST["dropsubject"])) {
			
			$dropsubjectid = $dropsubjectcode = $dropsubjectdescription = $dropsubjectresult = "";
			
			if (empty($_POST["subjectid"])) {
				
				$dropsubjectresult = "Please Select A Subject ID to Drop!";
				
				echo "<script> 
				var x = messagealert('$dropsubjectresult'); 
				if (x == true) {
					window.location = 'profile.php';
				}
				</script>"; 
				$dropsubjectresult = "";
				
				return;
			}
			else {
				$dropsubjectid = $_POST["subjectid"];
			}
			
			if (!empty($_POST["subjectcode"])) {
			$dropsubjectcode = $_POST["subjectcode"];
			}
			$dropsubjectdescription = $_POST["subjectdescription"];
			
			$sqlupdateenrolledsubjects = "UPDATE enrolledsubjects SET subjectstatus = 'Dropped' WHERE studentnumber = '$userid' AND subjectid = '$dropsubjectid'";
			$resultupdateenrolledsubjects = $connection->query($sqlupdateenrolledsubjects);
			
			$sqlenrolledsubjects = "INSERT INTO historyenrolledsubjects (referencenumber, studentnumber, subjectid, subjectstatus) SELECT referencenumber, studentnumber, subjectid, subjectstatus FROM enrolledsubjects WHERE studentnumber = '$userid' AND subjectid = '$dropsubjectid'";
			$resultenrolledsubjects = $connection->query($sqlenrolledsubjects);
			
			$sql = "DELETE FROM enrolledsubjects WHERE studentnumber = '$userid' AND subjectid = '$dropsubjectid'";
			$result = $connection->query($sql);
			
			if ($resultupdateenrolledsubjects === true && $resultenrolledsubjects && $result === true) {
			
				$dropsubjectresult = "Drop Subject Successful!";
				
					if ($emailaddresscode == 0) {
				
					$dropsubjectcode = $dropsubjectdescription = "";
				
					$sql = "SELECT * FROM curriculum WHERE subjectid = '$dropsubjectid'";
					$result = $connection->query($sql);
					
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_object()) {
							$dropsubjectcode = $row->subjectcode;
							$dropsubjectdescription = $row->subjectdescription;
						}
					}
					$recipient = $emailaddress;
					$name = "$firstname $lastname";
					$subject = "Drop Subject";
					$body = "Hello $firstname $lastname!\n\nYou have successfully dropped your subject: $dropsubjectcode: $dropsubjectdescription for Academic Year $academicyear: $semester\n\nThank You! Have a good day!";
					
					// sendemail($recipient, $name, $subject, $body);
						
					}

			}
			else {
				$dropsubjectresult = "Drop Subject Unsuccessful! Please Try Again.";
			}
			
			echo "<script> 
				var x = messagealert('$dropsubjectresult'); 
				if (x == true) {
					window.location = 'profile.php';
				}
			</script>"; 
			$dropsubjectresult = "";
		}
	}
	
	echo "<div class='printignore'>
			<br/><br/>
			<button onclick='window.print()'>Print Profile</button> 
			<br/><br/><br/>
			<form method='post' action='academichistory.php?viewuserid=$userid'>
			<input type='hidden' name='viewuserid' value='$userid'>
			<input type='submit' name='view' value='View Academic History'>
			</form>
			<br/><br/>
			<form method='post' action='login.php'>
				<input type='submit' name='log-out' value='Log-Out'>
				</form>
			<br/></div>";  // this form is only displayed when the user logged-in correctly
	
	$connection->close();
}
	else if ($userlevel == "Cashier"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Cashiers! <br/> <a href='payment.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
	}
	else if ($userlevel == "Registrar Staff"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Registrar Staffs! <br/> <a href='registrar.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
	}
	else if ($userlevel == "Admin"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Admins! <br/> <a href='admin.php'>Back</a>"; // if the user tries to access cashier.php with admin userlevel
	}
}
	else { // if the user tries to access a profile.php without logging-in
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