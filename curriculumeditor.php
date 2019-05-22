<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Curriculum Editor</title>
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
				   <li class="active"><a href='curriculumeditor.php'><span>Curriculum Editor</span></a></li>
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
							<li><a href="curriculumeditor.php">Curriculum Editor</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Curriculum Editor</h1>
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
	
	// if ($period == "Vacation") {
	
	$sqlcourse = "SELECT DISTINCT subjectcourse, coursecode FROM curriculum ORDER BY coursecode"; // SQL query to retrieve all subjectcourse
	$sqlyear = "SELECT year FROM sequenceyear"; // SQL query to retrieve all subjectyear
	$sqlsemester = "SELECT semester FROM sequencesemester";// SQL query to retrieve all subjectsemester
	
	$resultcourse = $connection->query($sqlcourse);
	$resultyear = $connection->query($sqlyear);
	$resultsemester = $connection->query($sqlsemester);
	
	$valuesubjectcourse = $valuecoursecode = $valueyear = $valuesemester = $arraycourse = $arrayyear = $arraysemester ="";
	$a = $b = $c = 0; // sets the first index of arrays to 0
		echo "<div class='printignore'>
				<form method='post' action='curriculumeditor.php'>
				<input type='submit' name='addcourse' value='Add Course'>
				</form>
				<br/>
				<form method='post' action='curriculumeditor.php'>
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
						<td class='label'>Semester:</td>
						<td><select name='semester'>
						<option value='All'> All </option>";
						
				if ($resultsemester->num_rows > 0) {		
					while($rowsemester = $resultsemester->fetch_object()) {
						$valuesemester = $rowsemester->semester;
						$arraysemester[$c] = $valuesemester; // fill the arraysemester[] with data from database
						echo "<option value= '$valuesemester'>$valuesemester</option>"; // fill the semester combo box with data from database
						$c++;
					}
				}
					echo "</select></td>
					</tr>
					<tr>
					<td class='label'>Filter By:</td>
					<td><select name='subjectactivated'>
					<option value='All'> All </option>
					<option value='1'> Activated </option>
					<option value='0'> Deactivated </option>
					</select>
					</td>
					</tr>
					</table>
					<br/>
					<input type='submit' name='search' value='Search'>
					</form>
					<br/><br/><hr/></div>"; // make the form containing three combo boxes for subjectcourse, subjectyear, and subjectsemester; and a search button

