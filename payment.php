<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
	<title>Payment</title>
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
				   <li class="active"><a href='payment.php'><span>Payment</span></a></li>
				   <li><a href='classlist.php'><span>Class Lists</span></a></li>
				   <li><a href='search.php'><span>Search</span></a></li>
				   <li><a href='creditsdebitsrefunds.php'><span>Discounts | Debits | Refunds</span></a></li>
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
							<li><a href="payment.php">Payment</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing:2px;font-weight:500;">Payment</h1>	
						</div>
						</div>
					</div>
				
<center>
<?php

$userid = $firstname = $lastname = "";
$academicyear = $semester = $period = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION
	
	if ($userlevel == "Cashier") { // if the user is cashier

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
			Welcome to Tanauan Institute, Inc. <b>Cashier:</b> $firstname $lastname.
			<br/>
			<form method='post' action='editprofile.php'>
			<input type='submit' name='editprofile' value='Edit Profile'>
			</form>
			<br/><br/>
			<form method='post' action='payment.php'>
			Enter Student Number: <input type='text' name='searchstudentnumber'> <br/> </br>
			<input type='submit' name='next' value='Next'>
			</form><br/><hr/>
			</div>";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if (isset($_POST["next"])) {
		
		$searchstudentnumber = $searchstudentnumbererror = "";
		$errorcount = 0;
		$_SESSION["recorded"] = "";
		
		if (empty($_POST["searchstudentnumber"])) {
			if (!empty($_SESSION["searchstudentnumber"])) {
				$searchstudentnumber = $_SESSION["searchstudentnumber"];
				$_SESSION["searchstudentnumber"] = "";
			}
			else {
			$searchstudentnumbererror = "<b>Error: </b>Please Enter Student Number!";
			$errorcount++;
			}
		}
		else {
		$searchstudentnumber = validateinput($_POST["searchstudentnumber"]);
		$_SESSION["checkedcreditpay"] = $_SESSION["paymentamount"] = $_SESSION["debitpaymentamount"] = $_SESSION["paymentchange"] = $_SESSION["addcreditvalue"] = $_SESSION["searchstudentnumber"] = "";
			if (!preg_match("/[0-9]/", $searchstudentnumber) OR preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $searchstudentnumber)) {
				$searchstudentnumbererror = "<b>Error: </b>Please Enter a Valid Student Number!";
				$errorcount++;
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
			
			$searchfirstname = $searchlastname = $studentnumber = $studentcourse = $studentyear = $studentsection = $enrollmenttype = $modeofpayment = $pendingpayment = $pendingpaymentname = $pendingpaymentvalue = $downpaymentfee = $prelimsfee = $midtermsfee = $prefinalsfee = $finalsfee = $remainingbalance = $addcreditvalue = $paymentamount = $debitpaymentamount = $checked = $accountname = $accountnumber = $chequeamount = "";
			
			if (!empty($_SESSION["paymentamount"])) {
				$paymentamount = $_SESSION["paymentamount"];
			}
			
			if (!empty($_SESSION["addcreditvalue"])) {
				$addcreditvalue = $_SESSION["addcreditvalue"];
			}
			
			if (!empty($_SESSION["debitpaymentamount"])) {
				$debitpaymentamount = $_SESSION["debitpaymentamount"];
			}
			
			if (!empty($_SESSION["accountname"])) {
				$accountname = $_SESSION["accountname"];
			}
			
			if (!empty($_SESSION["accountnumber"])) {
				$accountnumber = $_SESSION["accountnumber"];
			}
			
			if (!empty($_SESSION["chequeamount"])) {
				$chequeamount = $_SESSION["chequeamount"];
			}
			
			$_SESSION["paymentamount"] = "";
			$_SESSION["addcreditvalue"] = "";
			$_SESSION["debitpaymentamount"] = "";
			$_SESSION["accountname"] = "";
			$_SESSION["accountnumber"] = "";
			$_SESSION["chequeamount"] = "";
			
			$sql = "SELECT * FROM enrolledstudents INNER JOIN enrolledfees ON enrolledfees.studentnumber = enrolledstudents.studentnumber INNER JOIN users ON users.userid = enrolledfees.studentnumber WHERE users.userid = '$searchstudentnumber' AND users.userstatus = 1";
			$result = $connection->query($sql);
			
			if ($result->num_rows == 1) {
				while ($row = $result->fetch_object()) {
					$searchfirstname = $row->firstname;
					$searchlastname = $row->lastname;
					$studentnumber = $row->studentnumber;
					$studentcourse = $row->studentcourse;
					$studentyear = $row->studentyear;
					$studentsection = $row->studentsection;
					$enrollmenttype = $row->enrollmenttype;
					$modeofpayment = $row->modeofpayment;
					$pendingpayment = $row->pendingpayment;
					$downpaymentfee = $row->downpaymentfee;
					$prelimsfee = $row->prelimsfee;
					$midtermsfee = $row->midtermsfee;
					$prefinalsfee = $row->prefinalsfee;
					$finalsfee = $row->finalsfee;
					$remainingbalance = $row->remainingbalance;					
				}
				
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
				
					if ($pendingpayment == "downpaymentfee") { $pendingpaymentname = "Down-Payment Fee"; $pendingpaymentvalue = $downpaymentfee; }
					else if ($pendingpayment == "prelimsfee") { $pendingpaymentname = "Prelims Fee"; $pendingpaymentvalue = $prelimsfee; }
					else if ($pendingpayment == "midtermsfee") { $pendingpaymentname = "Midterms Fee"; $pendingpaymentvalue = $midtermsfee; }
					else if ($pendingpayment == "prefinalsfee") { $pendingpaymentname = "Pre-Finals Fee"; $pendingpaymentvalue = $prefinalsfee; }
					else if ($pendingpayment == "finalsfee") { $pendingpaymentname = "Finals Fee"; $pendingpaymentvalue = $finalsfee; }
					else { $pendingpaymentname = "None"; $pendingpaymentvalue = 0; }
					
					
					
				$studentcredits = $studentdebits = $studentrefunds = 0;

				$sql = "SELECT creditvalue FROM studentcredits WHERE studentnumber = '$searchstudentnumber'";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentcredits = $row->creditvalue;
					}
				}
				
				$sql = "SELECT SUM(debitvalue) AS debitvalue FROM studentdebits WHERE studentnumber = '$searchstudentnumber'";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentdebits = $row->debitvalue + 0;
					}
						if ($studentdebits > 0) {
							$pendingpaymentname = "Debits";
						}
				}
				
				$sql = "SELECT SUM(refundvalue) as refundvalue FROM studentrefunds WHERE studentnumber = '$searchstudentnumber'";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$studentrefunds = $row->refundvalue;
					}
				}
				
				$paymentname = $amountdue = $pendingaccountname = $pendingaccountnumber = $pendingchequeamount = $paymentchange = $paymentdate = "";
				$pendingapproval = false;
				$sql = "SELECT * FROM chequepayments WHERE studentnumber = '$searchstudentnumber' AND paymentstatus = 'Pending'";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$paymentname = $row->paymentname;
						$amountdue = $row->amountdue;
						$pendingaccountname = $row->accountname;
						$pendingaccountnumber = $row->accountnumber;
						$pendingchequeamount = $row->chequeamount;
						$paymentchange = $row->paymentchange;
						$paymentdate = $row->paymentdate;
						$pendingapproval = true;
					}
				}
				
					$_SESSION["pendingpayment"] = $pendingpayment;
					$_SESSION["pendingpaymentname"] = $pendingpaymentname;
					$_SESSION["pendingpaymentvalue"] = $pendingpaymentvalue;
					$_SESSION["remainingbalance"] = $remainingbalance;
					$_SESSION["studentcredits"] = $studentcredits;
					$_SESSION["studentdebits"] = $studentdebits;
					$_SESSION["studentrefunds"] = $studentrefunds;
					$_SESSION["paymentname"] = $paymentname;
					$_SESSION["amountdue"] = $amountdue;
					$_SESSION["pendingchequeamount"] = $pendingchequeamount;
					$_SESSION["paymentchange"] = $paymentchange;
					
			echo "<br/>
				<form method='post' action='payment.php#displayreceipt'>
				<table class='tbl'>
				<tr><td colspan=2 align='center'><h4>Student Details</h4></td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Student Name:</td> <td class='label1'>$searchfirstname $searchlastname</td></tr>
				<tr><td class='label'>Student Number:</td> <td class='label1'>$studentnumber</td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Course:</td> <td class='label1'>$studentcourse</td></tr>
				<tr><td class='label'>Year:</td> <td class='label1'>$studentyear</td></tr>
				<tr><td class='label'>Section:</td> <td class='label1'>$studentsection</td></tr>
				<tr><td class='label'>Enrollment Type:</td> <td class='label1'>$enrollmenttype</td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Mode of Payment:</td> <td class='label1'>$modeofpayment</td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Pending Payment:</td> <td class='label1'>$pendingpaymentname</td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Down-Payment Fee:</td> <td class='label1'> " , formatcurrency($downpaymentfee) , " </td></tr>
				<tr><td class='label'>Prelims Fee:</td> <td class='label1'> " , formatcurrency($prelimsfee) , " </td></tr>
				<tr><td class='label'>Midterms Fee:</td> <td class='label1'> " , formatcurrency($midtermsfee) , " </td></tr>
				<tr><td class='label'>Pre-Finals Fee:</td> <td class='label1'> " , formatcurrency($prefinalsfee) , " </td></tr>
				<tr><td class='label'>Finals Fee:</td> <td class='label1'> " , formatcurrency($finalsfee) , " </td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Remaining Balance:</td> <td class='label1'> " , formatcurrency($remainingbalance) , " </td></tr>
				<tr><td colspan=2><hr/></td></tr>
				<tr><td class='label'>Discounts:</td> <td class='label1'> " , formatcurrency($studentcredits) , " | <input type='text' name='addcreditvalue' value='$addcreditvalue' size='4'> <input type='submit' name='addcredit' value='Add to Discounts'></td></tr>
				<tr><td class='label'>Debits:</td> <td class='label1'> " , formatcurrency($studentdebits) , " </td></tr>";
				
				if ($studentrefunds != 0) {
					echo "<tr><td class='label'>Refunds:</td> <td class='label1'> " , formatcurrency($studentrefunds) , " | <input type='submit' name='refund' value='Refund'> </td></tr>";
				}
				
				echo "<tr><td colspan=2><hr/></td></tr>
				</table>
				<br/><br/><div id='payment'>";
				
				if ($studentcredits != 0) {
					
					if (!empty($_SESSION["checkedcreditpay"])) {
						$checked = true;
					}
					
					?> <input type="checkbox" name="creditpay" <?php if ($checked === true) { echo "checked"; } ?>> Pay With Discounts</input><br/></br/> <?php
				}
				
				if ($studentdebits != 0) { 
					echo "Enter Debit Payment: <input type='text' name='debitpayment' value='$debitpaymentamount' size='4'> <br/><br/>
					<input type='submit' name='debitpay' value='Pay'>
					<br/><br/>
					</div>
					</form>";
				}
				else {
					echo "Enter Payment: <input type='text' name='payment' value='$paymentamount' size='4'> <br/><br/>
					<input type='submit' name='pay' id='pay' value='Pay'>
					<br/><br/>
					</div>
					</form>";
					
					if ($modeofpayment == "Cheque Payment" && $pendingapproval == false) {
						echo "<button id='enablechequepayment' onclick='enablechequepayment()'>Cheque Payment</button>";
					}
					else if ($modeofpayment == "Cheque Payment" && $pendingapproval == true) {
						echo "<br/><form method='post' action='payment.php'>
						<table class='tbl'>
						<tr><td colspan=2 class='label'><hr noshade></td></tr>
						<tr><td align='center' colspan=2><h4>Cheque Payment Pending Approval</h4></td></tr>
						<tr><td colspan=2 class='label'><hr noshade></td></tr>
						<tr><td class='label'>Payment Name: </td><td class='label1'>$paymentname</td></tr>
						<tr><td class='label'>Payment Date: </td><td class='label1'>$paymentdate</td></tr>
						<tr><td colspan=2 class='label'><hr noshade></td></tr>
						<tr><td class='label'>Account Name: </td><td class='label1'>$pendingaccountname</td></tr>
						<tr><td class='label'>Account Number: </td><td class='label1'>$pendingaccountnumber</td></tr>
						<tr><td colspan=2 class='label'><hr noshade></td></tr>
						<tr><td class='label'>Amount Due: </td><td class='label1'> " , formatcurrency($amountdue) , " </td></tr>
						<tr><td class='label'>Cheque Amount: </td><td class='label1'> " , formatcurrency($pendingchequeamount) , " </td></tr>
						<tr><td colspan=2 class='label'><hr noshade></td></tr>
						<tr><td class='label'>Refunds: </td><td class='label1'> " , formatcurrency($paymentchange) , " </td></tr>
						<tr><td colspan=2 class='label'><hr noshade></td></tr>
						</table>
						<br/>
						<input type='submit' name='approvechequepayment' value='Approve'>
						</form>";
					}
				}
				
				echo "<div id='chequepayment'>
				<h4>Enter Cheque Details</h4><br/>
				<form method='post' action='payment.php#displayreceipt'>
				<table class='tbl'>
				<tr>
				<td class='label'>Account Name:</td> <td><input type='text' name='accountname' value='$accountname'></td>
				</tr>
				<tr>
				<td class='label'>Account Number:</td> <td><input type='text' name='accountnumber' value='$accountnumber'></td>
				</tr>
				<tr>
				<td class='label'>Cheque Amount:</td> <td><input type='text' name='chequeamount' value='$chequeamount'></td>
				</tr>
				</table>
				<br/>
				<input type='submit' name='chequepay' value='Submit'>
				</form>
				</div><br/><br/>";
				
				$_SESSION["searchfirstname"] = $searchfirstname;
				$_SESSION["searchlastname"] = $searchlastname;
				
			}
			else {
				echo "<br/>
					<span class='error'><b>Error: </b>Student Number Not Registered or Not Yet Enrolled! Please Try Again.</span>
					";
				}
			}
		}
		
		else if (isset($_POST["addcredit"])) {
		
			$transactionresult = "";
			$studentcredits = $addcreditvalue = $newstudentcredit = $studentnumber = $errorcount = 0;
			$transactionerror = $transactionnumber = $date = $searchfirstname = $searchlastname = "";
			$searchfirstname = $_SESSION["searchfirstname"];
			$searchlastname = $_SESSION["searchlastname"];
			$studentcredits = $_SESSION["studentcredits"];
			
			if (empty($_POST["addcreditvalue"])) {
				$transactionerror = "<b>Error: </b>Please Enter an Amount!";
				$errorcount++;
			}
			else {
				$addcreditvalue = $_POST["addcreditvalue"];
				$addcreditvalue = validateinput($addcreditvalue);
					if (!preg_match("/^[0-9.]*$/", $addcreditvalue)) {
						$transactionerror = "<b>Error: </b>Please Enter a Valid Amount!";
						$errorcount++;
				}
			}
			
			$_SESSION["addcreditvalue"] = $addcreditvalue;
			
			if ($errorcount > 0) {
				echo "
				<br/>
				<form method='post' action='payment.php#pay'>
				<table class='tbl'>
				<tr><td><span class='error'>$transactionerror</span></td></tr>
				</table>
				<br/>
				<input type='submit' name='next' value='Back'>
				</form><br/>
				";
			}
			else {
				
			$studentnumber = $_SESSION["searchstudentnumber"];
			$_SESSION["searchstudentnumber"] = "";
			$newstudentcredit = $studentcredits + $addcreditvalue;
			$transactionnumber = date("mdYHis");
			$date = date("m/d/Y H:i:s");
			
			if (empty($_SESSION["recorded"])) {


			$sql1 = "UPDATE studentcredits SET creditvalue = '$newstudentcredit' WHERE studentnumber = $studentnumber";
			$result1 = $connection->query($sql1);
			
			if ($connection->affected_rows == 1) {
				
				$sql2 = "INSERT INTO creditslog (transactionnumber, cashierid, studentnumber, credittype, addedusedcredit, newcreditvalue) VALUES ('$transactionnumber', '$userid', '$studentnumber', 'Added', '$addcreditvalue', '$newstudentcredit')";
				$result2 = $connection->query($sql2);
				
				if ($result2 === true) {
					$transactionresult = "Transaction Complete! Thank You.";
					$_SESSION["recorded"] = true;
					$_SESSION["addcreditvalue"] = "";
					$_SESSION["paymentamount"] = "";
				}
			}
			else {
				$sql3 = "INSERT INTO studentcredits (studentnumber, creditvalue) VALUES ('$studentnumber', '$newstudentcredit')";
				
				$result3 = $connection->query($sql3);
					if ($result3 === true) {
						
						$sql4 = "INSERT INTO creditslog (transactionnumber, cashierid, studentnumber, credittype, addedusedcredit, newcreditvalue) VALUES ('$transactionnumber', '$userid', '$studentnumber', 'Added', '$addcreditvalue', '$newstudentcredit')";
						$result4 = $connection->query($sql4);
						
						if ($result4 === true) {
							$transactionresult = "Transaction Complete! Thank You.";
							$_SESSION["recorded"] = true;
							$_SESSION["addcreditvalue"] = "";
							$_SESSION["paymentamount"] = "";
						}
					}
					else {
						$transactionresult = "Transaction Error! Please Try Again.";
						$_SESSION["recorded"] = false;
					}
				}
			}
			else {
				if ($_SESSION["recorded"] == true) {
					$transactionresult = "Transaction Complete! Thank You.";
					$_SESSION["addcreditvalue"] = "";
					$_SESSION["paymentamount"] = "";
					
				}
				else if ($_SESSION["recorded"] == false) {
					$transactionresult = "Transaction Error! Please Try Again.";
					
				}
			}
					echo "<br/>	
					<table class='receipt'>					
					<tr><td colspan=2 align='center' class='heading'>TANAUAN INSTITUTE, INC.</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>J. Gonzales St., Brgy. 4, Tanauan City, Batangas</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Tel. Nos.: 778-1742 / 784-1611</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Non-VAT Reg. TIN: 000-959-135-000</td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='heading2'><h5>OFFICIAL RECEIPT</h5></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='right' class='heading2'>Date: <u>$date</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='center' class='heading2'> <b>Transaction Number:</b> $transactionnumber </td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Student Name:</td> <td class='label1'>$searchfirstname $searchlastname</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Transaction Name:</td> <td class='label1'>Add Discounts</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Discounts Added:</td> <td class='label1'> " , formatcurrency($addcreditvalue) , " </td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>New Total Discounts:</td> <td class='label1'> " , formatcurrency($newstudentcredit) , " </td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td align='right' class='label'>Cashier:</td> <td class='label1' align='left'><u>$firstname $lastname</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					</table>";
					
				?>
				<button class='printignore' id='displayreceipt' onclick="printreceipt('<?php echo $transactionresult; ?>')">Okay</button><br/><br/><br/><br/>
				<?php
				}
		}
		
		else if (isset($_POST["pay"])) {
			
			$paymenterror = $transactionresult = "";
			$payment = $studentcredits = $newstudentcredit = $creditpayment = $totalpayment = $pendingpayment = $pendingpaymentname = $date = $pendingpaymentvalue = $paymentchange = $remainingbalance = $errorcount = 0;
			
			$payment = $_POST["payment"];
			$pendingpayment = $_SESSION["pendingpayment"];
			$pendingpaymentname = $_SESSION["pendingpaymentname"];
			$pendingpaymentvalue = $_SESSION["pendingpaymentvalue"];
			$remainingbalance = $_SESSION["remainingbalance"];
			$studentcredits = $_SESSION["studentcredits"];
			
			if (empty($pendingpaymentvalue)) {
				$paymenterror = "<b>Error: </b>No Pending Payment Available! Please Come Back Later.";
				$errorcount++;
				$_SESSION["paymentamount"] = "";
				$_SESSION["checkedcreditpay"] = "";
			}
			
			if (empty($payment)) {
				if (!empty($_POST["creditpay"])) {
					$_SESSION["checkedcreditpay"] = "true";
					$newstudentcredit = $studentcredits - $pendingpaymentvalue;
					if ($newstudentcredit >= 0) {
					$payment = 0;
					$creditpayment = $pendingpaymentvalue;
					}
					else {
						$paymenterror = "<b>Error: </b>Please Pay With Discounts More Than the Pending Payment!";
						$errorcount++;
					}
				}
				else {
				$paymenterror = "<b>Error: </b>Please Enter Payment Amount!";
				$errorcount++;
				$_SESSION["checkedcreditpay"] = "";
				}
			}
			else 
			{
				$payment = validateinput($payment);
				if (!preg_match("/^[0-9.]*$/", $payment)) {
				$paymenterror = "<b>Error: </b>Please Enter a Valid Payment Amount!";
				$errorcount++;
				}
				else if ($payment < $pendingpaymentvalue) {
					if (!empty($_POST["creditpay"])) {
						$_SESSION["checkedcreditpay"] = "true";
						$subtractvalue = 0;
						
						$subtractvalue = $pendingpaymentvalue - $payment;
						$newstudentcredit = $studentcredits - $subtractvalue;
							
							if ($newstudentcredit >= 0) {
							$creditpayment = $subtractvalue;
							}
							else {
							$paymenterror = "<b>Error: </b>Please Pay With Discounts More Than the Pending Payment!";
							$errorcount++;
						}
					}
					else {
						$paymenterror = "<b>Error: </b>Please Enter Payment Amount More Than the Pending Payment!";
						$errorcount++;
						$_SESSION["checkedcreditpay"] = "";
					}
				}
			}
			
			$_SESSION["paymentamount"] = $payment;
			
			if ($errorcount > 0) {
				
				echo "
					<br/>
					<form method='post' action='payment.php#pay'>
					<table class='tbl'>
					<tr><td><span class='error'>$paymenterror</span></td></tr>
					</table>
					<br/>
					<input type='submit' name='next' value='Back'>
					</form><br/>
					";
				
			}
			else {
				
				$studentnumber = $transactionnumber = $permitvalidity = $searchfirstname = $searchlastname = "";
				$searchfirstname = $_SESSION["searchfirstname"];
				$searchlastname = $_SESSION["searchlastname"];
				$studentnumber = $_SESSION["searchstudentnumber"];
				$_SESSION["searchstudentnumber"] = "";
				
				$totalpayment = $payment + $creditpayment;
				$transactionnumber = date("mdYHis");
				$date = date("m/d/Y H:i:s");
				$paymentchange = $totalpayment - $pendingpaymentvalue;
				$newremainingbalance = $remainingbalance - $pendingpaymentvalue;
				
				if (empty($_SESSION["recorded"])) {
				
				$sql = "INSERT INTO payments (transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, cashpayment, creditpayment, totalpayment,  paymentchange) VALUES ('$transactionnumber', '$userid', '$studentnumber', '$academicyear', '$semester', '$period', '$pendingpaymentname', '$pendingpaymentvalue', '$payment', '$creditpayment', '$totalpayment', '$paymentchange')";
				$result = $connection->query($sql);
					
				$sqlenrolledstudents = "UPDATE enrolledstudents SET studentstatus = 'Enrolled' WHERE studentnumber = $studentnumber AND studentstatus != 'Enrolled'";
				$resultenrolledstudents = $connection->query($sqlenrolledstudents);
					
				$sqlenrolledsubjects = "UPDATE enrolledsubjects SET subjectstatus = 'Enrolled' WHERE studentnumber = $studentnumber AND subjectstatus != 'Enrolled'";
				$resultenrolledsubjects = $connection->query($sqlenrolledsubjects);
				
				$sqlcreditedsubjects = "UPDATE creditedsubjects SET subjectstatus = 'Credited' WHERE studentnumber = $studentnumber AND subjectstatus != 'Credited'";
				$resultcreditedsubjects = $connection->query($sqlcreditedsubjects);
					
				if ($newremainingbalance == 0) {
					$sqlenrolledfees = "UPDATE enrolledfees SET pendingpayment = '', downpaymentfee = -1, prelimsfee = -1, midtermsfee = -1, prefinalsfee = -1, finalsfee = -1, remainingbalance = -1, balancestatus = 'Fully Paid' WHERE studentnumber = $studentnumber AND remainingbalance != -1";
					$resultenrolledfees = $connection->query($sqlenrolledfees);
					
					$resultenrolledfees1 = true;
				}
				else {
					
					$sqlenrolledfees1 = "UPDATE enrolledfees SET pendingpayment = '', $pendingpayment = -1, remainingbalance = '$newremainingbalance' WHERE studentnumber = $studentnumber AND balancestatus = 'Partially Paid'";
					$resultenrolledfees1 = $connection->query($sqlenrolledfees1);
					
					$sqlenrolledfees = "UPDATE enrolledfees SET pendingpayment = '', $pendingpayment = -1, remainingbalance = '$newremainingbalance', balancestatus = 'Partially Paid' WHERE studentnumber = $studentnumber AND balancestatus  != 'Partially Paid'";
					$resultenrolledfees = $connection->query($sqlenrolledfees);
					
				}
				
				if ($creditpayment != 0) {
					if ($newstudentcredit == 0) {
					$sqlstudentcredit = "DELETE FROM studentcredits WHERE studentnumber = $studentnumber";
					$resultstudentcredit = $connection->query($sqlstudentcredit);
					}
					else {
					$sqlstudentcredit = "UPDATE studentcredits SET creditvalue = '$newstudentcredit' WHERE studentnumber = $studentnumber";
					$resultstudentcredit = $connection->query($sqlstudentcredit);
					}
					
					$sqlcreditslog = "INSERT INTO creditslog (transactionnumber, cashierid, studentnumber, credittype, addedusedcredit, newcreditvalue) VALUES ('$transactionnumber', '$userid', '$studentnumber', 'Used', '$creditpayment', '$newstudentcredit')";
					$resultcreditslog = $connection->query($sqlcreditslog);
					
				}
				else {
					$resultstudentcredit = true;
					$resultcreditslog = true;
				}
					if ($result === true && $resultenrolledstudents === true && $resultenrolledsubjects === true && $resultcreditedsubjects === true && $resultenrolledfees === true && $resultenrolledfees1 === true && $resultstudentcredit === true && $resultcreditslog === true) {
						$transactionresult = "Transaction Complete! Thank You.";
						$_SESSION["recorded"] = true;
					}
					else {
						$transactionresult = "Transaction Error! Please Try Again.";
						$_SESSION["recorded"] = false;
					}
				}
			else {
				if ($_SESSION["recorded"] == true) {
					$transactionresult = "Transaction Complete! Thank You.";
				}
				else if ($_SESSION["recorded"] == false) {
					$transactionresult = "Transaction Error! Please Try Again.";
				}
			}
			
			if ($newremainingbalance == 0) {
				$permitvalidity = "Finals Period";
			}
			else {
					if ($pendingpaymentname == "Down-Payment Fee") {$permitvalidity = "None";}
					else if ($pendingpaymentname == "Prelims Fee") {$permitvalidity = "Prelims Period";}
					else if ($pendingpaymentname == "Midterms Fee") {$permitvalidity = "Midterms Period";}
					else if ($pendingpaymentname == "Pre-Finals Fee") {$permitvalidity = "Pre-Finals Period";}
					else if ($pendingpaymentname == "Finals Fee") {$permitvalidity = "Finals Period";}
			}
			
				echo "<br/>
					<table class='receipt'>
					<tr><td colspan=2 align='center' class='heading'>TANAUAN INSTITUTE, INC.</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>J. Gonzales St., Brgy. 4, Tanauan City, Batangas</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Tel. Nos.: 778-1742 / 784-1611</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Non-VAT Reg. TIN: 000-959-135-000</td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='heading2'><h5>OFFICIAL RECEIPT</h5></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='right' class='heading2'>Date: <u>$date</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='center' class='heading2'> <b>Transaction Number:</b> $transactionnumber </td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Student Name:</td> <td class='label1'>$searchfirstname $searchlastname</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Payment Name:</td> <td class='label1'>$pendingpaymentname</td></tr>
					<tr><td class='label'>Examination Permit Until:</td> <td class='label1'>$permitvalidity</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Amount Due:</td> <td class='label1'> " , formatcurrency($pendingpaymentvalue) , " </td></tr>";
					if ($creditpayment != 0) {
						echo "<tr><td colspan=2 class='label'><hr noshade></td></tr>
							<tr><td class='label'>Cash Payment:</td> <td class='label1'> " , formatcurrency($payment) , " </td></tr>
							<tr><td class='label'>Discounts Payment:</td> <td class='label1'> " , formatcurrency($creditpayment) , " </td></tr>
							<tr><td colspan=2 class='label'><hr noshade></td></tr>
							<tr><td class='label'>Total Payment:</td> <td class='label1'> " , formatcurrency($totalpayment) , " </td></tr>
							<tr><td colspan=2 class='label'><hr noshade></td></tr>";
					}
					else {
						echo "<tr><td class='label'>Total Payment:</td> <td class='label1'> " , formatcurrency($totalpayment) , " </td></tr>
							<tr><td colspan=2 class='label'><hr noshade></td></tr>";
					}
					echo "
					<tr><td class='label'>Change:</td> <td class='label1'> " , formatcurrency($paymentchange) , " </td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td align='right' class='label'>Cashier:</td> <td class='label1' align='left'><u>$firstname $lastname</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					</table>";
					
					$_SESSION["paymentamount"] = "";
					$_SESSION["checkedcreditpay"] = "";
				?>
				<button class='printignore' id='displayreceipt' onclick="printreceipt('<?php echo $transactionresult; ?>')">Okay</button><br/><br/><br/><br/>
				<?php
			}
			
		}
		else if (isset($_POST["debitpay"])) {
			
			$paymenterror = $permitvalidity = $searchfirstname = $searchlastname = "";
			$debitpayment = $studentdebits = $studentcredits = $newstudentcredit = $creditpayment = $totalpayment = $pendingpaymentname = $paymentchange = $remainingbalance = $errorcount = 0;
			$debitpayment = $_POST["debitpayment"];
			$studentdebits = $_SESSION["studentdebits"];
			$pendingpaymentname = $_SESSION["pendingpaymentname"];
			$remainingbalance = $_SESSION["remainingbalance"];
			$studentcredits = $_SESSION["studentcredits"];
				
			if (empty($debitpayment)) {
				if (!empty($_POST["creditpay"])) {
					$_SESSION["checkedcreditpay"] = "true";
					
					$newstudentcredit = $studentcredits - $studentdebits;
					if ($newstudentcredit >= 0) {
					$creditpayment = $studentdebits;
					$debitpayment = 0;
					}
					else {
						$paymenterror = "<b>Error: </b>Please Pay With Discounts More Than the Pending Payment!";
						$errorcount++;
					}
				}
				else {
				$paymenterror = "<b>Error: </b>Please Enter Payment Amount!";
				$errorcount++;
				$_SESSION["checkedcreditpay"] = "";
				}
			}
			else 
			{
				$debitpayment = validateinput($debitpayment);
				if (!preg_match("/^[0-9.]*$/", $debitpayment)) {
				$paymenterror = "<b>Error: </b>Please Enter a Valid Payment Amount!";
				$errorcount++;
				}
				else if ($debitpayment < $studentdebits) {
					if (!empty($_POST["creditpay"])) {
						$_SESSION["checkedcreditpay"] = "true";
						$subtractvalue = 0;
						
						$subtractvalue = $studentdebits - $debitpayment;
						$newstudentcredit = $studentcredits - $subtractvalue;
							
							if ($newstudentcredit >= 0) {
							$creditpayment = $subtractvalue;
							$debitpayment = $debitpayment;
							}
							else {
							$paymenterror = "<b>Error: </b>Please Pay With Discounts More Than the Pending Payment!";
							$errorcount++;
						}
					}
					else {
						$paymenterror = "<b>Error: </b>Please Enter Payment Amount More Than the Pending Payment!";
						$errorcount++;
						$_SESSION["checkedcreditpay"] = "";
					}
				}
			}
			
			$_SESSION["debitpaymentamount"] = $debitpayment;
			
			if ($errorcount > 0) {
				
				echo "
					<br/>
					<form method='post' action='payment.php#pay'>
					<table class='tbl'>
					<tr><td><span class='error'>$paymenterror</span></td></tr>
					</table>
					<br/>
					<input type='submit' name='next' value='Back'>
					</form><br/>
					";
			}
			else {
				
				$studentnumber = $transactionnumber = $debitvalue = $debitname = $debitdisplayname = "";
				$a = 0;
				
				$studentnumber = $_SESSION["searchstudentnumber"];
				$_SESSION["searchstudentnumber"] = "";
				
				$searchfirstname = $_SESSION["searchfirstname"];
				$searchlastname = $_SESSION["searchlastname"];
				$totalpayment = $debitpayment + $creditpayment;
				$transactionnumber = date("mdYHis");
				$date = date("m/d/Y H:i:s");
				$paymentchange = $totalpayment - $studentdebits;
				$newremainingbalance = $remainingbalance - $studentdebits;
				
				if (empty($_SESSION["recorded"])) {
				
				$transactionresult = "";
				
				$sql = "SELECT debitname FROM studentdebits WHERE studentnumber = $studentnumber";
				$result = $connection->query($sql);
				
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_object()) {
						$debitname[$a] = $row->debitname;
						$a++;
					}
					
					$sql = "INSERT INTO payments (transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, cashpayment, creditpayment, totalpayment, paymentchange) VALUES ('$transactionnumber', '$userid', '$studentnumber', '$academicyear', '$semester', '$period', '$pendingpaymentname', '$studentdebits', '$debitpayment', '$creditpayment', '$totalpayment', '$paymentchange')";
					$result = $connection->query($sql);
					
						for ($i=0;$i<sizeof($debitname);$i++) {
							$sqlenrolledfees = "UPDATE enrolledfees SET $debitname[$i] = -1, remainingbalance = '$newremainingbalance' WHERE studentnumber = $studentnumber";
							$resultenrolledfees = $connection->query($sqlenrolledfees);
							
							if ($debitname[$i] == "prelimsfee") {$permitvalidity = "Prelims Period";}
							else if ($debitname[$i] == "midtermsfee") {$permitvalidity = "Midterms Period";}
							else if ($debitname[$i] == "prefinalsfee") {$permitvalidity = "Pre-Finals Period";}
							else if ($debitname[$i] == "finalsfee") {$permitvalidity = "Finals Period";}
						}
					
					if ($newremainingbalance == 0) {
					$sqlpendingpayment = "UPDATE enrolledfees SET pendingpayment = '', downpaymentfee = -1, prelimsfee = -1, midtermsfee = -1, prefinalsfee = -1, finalsfee = -1, remainingbalance = -1, balancestatus = 'Fully Paid' WHERE studentnumber = $studentnumber AND remainingbalance != -1";
					$resultpendingpayment = $connection->query($sqlpendingpayment);
					
					$permitvalidity = "Finals Period";
					}
					else {
						$resultpendingpayment = true;
					}
					
					$sqlstudentdebits = "DELETE FROM studentdebits WHERE studentnumber = $studentnumber";
					$resultstudentdebits = $connection->query($sqlstudentdebits);
					
					if ($creditpayment != 0) {
					
						if ($newstudentcredit == 0) {
							$sqlstudentcredit = "DELETE FROM studentcredits WHERE studentnumber = $studentnumber";
							$resultstudentcredit = $connection->query($sqlstudentcredit);
						}
						else {
							$sqlstudentcredit = "UPDATE studentcredits SET creditvalue = '$newstudentcredit' WHERE studentnumber = $studentnumber";
							$resultstudentcredit = $connection->query($sqlstudentcredit);
						}
						
					$sqlcreditslog = "INSERT INTO creditslog (transactionnumber, cashierid, studentnumber, credittype, addedusedcredit, newcreditvalue) VALUES ('$transactionnumber', '$userid', '$studentnumber', 'Used', '$creditpayment', '$newstudentcredit')";
					$resultcreditslog = $connection->query($sqlcreditslog);
						
					}
					else {
						$resultstudentcredit = true;
						$resultcreditslog = true;
					}
					
					if ($result === true && $resultenrolledfees === true && $resultpendingpayment === true && $resultstudentdebits === true && $resultstudentcredit === true && $resultcreditslog === true) {
						$transactionresult = "Transaction Complete! Thank You.";
						$_SESSION["recorded"] = true;
					}
					else {
						$transactionresult = "Transaction Error! Please Try Again.";
						$_SESSION["recorded"] = true;
					}
				}
				else {
				if ($_SESSION["recorded"] == true) {
					$transactionresult = "Transaction Complete! Thank You.";
				}
				else if ($_SESSION["recorded"] == false) {
					$transactionresult = "Transaction Error! Please Try Again.";
				}
				}
				echo "<br/>
					<table class='receipt'>
					<tr><td colspan=2 align='center' class='heading'>TANAUAN INSTITUTE, INC.</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>J. Gonzales St., Brgy. 4, Tanauan City, Batangas</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Tel. Nos.: 778-1742 / 784-1611</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Non-VAT Reg. TIN: 000-959-135-000</td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='heading2'><h5>OFFICIAL RECEIPT</h5></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='right' class='heading2'>Date: <u>$date</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='center' class='heading2'> <b>Transaction Number:</b> $transactionnumber </td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Student Name:</td> <td class='label1'>$searchfirstname $searchlastname</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Payment Name:</td> <td class='label1'>$pendingpaymentname</td></tr>
					<tr><td class='label'>Examination Permit Until:</td> <td class='label1'>$permitvalidity</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Amount Due:</td> <td class='label1'> " , formatcurrency($studentdebits) , " </td></tr>";
					if ($creditpayment != 0) {
						echo "<tr><td colspan=2 class='label'><hr noshade></td></tr>
							<tr><td class='label'>Cash Payment:</td> <td class='label1'> " , formatcurrency($debitpayment) , " </td></tr>
							<tr><td class='label'>Discounts Payment:</td> <td class='label1'> " , formatcurrency($creditpayment) , " </td></tr>
							<tr><td colspan=2 class='label'><hr noshade></td></tr>
							<tr><td class='label'>Total Payment:</td> <td class='label1'> " , formatcurrency($totalpayment) , " </td></tr>
							<tr><td colspan=2 class='label'><hr noshade></td></tr>";
					}
					else {
						echo "<tr><td class='label'>Total Payment:</td> <td class='label1'> " , formatcurrency($totalpayment) , " </td></tr>
							<tr><td colspan=2 class='label'><hr noshade></td></tr>";
					}
					echo "
					<tr><td class='label'>Change:</td> <td class='label1'> " , formatcurrency($paymentchange) , " </td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td align='right' class='label'>Cashier:</td> <td class='label1' align='left'><u>$firstname $lastname</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					</table>";
					
					$_SESSION["paymentamount"] = "";
					$_SESSION["checkedcreditpay"] = "";
					
				?>
				<button class='printignore' id='displayreceipt' onclick="printreceipt('<?php echo $transactionresult; ?>')">Okay</button><br/><br/><br/><br/>
				<?php
				}
			}
		}
		else if (isset($_POST["chequepay"])) {
			
			$studentnumber = $accountname = $accountnumber = $chequeamount = $accountnameerror = $accountnumbererror = $chequeamounterror = $pendingpayment = $pendingpaymentname = $pendingpaymentvalue = $remainingbalance = $paymenterror = "";
			$errorcount = 0;
			
			$pendingpayment = $_SESSION["pendingpayment"];
			$pendingpaymentname = $_SESSION["pendingpaymentname"];
			$pendingpaymentvalue = $_SESSION["pendingpaymentvalue"];
			$accountname = $_POST["accountname"];
			$accountnumber = $_POST["accountnumber"];
			$chequeamount = $_POST["chequeamount"];
			$remainingbalance = $_SESSION["remainingbalance"];
			
			if (empty($pendingpaymentvalue)) {
				$paymenterror = "<b>Error: </b>No Pending Payment Available! Please Come Back Later.";
				$errorcount++;
				$_SESSION["accountname"] = "";
				$_SESSION["accountnumber"] = "";
				$_SESSION["chequeamount"] = "";
			}
			else {
				if (empty($accountname)) {
					$accountnameerror = "<b>Error:</b> Account Name is Required!";
					$errorcount++;
				} 
				else {
					$accountname = validateinput($accountname);
					$accountname = addslashes($accountname);
				}
				
				if (empty($accountnumber)) {
					$accountnumbererror = "<b>Error:</b> Account Number is Required!";
					$errorcount++;
				} 
				else {
					$accountnumber = validateinput($accountnumber);
					$accountnumber = addslashes($accountnumber);
					if (!preg_match("/^[0-9-]*$/", $accountnumber) || strlen($accountnumber) < 5) { 
					  $accountnumbererror = "<b>Error:</b> Account Number Should Be At Least 5 Numbers!";
					  $errorcount++;
					}
				}
				
				if (empty($_POST["chequeamount"])) {
					$chequeamounterror = "<b>Error: </b>Check Amount is Required!";
					$errorcount++;
				}
				else {
					$chequeamount = $_POST["chequeamount"];
					$chequeamount = validateinput($chequeamount);
						if (!preg_match("/^[0-9.]*$/", $chequeamount)) {
							$chequeamounterror = "<b>Error: </b>Please Enter a Valid Cheque Amount!";
							$errorcount++;
						}
						else if ($pendingpaymentvalue > $chequeamount) {
							$paymenterror = "<b>Error: </b>Please Enter Cheque Amount More Than the Pending Payment!";
							$errorcount++;
							$_SESSION["checkedcreditpay"] = "";
						}
				}
				
				
			$_SESSION["accountname"] = $accountname;
			$_SESSION["accountnumber"] = $accountnumber;
			$_SESSION["chequeamount"] = $chequeamount;
			
				
			}
			
			if ($errorcount > 0) {
				
				echo "
				<br/>
				<form method='post' action='payment.php#pay'>
				<table class='tbl'>
				<tr><td><span class='error'>$paymenterror</span></td></tr>
				<tr><td><span class='error'>$accountnameerror</span></td></tr>
				<tr><td><span class='error'>$accountnumbererror</span></td></tr>
				<tr><td><span class='error'>$chequeamounterror</span></td></tr>
				</table>
				<br/>
				<input type='submit' name='next' value='Back'>
				</form><br/>
				";
				
			}
			else {
				
				$studentnumber = $transactionnumber = $permitvalidity = $searchfirstname = $searchlastname = $paymentchange = $date = $virtualnewremainingbalance = "";
				
				$virtualnewremainingbalance = $remainingbalance - $pendingpaymentvalue;
				$paymentchange = $chequeamount - $pendingpaymentvalue;
				$searchfirstname = $_SESSION["searchfirstname"];
				$searchlastname = $_SESSION["searchlastname"];
				$studentnumber = $_SESSION["searchstudentnumber"];
				$_SESSION["searchstudentnumber"] = "";
				$transactionnumber = date("mdYHis");
				$date = date("m/d/Y H:i:s");
				
				if (empty($_SESSION["recorded"])) {
					
					$sql = "INSERT INTO chequepayments (transactionnumber, cashierid, studentnumber, academicyear, semester, period, paymentname, amountdue, accountname, accountnumber, chequeamount, paymentchange, paymentstatus) VALUES ('$transactionnumber', '$userid', '$studentnumber', '$academicyear', '$semester', '$period', '$pendingpaymentname', '$pendingpaymentvalue', '$accountname', '$accountnumber', '$chequeamount', '$paymentchange', 'Pending')";
					$result = $connection->query($sql);
					
					$sqlenrolledfees1 = "UPDATE enrolledfees SET pendingpayment = '', $pendingpayment = -3 WHERE studentnumber = $studentnumber";
					$resultenrolledfees1 = $connection->query($sqlenrolledfees1);
					
					if ($result === true && $resultenrolledfees1) {
						$transactionresult = "Transaction Complete! Thank You.";
						$_SESSION["recorded"] = true;
					}
					else {
						$transactionresult = "Transaction Error! Please Try Again.";
						$_SESSION["recorded"] = false;
					}
					
				}
				else {
					if ($_SESSION["recorded"] == true) {
					$transactionresult = "Transaction Complete! Thank You.";
					}
					else if ($_SESSION["recorded"] == false) {
					$transactionresult = "Transaction Error! Please Try Again.";
					}
				}
				
			if ($virtualnewremainingbalance == 0) {
				$permitvalidity = "Finals Period";
			}
			else {
					if ($pendingpaymentname == "Down-Payment Fee") {$permitvalidity = "None";}
					else if ($pendingpaymentname == "Prelims Fee") {$permitvalidity = "Prelims Period";}
					else if ($pendingpaymentname == "Midterms Fee") {$permitvalidity = "Midterms Period";}
					else if ($pendingpaymentname == "Pre-Finals Fee") {$permitvalidity = "Pre-Finals Period";}
					else if ($pendingpaymentname == "Finals Fee") {$permitvalidity = "Finals Period";}
			}
			
				echo "<br/>
					<table class='receipt'>
					<tr><td colspan=2 align='center' class='heading'>TANAUAN INSTITUTE, INC.</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>J. Gonzales St., Brgy. 4, Tanauan City, Batangas</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Tel. Nos.: 778-1742 / 784-1611</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Non-VAT Reg. TIN: 000-959-135-000</td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='heading2'><h5>OFFICIAL RECEIPT</h5></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='right' class='heading2'>Date: <u>$date</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='center' class='heading2'> <b>Transaction Number:</b> $transactionnumber </td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Student Name:</td> <td class='label1'>$searchfirstname $searchlastname</td></tr>
					<tr><td class='label'>Account Name:</td> <td class='label1'> $accountname </td></tr>
					<tr><td class='label'>Account Number:</td> <td class='label1'> $accountnumber </td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Payment Name:</td> <td class='label1'>$pendingpaymentname</td></tr>
					<tr><td class='label'>Examination Permit Until:</td> <td class='label1'>$permitvalidity</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Amount Due:</td> <td class='label1'> " , formatcurrency($pendingpaymentvalue) , " </td></tr>
					<tr><td class='label'>Cheque Amount:</td> <td class='label1'> " , formatcurrency($chequeamount) , " </td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Refunds:</td> <td class='label1'> " , formatcurrency($paymentchange) , " </td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td align='right' class='label'>Cashier:</td> <td class='label1' align='left'><u>$firstname $lastname</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					</table>";
					
				?>
				<button class='printignore' id='displayreceipt' onclick="printreceipt('<?php echo $transactionresult; ?>')">Okay</button><br/><br/><br/><br/>
				<?php
				
				$_SESSION["accountname"] = "";
				$_SESSION["accountnumber"] = "";
				$_SESSION["chequeamount"] = "";
				
				}
			}
			else if (isset($_POST["approvechequepayment"])) {
				
				$approveresult = $studentnumber = $paymentname = $pendingpayment = $amountdue = $chequeamount = $paymentchange = $remainingbalance = $newremainingbalance = $studentrefunds = $newstudentrefunds = $transactionnumber = "";
				
				$paymentname = $_SESSION["paymentname"];
				$amountdue = $_SESSION["amountdue"];
				$chequeamount = $_SESSION["pendingchequeamount"];
				$paymentchange = $_SESSION["paymentchange"];
				$remainingbalance = $_SESSION["remainingbalance"];
				$studentrefunds = $_SESSION["studentrefunds"];
				$newremainingbalance = $remainingbalance - $amountdue;
				$studentnumber = $_SESSION["searchstudentnumber"];
				$newstudentrefunds = $studentrefunds + $paymentchange;
				$_SESSION["searchstudentnumber"] = "";
				$transactionnumber = date("mdYHis");
				
				if ($paymentname == "Down-Payment Fee") {$pendingpayment = "downpaymentfee";}
				else if ($paymentname == "Prelims Fee") {$pendingpayment = "prelimsfee";}
				else if ($paymentname == "Midterms Fee") {$pendingpayment = "midtermsfee";}
				else if ($paymentname == "Pre-Finals Fee") {$pendingpayment = "prefinalsfee";}
				else if ($paymentname == "Finals Fee") {$pendingpayment = "finalsfee";}
				
				$sqlenrolledstudents = "UPDATE enrolledstudents SET studentstatus = 'Enrolled' WHERE studentnumber = $studentnumber AND studentstatus != 'Enrolled'";
				$resultenrolledstudents = $connection->query($sqlenrolledstudents);
					
				$sqlenrolledsubjects = "UPDATE enrolledsubjects SET subjectstatus = 'Enrolled' WHERE studentnumber = $studentnumber AND subjectstatus != 'Enrolled'";
				$resultenrolledsubjects = $connection->query($sqlenrolledsubjects);
				
				$sqlcreditedsubjects = "UPDATE creditedsubjects SET subjectstatus = 'Credited' WHERE studentnumber = $studentnumber AND subjectstatus != 'Credited'";
				$resultcreditedsubjects = $connection->query($sqlcreditedsubjects);
				
				if ($newremainingbalance == 0) {
					$sqlenrolledfees = "UPDATE enrolledfees SET pendingpayment = '', downpaymentfee = -1, prelimsfee = -1, midtermsfee = -1, prefinalsfee = -1, finalsfee = -1, remainingbalance = -1, balancestatus = 'Fully Paid' WHERE studentnumber = $studentnumber AND remainingbalance != -1";
					$resultenrolledfees = $connection->query($sqlenrolledfees);
						
					$resultenrolledfees1 = true;
				}
				else {
					
					$sqlenrolledfees1 = "UPDATE enrolledfees SET pendingpayment = '', $pendingpayment = -1, remainingbalance = '$newremainingbalance' WHERE studentnumber = $studentnumber AND balancestatus = 'Partially Paid'";
					$resultenrolledfees1 = $connection->query($sqlenrolledfees1);
					
					$sqlenrolledfees = "UPDATE enrolledfees SET pendingpayment = '', $pendingpayment = -1, remainingbalance = '$newremainingbalance', balancestatus = 'Partially Paid' WHERE studentnumber = $studentnumber AND balancestatus  != 'Partially Paid'";
					$resultenrolledfees = $connection->query($sqlenrolledfees);
					
				}
				
				$sqlchequepayments = "UPDATE chequepayments SET paymentstatus = 'Approved' WHERE studentnumber = $studentnumber AND paymentstatus = 'Pending'";
				$resultchequepayments = $connection->query($sqlchequepayments);
				
				if ($paymentchange > 0) {
				
				$sqlstudentrefunds = "INSERT INTO studentrefunds (studentnumber, refundvalue) VALUES ('$studentnumber', '$paymentchange')";
				$resultstudentrefunds = $connection->query($sqlstudentrefunds);
				
				$sqlrefundslog = "INSERT INTO refundslog (transactionnumber, cashierid, studentnumber, refundtype, addedrefundedvalue) VALUES ('$transactionnumber', '$userid', '$studentnumber', 'Added', '$paymentchange')";
				$resultrefundslog = $connection->query($sqlrefundslog);
				
				}
				else {
					$resultstudentrefunds = true;
					$resultrefundslog = true;
				}
				
				if ($resultenrolledstudents === true && $resultenrolledsubjects === true && $resultcreditedsubjects === true && $resultenrolledfees === true && $resultenrolledfees1 === true && $resultchequepayments === true && $resultstudentrefunds === true && $resultrefundslog === true) {
					$approveresult = "Cheque Payment Approve Successful! Thank You.";
					
					$sqlemail = "SELECT * FROM users WHERE userid = '$studentnumber' AND emailaddresscode = 0";
						$resultemail = $connection->query($sqlemail);
							
							if ($resultemail->num_rows == 1) {
								while ($row = $resultemail->fetch_object()) {
									
									$emailaddress = $row->emailaddress;
									$firstname = $row->firstname;
									$lastname = $row->lastname;
									$emailchequeamount = "₱" + number_format($chequeamount, 2);
									$emailrefunds =  "₱" + number_format($paymentchange, 2);
									
									$recipient = $emailaddress;
									$name = "$firstname $lastname";
									$subject = "Cheque Payment Approval";
									$body = "Hello $firstname $lastname!\n\nYour Cheque Payment with the amount of $emailchequeamount for your $paymentname was approved! You can now claim your refund with the amount of $emailrefunds.\n\nThank You! Have a good day!";
									
									// sendemail($recipient, $name, $subject, $body);
								}
							}
				}
				else {
					$approveresult = "Cheque Payment Approve Unsuccessful! Please Try Again.";
				}
				
				echo "<script> 
					var x = messagealert('$approveresult'); 
					if (x == true) {
						window.location = 'payment.php';
					}
				</script>"; 
				$approveresult = "";
				
			}
			else if (isset($_POST["refund"])) {
				
				$refundresult = $searchfirstname = $searchlastname = $studentnumber = $transactionnumber = $date = $studentrefunds = "";
				
				$searchfirstname = $_SESSION["searchfirstname"];
				$searchlastname = $_SESSION["searchlastname"];
				$studentnumber = $_SESSION["searchstudentnumber"];
				$studentrefunds = $_SESSION["studentrefunds"];
				$_SESSION["searchstudentnumber"] = "";
				$transactionnumber = date("mdYHis");
				$date = date("m/d/Y H:i:s");
				
				if (empty($_SESSION["recorded"])) {
				
					$sql = "DELETE FROM studentrefunds WHERE studentnumber = $studentnumber";
					$result = $connection->query($sql);
					
					$sqlrefundslog = "INSERT INTO refundslog (transactionnumber, cashierid, studentnumber, refundtype, addedrefundedvalue) VALUES ('$transactionnumber', '$userid', '$studentnumber', 'Refunded', '$studentrefunds')";
					$resultrefundslog = $connection->query($sqlrefundslog);
					
					if ($result === true && $resultrefundslog === true) {
						$refundresult = "Refund Successful! Thank You.";
						$_SESSION["recorded"] = true;
					}
					else {
						$refundresult = "Refund Unsuccessful! Thank You.";
						$_SESSION["recorded"] = false;
					}
				}
				else {
					if ($_SESSION["recorded"] == true) {
						$refundresult = "Refund Successful! Thank You.";
					}
					else if ($_SESSION["recorded"] == false) {
						$refundresult = "Refund Unsuccessful! Thank You.";
					}
				}
				
				echo "<br/>	
					<table class='receipt'>					
					<tr><td colspan=2 align='center' class='heading'>TANAUAN INSTITUTE, INC.</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>J. Gonzales St., Brgy. 4, Tanauan City, Batangas</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Tel. Nos.: 778-1742 / 784-1611</td></tr>
					<tr><td colspan=2 align='center' class='heading1'>Non-VAT Reg. TIN: 000-959-135-000</td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='heading2'><h5>OFFICIAL RECEIPT</h5></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='right' class='heading2'>Date: <u>$date</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 align='center' class='heading2'> <b>Transaction Number:</b> $transactionnumber </td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Student Name:</td> <td class='label1'>$searchfirstname $searchlastname</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Transaction Name:</td> <td class='label1'>Refund</td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td class='label'>Refund Amount:</td> <td class='label1'> " , formatcurrency($studentrefunds) , " </td></tr>
					<tr><td colspan=2 class='label'><hr noshade></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					<tr><td align='right' class='label'>Cashier:</td> <td class='label1' align='left'><u>$firstname $lastname</u></td></tr>
					<tr><td colspan=2 class='label'><br/></td></tr>
					</table>";
					
				?>
				<button class='printignore' id='displayreceipt' onclick="printreceipt('<?php echo $refundresult; ?>')">Okay</button><br/><br/><br/><br/>
				<?php
				
			}
		}	
	
				echo "<div class='printignore'><br/><br/>
				<form method='post' action='login.php'>
				<input type='submit' name='log-out' value='Log-Out'>
				</form><br/></div>
				";
				
$connection->close();
}
	else if ($userlevel == "Student"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Students! <br/> <a href='profile.php'>Back</a>"; // if the user tries to access cashier.php with student userlevel
	}
	else if ($userlevel == "Admin"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Admins! <br/> <a href='admin.php'>Back</a>"; // if the user tries to access cashier.php with admin userlevel
	}
	else if ($userlevel == "Registrar Staff"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Registrar Staffs! <br/> <a href='registrar.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
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