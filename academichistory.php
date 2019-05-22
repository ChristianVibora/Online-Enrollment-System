<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Academic History</title>
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
				   <li class="active"><a href='academichistory.php'><span>Academic History</span></a></li>
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
						<?php menubackpage1(); ?>
						<li><a href="academichistory.php">Academic History</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Academic History</h1>
							</div>
						</div>
					</div>
<center>					
<?php

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid

	$profilepicture = $viewuserid = $studentnumber = $firstname = $middlename = $lastname = $mobilenumber = $gender = $dateofbirth = $address = $emailaddress = $userstatus = $studentcourse = $studentyear = $studentsection = $academicyear = $semester = $enrollmenttype = $studentstatus = "";
	$subjectid = $subjectcode = $coursecode = $subjectyear = $subjectdescription = $subjectunits = $subjectstatus = "";
	$totalsubjectunits = 0;
	
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
	}
	echo "<h3> <span class='printonly'> Academic History </span></h3>
	<h4>$firstname $middlename $lastname</h4>
	<div class='printignore'><button onclick='window.print()'>Print</button></div>
	<br/><hr/ class='printignore'><br/>
	<div class='pagebreak'></div>";
}

	$arrayreferencenumber = "";
	$a = $b = 0;

	$sql = "SELECT DISTINCT referencenumber FROM historyenrolledstudents WHERE studentnumber = '$viewuserid'";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_object()) {
			$arrayreferencenumber[$a] = $row->referencenumber;
			$a++;
		}
			
			$studentnumber = $studentcourse = $studentyear = $studentsection = $academicyear = $semester = $enrollmenttype = $studentstatus = $enrollmentdate = $movedate = "";
			$tuitionfee = $scholarshipdiscount = $totaltuitionfee = $miscellaneousfee = $overloadfee = $id = $graduationfee = $studentteaching = $firingfee = $totalfee = $modeofpayment = $remainingbalance = $balancestatus = "";
			$subjectid = $subjectcode = $coursecode = $subjectyear = $subjectdescription = $subjectunits = $subjectstatus = "";
			$totalsubjectunits = 0;
			
			for ($i=0;$i<sizeof($arrayreferencenumber);$i++) {
					
			$sql = "SELECT * FROM historyenrolledstudents WHERE referencenumber = '$arrayreferencenumber[$i]'";
			$result = $connection->query($sql);
			
			if ($result->num_rows == 1) {
				while ($row = $result->fetch_object()) {
				$studentnumber = $row->studentnumber;
				$enrollmentdate = $row->enrollmentdate;
				$academicyear = $row->academicyear;
				$semester = $row->semester;
				$studentcourse = $row->studentcourse;
				$studentyear = $row->studentyear;
				$studentsection = $row->studentsection;
				$enrollmenttype = $row->enrollmenttype;
				$studentstatus = $row->studentstatus;
				$movedate = $row->movedate;
			}
				
			// displays academic details
			echo "<h4 class='header'>Academic Year $academicyear <br/> $semester</h4>
				<br/><table class='profile'>
				<tr><td colspan=2 align='center' class='label2'><h4>Academic Details</h4></td></tr>
				<tr><td class='label'>Student Number:</td> <td class='label1'>$studentnumber</td></tr>
				<tr><td class='label'>Enrollment Date:</td> <td class='label1'>$enrollmentdate</td></tr>
				<tr><td class='label'>Course:</td> <td class='label1'>$studentcourse</td></tr>
				<tr><td class='label'>Year:</td> <td class='label1'>$studentyear</td></tr>
				<tr><td class='label'>Section:</td> <td class='label1'>$studentsection</td></tr>
				<tr><td class='label'>Enrollment Type:</td> <td class='label1'> $enrollmenttype</td></tr>
				<tr><td class='label'>Status:</td> <td class='label1'>$studentstatus</td></tr>";
				
				if ($studentstatus == "Cleared") {
					echo "<tr><td class='label'>Clearance Date:</td> <td class='label1'>$movedate</td></tr>";
				}
				else if ($studentstatus == "Dropped") {
					echo "<tr><td class='label'>Drop Date:</td> <td class='label1'>$movedate</td></tr>";
				}
				
				echo "<tr><td colspan=2 class='label'><div class='printignore'><hr/></div></td></tr>";
			
			$sql = "SELECT * FROM historyenrolledfees WHERE referencenumber = '$arrayreferencenumber[$i]'";;
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
						$remainingbalance = $row->remainingbalance;
						$balancestatus = $row->balancestatus;
					}
				}
				
					if ($remainingbalance == -1) { $remainingbalance = 0; }
					
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
					<tr><td class='label'>Remaining Balance:</td> <td class='label1'> " , formatcurrency($remainingbalance) , " </td></tr>
					<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Balance Status:</td> <td class='label1'> $balancestatus </td></tr>
					</table>
					";
				
				$sql = "SELECT * FROM historyenrolledsubjects INNER JOIN curriculum ON curriculum.subjectid = historyenrolledsubjects.subjectid WHERE historyenrolledsubjects.referencenumber = '$arrayreferencenumber[$i]'"; // SQL query to relate different database tables
				$result = $connection->query($sql);
			
				if ($result->num_rows > 0) {
			
				echo "<br/><br/ class='printignore'> <h4> Subjects </h4><br/>
					<table border=1 width=1000 class='enrollmentform'>
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
						
						echo "<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $coursecode </td>
							<td> $subjectyear </td>
							<td> $subjectstatus </td>
						</tr>";
					}
					echo "<tr class='units'> <td colspan=8 align='center'> Total Units Enrolled: $totalsubjectunits </td> </tr>";

					$totalsubjectunits = 0;
					
					$sql = "SELECT * FROM historycreditedsubjects INNER JOIN curriculum ON curriculum.subjectid = historycreditedsubjects.subjectid WHERE historycreditedsubjects.referencenumber = '$arrayreferencenumber[$i]'";
					$result = $connection->query($sql);
			
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$coursecode = $row->coursecode;
						$subjectyear = $row->subjectyear;
						$subjectstatus = $row->subjectstatus;
						$totalsubjectunits += $row->subjectunits;
						
						echo "<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $coursecode </td>
							<td> $subjectyear </td>
							<td> $subjectstatus </td>
						</tr>";
					}
					echo "<tr class='units'> <td colspan=8 align='center'> Total Units Credited: $totalsubjectunits </td> </tr> 
					</table>";
				}
				else {
					echo "</table>";
				}
			}
				echo "<div class='printignore'><br/><hr/><br/></div><div class='pagebreak'></div>";
			}
			else {
				echo " ";
				}
			}
		}
		else {
			echo " Search Did Not Matched Any Results. Please Try Other Input. Thank You! ";
		}
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