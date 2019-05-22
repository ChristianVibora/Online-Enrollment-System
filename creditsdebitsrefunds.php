<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Discounts, Debits, & Refunds</title>
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
			<div id='cssmenu'>
				<ul>
					<li><a href="home.php">Home</a></li>
				   <li class="active"><a href='creditsdebitsrefunds.php'><span>Discounts | Debits | Refunds</span></a></li>
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
						<li><a href="creditsdebitsrefunds.php">Discounts | Debits | Refunds</a></li>
						</ul>
						</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Discounts | Debits | Refunds</h1>
							</div>
						</div>
					</div>
<center>
<?php

$userid = $firstname = $lastname = $password = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION
	
	if ($userlevel == "Admin" || $userlevel == "Cashier") { // if the user is admin
	
	$firstname = $_SESSION["firstname"];
	$lastname = $_SESSION["lastname"];
	
	echo "<div class='printignore'>
			<br/><form method='post' action='creditsdebitsrefunds.php'>
			<table>
			<tr><td>Select Search Type:</td> <td><select name='searchtype'>
			<option value='All'>All</option>
			<option value='Discounts'>Discounts</option>
			<option value='Debits'>Debits</option>
			<option value='Refunds'>Refunds</option>
			<select>
			</td></tr>
			</table>
			<br/>
			<input type='submit' name='search' value='Search'>
			</form><br/><hr/></div>";

	 if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if(isset($_POST["search"])) {
	
			$searchtype = "";
			
			$searchtype = $_POST["searchtype"];
			
			if ($searchtype == "All") {
				
				echo "<h3>Discounts, Debits, & Refunds</h3>
					 <div class='printignore'><button onclick='window.print()'>Print</button><hr/></div>";
				
				getcredits($firstname, $lastname, $userlevel);
				echo "<br/>";
				getdebits($firstname, $lastname, $userlevel);
				echo "<br/>";
				getrefunds($firstname, $lastname, $userlevel);
				
			}
			else if ($searchtype == "Discounts") {
				
				echo "<h3>Discounts</h3>
					<div class='printignore'><button onclick='window.print()'>Print</button><hr/></div>";
					
				getcredits($firstname, $lastname, $userlevel);
				
			}
			else if ($searchtype == "Debits") {
				
				echo "<h3>Debits</h3>
					<div class='printignore'><button onclick='window.print()'>Print</button><hr/></div>";
				
				getdebits($firstname, $lastname, $userlevel);
				
			}
			else if ($searchtype == "Refunds") {
				
				echo "<h3>Refunds</h3>
					<div class='printignore'><button onclick='window.print()'>Print</button><hr/></div>";
				
				getrefunds($firstname, $lastname, $userlevel);
				
			}
		}
	 }
 $connection->close();
	}
	else if ($userlevel == "Registrar Staff"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Registrar Staffs! <br/> <a href='registrar.php'>Back</a>"; // if the user tries to access cashier.php with cashier userlevel
	}
	else if ($userlevel == "Student"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Students! <br/> <a href='profile.php'>Back</a>"; // if the user tries to access cashier.php with student userlevel
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