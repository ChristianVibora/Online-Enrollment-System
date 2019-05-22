<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Class List</title>
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
				   <li class="active"><a href='classlist.php'><span>Class Lists</span></a></li>
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
						<li><a href="classlist.php">Class Lists</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Class Lists</h1>
							</div>
						</div>
					</div>
<center>					
<?php

$academicyear = $semester = $tableenrolledstudents = $tableenrolledfees = $display = "";
$_SESSION["backpage"] = "Class Lists";
if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid

	if (!empty($_SESSION["tableenrolledstudents"]) && !empty($_SESSION["tableenrolledfees"]) && !empty($_SESSION["classlistacademicyear"]) && !empty($_SESSION["classlistsemester"])) {

		$tableenrolledstudents = $_SESSION["tableenrolledstudents"];
		$tableenrolledfees = $_SESSION["tableenrolledfees"];
		$academicyear = $_SESSION["classlistacademicyear"];
		$semester = $_SESSION["classlistsemester"];
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
		$tableenrolledstudents = "enrolledstudents";
		$tableenrolledfees = "enrolledfees";
		$display = "Current";
	}

	echo "
	<h3> <span class='printonly'> $display Class Lists </span>
	<span class='printignore'>$display <br/> </span> Academic Year $academicyear <br/> $semester  </h3>
	<div class='printignore'>
	<form method='get' action='classlist.php'>
	<input type='submit' name='change' value='Change'>
	</form>
	</div>
	<div class='pagebreak'></div>";
	
	$sqlcourse = "SELECT DISTINCT studentcourse, coursecode FROM $tableenrolledstudents INNER JOIN curriculum ON curriculum.subjectcourse = $tableenrolledstudents.studentcourse WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' ORDER BY coursecode";
	$sqlyear = "SELECT year FROM sequenceyear";
	$sqlsection = "SELECT DISTINCT studentsection FROM $tableenrolledstudents WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' ORDER BY studentsection";
	
	$resultcourse = $connection->query($sqlcourse);
	$resultyear = $connection->query($sqlyear);
	$resultsection = $connection->query($sqlsection);
	
	$valuecourse = $valuecoursecode = $valueyear = $valuesection = $arraycourse = $arrayyear = $arraysection = "";
	$arraycourse[0] = $arrayyear[0] = $arraysection[0] = " ";
	$course = $year = $section = "";
	$a = $b = $c = 0; // sets the first index of arrays to 0
	
			echo "<div class='printignore'>
				<br/>
				
				<form method='post' action='classlist.php'>
				<table class='tbl'>
				<tr>
					<td class='label'>Course:</td>
					<td><select name='course'>
					<option value='All'> All </option>";
					
				if ($resultcourse->num_rows > 0) {	
					while($rowcourse = $resultcourse->fetch_object()) {
						$valuesubjectcourse = $rowcourse->studentcourse;
						$valuecoursecode = $rowcourse->coursecode;
						$arraycourse[$a] = $valuesubjectcourse; // fill the arraycourse[] with data from database
						echo "<option value='$valuesubjectcourse'> $valuecoursecode </option>"; // fill the course combo box with data from database
						$a++;
					}
				}
					echo "</select></td>
						</tr>
						<tr>
						<td class='label'>Year:</td>
						<td><select name='year'>
						<option value='All'> All </option>";
						
				if ($resultyear->num_rows > 0) {	
					while($rowyear = $resultyear->fetch_object()) {
						$valueyear = $rowyear->year;
						$arrayyear[$b] = $valueyear; // fill the arrayyear[] with data from database
						echo "<option value='$valueyear'> $valueyear </option>"; // fill the year combo box with data from database
						$b++;
					}
				}
					echo "</select></td>
						</tr>
						<tr>
						<td class='label'>Section:</td>
						<td><select name='section'>
						<option value='All'> All </option>";
						
				if ($resultsection->num_rows > 0) {		
					while($rowsection = $resultsection->fetch_object()) {
						$valuesection = $rowsection->studentsection;
						$arraysection[$c] = $valuesection; // fill the arraysemester[] with data from database
						echo "<option value= '$valuesection'>$valuesection</option>"; // fill the semester combo box with data from database
						$c++;
					}
				}
					echo "</select></td>
					</tr>
					<tr>
					<td class='label'>Status:</td>
					<td><select name='status'>
					<option value='All'> All </option>
					<option value='Pending'> Pending </option>
					<option value='Enrolled'> Enrolled </option>
					<option value='Cleared'> Cleared </option>
					<option value='Dropped'> Dropped </option>
					<option value='Revoked'> Revoked </option>
					</select>
					</td>
					</tr>
					</table>
					<br/>
					<input type='submit' name='submit' value='Search'>
					</form>
					<br/><br/><hr/></div>"; // make the form containing three combo boxes for subjectcourse, subjectyear, and subjectsemester; and a search button
						
