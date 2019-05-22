<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Search</title>
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
				   <li class="active"><a href='search.php'><span>Search</span></a></li>
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
						<li><a href="search.php">Search</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Search</h1>
							</div>
						</div>
					</div>
<center>					
<?php
$_SESSION["backpage"] = "Search";
if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid

	echo "
			<br/>
			<form method='post' action='search.php'>
			Enter User ID or Name: <input type='text' name='searchvalue'> <br/> </br>
			<input type='submit' name='search' value='Search'>
			</form><br/><hr/>";
			
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$profilepicture = $userid = $firstname = $middlename = $lastname = $mobilenumber = $address = $emailaddress = "";
		$totalusers = 0;
		
	if (isset($_POST["search"])) {
		
		$searchvalue = $searchvalueerror = "";
		$errorcount = 0;
		
		if (empty($_POST["searchvalue"])) {
			if (!empty($_SESSION["searchvalue"])) {
				$searchvalue = $_SESSION["searchvalue"];
			}
			else {
			$searchvalueerror = "<b>Error: </b>Please Enter User ID or Name!";
			$errorcount++;
			$_SESSION["searchvalue"] = "";
			}
		}
		else {
			$searchvalue = validateinput($_POST["searchvalue"]);
			$searchvalue = validateinput($searchvalue);
			if (preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $searchvalue)) {
				$searchvalueerror = "<b>Error: </b>Please Enter User ID or Name!";
				$errorcount++;
			}
		}
		
		if ($errorcount > 0) {
			
			echo "<br/>
					
					<table>
					<tr><td><span class='error'>$searchvalueerror</span></td></tr>
					</table>
					";
		}
		else {
			
			$_SESSION["searchvalue"] = $searchvalue;
			
			echo " <h3> $searchvalue </h3>  <hr/> <br/>";
			
			$sql = "SELECT * FROM users WHERE userlevel='Student' AND (userid = '$searchvalue' OR CONCAT(firstname, ' ', middlename) LIKE '%$searchvalue%' OR CONCAT(middlename, ' ', lastname) LIKE '%$searchvalue%' OR CONCAT(firstname, ' ', lastname) LIKE '%$searchvalue%' OR CONCAT(firstname, ' ', middlename, ' ', lastname) LIKE '%$searchvalue%')";
			$result = $connection->query($sql);
			
			if ($result->num_rows > 0) {
				
				echo "
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th>View Profile</th>
						<th> Profile Picture </th>
						<th> User ID</th> 
						<th> Full Name </th>
						<th> Mobile Number </th>
						<th> Address </th>
						<th> Email Address </th>
					</tr>";
				
				while ($row = $result->fetch_object()) {
					$profilepicture = $row->profilepicture;
					$userid = $row->userid;
					$firstname = $row->firstname;
					$middlename = $row->middlename;
					$lastname = $row->lastname;
					$mobilenumber = $row->mobilenumber;
					$address = $row->address;
					$emailaddress = $row->emailaddress;
					$totalusers++;
					
					if ($profilepicture == "") {
						$profilepicture = "logo.png";
					}
					
					echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$userid'>
							<input type='hidden' name='viewuserid' value='$userid'>
							<td> <input type='submit' name='view' value='View'> </td>
							<td> <img src='images/profilepictures/$profilepicture' style='width:50px;height:50px;border-style: solid;border-width:1px;border-color:black;' alt='N/A'></td>
							<td> $userid </td>
							<td> $firstname $middlename $lastname </td>
							<td> $mobilenumber </td>
							<td> $address </td>
							<td> $emailaddress </td>
							</form>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=7 align='center'> Total Users: $totalusers </td> 
					</tr></table>";
			}
			else {
				echo " Search Did Not Matched Any Results. Please Try Other Input. Thank You! ";
			}
		}
	}
}
// 

$connection->close();
}
else {
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