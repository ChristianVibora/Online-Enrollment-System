<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Discounts Log</title>
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
				   <li class="active"><a href='paymentlog.php'><span>Discounts Log</span></a></li>
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
							<li><a href="paymentlog.php">Discounts Log</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing:2px;font-weight:500;">Discounts Log</h1>
						</div>							
						</div>
					</div>
<center>
<?php

$searchtable = $academicyear = $semester = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION
	
	if ($userlevel == "Admin") { // if the user is admin

	$sql = "SELECT * FROM academic";
	$result = $connection->query($sql);

		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
				$academicyear = $row->academicyear;
				$semester = $row->semester;
			}
		}

	$adminfirstname = $adminlastname = "";
	
	$adminfirstname = $_SESSION["firstname"];
	$adminlastname = $_SESSION["lastname"];

	echo " <h3> <span class='printonly'> Discounts Log </span> </h3> <br/>
	<div class='pagebreak'></div>";
	
	$datevalue = "";
	$datevalue = date("Y-m-d");
	$searchtypevalue = "";
	
	echo "<div class='printignore'>
			<br/>
			<form method='post' action='creditslog.php'>
			<div class='left'>
			<table>
			<tr>
			<td class='label'>Select Type: </td> <td><select name='searchdatetype'>
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
			<td class='label'>Select Discount Type: </td> <td><select name='searchtype'>
			<option value='All'>All</option>
			<option value='Added'>Added</option>
			<option value='Used'>Used</option>
			</select>
			</td>
			</tr>
			</table>
			<br/>
			<input type='submit' name='searchbytype' value='Search by Discount Type'>
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
	
	$transactionnumber = $firstname = $lastname = $transactiondate = $credittype = $cashierid = $cashierfirstname = $cashierlastname = "";
	$addedusedcredit = $totaladdedusedcredit = $newcreditvalue = 0;
	
	if (isset($_POST["searchbydate"])) {
	
	$searchtypevalue = "All";
	$searchdatetypevalue = $searchdatevalue = $searchdateerror = $sql = "";
	$errorcount = 0;
	
	$searchdatetypevalue = $_POST["searchdatetype"];
	
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
	
	if ($searchdatetypevalue == "All") {
		
		$sql = "SELECT * FROM creditslog INNER JOIN users ON users.userid = creditslog.studentnumber";
	}
	else if ($searchdatetypevalue == "Daily") {
		$displaysearchdatevalue = date('F d, Y', strtotime($searchdatevalue));
		
		$sql = "SELECT * FROM creditslog INNER JOIN users ON users.userid = creditslog.studentnumber WHERE (DAY(transactiondate) = DAY('$searchdatevalue')) AND (MONTH(transactiondate) = MONTH('$searchdatevalue')) AND (YEAR(transactiondate) = YEAR('$searchdatevalue'))";
	}
	else if ($searchdatetypevalue == "Weekly") {
		$displaysearchdatevalue = date('W, Y', strtotime($searchdatevalue));
		
		$sql = "SELECT * FROM creditslog INNER JOIN users ON users.userid = creditslog.studentnumber WHERE (WEEK(transactiondate, 3) = WEEK('$searchdatevalue', 3)) AND (YEAR(transactiondate) = YEAR('$searchdatevalue'))";
	}
	else if ($searchdatetypevalue == "Monthly") {
		$displaysearchdatevalue = date('F, Y', strtotime($searchdatevalue));
		
		$sql = "SELECT * FROM creditslog INNER JOIN users ON users.userid = creditslog.studentnumber WHERE (MONTH(transactiondate) = MONTH('$searchdatevalue')) AND (YEAR(transactiondate) = YEAR('$searchdatevalue'))";
	}
	else if ($searchdatetypevalue == "Yearly") {
		$displaysearchdatevalue = date('Y', strtotime($searchdatevalue));
		
		$sql = "SELECT * FROM creditslog INNER JOIN users ON users.userid = creditslog.studentnumber WHERE (YEAR(transactiondate) = YEAR('$searchdatevalue'))";
	}
	
		echo " <h3> <div class='printignore'> Search by Date<br/></div>
		$searchdatetypevalue Discounts <br>
		$displaysearchdatevalue
		</h3> <div class='printignore'><button onclick='window.print()'>Print</button><hr/> <br/> </div>
		 
		<div class='pagebreak'></div>";
}
}
else if (isset($_POST["searchbystudent"])) {
		
		$searchtypevalue = "All";
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
			
			$sql = "SELECT * FROM creditslog INNER JOIN users ON users.userid = creditslog.studentnumber WHERE (userid = '$searchstudentvalue' OR CONCAT(firstname, ' ', middlename) LIKE '%$searchstudentvalue%' OR CONCAT(middlename, ' ', lastname) LIKE '%$searchstudentvalue%' OR CONCAT(firstname, ' ', lastname) LIKE '%$searchstudentvalue%' OR CONCAT(firstname, ' ', middlename, ' ', lastname) LIKE '%$searchstudentvalue%')";
			
		}
	}
	else if (isset($_POST["searchbytype"])) {
		
		$sqlextension = "";
		
		$searchtypevalue = $_POST["searchtype"];
		
		if ($searchtypevalue != "All") { $sqlextension = "AND credittype = '$searchtypevalue'"; }
		
		echo " <h3><div class='printignore'> Search by Discount Type<br/></div>
		$searchtypevalue Discounts</h3> <div class='printignore'> <button onclick='window.print()'>Print</button>
		<hr/> <br/> </div>
		
		<div class='pagebreak'></div>";
		
		$sql = "SELECT * FROM creditslog INNER JOIN users ON users.userid = creditslog.studentnumber $sqlextension";
	
}

	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		
		echo "<table border=1 class='paymentlog'>
					<tr class='curriculum-header'>
						<th> Transaction Number </th>
						<th> Cashier Name </th>
						<th> Student Name </th>
						<th> Discount Type </th>
						<th> Added/Used Discount </th>
						<th> New Discount Value </th>
						<th> Transaction Date </th>
					</tr>";
		
		while ($row = $result->fetch_object()) {
			
			$transactionnumber = $row->transactionnumber;
			$cashierid = $row->cashierid;
			$firstname = $row->firstname;
			$lastname = $row->lastname;
			$credittype = $row->credittype;
			$addedusedcredit = $row->addedusedcredit;
			$totaladdedusedcredit += $addedusedcredit;
			$newcreditvalue = $row->newcreditvalue;
			$transactiondate = $row->transactiondate;
					
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
						<td> $credittype </td>
						<td> " , formatcurrency($addedusedcredit) , " </td>
						<td> " , formatcurrency($newcreditvalue) , " </td>
						<td> $transactiondate </td>
					</tr>";
		}
		if ($searchtypevalue == "All") {
			echo "<tr class='units'>
					<td colspan=8 align='center'> Total Discounts Added/Used: " , formatcurrency($totaladdedusedcredit) , " </td> 
					</tr>
				</table><br/>";
		}
		else if ($searchtypevalue == "Added") {
			echo "<tr class='units'>
					<td colspan=8 align='center'> Total Discounts Added: " , formatcurrency($totaladdedusedcredit) , " </td> 
					</tr>
				</table><br/>";
		}
			else if ($searchtypevalue == "Used") {
			echo "<tr class='units'>
					<td colspan=8 align='center'> Total Discounts Used: " , formatcurrency($totaladdedusedcredit) , " </td> 
					</tr>
				</table><br/>";
		}
				
			echo "<table class='tbl' width='1000'>
				<tr><td colspan=4 class='label'><br/></td></tr>
				<tr><td class='label'>Prepared by: </td><td class='label' align='right'>Admin:</td> <td class='label1' align='left'> <u>$adminfirstname $adminlastname</u> </td><td class='label' align='right'>Checked by:</td> <td class='label1' align='left'><u>Accounting Department</u></td></tr>
				</table>";
	}
	else {
		echo "Search Did Not Matched Any Results. Please Try Other Input. Thank You! ";
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