$course = $year = $section = $studentstatus = $sqlextension = $studentnumber = $firstname = $lastname = $enrollmenttype = $arraycoursesize = $arrayyearsize =  $arraysectionsize = "";
$totalstudents = $remainingbalance = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$course = $_POST["course"];
	$year = $_POST["year"];
	$section = $_POST["section"]; 
	$studentstatus = $_POST["status"]; // retrieves the values of course, year, and semester from the search combo boxes for use as parameter if needed (when an input is not 'All'); and also for displaying search details
	
	if ($studentstatus == "All") { $sqlextension = ""; }
	else { $sqlextension = "AND studentstatus = '$studentstatus'"; }
	
	$arraycoursesize = sizeof($arraycourse);
	$arrayyearsize = sizeof($arrayyear);
	$arraysectionsize = sizeof($arraysection);

	if ($course == "All" && $year == "All" && $section == "All") { // if the search contains 'all course - all year - all section' combination
		
		echo "<div class='printignore'>
			 <h3> $course Course <br/>
			$year Year <br/>
			$section Section <br/>
			$studentstatus Status</h3><button onclick='window.print()'>Print</button>
			 <hr/>
			</div>"; // displays the search details -- this is repeated for all the blocks below
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($j=0;$j<$arrayyearsize;$j++) {
		for ($k=0;$k<$arraysectionsize;$k++) { // nested for loops through the needed values of arraycourse[], arrayyear[], and arraysemester[] -- this process is repeated for all the blocks below
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM $tableenrolledstudents INNER JOIN $tableenrolledfees ON $tableenrolledfees.referencenumber = $tableenrolledstudents.referencenumber INNER JOIN users ON users.userid = $tableenrolledstudents.studentnumber WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' AND studentcourse = '$arraycourse[$i]' AND studentyear = '$arrayyear[$j]' AND studentsection = '$arraysection[$k]' $sqlextension"; // SQL query for 'all course - all year - all section search combination that uses the values of arraycourse[], arrayyear[], and arraysemester[] as parameters as demoed above
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$arrayyear[$j] <br/>
					Section $arraysection[$k] <br/> </h4>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th class='printignore'> View Profile </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>";
						if ($userlevel == "Cashier") {
							echo "<th> Remaining Balance</th>";
						}
					echo "</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus;
						$remainingbalance = $row->remainingbalance;
						$totalstudents++;
						
						if ($remainingbalance == -1) {
							$remainingbalance = "Fully Paid";
						}
						
						echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$studentnumber'>
							<input type='hidden' name='viewuserid' value='$studentnumber'>
							<td class='printignore'> <input type='submit' name='view' value='View'> </td>
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>";
							if ($userlevel == "Cashier") {
								echo "<td> " , formatcurrency($remainingbalance) , " </td>";
							}
							echo "</form>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<div class='pagebreak'></div>"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
				}
				else { // if a specific combination do not return a result
				echo ""; // prints whitespace -- this is repeated for all the blocks below
				}				
			}
		}
	}
}

	else if ($course == "All" && $year == "All" && $section != "All") { // if the search contains 'all course - all year - one section' combination
		
		echo "<div class='printignore'>
			 <h3> $course Course <br/>
			$year Year <br/>
			Section $section <br/>
			$studentstatus Status</h3> <button onclick='window.print()'>Print</button>
			 <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($j=0;$j<$arrayyearsize;$j++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM $tableenrolledstudents INNER JOIN $tableenrolledfees ON $tableenrolledfees.referencenumber = $tableenrolledstudents.referencenumber INNER JOIN users ON users.userid = $tableenrolledstudents.studentnumber WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' AND studentcourse = '$arraycourse[$i]' AND studentyear = '$arrayyear[$j]' AND studentsection = '$section' $sqlextension"; // SQL query for 'all course - all year - one section' search combination that uses the values of arraycourse[], arrayyear[], and $section (contains the value from the section combo box) as parameters -- this process is also applied in the queries below
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo " <br/> <h4>
					$arraycourse[$i] <br/>
					$arrayyear[$j] <br/>
					Section $section <br/> </h4>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th class='printignore'> View Profile </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>";
						if ($userlevel == "Cashier") {
							echo "<th> Remaining Balance</th>";
						}
					echo "</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus;
						$remainingbalance = $row->remainingbalance;
						$totalstudents++;
						
						if ($remainingbalance == -1) {
							$remainingbalance = "Fully Paid";
						}
						
						echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$studentnumber'>
							<input type='hidden' name='viewuserid' value='$studentnumber'>
							<td class='printignore'> <input type='submit' name='view' value='View'> </td>
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>";
							if ($userlevel == "Cashier") {
								echo "<td> " , formatcurrency($remainingbalance) , " </td>";
							}
							echo "</form>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
			}								
		}
	}
}
	
	else if ($course == "All" && $year != "All" && $section == "All") {  // if the search contains 'all course - one year - all section' combination
		
		echo "<div class='printignore'>
			 <h3> $course Course <br/>
			$year <br/>
			$section Section  <br/>
			$studentstatus Status</h3><button onclick='window.print()'>Print</button>
			 <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($k=0;$k<$arraysectionsize;$k++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM $tableenrolledstudents INNER JOIN $tableenrolledfees ON $tableenrolledfees.referencenumber = $tableenrolledstudents.referencenumber INNER JOIN users ON users.userid = $tableenrolledstudents.studentnumber WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' AND studentcourse = '$arraycourse[$i]' AND studentyear = '$year' AND studentsection = '$arraysection[$k]' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$year <br/>
					Section $arraysection[$k] <br/> </h4>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th class='printignore'> View Profile </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>";
						if ($userlevel == "Cashier") {
							echo "<th> Remaining Balance</th>";
						}
					echo "</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus;
						$remainingbalance = $row->remainingbalance;
						$totalstudents++;
						
						if ($remainingbalance == -1) {
							$remainingbalance = "Fully Paid";
						}
						
						echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$studentnumber'>
							<input type='hidden' name='viewuserid' value='$studentnumber'>
							<td class='printignore'> <input type='submit' name='view' value='View'> </td>
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>";
							if ($userlevel == "Cashier") {
								echo "<td> " , formatcurrency($remainingbalance) , " </td>";
							}
							echo "</form>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
			}				 
		}
	}
}
	
	else if ($course == "All" && $year != "All" && $section != "All") { // if the search contains 'all course - one year - one section' combination
		
		echo "<div class='printignore'>
			 <h3> $course Course <br/>
			$year <br/>
			Section $section <br/>
			$studentstatus Status </h3><button onclick='window.print()'>Print</button>
			  <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM $tableenrolledstudents INNER JOIN $tableenrolledfees ON $tableenrolledfees.referencenumber = $tableenrolledstudents.referencenumber INNER JOIN users ON users.userid = $tableenrolledstudents.studentnumber WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' AND studentcourse = '$arraycourse[$i]' AND studentyear = '$year' AND studentsection = '$section' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$year <br/>
					Section $section <br/> </h4>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th class='printignore'> View Profile </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>";
						if ($userlevel == "Cashier") {
							echo "<th> Remaining Balance</th>";
						}
					echo "</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus;
						$remainingbalance = $row->remainingbalance;
						$totalstudents++;
						
						if ($remainingbalance == -1) {
							$remainingbalance = "Fully Paid";
						}
						
						echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$studentnumber'>
							<input type='hidden' name='viewuserid' value='$studentnumber'>
							<td class='printignore'> <input type='submit' name='view' value='View'> </td>
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>";
							if ($userlevel == "Cashier") {
								echo "<td> " , formatcurrency($remainingbalance) , " </td>";
							}
							echo "</form>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
		}
	}
}
	
	else if ($course != "All" && $year == "All" && $section == "All") { // if the search contains 'one course - all year - all section' combination
		
		echo "<div class='printignore'>
			 <h3> $course <br/>
			$year Year <br/>
			$section Section <br/>
			$studentstatus Status </h3><button onclick='window.print()'>Print</button>
			  <hr/>
			</div>";
		
		for ($j=0;$j<$arrayyearsize;$j++) {
		for ($k=0;$k<$arraysectionsize;$k++) {
	
		$totalstudents = 0;
		
			$sql = "SELECT * FROM $tableenrolledstudents INNER JOIN $tableenrolledfees ON $tableenrolledfees.referencenumber = $tableenrolledstudents.referencenumber INNER JOIN users ON users.userid = $tableenrolledstudents.studentnumber WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' AND studentcourse = '$course' AND studentyear = '$arrayyear[$j]' AND studentsection = '$arraysection[$k]' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$arrayyear[$j] <br/>
					Section $arraysection[$k] <br/> </h4>
				<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th class='printignore'> View Profile </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>";
						if ($userlevel == "Cashier") {
							echo "<th> Remaining Balance</th>";
						}
					echo "</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus;
						$remainingbalance = $row->remainingbalance;
						$totalstudents++;
						
						if ($remainingbalance == -1) {
							$remainingbalance = "Fully Paid";
						}
						
						echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$studentnumber'>
							<input type='hidden' name='viewuserid' value='$studentnumber'>
							<td class='printignore'> <input type='submit' name='view' value='View'> </td>
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>";
							if ($userlevel == "Cashier") {
								echo "<td> " , formatcurrency($remainingbalance) , " </td>";
							}
							echo "</form>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
			}	
		}
	}	
}
	
	else if ($course != "All" && $year == "All" && $section != "All") { // if the search contains 'one course - all year - one section' combination
		
		echo "<div class='printignore'>
			 <h3> $course<br/>
			$year Year <br/>
			Section $section <br/>
			$studentstatus Status </h3> <button onclick='window.print()'>Print</button>
			 <hr/>
			</div>";
		
		for ($j=0;$j<$arrayyearsize;$j++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM $tableenrolledstudents INNER JOIN $tableenrolledfees ON $tableenrolledfees.referencenumber = $tableenrolledstudents.referencenumber INNER JOIN users ON users.userid = $tableenrolledstudents.studentnumber WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' AND studentcourse = '$course' AND studentyear = '$arrayyear[$j]' AND studentsection = '$section' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$arrayyear[$j] <br/>
					Section $section <br/> </h4>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th class='printignore'> View Profile </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>";
						if ($userlevel == "Cashier") {
							echo "<th> Remaining Balance</th>";
						}
					echo "</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus;
						$remainingbalance = $row->remainingbalance;
						$totalstudents++;
						
						if ($remainingbalance == -1) {
							$remainingbalance = "Fully Paid";
						}
						
						echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$studentnumber'>
							<input type='hidden' name='viewuserid' value='$studentnumber'>
							<td class='printignore'> <input type='submit' name='view' value='View'> </td>
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>";
							if ($userlevel == "Cashier") {
								echo "<td> " , formatcurrency($remainingbalance) , " </td>";
							}
							echo "</form>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
			}				
		}
	}
	
	else if ($course != "All" && $year != "All" && $section == "All") { // if the search contains 'one course - one year - all section' combination
		
		echo "<div class='printignore'>
			 <h3> $course <br/>
			$year <br/>
			$section Section <br/>
			$studentstatus Status </h3> <button onclick='window.print()'>Print</button>
			 <hr/>
			</div>";
		
		for ($k=0;$k<$arraysectionsize;$k++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM $tableenrolledstudents INNER JOIN $tableenrolledfees ON $tableenrolledfees.referencenumber = $tableenrolledstudents.referencenumber INNER JOIN users ON users.userid = $tableenrolledstudents.studentnumber WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' AND studentcourse = '$course' AND studentyear = '$year' AND studentsection = '$arraysection[$k]' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$year <br/>
					Section $arraysection[$k] <br/> </h4>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th class='printignore'> View Profile </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>";
						if ($userlevel == "Cashier") {
							echo "<th> Remaining Balance</th>";
						}
					echo "</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus;
						$remainingbalance = $row->remainingbalance;
						$totalstudents++;
						
						if ($remainingbalance == -1) {
							$remainingbalance = "Fully Paid";
						}
						
						echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$studentnumber'>
							<input type='hidden' name='viewuserid' value='$studentnumber'>
							<td class='printignore'> <input type='submit' name='view' value='View'> </td>
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>";
							if ($userlevel == "Cashier") {
								echo "<td> " , formatcurrency($remainingbalance) , " </td>";
							}
							echo "</form>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
			}
		}
	}
	
	else if ($course != "All" && $year != "All" && $section != "All"){ // if the search contains 'one course - one year - one section' combination
		
		echo "<div class='printignore'>
			 <h3> $course <br/>
			$year <br/>
			Section $section <br/>
			$studentstatus Status </h3> <button onclick='window.print()'>Print</button>
			 <hr/>
			</div>";
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM $tableenrolledstudents INNER JOIN $tableenrolledfees ON $tableenrolledfees.referencenumber = $tableenrolledstudents.referencenumber INNER JOIN users ON users.userid = $tableenrolledstudents.studentnumber WHERE $tableenrolledstudents.academicyear = '$academicyear' AND $tableenrolledstudents.semester = '$semester' AND studentcourse = '$course' AND studentyear = '$year' AND studentsection = '$section' $sqlextension";
			$result = $connection->query($sql);
			
			if ($result->num_rows > 0) {
			echo "	 <br/> <h4>
					$course <br/>
					$year <br/>
					Section $section <br/> </h4>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th class='printignore'> View Profile </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>";
						if ($userlevel == "Cashier") {
							echo "<th> Remaining Balance</th>";
						}
					echo "</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus;
						$remainingbalance = $row->remainingbalance;
						$totalstudents++;
						
						if ($remainingbalance == -1) {
							$remainingbalance = "Fully Paid";
						}
						
						echo "
						<tr class='curriculum'>
							<form method='post' action='viewprofile.php?viewuserid=$studentnumber'>
							<input type='hidden' name='viewuserid' value='$studentnumber'>
							<td class='printignore'> <input type='submit' name='view' value='View'> </td>
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>";
							if ($userlevel == "Cashier") {
								echo "<td> " , formatcurrency($remainingbalance) , " </td>";
							}
							echo "</form>
						</tr>";
			}
			echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<div class='pagebreak'></div>";
			} 
			else {
			echo "<br/><br/>  Course / Year / Section Did Not Matched Any Students. Please Try Other Input. Thank You! ";
			}
	}
	else {
			echo "<br/>  Invalid Input ";
}
}
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
		
		if (isset($_GET["change"])) {
		echo "<br/>
				<h3>Change Class List</h3><br/>
				<form method='get' action='classlist.php'>
				<table>
				<tr><td>Select Table: </td>
				<td> <select name='searchtable'>
				<option value='currentclasslist'>Current Class List</option>
				<option value='historyclasslist'>History Class List</option>
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
			
			if ($searchtable == "historyclasslist") {
				$_SESSION["tableenrolledstudents"] = "historyenrolledstudents";
				$_SESSION["tableenrolledfees"] = "historyenrolledfees";
				$result = "Class List Has Been Successfully Set to Academic Year $selectedacademicyear: $selectedsemester!";
			}
			else {
				$_SESSION["tableenrolledstudents"] = "";
				$_SESSION["tableenrolledfees"] = "";
				$result = "Class List Has Been Successfully Set to Current Academic Year and Semester!";
			}
			$_SESSION["classlistacademicyear"] = $selectedacademicyear;
			$_SESSION["classlistsemester"] = $selectedsemester;
			
			echo "<script> 
				var x = messagealert('$result'); 
				if (x == true) {
					window.location = 'classlist.php';
				}
			</script>"; 
			$result = "";
		}
		else if (isset($_GET["reset"])) {
			
			$result = "";
			
			$_SESSION["tableenrolledstudents"] = "";
			$_SESSION["tableenrolledfees"] = "";
			$_SESSION["classlistacademicyear"] = "";
			$_SESSION["classlistsemester"] = "";
			
			$result = "Class List Has Been Successfully Reset to the Current Academic Year and Semester!";
			
			echo "<script> 
				var x = messagealert('$result'); 
				if (x == true) {
					window.location = 'classlist.php';
				}
			</script>"; 
			$result = "";
		}
	}
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