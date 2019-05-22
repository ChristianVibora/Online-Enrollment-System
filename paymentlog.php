<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Payment Log</title>
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
					<li><a href="home.php">Home</a></li>
				   <li class="active"><a href='paymentlog.php'><span>Payment Log</span></a></li>
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
							<li><a href="paymentlog.php">Payment Log</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing:2px;font-weight:500;">Payment Log</h1>
						</div>							
						</div>
					</div>
				
<center>
<?php

$searchtable = $academicyear = $semester = $display = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION
	
	if ($userlevel == "Admin") { // if the user is admin

if (!empty($_SESSION["searchtable"]) && !empty($_SESSION["paymentlogacademicyear"]) && !empty($_SESSION["paymentlogsemester"])) {

$searchtable = $_SESSION["searchtable"];
$academicyear = $_SESSION["paymentlogacademicyear"];
$semester = $_SESSION["paymentlogsemester"];
	
	$display = "History";
	
}
else {
	$sql = "SELECT * FROM academic";
	$result = $connection->query($sql);

		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
				$academicyear = $row->academicyear;
				$semester = $row->semester;
			}
		}
		$searchtable = "payments";
		$display = "Current";
}

	$adminfirstname = $adminlastname = "";
	
	$adminfirstname = $_SESSION["firstname"];
	$adminlastname = $_SESSION["lastname"];

	echo " <h3> <span class='printonly'> $display Payment Log </span>
	<span class='printignore'>$display <br/> </span> Academic Year $academicyear <br/> $semester  </h3>
	<div class='printignore'>
	<form method='get' action='paymentlog.php'>
	<input type='submit' name='change' value='Change'>
	</form>
	</div>
	<div class='pagebreak'></div>";
	
	$datevalue = "";
	$datevalue = date("Y-m-d");
	
	echo "<div class='printignore'>
			<br/>
			<form method='post' action='paymentlog.php'>
			<div class='left'>
			<table>
			<tr>
			<td class='label'>Select Type: </td> <td><select name='searchtype'>
			<option value='All'>All</option>
			<option value='Daily'>Daily</option>
			<option value='Weekly'>Weekly</option>
			<option value='Monthly'>Monthly</option>
			<option value='Yearly'>Yearly</option>
			</select>
			</td>
			</tr>
			<tr><td class='label'>Select Date: </td> <td> <input type='date' name='searchdate' value='$datevalue'> </td></tr>
			</table>
			<br/>
			<input type='submit' name='searchbydate' value='Search by Date'>
			<br/>
			</div>
			<div class='right'>
			<table>
			<tr>
			<td class='label'>Select Period: </td> <td><select name='searchperiod'>
			<option value='All'>All</option>
			<option value='Enrollment'>Enrollment</option>
			<option value='Payment'>Payment</option>
			<option value='Prelims'>Prelims</option>
			<option value='Midterms'>Midterms</option>
			<option value='Pre-Finals'>Pre-Finals</option>
			<option value='Finals'>Finals</option>
			<option value='Clearance'>Clearance</option>
			<option value='Vacation'>Vacation</option>
			</select>
			</td>
			</tr>
			</table>
			<br/>
			<input type='submit' name='searchbyperiod' value='Search by Period'>
			<br/>
			</div>
			<div class='center'>
			<table>
			<tr>
			<td class='label'>Enter Student Number or Name:</td> <td><input type='text' name='searchstudent'></td>
			</tr>
			</table>
			</br>
			<input type='submit' name='searchbystudent' value='Search by Student'>
			</div>
			</form><br/><br/>
			<hr/></div>";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$transactionnumber = $firstname = $lastname = $paymentdate = $cashierid = $cashierfirstname = $cashierlastname = "";
	$amountdue = $cashpayment = $creditpayment = $totalpayment = $paymentchange = 0;
	
	if (isset($_POST["searchbydate"])) {
	
	$searchtypevalue = $searchdatevalue = $searchdateerror = $sql = "";
	$errorcount = 0;
	
	$searchtypevalue = $_POST["searchtype"];
	
		if (empty($_POST["searchdate"])) {
				$searchdateerror = "<b>Error: </b> Please Enter a Date!";
				$errorcount++;
		}
		else {
			$searchdatevalue = $_POST["searchdate"];
		}
		
		if ($errorcount > 0) {
			
			echo "<br/>
					
					<table class='tbl'>
					<tr><td><span class='error'>$searchdateerror</span></td></tr>
					</table>
					";
					return 0;
		}
		else {
			
	$displaysearchdatevalue = "";
	
	if ($searchtypevalue == "All") {
		
		$sql = "SELECT * FROM $searchtable INNER JOIN users ON users.userid = $searchtable.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester'";
	}
	else if ($searchtypevalue == "Daily") {
		$displaysearchdatevalue = date('F d, Y', strtotime($searchdatevalue));
		
		$sql = "SELECT * FROM $searchtable INNER JOIN users ON users.userid = $searchtable.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND (DAY(paymentdate) = DAY('$searchdatevalue')) AND (MONTH(paymentdate) = MONTH('$searchdatevalue')) AND (YEAR(paymentdate) = YEAR('$searchdatevalue'))";
	}
	else if ($searchtypevalue == "Weekly") {
		$displaysearchdatevalue = date('W, Y', strtotime($searchdatevalue));
		
		$sql = "SELECT * FROM $searchtable INNER JOIN users ON users.userid = $searchtable.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND (WEEK(paymentdate, 3) = WEEK('$searchdatevalue', 3)) AND (YEAR(paymentdate) = YEAR('$searchdatevalue'))";
	}
	else if ($searchtypevalue == "Monthly") {
		$displaysearchdatevalue = date('F, Y', strtotime($searchdatevalue));
		
		$sql = "SELECT * FROM $searchtable INNER JOIN users ON users.userid = $searchtable.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND (MONTH(paymentdate) = MONTH('$searchdatevalue')) AND (YEAR(paymentdate) = YEAR('$searchdatevalue'))";
	}
	else if ($searchtypevalue == "Yearly") {
		$displaysearchdatevalue = date('Y', strtotime($searchdatevalue));
		
		$sql = "SELECT * FROM $searchtable INNER JOIN users ON users.userid = $searchtable.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND (YEAR(paymentdate) = YEAR('$searchdatevalue'))";
	}
	
		echo " <h3> <div class='printignore'> Search by Date<br/></div>
		$searchtypevalue Payments <br>
		$displaysearchdatevalue
		</h3> <div class='printignore'><button onclick='window.print()'>Print</button><hr/> <br/> </div>
		<div class='pagebreak'></div>";
}
}
else if (isset($_POST["searchbystudent"])) {
		
		$searchstudentvalue = $searchstudentvalueerror = "";
		$errorcount = 0;
		
		if (empty($_POST["searchstudent"])) {
			$searchstudentvalueerror = "<b>Error: </b>Please Enter Student Number or Name!";
			$errorcount++;
		}
		else {
			$searchstudentvalue = validateinput($_POST["searchstudent"]);
			$searchstudentvalue = validateinput($searchstudentvalue);
			if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $searchstudentvalue)) {
				$searchstudentvalueerror = "<b>Error: </b>Please Enter Student Number or Name!";
				$errorcount++;
			}
		}
		
		if ($errorcount > 0) {
			
			echo "<br/>
					
					<table class='tbl'>
					<tr><td><span class='error'>$searchstudentvalueerror</span></td></tr>
					</table>
					";
					return 0;
		}
		else {
			echo " <h3><div class='printignore'> Search by Student<br/></div>
			$searchstudentvalue</h3> <div class='printignore'> <button onclick='window.print()'>Print</button>
			<hr/> <br/> </div>
			
			<div class='pagebreak'></div>";
			
			$sql = "SELECT * FROM $searchtable INNER JOIN users ON users.userid = $searchtable.studentnumber WHERE (userid = '$searchstudentvalue' OR CONCAT(firstname, ' ', middlename) LIKE '%$searchstudentvalue%' OR CONCAT(middlename, ' ', lastname) LIKE '%$searchstudentvalue%' OR CONCAT(firstname, ' ', lastname) LIKE '%$searchstudentvalue%' OR CONCAT(firstname, ' ', middlename, ' ', lastname) LIKE '%$searchstudentvalue%') AND academicyear = '$academicyear' AND semester = '$semester'";
			
		}
	}
	else if (isset($_POST["searchbyperiod"])) {
		
		$searchperiodvalue = $sqlextension = "";
		
		$searchperiodvalue = $_POST["searchperiod"];
		
		if ($searchperiodvalue != "All") { $sqlextension = "AND period = '$searchperiodvalue'"; }
		
		echo " <h3><div class='printignore'> Search by Period<br/></div>
		$searchperiodvalue Period Payments</h3> <div class='printignore'> <button onclick='window.print()'>Print</button>
		<hr/> <br/> </div>
		
		<div class='pagebreak'></div>";
		
		$sql = "SELECT * FROM $searchtable INNER JOIN users ON users.userid = $searchtable.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' $sqlextension";
	
}

	$totalamountpaid = 0;

	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		
		echo "<table border=1 class='paymentlog'>
					<tr class='curriculum-header'>
						<th> Transaction Number </th>
						<th> Cashier Name </th>
						<th> Student Name </th>
						<th> Amount Due </th>
						<th> Cash Payment </th>
						<th> Discount Payment </th>
						<th> Total Payment </th>
						<th> Payment Change </th>
						<th> Payment Date </th>
					</tr>";
		
		while ($row = $result->fetch_object()) {
			
			$transactionnumber = $row->transactionnumber;
			$cashierid = $row->cashierid;
			$firstname = $row->firstname;
			$lastname = $row->lastname;
			$amountdue = $row->amountdue;
			$totalamountpaid += $amountdue;
			$cashpayment = $row->cashpayment;
			$creditpayment = $row->creditpayment;
			$totalpayment = $row->totalpayment;
			$paymentchange = $row->paymentchange;
			$paymentdate = $row->paymentdate;
					
			$sql1 = "SELECT firstname, lastname FROM users WHERE userid = '$cashierid'";
			$result1 = $connection->query($sql1);
					
			if ($result1->num_rows == 1) {		
				while ($row1 = $result1->fetch_object()) {
					$cashierfirstname = $row1->firstname;
					$cashierlastname = $row1->lastname;
				}
			}
				echo "<tr class='curriculum'>
						<td> $transactionnumber </td>
						<td> $cashierfirstname $cashierlastname </td>
						<td> $firstname $lastname </td>
						<td> " , formatcurrency($amountdue) , " </td>
						<td> " , formatcurrency($cashpayment) , " </td>
						<td> " , formatcurrency($creditpayment) , " </td>
						<td> " , formatcurrency($totalpayment) , " </td>
						<td> " , formatcurrency($paymentchange) , " </td>
						<td> $paymentdate </td>
					</tr>";
		}
			echo "<tr class='units'>
					<td colspan=9 align='center'> Total Amount Paid:  " , formatcurrency($totalamountpaid) , " </td> 
					</tr>
				</table><br/>";
				
			echo "<table class='tbl' width='1000'>
				<tr><td colspan=4 class='label'><br/></td></tr>
				<tr><td class='label'>Prepared by: </td><td class='label' align='right'>Admin:</td> <td class='label1' align='left'> <u>$adminfirstname $adminlastname</u> </td><td class='label' align='right'>Checked by:</td> <td class='label1' align='left'><u>Accounting Department</u></td></tr>
				</table>";
	}
	else {
		echo "Search Did Not Matched Any Results. Please Try Other Input. Thank You! ";
	}
}
	else if ($_SERVER["REQUEST_METHOD"] == "GET") {
		
		if (isset($_GET["change"])) {
		echo "<br/>
				<h3>Change Payment Log</h3><br/>
				<form method='get' action='paymentlog.php'>
				<table>
				<tr><td>Select Table: </td>
				<td> <select name='searchtable'>
				<option value='currentpayments'>Current Payment Log</option>
				<option value='historypayments'>History Payment Log</option>
				</select>
				</td>
				</tr>
				<tr><td>Select Academic Year: </td>
				<td> <select name='academicyear'>
				<option value='2016-2017'>2016-2017</option>
				<option value='2017-2018'>2017-2018</option>
				<option value='2018-2019'>2018-2019</option>
				<option value='2019-2020'>2019-2020</option>
				<option value='2020-2021'>2020-2021</option>
				<option value='2021-2022'>2021-2022</option>
				<option value='2022-2023'>2022-2023</option>
				<option value='2023-2024'>2023-2024</option>
				<option value='2024-2025'>2024-2025</option>
				<option value='2025-2026'>2025-2026</option>
				</select></td>
				</tr>
				<tr>
				<td>Select Semester: </td>
				<td> <select name='semester'>
				<option value='First Semester'>First Semester</option>
				<option value='Second Semester'>Second Semester</option>
				<option value='Summer'>Summer</option>
				</select></td>
				</table>
				<br/>
				<input type='submit' name='submit' value='Submit'>
				<br/><br/>
				<input type='submit' name='reset' value='Reset'>
				</form>
				";
		}
		else if (isset($_GET["submit"])) {
			
			$searchtable = $selectedacademicyear = $selectedsemester = $result = "";
			
			$searchtable = $_GET["searchtable"];
			$selectedacademicyear = $_GET["academicyear"];
			$selectedsemester = $_GET["semester"];
			
			if ($searchtable == "historypayments") {
				$_SESSION["searchtable"] = "historypayments";
				$result = "Payment Log Has Been Successfully Set to Academic Year $selectedacademicyear: $selectedsemester!";
			}
			else {
				$_SESSION["searchtable"] = "";
				$result = "Payment Log Has Been Successfully Set to Current Academic Year and Semester!";
			}
			
			$_SESSION["paymentlogacademicyear"] = $selectedacademicyear;
			$_SESSION["paymentlogsemester"] = $selectedsemester;
			
			echo "<script> 
				var x = messagealert('$result'); 
				if (x == true) {
					window.location = 'paymentlog.php';
				}
			</script>"; 
			$result = "";
		}
		else if (isset($_GET["reset"])) {
			
			$result = "";
			
			$_SESSION["searchtable"] = "";
			$_SESSION["paymentlogacademicyear"] = "";
			$_SESSION["paymentlogsemester"] = "";
			
			$result = "Payment Log Has Been Successfully Reset to the Current Academic Year and Semester!";
			
			echo "<script> 
				var x = messagealert('$result'); 
				if (x == true) {
					window.location = 'paymentlog.php';
				}
			</script>"; 
			$result = "";
		}
	}
$connection->close();
}
	else if ($userlevel == "Student"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Students! <br/> <a href='profile.php'>Back</a>"; // if the user tries to access cashier.php with student userlevel
	}
	else if ($userlevel == "Cashier"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Cashiers! <br/> <a href='payment.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
	}
	else if ($userlevel == "Registrar Staff"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Registrar Staffs! <br/> <a href='registrar.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
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