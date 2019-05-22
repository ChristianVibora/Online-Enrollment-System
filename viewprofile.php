<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>View Profile</title>
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
				   <li class="active"><a href='viewprofile.php'><span>View Profile</span></a></li>
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
						<?php menubackpage(); ?>
						<li><a href="viewprofile.php">View Profile</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">View Profile</h1>
							</div>
						</div>
					</div>
<center>					
<?php

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	
	$profilepicture = $viewuserid = $studentnumber = $firstname = $middlename = $lastname = $mobilenumber = $gender = $dateofbirth = $address = $emailaddress = $guardianname =$guardianmobilenumber = $userstatus = $studentcourse = $studentyear = $studentsection = $academicyear = $semester = $enrollmenttype = $studentstatus = "";
	$subjectid = $subjectcode = $coursecode = $subjectyear = $subjectdescription = $subjectunits = $subjectstatus = "";
	$totalsubjectunits = 0;
	
	$_SESSION["backpage1"] = "View Profile";
	
	if (!empty($_GET["viewuserid"])) {
		$viewuserid = $_GET["viewuserid"];
	}
	else {
		$viewuserid = $_SESSION["userid"];
	}
	
	$sql = "SELECT * FROM users WHERE userid = '$viewuserid'";
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
		$guardianname = $row->guardianname;
		$guardianmobilenumber = $row->guardianmobilenumber;
		$userstatus = $row->userstatus;
		$profilepicture = $row->profilepicture;
	}
}

		if ($userstatus == 0 || $userstatus == 2) { // if the user is not yet enrolled
		
			if ($profilepicture != "") {
				echo "<br/ class='printignore'><img class='profilepicture' src='images/profilepictures/$profilepicture' alt='Profile Picture Unavailable'><br/ class='printignore'><br/ class='printignore'>";
			}
			else {
				echo "<br/ class='printignore'><img class='profilepicture' src='images/profilepictures/logo.png' alt='Profile Picture Unavailable'>
				<br/ class='printignore'><br/ class='printignore'>";
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
				<tr><td class='label'>Email Address:</td> <td class='label1'>$emailaddress</td></tr>
				<tr><td class='label'>Guardian Name:</td> <td class='label1'>$guardianname</td></tr>
				<tr><td class='label'>Guardian Mobile Number:</td> <td class='label1'>$guardianmobilenumber</td></tr>
				</table>
				";
		}
		else if ($userstatus == 1) { // if the user is already enrolled, display the academic details
		
			if ($profilepicture != "") {
				echo "<br/ class='printignore'><img class='profilepicture' src='images/profilepictures/$profilepicture' alt='Profile Picture Unavailable'><br/ class='printignore'><br/ class='printignore'>";
			}
			else {
				echo "<br/ class='printignore'><img class='profilepicture' src='images/profilepictures/logo.png' alt='Profile Picture Unavailable'>
				<br/ class='printignore'><br/ class='printignore'>";
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
				<tr><td class='label'>Email Address:</td> <td class='label1'>$emailaddress</td></tr>";
				
				if ($userlevel == "Admin" || $userlevel == "Cashier" || $userlevel == "Registrar") {
				
					echo "<tr><td class='label'>Guardian Name:</td> <td class='label1'>$guardianname</td></tr>
					<tr><td class='label'>Guardian Mobile Number:</td> <td class='label1'>$guardianmobilenumber</td></tr>
					<tr><td colspan=2 class='label'><div class='printignore'><hr/></div></td></tr>";
				}
				
				

			$studentnumber = $studentcourse = $studentyear = $studentsection = $academicyear = $semester = $enrollmenttype = $studentstatus = "";
			$tuitionfee = $scholarshipdiscount = $totaltuitionfee = $miscellaneousfee = $overloadfee = $id = $graduationfee = $studentteaching = $firingfee = $totalfee = $modeofpayment = $pendingpayment = $downpaymentfee = $prelimsfee = $midtermsfee = $prefinalsfee = $finalsfee = $remainingbalance = "";
			$subjectid = $subjectcode = $coursecode = $subjectyear = $subjectdescription = $subjectunits = $subjectstatus = "";
			$totalsubjectunits = 0;
			
			$sql = "SELECT * FROM enrolledstudents WHERE studentnumber = $viewuserid";
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
			
			$sql = "SELECT * FROM enrolledfees WHERE studentnumber = $viewuserid";
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
				
				$sql = "SELECT creditvalue FROM studentcredits WHERE studentnumber = $viewuserid";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentcredits = $row->creditvalue;
					}
				}
				
				$sql = "SELECT SUM(debitvalue) AS debitvalue FROM studentdebits WHERE studentnumber = $viewuserid";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentdebits = $row->debitvalue + 0;
					}
						if ($studentdebits > 0) {
							$pendingpayment = "Debits";
						}
				}
					
				$sql = "SELECT SUM(refundvalue) as refundvalue FROM studentrefunds WHERE studentnumber = $viewuserid";
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
					<tr><td class='label'>Mode of Payment:</td> <td class='label1'>$modeofpayment</td></tr>";
					
					if ($userlevel == "Admin" || $userlevel == "Cashier") {
						echo "<tr><td class='label'><hr/></td></tr>
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
					}
					echo "</table>";
				
				$sql = "SELECT * FROM enrolledsubjects INNER JOIN enrolledstudents ON enrolledstudents.studentnumber = enrolledsubjects.studentnumber INNER JOIN curriculum ON curriculum.subjectid = enrolledsubjects.subjectid WHERE enrolledstudents.studentnumber = $viewuserid"; // SQL query to relate different database tables
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
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$coursecode = $row->coursecode;
						$subjectyear = $row->subjectyear;
						$subjectstatus = $row->subjectstatus;
						$totalsubjectunits += $row->subjectunits;
						
						echo "
						<tr class='curriculum-row'>
						<form method='post' action='profile.php?subjectid=$subjectid&subjectcode=$subjectcode&subjectdescription=$subjectcode&subjectdescription'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $coursecode </td>
							<td> $subjectyear </td>
							<td> $subjectstatus </td>
						</form>
						</tr>";
					}
					echo "<tr class='units'> <td colspan=8 align='center'> Total Subject Units: $totalsubjectunits </td> </tr> </table> ";
				}
				
					$totalsubjectunits = 0;
					
					$sql = "SELECT * FROM creditedsubjects INNER JOIN enrolledstudents ON enrolledstudents.studentnumber = creditedsubjects.studentnumber  INNER JOIN curriculum ON curriculum.subjectid = creditedsubjects.subjectid WHERE enrolledstudents.studentnumber = '$viewuserid'"; // SQL query to relate different database tables
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
			}
		}
		echo "<br/><br/><form method='post' action='academichistory.php?viewuserid=$viewuserid'>
			<input type='hidden' name='viewuserid' value='$viewuserid'>
			<input type='submit' name='view' value='View Academic History'>
			</form>";
}
else {
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