$course = $year = $semester = $subjectid = $subjectcode = $subjectdescription = $subjectunits = $subjectactivated = $subjectactivateddisplay = $totalactivatedsubjectunits = $totaldeactivatedsubjectunits = $arraycoursesize = $arrayyearsize = $arraysemestersize = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (isset($_POST["search"])) {
	
	$subjectactivated = $_POST["subjectactivated"];
	
	if ($subjectactivated == "All") { $sqlextension = ""; $subjectactivateddisplay = "All"; }
	else if ($subjectactivated == 1){ $sqlextension = "AND subjectactivated = 1"; $subjectactivateddisplay = "Activated"; }
	else if ($subjectactivated == 0){ $sqlextension = "AND subjectactivated = 0"; $subjectactivateddisplay = "Deactivated"; }
	
	$_SESSION["subjectcode"] = "";
	$_SESSION["subjectdescription"] = "";
	$_SESSION["subjectunits"] = "";
	
	$course = ($_POST["course"]);
	$year = ($_POST["year"]);
	$semester = ($_POST["semester"]); // retrieves the values of course, year, and semester from the search combo boxes for use as parameter if needed (when an input is not 'All'); and also for displaying search details

	$arraycoursesize = sizeof($arraycourse);
	$arrayyearsize = sizeof($arrayyear);
	$arraysemestersize = sizeof($arraysemester);
	
	if ($course == "All" && $year == "All" && $semester == "All") { // if the search contains 'all course - all year - all semester' combination
		
		echo "<div class='printignore'>
			<h3> $course Course <br/>
			$year Year <br/>
			$semester Semester <br/>
			$subjectactivateddisplay Subjects</h3> <hr/>
			</div>"; // displays the search details -- this is repeated for all the blocks below
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($j=0;$j<$arrayyearsize;$j++) {
		for ($k=0;$k<$arraysemestersize;$k++) { // nested for loops through the needed values of arraycourse[], arrayyear[], and arraysemester[] -- this process is repeated for all the blocks below
			
			// Demo:
			// first loop: arraycourse[0] = 'ACS', arrayyear[0] = 'First Year', arraysemester[0] = 'First Semester'
			// second loop: arraycourse[0] =  'ACS', arrayyear[0] = 'First Year', arraysemester[1] = 'Second Semester'
			// third loop: arraycourse[0] =  'ACS', arrayyear[0] = 'First Year', arraysemester[2] = 'Summer'
			// fourth loop: arraycourse[0] =  'ACS', arrayyear[1] = 'Second Year', arraysemester[0] = 'First Semester'
			// fifth loop: arraycourse[0] =  'ACS', arrayyear[1] = 'Second Year', arraysemester[1] = 'Second Semester'
			// ..
			// ..
			// last loop: arraycourse[8] =  'MaEd', arrayyear[3] = 'Fouth Year', arraysemester[2] = 'Summer'
		
		$totalactivatedsubjectunits = $totaldeactivatedsubjectunits = 0; // totalsubjectunits is reset every loop -- also applied below
		
			$sql = "SELECT * FROM curriculum WHERE subjectcourse = '$arraycourse[$i]' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$arraysemester[$k]' $sqlextension ORDER BY subjectactivated"; // SQL query for 'all course - all year - all semester' search combination that uses the values of arraycourse[], arrayyear[], and arraysemester[] as parameters as demoed above
			
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo " <br/> <h4>
					$arraycourse[$i] <br/>
					$arrayyear[$j] <br/>
					$arraysemester[$k] <br/> </h4>
					
					<form method='post' action='curriculumeditor.php?course=$arraycourse[$i]&year=$arrayyear[$j]&semester=$arraysemester[$k]'>
					<table width=350>
					<tr>
					<td align='left'><input type='submit' name='activatesemester' value='Activate Semester'></td>
					<td align='right'><input type='submit' name='deactivatesemester' value='Deactivate Semester'></td>
					</tr>
					<tr>
					<td class='label' align='center'>Select Number:</td> <td align='left'><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
					</tr>
					<tr>
					<td align='center' colspan=2><input type='submit' name='addsubjects' value='Add Subjects'></td>
					</tr>
					</table>
					</form>
					<br/>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
						<th> Activate/Deactivate </th> 
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Activated </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$subjectactivated = $row->subjectactivated;
						
						if ($subjectactivated == 1) {
							$totalactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						else if ($subjectactivated == 0) {
							$totaldeactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						echo "
						<tr class='curriculum-row'>
							<td>
							<form method='post' action='curriculumeditor.php?subjectid=$subjectid'>";
							
							if ($subjectactivated == 1) {
								echo " <input type='submit' name='deactivatesubject' value='Deactivate'>";
							}
							else if ($subjectactivated == 0) {
								echo " <input type='submit' name='activatesubject' value='Activate'>";
							}
							
							echo "</form></td>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $subjectactivated </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Activated Subject Units: $totalactivatedsubjectunits </td> 
					</tr>
					<tr class='units'>
					<td colspan=6 align='center'> Total Deactivated Subject Units: $totaldeactivatedsubjectunits </td> 
					</tr>
					</table>"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
				}
				else { // if a specific combination do not return a result
					echo "";
				}				
			}
		echo "<div class='pagebreak'></div>";
		}
	}
}

	else if ($course == "All" && $year == "All" && $semester != "All") { // if the search contains 'all course - all year - one semester' combination
		
		echo "<div class='printignore'>
			<h3> $course Course <br/>
			$year Year <br/>
			$semester <br/>
			$subjectactivateddisplay Subjects</h3> <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($j=0;$j<$arrayyearsize;$j++) {
		
		$totalactivatedsubjectunits = $totaldeactivatedsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectcourse = '$arraycourse[$i]' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$semester' $sqlextension ORDER BY subjectactivated"; // SQL query for 'all course - all year - one semester' search combination that uses the values of arraycourse[], arrayyear[], and $semester (contains the value from the semester combo box) as parameters -- this process is also applied in the queries below
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo " <br/> <h4>
					$arraycourse[$i] <br/>
					$arrayyear[$j] <br/>
					$semester <br/> </h4>
					
					<form method='post' action='curriculumeditor.php?course=$arraycourse[$i]&year=$arrayyear[$j]&semester=$semester'>
					<table width=350>
					<tr>
					<td align='left'><input type='submit' name='activatesemester' value='Activate Semester'></td>
					<td align='right'><input type='submit' name='deactivatesemester' value='Deactivate Semester'></td>
					</tr>
					<tr>
					<td class='label' align='center'>Select Number:</td> <td align='left'><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
					</tr>
					<tr>
					<td align='center' colspan=2><input type='submit' name='addsubjects' value='Add Subjects'></td>
					</tr>
					</table>
					</form>
					<br/>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
						<th> Activate/Deactivate </th> 
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Activated </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$subjectactivated = $row->subjectactivated;
						
							if ($subjectactivated == 1) {
							$totalactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						else if ($subjectactivated == 0) {
							$totaldeactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						echo "
						<tr class='curriculum-row'>
							<td>
							<form method='post' action='curriculumeditor.php?subjectid=$subjectid'>";
							
							if ($subjectactivated == 1) {
								echo " <input type='submit' name='deactivatesubject' value='Deactivate'>";
							}
							else if ($subjectactivated == 0) {
								echo " <input type='submit' name='activatesubject' value='Activate'>";
							}
							
							echo "</form></td>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $subjectactivated </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Activated Subject Units: $totalactivatedsubjectunits </td> 
					</tr>
					<tr class='units'>
					<td colspan=6 align='center'> Total Deactivated Subject Units: $totaldeactivatedsubjectunits </td> 
					</tr>
					</table>
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
			}								
		}
	}
}
	
	else if ($course == "All" && $year != "All" && $semester == "All") {  // if the search contains 'all course - one year - all semester combination
		
		echo "<div class='printignore'>
			<h3> $course Course <br/>
			$year <br/>
			$semester Semester <br/>
			$subjectactivateddisplay Subjects</h3> <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($k=0;$k<$arraysemestersize;$k++) {
		
		$totalactivatedsubjectunits = $totaldeactivatedsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectcourse = '$arraycourse[$i]' AND subjectyear = '$year' AND subjectsemester = '$arraysemester[$k]' $sqlextension ORDER BY subjectactivated";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$year <br/>
					$arraysemester[$k] <br/> </h4>
					
					<form method='post' action='curriculumeditor.php?course=$arraycourse[$i]&year=$year&semester=$semester'>
					<table width=350>
					<tr>
					<td align='left'><input type='submit' name='activateyear' value='Activate Year'></td></td>
					<td align='right'><input type='submit' name='deactivateyear' value='Deactivate Year'></td></td>
					</tr>
					<tr>
					<td class='label' align='center'>Select Number:</td> <td align='left'><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
					</tr>
					<tr>
					<td align='center' colspan=2><input type='submit' name='addsubjects' value='Add Subjects'></td>
					</tr>
					</table>
					</form>
					<br/>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
						<th> Activate/Deactivate </th> 
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Activated </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$subjectactivated = $row->subjectactivated;
						
							if ($subjectactivated == 1) {
							$totalactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						else if ($subjectactivated == 0) {
							$totaldeactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						echo "
						<tr class='curriculum-row'>
							<td>
							<form method='post' action='curriculumeditor.php?subjectid=$subjectid'>";
							
							if ($subjectactivated == 1) {
								echo " <input type='submit' name='deactivatesubject' value='Deactivate'>";
							}
							else if ($subjectactivated == 0) {
								echo " <input type='submit' name='activatesubject' value='Activate'>";
							}
							
							echo "</form></td>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $subjectactivated </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Activated Subject Units: $totalactivatedsubjectunits </td> 
					</tr>
					<tr class='units'>
					<td colspan=6 align='center'> Total Deactivated Subject Units: $totaldeactivatedsubjectunits </td> 
					</tr>
					</table>"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
				}
				else {
				echo "";
			}				 
		}
	echo "<div class='pagebreak'></div>";
	}
}
	
	else if ($course == "All" && $year != "All" && $semester != "All") { // if the search contains 'all course - one year - one semester' combination
		
		echo "<div class='printignore'>
			<h3> $course Course <br/>
			$year <br/>
			$semester <br/>
			$subjectactivateddisplay Subjects</h3> <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		
		$totalactivatedsubjectunits = $totaldeactivatedsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectcourse = '$arraycourse[$i]' AND subjectyear = '$year' AND subjectsemester = '$semester' $sqlextension ORDER BY subjectactivated";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$year <br/>
					$semester <br/> </h4>
					
					<form method='post' action='curriculumeditor.php?course=$arraycourse[$i]&year=$year&semester=$semester'>
					<table width=350>
					<tr>
					<td align='left'><input type='submit' name='activatesemester' value='Activate Semester'></td>
					<td align='right'><input type='submit' name='deactivatesemester' value='Deactivate Semester'></td>
					</tr>
					<tr>
					<td class='label' align='center'>Select Number:</td> <td align='left'><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
					</tr>
					<tr>
					<td align='center' colspan=2><input type='submit' name='addsubjects' value='Add Subjects'></td>
					</tr>
					</table>
					</form>
					<br/>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
						<th> Activate/Deactivate </th> 
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Activated </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$subjectactivated = $row->subjectactivated;
						
							if ($subjectactivated == 1) {
							$totalactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						else if ($subjectactivated == 0) {
							$totaldeactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						echo "
						<tr class='curriculum-row'>
							<td>
							<form method='post' action='curriculumeditor.php?subjectid=$subjectid'>";
							
							if ($subjectactivated == 1) {
								echo " <input type='submit' name='deactivatesubject' value='Deactivate'>";
							}
							else if ($subjectactivated == 0) {
								echo " <input type='submit' name='activatesubject' value='Activate'>";
							}
							
							echo "</form></td>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $subjectactivated </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Activated Subject Units: $totalactivatedsubjectunits </td> 
					</tr>
					<tr class='units'>
					<td colspan=6 align='center'> Total Deactivated Subject Units: $totaldeactivatedsubjectunits </td> 
					</tr>
					</table>
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
		}
	}
}
	
	else if ($course != "All" && $year == "All" && $semester == "All") { // if the search contains 'one course - all year - all semester' combination
		
		echo "<div class='printignore'>
			<h3> $course <br/>
			$year Year <br/>
			$semester Semester <br/>
			$subjectactivateddisplay Subjects</h3> <hr/>
			</div>";
		
		for ($j=0;$j<$arrayyearsize;$j++) {
		for ($k=0;$k<$arraysemestersize;$k++) {
	
		$totalactivatedsubjectunits = $totaldeactivatedsubjectunits = 0;
	
			$sql = "SELECT * FROM curriculum WHERE subjectcourse = '$course' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$arraysemester[$k]' $sqlextension ORDER BY subjectactivated";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$arrayyear[$j] <br/>
					$arraysemester[$k] <br/> </h4>
					
					<form method='post' action='curriculumeditor.php?course=$course&year=$arrayyear[$j]&semester=$arraysemester[$k]'>
					<table width=350>
					<tr>
					<td align='left'><input type='submit' name='activatecourse' value='Activate Course'></td>
					<td align='right'><input type='submit' name='deactivatecourse' value='Deactivate Course'></td>
					</tr>
					<tr>
					<td class='label' align='center'>Select Number:</td> <td align='left'><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
					</tr>
					<tr>
					<td align='center' colspan=2><input type='submit' name='addsubjects' value='Add Subjects'></td>
					</tr>
					</table>
					</form>
					<br/>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
						<th> Activate/Deactivate </th> 
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Activated </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$subjectactivated = $row->subjectactivated;
						
							if ($subjectactivated == 1) {
							$totalactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						else if ($subjectactivated == 0) {
							$totaldeactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						echo "
						<tr class='curriculum-row'>
							<td>
							<form method='post' action='curriculumeditor.php?subjectid=$subjectid'>";
							
							if ($subjectactivated == 1) {
								echo " <input type='submit' name='deactivatesubject' value='Deactivate'>";
							}
							else if ($subjectactivated == 0) {
								echo " <input type='submit' name='activatesubject' value='Activate'>";
							}
							
							echo "</form></td>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $subjectactivated </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Activated Subject Units: $totalactivatedsubjectunits </td> 
					</tr>
					<tr class='units'>
					<td colspan=6 align='center'> Total Deactivated Subject Units: $totaldeactivatedsubjectunits </td> 
					</tr>
					</table>"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
				}
				else {
				echo "";
			}	
		}
	echo "<div class='pagebreak'></div>";
	}
}
	
	else if ($course != "All" && $year == "All" && $semester != "All") { // if the search contains 'one course - all year - one semester' combination
		
		echo "<div class='printignore'>
			<h3> $course<br/>
			$year Year <br/>
			$semester <br/>
			$subjectactivateddisplay Subjects</h3>  <hr/>
			</div>";
		
		for ($j=0;$j<$arrayyearsize;$j++) {
		
		$totalactivatedsubjectunits = $totaldeactivatedsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectcourse = '$course' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$semester' $sqlextension ORDER BY subjectactivated";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$arrayyear[$j] <br/>
					$semester <br/> </h4>
					
					<form method='post' action='curriculumeditor.php?course=$course&year=$arrayyear[$j]&semester=$semester'>
					<table width=350>
					<tr>
					<td align='left'><input type='submit' name='activatesemester' value='Activate Semester'></td>
					<td align='right'><input type='submit' name='deactivatesemester' value='Deactivate Semester'></td>
					</tr>
					<tr>
					<td class='label' align='center'>Select Number:</td> <td align='left'><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
					</tr>
					<tr>
					<td align='center' colspan=2><input type='submit' name='addsubjects' value='Add Subjects'></td>
					</tr>
					</table>
					</form>
					<br/>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
						<th> Activate/Deactivate </th> 
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Activated </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$subjectactivated = $row->subjectactivated;
						
							if ($subjectactivated == 1) {
							$totalactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						else if ($subjectactivated == 0) {
							$totaldeactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						echo "
						<tr class='curriculum-row'>
							<td>
							<form method='post' action='curriculumeditor.php?subjectid=$subjectid'>";
							
							if ($subjectactivated == 1) {
								echo " <input type='submit' name='deactivatesubject' value='Deactivate'>";
							}
							else if ($subjectactivated == 0) {
								echo " <input type='submit' name='activatesubject' value='Activate'>";
							}
							
							echo "</form></td>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $subjectactivated </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Activated Subject Units: $totalactivatedsubjectunits </td> 
					</tr>
					<tr class='units'>
					<td colspan=6 align='center'> Total Deactivated Subject Units: $totaldeactivatedsubjectunits </td> 
					</tr>
					</table>
					<div class='pagebreak'></div>";
				}
				else {
				echo "";
			}				
		}
	}
	
	else if ($course != "All" && $year != "All" && $semester == "All") { // if the search contains 'one course - one year - all semester' combination
		
		echo "<div class='printignore'>
			<h3> $course <br/>
			$year <br/>
			$semester Semester <br/>
			$subjectactivateddisplay Subjects</h3>  <hr/>
			</div>";
		
		for ($k=0;$k<$arraysemestersize;$k++) {
		
		$totalactivatedsubjectunits = $totaldeactivatedsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectcourse = '$course' AND subjectyear = '$year' AND subjectsemester = '$arraysemester[$k]' $sqlextension ORDER BY subjectactivated";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$year <br/>
					$arraysemester[$k] <br/> </h4>
					
					<form method='post' action='curriculumeditor.php?course=$course&year=$year&semester=$arraysemester[$k]'>
					<table width=350>
					<tr>
					<td align='left'><input type='submit' name='activateyear' value='Activate Year'></td></td>
					<td align='right'><input type='submit' name='deactivateyear' value='Deactivate Year'></td></td>
					</tr>
					<tr>
					<td class='label' align='center'>Select Number:</td> <td align='left'><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
					</tr>
					<tr>
					<td align='center' colspan=2><input type='submit' name='addsubjects' value='Add Subjects'></td>
					</tr>
					</table>
					</form>
					<br/>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
						<th> Activate/Deactivate </th> 
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Activated </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$subjectactivated = $row->subjectactivated;
						
							if ($subjectactivated == 1) {
							$totalactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						else if ($subjectactivated == 0) {
							$totaldeactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						echo "
						<tr class='curriculum-row'>
							<td>
							<form method='post' action='curriculumeditor.php?subjectid=$subjectid'>";
							
							if ($subjectactivated == 1) {
								echo " <input type='submit' name='deactivatesubject' value='Deactivate'>";
							}
							else if ($subjectactivated == 0) {
								echo " <input type='submit' name='activatesubject' value='Activate'>";
							}
							
							echo "</form></td>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $subjectactivated </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Activated Subject Units: $totalactivatedsubjectunits </td> 
					</tr>
					<tr class='units'>
					<td colspan=6 align='center'> Total Deactivated Subject Units: $totaldeactivatedsubjectunits </td> 
					</tr>
					</table>"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
				}
				else {
				echo "";
			}
		echo "<div class='pagebreak'></div>";
		}
	}
	
	else if ($course != "All" && $year != "All" && $semester != "All"){ // if the search contains 'one course - one year - one semester' combination
		
		echo "<div class='printignore'>
			<h3> $course <br/>
			$year <br/>
			$semester <br/>
			$subjectactivateddisplay Subjects</h3> <hr/>
			</div>";
		
		$totalactivatedsubjectunits = $totaldeactivatedsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectcourse = '$course' AND subjectyear = '$year' AND subjectsemester = '$semester' $sqlextension ORDER BY subjectactivated";
			$result = $connection->query($sql);
			
			if ($result->num_rows > 0) {
			echo " <h4>
				$course <br/>
				$year <br>
				$semester <br/> </h4> 
				<form method='post' action='curriculumeditor.php?course=$course&year=$year&semester=$semester'>
				<table width=350>
				<tr>
				<td align='left'><input type='submit' name='activatesemester' value='Activate Semester'></td>
				<td align='right'><input type='submit' name='deactivatesemester' value='Deactivate Semester'></td>
				</tr>
				<tr>
				<td class='label' align='center'>Select Number:</td> <td align='left'><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
				</tr>
				<tr>
				<td align='center' colspan=2><input type='submit' name='addsubjects' value='Add Subjects'></td>
				</tr>
				</table>
				</form>
				<br/>
				<table  class='curriculum'>
					<tr class='curriculum-header'>
						<th> Activate/Deactivate </th> 
						<th> Subject ID </th> 
						<th> Subject Code </th>
						<th> Subject Description </th>
						<th> Subject Units </th>
						<th> Subject Activated </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$subjectactivated = $row->subjectactivated;
						
							if ($subjectactivated == 1) {
							$totalactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						else if ($subjectactivated == 0) {
							$totaldeactivatedsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						}
						echo "
						<tr class='curriculum-row'>
							<td>
							<form method='post' action='curriculumeditor.php?subjectid=$subjectid'>";
							
							if ($subjectactivated == 1) {
								echo " <input type='submit' name='deactivatesubject' value='Deactivate'>";
							}
							else if ($subjectactivated == 0) {
								echo " <input type='submit' name='activatesubject' value='Activate'>";
							}
							
							echo "</form></td>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
							<td> $subjectactivated </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Activated Subject Units: $totalactivatedsubjectunits </td> 
					</tr>
					<tr class='units'>
					<td colspan=6 align='center'> Total Deactivated Subject Units: $totaldeactivatedsubjectunits </td> 
					</tr>
					</table>
					<div class='pagebreak'></div>";
			} 
			else {
			echo "<br/>  Course / Year / Semester Did Not Matched Any Subjects. Please Try Other Input. Thank You! ";
			}
	}
	else {
			echo "<br/>  Invalid Input ";
}
	}
	else if (isset($_POST["deactivatesubject"])) {
		
		$subjectid = $deactivateresult = "";
		
		$subjectid = $_GET["subjectid"];
		
		$sql = "UPDATE curriculum SET subjectactivated = 0 WHERE subjectid = '$subjectid'";
		$result = $connection->query($sql);
		
		if ($result === true) {
			$deactivateresult = "Subject Deactivate Successful! The Subject Will Not Be Available Next Enrollment Period.";
		}
		else {
			$deactivateresult = "Subject Deactivate Unsuccessful! Please Try Again.";
		}
		
		echo "<script> 
		var x = messagealert('$deactivateresult'); 
		if (x == true) {
			window.location = 'curriculumeditor.php';
		}
	</script>"; 
	$deactivateresult = "";	
	}
	else if (isset($_POST["deactivatecourse"])) {
		
		$subjectcourse = $deactivateresult = "";
		
		$subjectcourse = $_GET["course"];
		
		$sql = "UPDATE curriculum SET subjectactivated = 0 WHERE subjectcourse = '$subjectcourse'";
		$result = $connection->query($sql);
		
		if ($result === true) {
			$deactivateresult = "Course Deactivate Successful! The Course Will Not Be Available Next Enrollment Period.";
		}
		else {
			$deactivateresult = "Course Deactivate Unsuccessful! Please Try Again.";
		}
		
		echo "<script> 
		var x = messagealert('$deactivateresult'); 
		if (x == true) {
			window.location = 'curriculumeditor.php';
		}
	</script>"; 
	$deactivateresult = "";	
	}
	else if (isset($_POST["deactivateyear"])) {
		
		$subjectcourse = $subjectyear = $deactivateresult = "";
		
		$subjectcourse = $_GET["course"];
		$subjectyear = $_GET["year"];
		
		$sql = "UPDATE curriculum SET subjectactivated = 0 WHERE subjectcourse = '$subjectcourse' AND subjectyear = '$subjectyear'";
		$result = $connection->query($sql);
		
		if ($result === true) {
			$deactivateresult = "Course-Year Deactivate Successful! The Course-Year Will Not Be Available Next Enrollment Period.";
		}
		else {
			$deactivateresult = "Course-Year Deactivate Unsuccessful! Please Try Again.";
		}
		
		echo "<script> 
		var x = messagealert('$deactivateresult'); 
		if (x == true) {
			window.location = 'curriculumeditor.php';
		}
	</script>"; 
	$deactivateresult = "";	
	}
	else if (isset($_POST["deactivatesemester"])) {
		
		$subjectcourse = $subjectyear = $subjectsemester = $deactivateresult = "";
		
		$subjectcourse = $_GET["course"];
		$subjectyear = $_GET["year"];
		$subjectsemester = $_GET["semester"];
		
		$sql = "UPDATE curriculum SET subjectactivated = 0 WHERE subjectcourse = '$subjectcourse' AND subjectyear = '$subjectyear' AND subjectsemester = '$subjectsemester'";
		$result = $connection->query($sql);
		
		if ($result === true) {
			$deactivateresult = "Course-Year-Semester Deactivate Successful! The Course-Year-Semester Will Not Be Available Next Enrollment Period.";
		}
		else {
			$deactivateresult = "Course-Year-Semester Deactivate Unsuccessful! Please Try Again.";
		}
		
		echo "<script> 
		var x = messagealert('$deactivateresult'); 
		if (x == true) {
			window.location = 'curriculumeditor.php';
		}
	</script>"; 
	$deactivateresult = "";	
	}
		else if (isset($_POST["activatesubject"])) {
		
		$subjectid = $activateresult = "";
		
		$subjectid = $_GET["subjectid"];
		
		$sql = "UPDATE curriculum SET subjectactivated = 1 WHERE subjectid = '$subjectid'";
		$result = $connection->query($sql);
		
		if ($result === true) {
			$activateresult = "Subject Activate Successful! The Subject Will Be Available Next Enrollment Period.";
		}
		else {
			$activateresult = "Subject Activate Unsuccessful! Please Try Again.";
		}
		
		echo "<script> 
		var x = messagealert('$activateresult'); 
		if (x == true) {
			window.location = 'curriculumeditor.php';
		}
	</script>"; 
	$activateresult = "";	
	}
	else if (isset($_POST["activatecourse"])) {
		
		$subjectcourse = $activateresult = "";
		
		$subjectcourse = $_GET["course"];
		
		$sql = "UPDATE curriculum SET subjectactivated = 1 WHERE subjectcourse = '$subjectcourse'";
		$result = $connection->query($sql);
		
		if ($result === true) {
			$activateresult = "Course Activate Successful! The Course Will Be Available Next Enrollment Period.";
		}
		else {
			$activateresult = "Course Activate Unsuccessful! Please Try Again.";
		}
		
		echo "<script> 
		var x = messagealert('$activateresult'); 
		if (x == true) {
			window.location = 'curriculumeditor.php';
		}
	</script>"; 
	$activateresult = "";	
	}
	else if (isset($_POST["activateyear"])) {
		
		$subjectcourse = $subjectyear = $activateresult = "";
		
		$subjectcourse = $_GET["course"];
		$subjectyear = $_GET["year"];
		
		$sql = "UPDATE curriculum SET subjectactivated = 1 WHERE subjectcourse = '$subjectcourse' AND subjectyear = '$subjectyear'";
		$result = $connection->query($sql);
		
		if ($result === true) {
			$activateresult = "Course-Year Activate Successful! The Course-Year Will Be Available Next Enrollment Period.";
		}
		else {
			$activateresult = "Course-Year Activate Unsuccessful! Please Try Again.";
		}
		
		echo "<script> 
		var x = messagealert('$activateresult'); 
		if (x == true) {
			window.location = 'curriculumeditor.php';
		}
	</script>"; 
	$activateresult = "";	
	}
	else if (isset($_POST["activatesemester"])) {
		
		$subjectcourse = $subjectyear = $subjectsemester = $activateresult = "";
		
		$subjectcourse = $_GET["course"];
		$subjectyear = $_GET["year"];
		$subjectsemester = $_GET["semester"];
		
		$sql = "UPDATE curriculum SET subjectactivated = 1 WHERE subjectcourse = '$subjectcourse' AND subjectyear = '$subjectyear' AND subjectsemester = '$subjectsemester'";
		$result = $connection->query($sql);
		
		if ($result === true) {
			$activateresult = "Course-Year-Semester Activate Successful! The Course-Year-Semester Will Be Available Next Enrollment Period.";
		}
		else {
			$activateresult = "Course-Year-Semester Activate Unsuccessful! Please Try Again.";
		}
		
		echo "<script> 
		var x = messagealert('$activateresult'); 
		if (x == true) {
			window.location = 'curriculumeditor.php';
		}
	</script>"; 
	$activateresult = "";	
	}
		else if (isset($_POST["addsubjects"])) {
		
			$subjectcourse = $subjectyear = $subjectsemester = $numberofsubjects = $subjectcode = $subjectdescription = $subjectunits = "";
			
			$subjectcourse = $_GET["course"];
			$subjectyear = $_GET["year"];
			$subjectsemester = $_GET["semester"];
			$numberofsubjects = $_POST["numberofsubjects"];
			
			for ($i=0;$i<$numberofsubjects;$i++) {
				$subjectcode[$i] = "";
				$subjectdescription[$i] = "";
				$subjectunits[$i] = "";
			}
			
			if (!empty($_SESSION["subjectcode"])) {
				$subjectcode = $_SESSION["subjectcode"];
			}
			
			if (!empty($_SESSION["subjectdescription"])) {
				$subjectdescription = $_SESSION["subjectdescription"];
			}
			
			if (!empty($_SESSION["subjectunits"])) {
				$subjectunits = $_SESSION["subjectunits"];
			}
			
			
			echo "<br/><h3>Add Subjects</h3><br/>
				<form method='post' action='curriculumeditor.php?numberofsubjects=$numberofsubjects&course=$subjectcourse&year=$subjectyear&semester=$subjectsemester'>
				<table class='tbl'>
				<tr><td class='label'>Subject Course: </td><td class='label1'>$subjectcourse</td></tr>
				<tr><td class='label'>Subject Year: </td><td class='label1'>$subjectyear</td></tr>
				<tr><td class='label'>Subject Semester: </td><td class='label1'>$subjectsemester</td></tr>
				<tr><td colspan='2'><br/></td></tr>
				<tr><td colspan='2'><hr/></td></tr>";
				
				for ($i=0;$i<$numberofsubjects;$i++) {
					
				$number = $i + 1;
				
				echo "<tr><td class='label'>No. $number </td></tr>
				<tr><td class='label'>Subject Code: </td><td><input type='text' name='subjectcode[]' value='$subjectcode[$i]'></td></tr>
				<tr><td class='label'>Subject Description: </td><td><input type='text' name='subjectdescription[]' value='$subjectdescription[$i]'></td></tr>
				<tr><td class='label'>Subject Units: </td><td><input type='number' class='numberofunits' name='subjectunits[]' value='$subjectunits[$i]'></td></tr>
				<tr><td colspan='2'><br/></td></tr>
				<tr><td colspan='2'><hr/></td></tr>";
				}
				echo "</table>
				<br/>
				<input type='submit' name='submitaddsubjects' value='Submit'>
				</form>";
	}
	else if (isset($_POST["submitaddsubjects"])) {
		$numberofsubjects = $subjectcourse = $coursecode = $subjectyear = $subjectsemester = $subjectcode = $subjectdescription = $subjectunits = "";
		$errorcount = 0;
		$subjectcodeerror = $subjectdescriptionerror = $subjectunitserror = "";
		
		$subjectcourse = $_GET["course"];
		$subjectyear = $_GET["year"];
		$subjectsemester = $_GET["semester"];
		$numberofsubjects = $_GET["numberofsubjects"];
		$subjectcode = $_POST["subjectcode"];
		$subjectdescription = $_POST["subjectdescription"];
		$subjectunits = $_POST["subjectunits"];
		
		$_SESSION["subjectcode"] = $subjectcode;
		$_SESSION["subjectdescription"] = $subjectdescription;
		$_SESSION["subjectunits"] = $subjectunits;
		
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$subjectcourse' LIMIT 1";
				$result = $connection->query($sql);
				
				if ($result->num_rows == 1) {
					while ($row = $result->fetch_object()) {
						$coursecode = $row->coursecode;
					}
				}
		
		for ($i=0;$i<$numberofsubjects;$i++) {
		
		$subjectcodeerror[$i] = "";
		$subjectdescriptionerror[$i] = "";
		$subjectunitserror[$i] = "";
		
			$number = $i + 1;
			
			if (empty($subjectcode[$i])) {
				$subjectcodeerror[$i] = "<b>Error at Subject Code No. $number:</b> Subject Code is Required!";
				$errorcount++;
			} 
			else {
			$subjectcode[$i] = validateinput($subjectcode[$i]);
				if (!preg_match("/^[a-zA-Z0-9 ]*$/", $subjectcode[$i])) {
					$subjectcodeerror[$i] = "<b>Error at Subject Code No. $number:</b> Only Letters, Numbers, and White Spaces Allowed!";
					$errorcount++;
				}
			}
			
			if (empty($subjectdescription[$i])) {
				$subjectdescriptionerror[$i] = "<b>Error at Subject Description No. $number:</b> Subject Description is Required!";
				$errorcount++;
			} 
			else {
			$subjectdescription[$i] = validateinput($subjectdescription[$i]);
				if (!preg_match("/^[a-zA-Z0-9 ]*$/", $subjectdescription[$i])) {
					$subjectdescriptionerror[$i] = "<b>Error at Subject Description No. $number:</b> Only Letters, Numbers, and White Spaces Allowed!";
					$errorcount++;
				}
			}
			
			if (empty($subjectunits[$i])) {
				$subjectunitserror[$i] = "<b>Error at Subject Units No. $number:</b> Subject Units is Required!";
				$errorcount++;
			} 
			else {
				$subjectunits[$i] = validateinput($subjectunits[$i]);
				if ($subjectunits[$i] < 1 || $subjectunits[$i] > 10) {
					$subjectunitserror[$i] = "<b>Error at Subject Units No. $number:</b> Subject Units Must Be Between 1 and 10 Only!";
					$errorcount++;
				}
			}
		}
		
		if ($errorcount > 0) {
			
			echo "<form method='post' action='curriculumeditor.php?course=$subjectcourse&year=$subjectyear&semester=$subjectsemester'>
			<input type='hidden' name='numberofsubjects' value='$numberofsubjects'>
			<br/><table class='tbl'>";
			
			for ($i=0;$i<$numberofsubjects;$i++) {
				echo "<tr><td><span class='error'>$subjectcodeerror[$i]</span></td></tr>
					<tr><td><span class='error'>$subjectdescriptionerror[$i]</span></td></tr>
					<tr><td><span class='error'>$subjectunitserror[$i]</span></td></tr>";
			}
			
			echo "</table><br/>
				<input type='submit' name='addsubjects' value='Back'>
				</form>";
			
		}
		else {
			$resultsuccess = 0;
			$addsubjectresult = "";
			
			$_SESSION["subjectcode"] = "";
			$_SESSION["subjectdescription"] = "";
			$_SESSION["subjectunits"] = "";
			
				for ($i=0;$i<$numberofsubjects;$i++) {
					
					$sql = "INSERT INTO curriculum (subjectcourse, coursecode, subjectcode, subjectyear, subjectsemester, subjectdescription, subjectunits, subjectactivated) VALUES ('$subjectcourse', '$coursecode', '$subjectcode[$i]', '$subjectyear', '$subjectsemester', '$subjectdescription[$i]', '$subjectunits[$i]', 1)";
					$result = $connection->query($sql);
					
					if ($result === true) {
						$resultsuccess++;
					}
					
				}
				
				if ($resultsuccess == $numberofsubjects) {
					$addsubjectresult = "Add Subjects Successful! Subjects Will Be Available Next Enrollment Period.";
				}
				else {
					$addsubjectresult = "Add Subjects Unsuccessful! Please Try Again.";
				}
				
					echo "<script> 
						var x = messagealert('$addsubjectresult'); 
						if (x == true) {
							window.location = 'curriculumeditor.php';
						}
					</script>"; 
					$addsubjectresult = "";	
			
		}
	}
	else if (isset($_POST["addcourse"])) {
		
		$coursename = $coursecode = "";
		
		if (!empty($_SESSION["coursename"])) {
			$coursename = $_SESSION["coursename"];
		}
		if (!empty($_SESSION["coursecode"])) {
			$coursecode = $_SESSION["coursecode"];
		}
		
		echo "<br/><h3>Add Course</h3><br/>
		<form method='get' action='curriculumaddcourse.php'>
		<table>
		<tr><td class='label'>Enter Course Name:</td><td><input type='text' name='coursename' value='$coursename'></td></tr>
		<tr><td class='label'>Enter Course Code:</td><td><input type='text' name='coursecode' value='$coursecode'></td></tr>
		</table>
		<br/>
		<input type='submit' name='addcoursesubjects' value='Next'>
		</form>";
		
	}
}
$connection->close();
/*	}
	else {
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Only Available During Vacation Period! <br/> <a href='admin.php'>Back</a>"; // if the user tries to access cashier.php with student userlevel
	}
	*/
}
	else if ($userlevel == "Student"){
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