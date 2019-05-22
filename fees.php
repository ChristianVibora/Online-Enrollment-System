<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Fees</title>
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
				   <li><a href='home.php'><span>Home</span></a></li>
				   <li class="active"><a href='fees.php'><span>Fees</span></a></li>
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
							<li><a href="fees.php">Fees</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Fees</h1>
							</div>
						</div>
					</div>
				
<center>

<?php

$period = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION
	
	if ($userlevel == "Admin") { // if the user is admin

	$sql = "SELECT * FROM academic";
	$result = $connection->query($sql);
	
	if ($result->num_rows == 1) {
		while ($row = $result->fetch_object()) {
			$period = $row->period;
		}
	}
	
	$sqlcourse = "SELECT DISTINCT subjectcourse, coursecode FROM fees INNER JOIN curriculum ON curriculum.subjectcourse = fees.course WHERE subjectactivated = 1 ORDER BY coursecode"; // SQL query to retrieve all subjectcourse
	$resultcourse = $connection->query($sqlcourse);
	
	$valuesubjectcourse = $valuecoursecode = $arraycourse = "";
	$a = 0; // sets the first index of arrays to 0
		echo "<div class='printignore'>
				<form method='post' action='feeseditor.php'>
				<input type='submit' name='submit' value='Edit Fees'>
				</form><br/>
				<form method='post' action='fees.php'>
				<table class='tbl'>
				<tr>
					<td class='label'>Course:</td>
					<td><select name='course'>
					<option value='All'> All </option>";
					
				if ($resultcourse->num_rows > 0) {	
					while($rowcourse = $resultcourse->fetch_object()) {
						$valuesubjectcourse = $rowcourse->subjectcourse;
						$valuecoursecode = $rowcourse->coursecode;
						$arraycourse[$a] = $valuesubjectcourse; // fill the arraycourse[] with data from database
						echo "<option value='$valuesubjectcourse'> $valuecoursecode </option>"; // fill the course combo box with data from database
						$a++;
					}
				}
					echo "</select></td>
						</tr>
					</table>
					<br/>
					<input type='submit' name='search' value='Search'>
					</form>
					<br/><br/><hr/></div>"; // make the form containing three combo boxes for subjectcourse, subjectyear, and subjectsemester; and a search button

					echo "<h3 class='pagebreak'> <span class='printonly'> Fees </span> </h3>";
					
$course = $year = $semester = $costperunit = $miscellaneousfee = $id = $graduationfee = $studentteaching = $firingfee = $downpaymentfee = $prelimsfee = $midtermsfee = $prefinalsfee = $finalsfee = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (isset($_POST["search"])) {
	
	$_SESSION["subjectcode"] = "";
	$_SESSION["subjectdescription"] = "";
	$_SESSION["subjectunits"] = "";
	
	$course = ($_POST["course"]);

	$arraycoursesize = sizeof($arraycourse);
	
	if ($course == "All") { // if the search contains 'all course - all year - all semester' combination
		
		echo "<div class='printignore'>
			<h3> $course Course </h3>
			<button onclick='window.print()'>Print</button>
			<hr/>
			</div>"; // displays the search details -- this is repeated for all the blocks below
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		
			$sql = "SELECT * FROM fees WHERE course = '$arraycourse[$i]'"; // SQL query for 'all course - all year - all semester' search combination that uses the values of arraycourse[], arrayyear[], and arraysemester[] as parameters as demoed above
			
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo " <br/> <h4>
					$arraycourse[$i] </h4>
					<div class='printignore'>
					<form method='post' action='feeseditor.php'>
					<input type='hidden' name='course' value='$arraycourse[$i]'>
					<input type='submit' name='search' value='Edit Course Fees'>
					</form>
					<br/>
					</div>
					<table class='fees'>
					<tr class='curriculum-header'>
						<th> Year </th>
						<th> Semester </th>
						<th> Miscellaneous Fee </th>
						<th> ID Fee </th>
						<th> Graduation Fee </th>";
						
						if ($arraycourse[$i] == "Bachelor of Science in Criminology") {
							echo "<th> Firing Fee </th>";
						}
						else if ($arraycourse[$i] == "Bachelor of Elementary Education Major in Early Childhood/Pre-School Education" || $arraycourse[$i] == "Bachelor of Secondary Education Major in English" || $arraycourse[$i] == "Bachelor of Secondary Education Major in Mathematics") {
							echo "<th> Student Teaching Fee </th>";
						}
						
						echo "<th> Down-Payment Fee </th>
						<th> Prelims Fee </th>
						<th> Midterms Fee </th>
						<th> Pre-Finals Fee </th>
						<th> Finals Fee </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$year = $row->year;
						$semester = $row->semester;
						$costperunit = $row->costperunit;
						$miscellaneousfee = $row->miscellaneousfee;
						$id = $row->id;
						$graduationfee = $row->graduationfee;
						$studentteaching = $row->studentteaching;
						$firingfee = $row->firingfee;
						$downpaymentfee = $row->downpaymentfee;
						$prelimsfee = $row->prelimsfee;
						$midtermsfee = $row->midtermsfee;
						$prefinalsfee = $row->prefinalsfee;
						$finalsfee = $row->finalsfee;
						
						echo "<tr class='curriculum-row'>
							<td> $year </td>
							<td> $semester</td>
							<td> " , formatcurrency($miscellaneousfee) , " </td>
							<td> " , formatcurrency($id) , " </td>
							<td> " , formatcurrency($graduationfee) , " </td>";
							
							if ($arraycourse[$i] == "Bachelor of Science in Criminology") {
								echo "<td> " , formatcurrency($firingfee) , " </td>";
							}
							else if ($arraycourse[$i] == "Bachelor of Elementary Education Major in Early Childhood/Pre-School Education" || $arraycourse[$i] == "Bachelor of Secondary Education Major in English" || $arraycourse[$i] == "Bachelor of Secondary Education Major in Mathematics") {
								echo "<td> " , formatcurrency($studentteaching) , " </td>";
							}
							
							echo "<td> " , formatcurrency($downpaymentfee) , " </td>
							<td> " , formatcurrency($prelimsfee) , " </td>
							<td> " , formatcurrency($midtermsfee) , " </td>
							<td> " , formatcurrency($prefinalsfee) , " </td>
							<td> " , formatcurrency($finalsfee) , " </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=14 align='center'> Cost Per Unit: " , formatcurrency($costperunit) , " </td> 
					</tr>
					</table>"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
				}
				else { // if a specific combination do not return a result
					echo "";
				}			
				echo "<div class='pagebreak'></div>";				
			}
		}

	else if ($course != "All") { // if the search contains 'all course - all year - one semester' combination
		
		echo "<div class='printignore'>
			<h3> $course </h3>
			<button onclick='window.print()'>Print</button>
			<hr/>
			</div>";
		
			$sql = "SELECT * FROM fees WHERE course = '$course'"; // SQL query for 'all course - all year - all semester' search combination that uses the values of arraycourse[], arrayyear[], and arraysemester[] as parameters as demoed above
			
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo " <br/> <h4>
					$course </h4>
					<div class='printignore'>
					<form method='post' action='feeseditor.php'>
					<input type='hidden' name='course' value='$course'>
					<input type='submit' name='search' value='Edit Course Fees'>
					</form>
					<br/>
					</div>
					<table  class='fees'>
					<tr class='curriculum-header'>
						<th> Year </th>
						<th> Semester </th>
						<th> Miscellaneous Fee </th>
						<th> ID Fee </th>
						<th> Graduation Fee </th>";
						
						if ($course == "Bachelor of Elementary Education Major in Early Childhood/Pre-School Education" || $course == "Bachelor of Secondary Education Major in English" || $course == "Bachelor of Secondary Education Major in Mathematics") {
							echo "<th> Student Teaching Fee </th>";
						}
						else if ($course == "Bachelor of Science in Criminology") {
							echo "<th> Firing Fee </th>";
						}
						
						echo "<th> Down-Payment Fee </th>
						<th> Prelims Fee </th>
						<th> Midterms Fee </th>
						<th> Pre-Finals Fee </th>
						<th> Finals Fee </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$year = $row->year;
						$semester = $row->semester;
						$costperunit = $row->costperunit;
						$miscellaneousfee = $row->miscellaneousfee;
						$id = $row->id;
						$graduationfee = $row->graduationfee;
						$studentteaching = $row->studentteaching;
						$firingfee = $row->firingfee;
						$downpaymentfee = $row->downpaymentfee;
						$prelimsfee = $row->prelimsfee;
						$midtermsfee = $row->midtermsfee;
						$prefinalsfee = $row->prefinalsfee;
						$finalsfee = $row->finalsfee;
						
						echo "<tr class='curriculum-row'>
							<td> $year </td>
							<td> $semester</td>
							<td> " , formatcurrency($miscellaneousfee) , " </td>
							<td> " , formatcurrency($id) , " </td>
							<td> " , formatcurrency($graduationfee) , " </td>";
							
							if ($course == "Bachelor of Elementary Education Major in Early Childhood/Pre-School Education" || $course == "Bachelor of Secondary Education Major in English" || $course == "Bachelor of Secondary Education Major in Mathematics") {
								echo "<td> " , formatcurrency($studentteaching) , " </td>";
							}
							else if ($course == "Bachelor of Science in Criminology") {
								echo "<td> " , formatcurrency($firingfee) , " </td>";
							}
							
							echo "<td> " , formatcurrency($downpaymentfee) , " </td>
							<td> " , formatcurrency($prelimsfee) , " </td>
							<td> " , formatcurrency($midtermsfee) , " </td>
							<td> " , formatcurrency($prefinalsfee) , " </td>
							<td> " , formatcurrency($finalsfee) , " </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=14 align='center'> Cost Per Unit: " , formatcurrency($costperunit) , " </td> 
					</tr>
					</table>"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
				}
				else { // if a specific combination do not return a result
					echo "";
				}								
		}
	else {
			echo "<br/>  Invalid Input ";
	}
}
	
}
$connection->close();
}
	else if ($userlevel == "Student") {
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