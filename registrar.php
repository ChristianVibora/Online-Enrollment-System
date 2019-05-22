<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
	<title>Registrar</title>
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
				   <li class="active"><a href='payment.php'><span>Registrar</span></a></li>
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
							<li><a href='login.php'><span>Log-In</span></a></li>
							<li><a href="registrar.php">Registrar</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing:2px;font-weight:500;">Registrar</h1>	
						</div>
						</div>
					</div>
				
<center>
<?php

$userid = $firstname = $lastname = "";
$academicyear = $semester = $period = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION
	
	if ($userlevel == "Registrar Staff") { // if the user is cashier

	$sql = "SELECT * FROM academic";
	$result = $connection->query($sql);

		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
			$academicyear = $row->academicyear;
			$semester = $row->semester;
			$period = $row->period;
			}
		}

	$sql = "SELECT * FROM users WHERE userid = '$userid'";
	$result = $connection->query($sql);

if ($result->num_rows == 1) {
	while ($row = $result->fetch_object()) {
		$firstname = $row->firstname;
		$lastname = $row->lastname;
		$_SESSION["firstname"] = $firstname;
		$_SESSION["lastname"] = $lastname;
}
}
	echo "<div class='printignore'>
			Welcome to Tanauan Institute, Inc. <b>Registrar Staff:</b> $firstname $lastname.
			<br/>
			<form method='post' action='editprofile.php'>
			<input type='submit' name='editprofile' value='Edit Profile'>
			</form>
			<br/><br/>
			<form method='post' action='registrar.php'>
			Enter Student Number: <input type='text' name='searchstudentnumber'> <br/> </br>
			<input type='submit' name='next' value='Next'>
			</form><br/><hr/><br/>
			</div>";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if (isset($_POST["next"])) {
		$searchstudentnumber = $searchstudentnumbererror = "";
		$errorcount = 0;
		
		if (empty($_POST["searchstudentnumber"])) {
			if (!empty($_SESSION["searchstudentnumber"])) {
				$searchstudentnumber = $_SESSION["searchstudentnumber"];
			}
			else {
			$searchstudentnumbererror = "<b>Error: </b>Please Enter Student Number!";
			$errorcount++;
			$_SESSION["searchstudentnumber"] = "";
			}
		}
		else {
		$searchstudentnumber = validateinput($_POST["searchstudentnumber"]);
		
		if (!preg_match("/[0-9]/", $searchstudentnumber) OR preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $searchstudentnumber)) {
				$searchstudentnumbererror = "<b>Error: </b>Please Enter a Valid Student Number!";
				$errorcount++;
				$_SESSION["searchstudentnumber"] = "";
			}
		}
		
			if ($errorcount > 0) {
				echo "<br/>
					<table class='tbl'>
					<tr><td><span class='error'>$searchstudentnumbererror</span></td></tr>
					</table>
					";
			}
			else {
				
			$_SESSION["searchstudentnumber"] = $searchstudentnumber;
			$searchfirstname = $searchmiddlename = $searchlastname = $studentnumber = $studentcourse = $studentyear = $studentsection = $academicyear = $semester = $enrollmenttype = $enrollmentstatus = $address = $mobilenumber = $subjectid = $subjectcode = $subjectdescription = $subjectunits = $subjectstatus = $modeofpayment = $coursecode = $subjectyear = $cashierfirstname = $cashierlastname = $enrollmentformcopy = $guardianname = $guardianmobilenumber = "";
			$miscellaneousfee = $tuitionfee = $scholarshipdiscount = $totaltuitionfee = $overloadfee = $id = $graduationfee = $studentteaching = $firingfee = $totalfee = $downpaymentfee = $remainingbalance = $totalsubjectunits = 0;
			$datenow = date("m/d/Y H:i:s");
			
			$enrollmentformcopy = array("DEAN'S COPY", "ACCOUNTING'S COPY", "STUDENT'S COPY", "REGISTRAR'S COPY");
			
			$sql = "SELECT * FROM enrolledstudents INNER JOIN enrolledfees ON enrolledfees.studentnumber = enrolledstudents.studentnumber INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE enrolledstudents.studentnumber = '$searchstudentnumber' AND users.userstatus = 1 AND enrolledstudents.studentstatus = 'Enrolled'";
			$result = $connection->query($sql);
			
			if ($result->num_rows == 1) {
				while ($row = $result->fetch_object()) {
					$searchfirstname = $row->firstname;
					$searchmiddlename = $row->middlename;
					$searchlastname = $row->lastname;
					$studentnumber = $row->studentnumber;
					$studentcourse = $row->studentcourse;
					$studentyear = $row->studentyear;
					$studentsection = $row->studentsection;
					$academicyear = $row->academicyear;
					$semester = $row->semester;
					$enrollmenttype = $row->enrollmenttype;
					$studentstatus = $row->studentstatus;
					$enrollmentdate = date("m/d/Y H:i:s", strtotime($row->enrollmentdate));
					$address = $row->address;
					$mobilenumber = $row->mobilenumber;
					$guardianname = $row->guardianname;
					$guardianmobilenumber = $row->guardianmobilenumber;
					$modeofpayment = $row->modeofpayment;
					$miscellaneousfee = $row->miscellaneousfee;
					$tuitionfee = $row->tuitionfee;
					$scholarshipdiscount = $row->scholarshipdiscount;
					$totaltuitionfee = $tuitionfee - $scholarshipdiscount;
					$overloadfee = $row->overloadfee;
					$id = $row->id;
					$graduationfee = $row->graduationfee;
					$studentteaching = $row->studentteaching;
					$firingfee = $firingfee;
					$totalfee = $row->totalfee;
					$remainingbalance = $row->remainingbalance;
					
					if ($remainingbalance == -1) {
						$remainingbalance = "Fully Paid";
					}
					
				}
				
				$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$studentcourse' LIMIT 1";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentcourse = $row->coursecode;
					}
				}
				
				if ($modeofpayment == "Cheque Payment") {
					$table = "chequepayments";
				}
				else {
					$table = "payments";
				}
				
				$sql = "SELECT * FROM $table INNER JOIN users ON users.userid = $table.cashierid WHERE studentnumber = '$searchstudentnumber' AND paymentname = 'Down-Payment Fee'";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$downpaymentfee = $row->amountdue;
						$cashierfirstname = $row->firstname;
						$cashierlastname = $row->lastname;
					}
				}
				
				for ($i=0;$i<sizeof($enrollmentformcopy);$i++) {
				
				echo "<table class='profile'>
				<tr><td colspan=4 align='center' class='heading'>TANAUAN INSTITUTE, INC.</td></tr>
				<tr><td colspan=4 align='center' class='heading2'><h4>COLLEGE DEPARTMENT</h4></td></tr>
				<tr><td colspan=4 align='center' class='heading2'><h5>ENROLLMENT FORM</h5></td></tr>
				<tr><td colspan=4 align='center' class='heading2'><h5>($enrollmentformcopy[$i])</h5></td></tr>
				<tr><td colspan=4 class='label'><br/></td></tr>
				<tr><td colspan=4 class='heading2' align='right'>Date: <u>$datenow</u></td></tr>
				<tr><td class='label'>Student Number: </td> <td class='label1'>$studentnumber</td></tr>
				<tr><td colspan=4 class='label'><br/></td></tr>
				<tr><td class='label'>Full Name: </td> <td class='label1'>$searchfirstname $searchmiddlename $searchlastname</td></tr>
				<td class='label'>Address: </td> <td class='label1'>$address</td></tr>
				<td class='label'>Mobile Number: </td> <td class='label1'>$mobilenumber</td></tr>
				<td class='label'>Guardian Name: </td> <td class='label1'>$guardianname</td></tr>
				<tr><td class='label'>Guardian Mobile Number: </td><td class='label1'>$guardianmobilenumber</td><td class='label'>Course: </td> <td class='label1'>$studentcourse</td></tr>
				<tr><td class='label'>Academic Year:</td> <td class='label1'>$academicyear</td><td class='label'>Year: </td> <td class='label1'>$studentyear</td></tr>
				<tr><td class='label'>Semester: </td> <td class='label1'>$semester</td><td class='label'>Section: </td> <td class='label1'>$studentsection</td></tr>
				<tr><td class='label'>Enrollment Date: </td> <td class='label1'>$enrollmentdate</td><td class='label'>Status: </td> <td class='label1'>$studentstatus</td></tr>
				<tr><td colspan=4 class='label'><br/></td></tr>
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
					<tr><td class='label'>Down-Payment Fee:</td> <td class='label1'> " , formatcurrency($downpaymentfee) , " </td></tr>
					<tr><td class='label'><hr/></td></tr>
					<tr><td class='label'>Remaining Balance:</td> <td class='label1'> " , formatcurrency($remainingbalance) , " </td></tr>
					</table>";
				
				$sql = "SELECT * FROM enrolledsubjects INNER JOIN enrolledstudents ON enrolledstudents.studentnumber = enrolledsubjects.studentnumber INNER JOIN curriculum ON curriculum.subjectid = enrolledsubjects.subjectid WHERE enrolledstudents.studentnumber = '$searchstudentnumber'";
				$result = $connection->query($sql);
			
				if ($result->num_rows > 0) {
			
				echo "<br/><br/ class='printignore'> <h4>Subjects</h4><br/ class='printignore'> <br/ class='printonly'>
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
					
					$sql = "SELECT * FROM creditedsubjects INNER JOIN enrolledstudents ON enrolledstudents.studentnumber = creditedsubjects.studentnumber INNER JOIN curriculum ON curriculum.subjectid = creditedsubjects.subjectid WHERE enrolledstudents.studentnumber = '$searchstudentnumber'";
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
				echo "<table class='signatures' width='1000'>
					<tr><td colspan=4 class='label'><br/></td></tr>
					<tr><td class='label'>Approved by: </td><td class='label' align='right'>Dean:</td> <td class='label1' align='left'> <u>Christian Vibora</u> </td><td class='label' align='right'>Registrar:</td> <td class='label1' align='left'> <u>$firstname $lastname</u> </td><td class='label' align='right'>Cashier:</td> <td class='label1' align='left'> <u>$cashierfirstname $cashierlastname</u> </td></tr>
					</table>
					<br/><hr class='printignore'><br/>
					<div class='pagebreak'></div>";
				}	
			}
				echo "<div class='printignore'>
				<br/><br/>
				<button onclick='printenrollmentform()'>Print Enrollment Form</button> 
				<br/><br/></div>";
				
			}
			else {
				echo "<br/>
					<span class='error'><b>Error: </b>Student Number Not Registered or Not Yet Paid! Please Try Again.</span>
					";
				}
		}
	}
}
	
			echo "<div class='printignore'>
			<br/><br/>
			<form method='post' action='login.php'>
				<input type='submit' name='log-out' value='Log-Out'>
				</form>
			<br/></div>";
			
$connection->close();
}
	else if ($userlevel == "Student"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Students! <br/> <a href='profile.php'>Back</a>"; // if the user tries to access cashier.php with student userlevel
	}
	else if ($userlevel == "Cashier"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Cashiers! <br/> <a href='payment.php'>Back</a>"; // if the user tries to access cashier.php with student userlevel
	}
	else if ($userlevel == "Admin"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Admins! <br/> <a href='admin.php'>Back</a>"; // if the user tries to access cashier.php with admin userlevel
	}
}
else {  // if the user tries to access payment.php without logging-in
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