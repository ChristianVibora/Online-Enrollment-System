<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Enroll</title>
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
				   <li class="active"><a href='enroll.php'><span>Enroll</span></a></li>
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
							<li><a href="enroll.php">Enroll</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Enroll</h1>
						</div>
						</div>
					</div>
					
<center>
<?php

$academicyear = $semester = $period = $userid = "";

if (!empty($_SESSION["userid"]) ) { // checks if the $_SESSION contains userid
	$userid = $_SESSION["userid"]; // retrieves userid on the $_SESSION
	
	if ($userlevel == "Student") { // if the user is student
		$userstatus = $_SESSION["userstatus"];

	$sql = "SELECT * FROM academic";
	$result = $connection->query($sql);

		if ($result->num_rows == 1) {
			while ($row = $result->fetch_object()) {
			$academicyear = $row->academicyear;
			$semester = $row->semester;
			$period = $row->period;
			}
		}

		if ($userstatus == 0) { // if the user is not yet enrolled
			if ($period == "Enrollment") {

	echo "<h3> Enrollment Ongoing For: </h3>
	<form method='post' action='enroll.php?page=1'>
	<table class='tbl'>
	<tr><td class='label'>Academic Year: </td><td class='label1'>$academicyear </td></tr>
	<tr><td class='label'>Semester:</td><td class='label1'>$semester </td></tr>
	</table>
	<br/>
	<input type='submit' name='enroll' value='Enroll'>
	</form>
	<br/>"; // displays the first form of enrollment.php
	
	$arraycourse = $arrayyear = $valuecourse = $valueyear = $course = $year = $enrollmenttype = "";
	$a = $b = $errorcount = 0;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if(isset($_POST["enroll"])) { // display the second form
			
			$errorcount = 0;
			
			if (!empty($_SESSION["course"])) {
				$course = $_SESSION["course"];
			}
			if (!empty($_SESSION["year"])) {
				$year = $_SESSION["year"];
			}
			if (!empty($_SESSION["enrollmenttype"])) {
				$enrollmenttype = $_SESSION["enrollmenttype"];
			}
			
			$_SESSION["course"] = $_SESSION["year"] = $_SESSION["enrollmenttype"] = ""; // $_SESSION values are emptied to remove selected values from before. -- also applied below
			$_SESSION["enrollsubjectids"] = $_SESSION["creditsubjectids"] = ""; 
			
			echo "<hr/>  <h3> Step 1 out of 4 <br/> Choose Your Course, Year, and Enrollment Type </h3> 
			<br/>
			<form method='post' action='enroll.php?page=2'>
			<table>";
			$sqlcourse = "SELECT DISTINCT subjectcourse, coursecode FROM curriculum WHERE subjectactivated = 1 ORDER BY coursecode"; // SQL query to retrieve all subjectcourse
			$sqlyear = "SELECT year FROM sequenceyear"; // SQL query to retrieve all subjectyear
			
			$resultcourse = $connection->query($sqlcourse);
			$resultyear = $connection->query($sqlyear);
			
			echo "<tr><td class='label'>Course:</td> <td class='label1'><select name='course'>";
?>
			<option <?php if ($course == "") { echo "selected"; } ?> disabled select value="">Select Course:</option>";
<?php
		if ($resultcourse->num_rows > 0) {
			while($rowcourse = $resultcourse->fetch_object()) {
								$valuecourse = $rowcourse->subjectcourse;
								$arraycourse[$a] = $valuecourse; // fill the arraycourse[] with data from database
?>
								<option <?php if ($valuecourse == $course) { echo "selected"; } ?> value="<?php echo $valuecourse; ?>"> <?php echo $valuecourse; ?> </option> <!-- fill the course combo box with data from database -->
<?php
								$a++;
							}
		}
			echo "</td></select><tr/> 
			<tr><td class='label'>Year:</td> <td class='label1'><select name='year'>";
?>
			<option <?php if ($year == "") { echo "selected"; } ?> disabled select value="">Select Year:</option>
<?php		
		if ($resultyear->num_rows > 0) {
			while($rowyear = $resultyear->fetch_object()) {
								$valueyear = $rowyear->year;
								$arrayyear[$b] = $valueyear; // fill the arrayyear[] with data from database
?>
								<option <?php if ($valueyear == $year) { echo "selected"; } ?> value="<?php echo $valueyear; ?>"> <?php echo $valueyear; ?> </option> <!-- fill the year combo box with data from database -->
<?php
								$b++;
							}
		}
			echo "</td></select></tr>";
?>
			<tr><td class='label'>Enrollment Type:</td><td class='label1'><input type="radio" name="enrollmenttype" <?php if (isset($enrollmenttype) && $enrollmenttype == "Regular") { echo "checked"; } ?> value="Regular">Regular</input> <input type="radio" name="enrollmenttype" <?php if (isset($enrollmenttype) && $enrollmenttype == "Irregular") { echo "checked"; } ?> value="Irregular">Irregular</input></td></tr>
<?php
			echo "</table>
			<br/>
			<input type='submit' name='next' value='Next'>
			</form> 
			<br/>
			";
			$_SESSION["arraycourse"] = $arraycourse; // assign arraycourse to $_SESSION for further usage
			$_SESSION["arrayyear"] = $arrayyear; // assign arrayyear to $_SESSION for further usage
		}
		else if(isset($_POST["next"])) { // display the third form
		
		$selectedcourse = $selectedyear = $selectedenrollmenttype = $selectedcourseerror = $selectedyearerror = $selectedenrollmenttypeerror = "";
		$errorcount = 0;
		
		if (!empty($_SESSION["course"])) {
			$_POST["course"] = $_SESSION["course"];
		}
		if (!empty($_SESSION["year"])) {
			$_POST["year"] = $_SESSION["year"];
		}
		if (!empty($_SESSION["enrollmenttype"])) {
			$_POST["enrollmenttype"] = $_SESSION["enrollmenttype"];
		}
		
		// validation blocks
		if (empty($_POST["course"])) {
		$selectedcourseerror = "<b>Error:</b> Please Select Your Course!";
		$errorcount++;
		} 
		else {
		$selectedcourse = $_POST["course"];
		$selectedcourse = validateinput($selectedcourse);
		}
		
		if (empty($_POST["year"])) {
		$selectedyearerror = "<b>Error:</b> Please Select Your Year!";
		$errorcount++;
		} 
		else {
		$selectedyear = $_POST["year"];
		$selectedyear = validateinput($selectedyear);
		}
		
		if (empty($_POST["enrollmenttype"])) {
		$selectedenrollmenttypeerror = "<b>Error:</b> Please Select Your Enrollment Type!";
		$errorcount++;
		} 
		else {
		$selectedenrollmenttype = $_POST["enrollmenttype"];
		$selectedenrollmenttype = validateinput($selectedenrollmenttype);
		}
		// validation blocks
		
		$_SESSION["course"] = $selectedcourse;	
		$_SESSION["year"] = $selectedyear;		
		$_SESSION["enrollmenttype"] = $selectedenrollmenttype;
		
		if ($errorcount > 0) {
			
			echo "<hr/> 
				 <h3> Step 1 out of 4 <br/> Choose Your Course, Year, and Enrollment Type </h3>
				<br/>
				<form method='post' action='enroll.php?page=1'>
				<table class='tbl'>
				<tr><td> <span class='error'>$selectedcourseerror</span> </td></tr>
				<tr><td> <span class='error'>$selectedyearerror</span> </td></tr>
				<tr><td> <span class='error'>$selectedenrollmenttypeerror</span> </td></tr>
				</table>
				<br/><br/>
				<input type='submit' name='enroll' value='Back'>
				</form>
				<br/>
				";
		}
		else {
			
		
		$course = $year = $section = $subjectid = $subjectcode = $subjectdescription = $subjectunits = $enrollmenttype = $totalsubjectunits = $arraycourse = $arrayyear = "";
		
			$_SESSION["modeofpayment"] = "";
		
			$course = $selectedcourse;
			$year = $selectedyear; 
			$section = getstudentsection($course, $year);
			$enrollmenttype = $selectedenrollmenttype;
				
			$_SESSION["course"] = $course;	
			$_SESSION["year"] = $year;
			$_SESSION["section"] = $section;	
			$_SESSION["enrollmenttype"] = $enrollmenttype;
			
			$arraycourse = $_SESSION["arraycourse"];
			$arrayyear = $_SESSION["arrayyear"]; // various assigning and retrieving variables to/from $_POST and $_SESSION
			
			if ($enrollmenttype == "Regular") { // if the user opts for regular enrollment
			
			$enrollsubjectids = "";
			$totalsubjectunits = $a = 0;	
				
			echo "<hr/>  <h3> Step 2 out of 4 <br/> Check Your Subjects </h3>  <br/> ";
				
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$course' AND subjectyear = '$year' AND subjectsemester = '$semester'"; // SQL to retrieve the subjects using course and year -- both from user's input; and semester -- from the current semester
			$result = $connection->query($sql);
				
				echo " <h4>
				$course <br/>
				$year <br/>
				$semester <br/><br/>
				Section: $section <br/><br/>
				 </h4>";
			
			if ($result->num_rows > 0) {
				echo "<form method='post' action='enroll.php?page=3'>
				<table border=1 class='prof'>
				<tr class='curriculum-header'>
					<th> Enroll Subject </th>
					<th> Subject ID </th> 
					<th> Subject Code </th>
					<th> Subject Description </th>
					<th> Subject Units </th>
				</tr>";
				
				while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$enrollsubjectids[$a] = $subjectid; // subject ids are stored in an array
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$totalsubjectunits += $row->subjectunits; 
						$_SESSION["defaultunits"] = $totalsubjectunits;// add every subjectunit to the total subject units
						$_SESSION["totalsubjectunits"] = $totalsubjectunits; // assign totalsubjectunits to $_SESSION for later use
						$a++;
					echo "
					<tr class='curriculum'>
						<td> <input type='checkbox' value='$subjectid' name='enrollsubjects' disabled checked > Enroll </input> </td>
						<td> $subjectid </td>
						<td> $subjectcode </td>
						<td> $subjectdescription </td>
						<td> $subjectunits </td>
					</tr>"; // displays the regular subjects of a course-year-semester that are checked by default
			}
			$_SESSION["enrollsubjectids"] = $enrollsubjectids; // subject ids are stored in the $_SESSION
			
			echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
					</tr>
				</table> 
				<br/>
					<input type='submit' name='next1' value='Next'>
				</form> 
				<br/>
				<form method='post' action='enroll.php?page=1'>
				<input type='submit' name='enroll' value='Back'>
				</form><br/>";
			} 
			else {
			echo " Course / Year / Semester Did Not Matched Any Subjects. Please Try Other Input. Thank You! 
				<br/><br/>
				<form method='post' action='enroll.php?page=1'>
				<input type='submit' name='enroll' value='Back'>
				</form><br/>";
			}
					
		}
			else if ($enrollmenttype == "Irregular") { // todo -- update: completed. when the user opts for irregular enrollment

			echo "<hr/>  <h3> Step 2.1 out of 4 <br/> Choose Your Subjects to Enroll and to Credit </h3>  <br/> "; // step 2 is sub-divided in two steps. Step 2.1 will let the user choose subjects from all courses and year levels, but only with the current semester.
			
			$enrollsubjectids = $creditsubjectids = 0;
			
			if (!empty($_SESSION["enrollsubjectids"])) {
				$enrollsubjectids = $_SESSION["enrollsubjectids"];
			}
				
			if (!empty($_SESSION["creditsubjectids"])) {
				$creditsubjectids = $_SESSION["creditsubjectids"];
			}
			
			$_SESSION["enrollsubjectids"] = "";
			$_SESSION["creditsubjectids"] = "";
			
			
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$course' AND subjectyear = '$year' AND subjectsemester = '$semester'"; // SQL query to retrieve the user's course and year input
			$result = $connection->query($sql);
		
			echo " <h4>
				$course <br/>
				$year <br/>
				$semester
				 </h4>";
			
			if ($result->num_rows > 0) {
				echo "<form method='post' action='enroll.php?page=2.1'>
				<table border=1 class='prof'>
				<tr class='curriculum-header'>
					<th> Enroll Subject </th>
					<th> Credit Subject </th>
					<th> Subject ID </th> 
					<th> Subject Code </th>
					<th> Subject Description </th>
					<th> Subject Units </th>
				</tr>";
				
				while($row = $result->fetch_object()) {
						$subjectid = $row->subjectid;
						$subjectcode = $row->subjectcode;
						$subjectdescription = $row->subjectdescription;
						$subjectunits = $row->subjectunits;
						$totalsubjectunits += $row->subjectunits;
						$_SESSION["defaultunits"] = $totalsubjectunits;
					echo "<tr class='curriculum'>";
?>
						<!-- uses function 'checkids()' to check if the subject is already chosen before, if yes, the specific subject checkbox is automatically checked. (also applied below) -->
						<td> <input type="checkbox" value="<?php echo $subjectid; ?>" name="enrollsubjects[]" <?php if (checkids($enrollsubjectids, $subjectid) === true) { echo "checked"; } ?> > Enroll </input> </td>
						<td> <input type="checkbox" value="<?php echo $subjectid; ?>" name="creditsubjects[]" <?php if (checkids($creditsubjectids, $subjectid) === true) { echo "checked"; } ?> > Credit </input> </td>
<?php
						echo "<td> $subjectid </td>
						<td> $subjectcode </td>
						<td> $subjectdescription </td>
						<td> $subjectunits </td>
					</tr>"; // two checkbox is provided: enrollsubjects[] and creditsubjects[] with the same values but will be processed in different variables -- this is also applied below. TODO: validate if the user checks both checkbox for the same subject
			}
			echo "<tr class='units'>
					<td colspan=6 align='center'> Total Subject Units: $totalsubjectunits </td> 
					</tr>
				</table> ";
				
			echo " <br/> <hr/> <h3> Choose Other Subjects You May Enroll in the Same Course <br/> </h3> "; // displays subjects from the same course, same semester, and other year
			
			for ($i=0;$i<sizeof($arrayyear);$i++) { // looping process copied from curriculum.php
			
			$totalsubjectunits = 0;	// totalsubjectunits are reset every loop. copied from curriculum.php 
			
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$course' AND subjectyear = '$arrayyear[$i]' AND subjectyear != '$year' AND subjectsemester = '$semester'";
			$result = $connection->query($sql);
			
			if ($result->num_rows > 0) {
			echo " <h4> <br/>
				$course <br/>
				$arrayyear[$i] <br/>
				$semester <br/>
				 </h4> 
				<table border=1 class='prof'>
				<tr class='curriculum-header'>
					<th> Enroll Subject </th>
					<th> Subject ID </th> 
					<th> Subject Code </th>
					<th> Subject Description </th>
					<th> Subject Units </th>
				</tr>";
				
				while($row = $result->fetch_object()) {
					$subjectid = $row->subjectid;
					$subjectcode = $row->subjectcode;
					$subjectdescription = $row->subjectdescription;
					$subjectunits = $row->subjectunits;
					$totalsubjectunits += $row->subjectunits;
					echo "<tr class='curriculum'>";
?>
						<td> <input type="checkbox" value="<?php echo $subjectid; ?>" name="enrollsubjects[]" <?php if (checkids($enrollsubjectids, $subjectid) === true) { echo "checked"; } ?> > Enroll </input> </td>
<?php
					echo "<td> $subjectid </td>
						<td> $subjectcode </td>
						<td> $subjectdescription </td>
						<td> $subjectunits </td>
					</tr>"; // the user cannot credit subjects from other course and year, displays enrollsubjects[] checkbox only
			}
			echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
					</tr>
				</table> ";
			} 
			else {
			echo " ";
			}
			}
			echo " <br/> <hr/> <h3> Choose Other Subjects You May Enroll in the Other Courses <br/> </h3> ";
			
			for ($i=0;$i<sizeof($arraycourse);$i++) {
			for ($j=0;$j<sizeof($arrayyear);$j++) {
			
			$totalsubjectunits = 0;	
			
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$arraycourse[$i]' AND subjectcourse != '$course' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$semester'";
			$result = $connection->query($sql);
			
			if ($result->num_rows > 0) {
			echo " <h4> <br/>
				$arraycourse[$i] <br/>
				$arrayyear[$j] <br/>
				$semester <br/>
				 </h4> 
				<table border=1 class='prof'>
				<tr class='curriculum-header'>
					<th> Enroll Subject </th>
					<th> Subject ID </th> 
					<th> Subject Code </th>
					<th> Subject Description </th>
					<th> Subject Units </th>
				</tr>";
				
				while($row = $result->fetch_object()) {
					$subjectid = $row->subjectid;
					$subjectcode = $row->subjectcode;
					$subjectdescription = $row->subjectdescription;
					$subjectunits = $row->subjectunits;
					$totalsubjectunits += $row->subjectunits;
					echo "<tr class='curriculum'>";
?>
						<td> <input type="checkbox" value="<?php echo $subjectid; ?>" name="enrollsubjects[]" <?php if (checkids($enrollsubjectids, $subjectid) === true) { echo "checked"; } ?> > Enroll </input> </td>
<?php
					echo "<td> $subjectid </td>
						<td> $subjectcode </td>
						<td> $subjectdescription </td>
						<td> $subjectunits </td>
					</tr>"; // the user cannot credit subjects from other course and year, displays enrollsubjects[] checkbox only
			}
			echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
					</tr>
				</table>";
			}
			else {
			echo " ";
				}
			}
		}
				echo "<br/><br/><input type='submit' name='next0' value='Next'>
				</form>
				<br/>
				<form method='post' action='enroll.php?page=1'>
				<input type='submit' name='enroll' value='Back'>
				</form> <br/>";
	}
			else {
			echo " Course / Year / Semester Did Not Matched Any Subjects. Please Try Other Input. Thank You!
				<br/><br/>
				<form method='post' action='enroll.php?page=1'>
				<input type='submit' name='enroll' value='Back'>
				</form><br/>";
			}
		}
	}
}
		else if (isset($_POST["next0"])) { // displays Step 2.2 form
					
		$enrollsubjectserror = $creditsubjectserror = $duplicatesubjectidserror = $course = $year = "";
		$enrollsubjectids = $creditsubjectids = $errorcount = 0;
		
		$course = $_SESSION["course"];	
		$year = $_SESSION["year"];
		
		$section = getstudentsection($course, $year);
		
			// validation blocks
			if (empty($_POST["enrollsubjects"])) {
				if (!empty($_SESSION["enrollsubjectids"])) {
					$enrollsubjectids = $_SESSION["enrollsubjectids"];
				}
				else {
				$enrollsubjectserror = "<b>Error:</b> Please Choose Subjects to Enroll!";
				$errorcount++;
				}
			} 
			else {
				$enrollsubjectids = $_POST["enrollsubjects"];
			}
			
			if (empty($_POST["creditsubjects"])) {
				if (!empty($_SESSION["creditsubjectids"])) {
					$creditsubjectids = $_SESSION["creditsubjectids"];
				}
			} 
			else {
				$creditsubjectids = $_POST["creditsubjects"];
			}
			
			if ($enrollsubjectids > 0 && $creditsubjectids > 0) {
				if (checkduplicatesubjectids($enrollsubjectids, $creditsubjectids) === true) {
					$duplicatesubjectidserror = "<b>Error:</b> Please Check Only One Check-Box For Each Subject!";
					$errorcount++;
				}
			}
			// validation blocks
			
			$_SESSION["enrollsubjectids"] = $enrollsubjectids; // subject ids to enroll are stored in $_SESSION
			$_SESSION["creditsubjectids"] = $creditsubjectids; // subject ids to credit are stored in $_SESSION
			
		if ($errorcount > 0) {
			echo "<hr/>  <h3> Step 2.1 out of 4 <br/> Choose Your Subjects to Enroll and to Credit </h3>
				<br/>
				<table class='tbl'>
				<tr><td> <span class='error'>$enrollsubjectserror</span> </td></tr>
				<tr><td> <span class='error'>$creditsubjectserror</span> </td></tr>
				<tr><td> <span class='error'>$duplicatesubjectidserror</span> </td></tr>
				</table>
				<br/><br/>
				<form method='post' action='enroll.php?page=2'>
				<input type='submit' name='next' value='Back'>
				</form><br/>
				";
		}
		else {
		
		$course = $year = $totalsubjectunits = "";
		$subjectid = $subjectcode = $coursecode = $subjectyear = $subjectdescription = $subjectunits = $subjectstatus = "";
		
		$course = $_SESSION["course"];
		$year = $_SESSION["year"];
		
		echo "<hr/>  <h3> Step 2.2 out of 4 <br/> Check Your Subjects </h3> <br/>"; // Step 2.2 will display the subjects selected to enroll and credit like the regular enrollment's Step 2
		
		echo " <h4>
				$course <br/>
				$year <br/>
				$semester<br/><br/>
				Section: $section<br/><br/>
				</h4> ";
				 
		$totalsubjectunits = 0;
		
		echo " <h4> Subjects to Enroll </h4> ";
		echo "<form method='post' action='enroll.php?page=3'>
			<table border=1 class='prof'>
			<tr class='curriculum-header'>
				<th> Enroll Subject </th>
				<th> Subject ID </th> 
				<th> Subject Code </th>
				<th> Subject Description </th>
				<th> Subject Course </th>
				<th> Subject Year </th>
				<th> Subject Units </th>
			</tr>";
		
		for ($i=0;$i<sizeof($enrollsubjectids);$i++) { // displays the subjects to enroll
			
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectid = '$enrollsubjectids[$i]'";
			$result = $connection->query($sql);
			
			if ($result->num_rows == 1) {
				while($row = $result->fetch_object()) {
					$subjectid = $row->subjectid;
					$subjectcode = $row->subjectcode;
					$subjectdescription = $row->subjectdescription;
					$subjectunits = $row->subjectunits;
					$coursecode = $row->coursecode;
					$subjectyear = $row->subjectyear;
					$totalsubjectunits += $row->subjectunits;
					$_SESSION["totalsubjectunits"] = $totalsubjectunits;
					echo "
					<tr class='curriculum'>
						<td> <input type='checkbox' value='$subjectid' name='enrollsubjects' disabled checked > Enroll </input> </td>
						<td> $subjectid </td>
						<td> $subjectcode </td>
						<td> $subjectdescription </td>
						<td> $coursecode </td>
						<td> $subjectyear </td>
						<td> $subjectunits </td>
					</tr>";
				}
			}
		}
		echo "<tr class='units'>
				<td colspan=7 align='center'> Total Subject Units: $totalsubjectunits </td> 
			</tr>
			</table> ";
			
		$totalsubjectunits = 0;
		
		if ($creditsubjectids > 0) {
		
		echo " <br/> <h4> Subjects to Credit </h4> ";
		echo "
			<table border=1 class='prof'>
			<tr class='curriculum-header'>
				<th> Credit Subject </th>
				<th> Subject ID </th> 
				<th> Subject Code </th>
				<th> Subject Description </th>
				<th> Subject Course </th>
				<th> Subject Year </th>
				<th> Subject Units </th>
			</tr>";
		
		for ($j=0;$j<sizeof($creditsubjectids);$j++) {  // displays the subjects to credit
		
		$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectid = '$creditsubjectids[$j]'";
		$result = $connection->query($sql);
			
			if ($result->num_rows == 1) {
				while($row = $result->fetch_object()) {
					$subjectid = $row->subjectid;
					$subjectcode = $row->subjectcode;
					$subjectdescription = $row->subjectdescription;
					$subjectunits = $row->subjectunits;
					$coursecode = $row->coursecode;
					$subjectyear = $row->subjectyear;
					$totalsubjectunits += $row->subjectunits;
					echo "
					<tr class='curriculum'>
						<td> <input type='checkbox' value='$subjectid' name='creditsubjects' disabled checked > Credit </input> </td>
						<td> $subjectid </td>
						<td> $subjectcode </td>
						<td> $subjectdescription </td>
						<td> $coursecode </td>
						<td> $subjectyear </td>
						<td> $subjectunits </td>
					</tr>";
				}
			}
		}
			echo "<tr class='units'>
					<td colspan=7 align='center'> Total Subject Units: $totalsubjectunits </td> 
				</tr>
				</table>";
		}
			echo "
				<br/><br/>
				<input type='submit' name='next1' value='Next'>
				</form>
				<br/>
				<form method='post' action='enroll.php?page=2'>
				<input type='submit' name='next' value='Back'>
				</form><br/>
				";
		}
	}
		else if (isset($_POST["next1"])) { // displays the fouth form, where regular and irregular enrollment will both proceed
			
		$modeofpayment = $enrollmenttype = "";
		$costperunit = $tuitionfee = $totaltuitionfee = $miscellaneousfee = $id = $graduationfee = $studentteaching = $firingfee = $scholarshipdiscount = $totalfee = $totalsubjectunits = $overloadunits = $overloadfee = $defaultunits = 0;
		
		$defaultunits = $_SESSION["defaultunits"];
		$totalsubjectunits = $_SESSION["totalsubjectunits"];
		$enrollmenttype = $_SESSION["enrollmenttype"];
		$course = $year = "";
		
		$course = $_SESSION["course"];
		$year = $_SESSION["year"];
		
		if (!empty($_SESSION["modeofpayment"])) {
			$modeofpayment = $_SESSION["modeofpayment"];
		}
		
		echo "<hr/>  <h3> Step 3 out of 4 <br/> Choose Your Mode of Payment </h3>  <br/>"; // Step 3 will ask for the mode of payment
		
		$sql = "SELECT * FROM fees WHERE course = '$course' AND year = '$year' AND semester = '$semester'";
		$result = $connection->query($sql);
		
		if ($result->num_rows == 1) {
			while($row = $result->fetch_object()) {
				$costperunit = $row->costperunit;
				// $tuitionfee = $row->tuitionfee;
				$miscellaneousfee = $row->miscellaneousfee;
				$id = $row->id;
				$graduationfee = $row->graduationfee;
				$studentteaching = $row->studentteaching;
				$firingfee = $row->firingfee;
			}
		}
		
		$tuitionfee = $costperunit * $totalsubjectunits;
		
		if ($totalsubjectunits > $defaultunits) {
			$overloadunits = $totalsubjectunits - $defaultunits;
			$overloadfee = $overloadunits * $costperunit;
		}
		
		$scholarshipdiscount = $tuitionfee;
		$totaltuitionfee = $tuitionfee - $scholarshipdiscount; // tuition fee will be cancelled out by 100% scholarship
		$totalfee = $totaltuitionfee + $miscellaneousfee + $overloadfee + $id + $graduationfee + $studentteaching + $firingfee;
		
		echo "<br/>  <table class='tbl'>
		<tr><td class='label'>Tuition Fee:</td> <td class='label1'> " , formatcurrency($tuitionfee) , " </td></tr> 
		<tr><td class='label'>Scholarship (100%):</td> <td class='label1'> " , formatcurrency($scholarshipdiscount) , " </td></tr>
		<tr><td colspan=2><hr/></td></tr>
		<tr><td class='label'>Total Tuition Fee:</td> <td class='label1'> " , formatcurrency($totaltuitionfee) , " </td></tr> 
		<tr><td class='label'>Miscellaneous Fee:</td> <td class='label1'> " , formatcurrency($miscellaneousfee) , " </td></tr>
		<tr><td class='label'>Overload Fee:</td> <td class='label1'> " , formatcurrency($overloadfee) , " </td></tr>
		<tr><td colspan=2><hr/></td></tr>
		<tr><td class='label'>ID Fee (First Year Only):</td> <td class='label1'> " , formatcurrency($id) , " </td></tr>
		<tr><td class='label'>Graduation Fee (Fourth Year Only):</td> <td class='label1'> " , formatcurrency($graduationfee) , " </td></tr>
		<tr><td class='label'>Student Teaching Fee (BEED/BSED Only):</td> <td class='label1'> " , formatcurrency($studentteaching) , " </td></tr>
		<tr><td class='label'>Firing Fee (BSC Only):</td> <td class='label1'> " , formatcurrency($firingfee) , " </td></tr>
		<tr><td colspan=2><hr/></td></tr>
		<tr><td class='label'>Total Fee:</td> <td class='label1'> " , formatcurrency($totalfee) , " </td></tr>
		</table>";
		
		$_SESSION["tuitionfee"] = $tuitionfee;
		$_SESSION["scholarshipdiscount"] = $scholarshipdiscount;
		$_SESSION["miscellaneousfee"] = $miscellaneousfee;
		$_SESSION["totalfee"] = $totalfee;
		$_SESSION["overloadfee"] = $overloadfee;
		$_SESSION["id"] = $id;
		$_SESSION["graduationfee"] = $graduationfee;
		$_SESSION["studentteaching"] = $studentteaching;
		$_SESSION["firingfee"] = $firingfee;

		echo "<br/><br/><form method='post' action='enroll.php?page=4'> 
		<table class='tbl'>
		<tr><td align='center'><h4>Mode of Payment:</h4></td></tr>";
?>
			<tr><td class='label'><input type="radio" name="modeofpayment" <?php if ($modeofpayment == "Full Payment") { echo "checked"; } ?> value="Full Payment"> Full Payment</td></tr>
			<tr><td class='label'><input type="radio" name="modeofpayment" <?php if ($modeofpayment == "Standard Installment") { echo "checked"; } ?> value="Standard Installment"> Standard Installment </td></tr>
			<tr><td class='label'><input type="radio" name="modeofpayment" <?php if ($modeofpayment == "Customized Installment") { echo "checked"; } ?> value="Customized Installment"> Customized Installment </td></tr>
			<tr><td class='label'><input type="radio" name="modeofpayment" <?php if ($modeofpayment == "Cheque Payment") { echo "checked"; } ?> value="Cheque Payment"> Cheque Payment</td></tr>
			</table>
			<br/>
			<input type="submit" name="next2" value="Next">
			</form>
			<br/>
			<form method="post" action="<?php if ($enrollmenttype == "Regular") { echo "enroll.php?page=2"; } else if ($enrollmenttype == "Irregular") { echo "enroll.php?page=2.1"; } ?>">
			<input type="submit" name="<?php if ($enrollmenttype == "Regular") { echo "next"; } else if ($enrollmenttype == "Irregular") { echo "next0"; } ?>" value="Back">
			</form>
<?php
		echo "<br/>"; // displays radio buttons of payment modes
		}
		
		else if (isset($_POST["next2"])) { // displays the fifth form
		
		$selectedmodeofpayment = $selectedmodeofpaymenterror = "";
		$errorcount = 0;
		
		// validation block
		if (empty($_POST["modeofpayment"])) {
			if (!empty($_SESSION["modeofpayment"])) {
				$selectedmodeofpayment = $_SESSION["modeofpayment"];
				$selectedmodeofpayment = validateinput($selectedmodeofpayment);
			}
			else {
				$selectedmodeofpaymenterror = "<b>Error:</b> Please Select Your Mode of Payment!";
				$errorcount++;
			}
		}
		else {
		$selectedmodeofpayment = $_POST["modeofpayment"];
		$selectedmodeofpayment = validateinput($selectedmodeofpayment);
		}
		// validation block
		
		$_SESSION["modeofpayment"] = $selectedmodeofpayment;
		
		if ($errorcount > 0) {
			
			echo "<hr/> 
				 <h3> Step 3 out of 4 <br/> Choose Your Mode of Payment </h3>
				<br/>
				<form method='post' action='enroll.php?page=3'>
				<span class='error'>$selectedmodeofpaymenterror</span>
				<br/><br/>
				<input type='submit' name='next1' value='Back'>
				</form><br>
				";
		}
		else {
			
		$modeofpayment = $selectedmodeofpayment; // retrieves modeofpayment from Step 3
		$downpaymentfee = $prelimsfee = $midtermsfee = $prefinalsfee = $finalsfee = $totalfee = $overloadfee = 0;
		$totalfee = $_SESSION["totalfee"];
		
		echo "<hr/>  <h3> Step 4 out of 4 <br/> Check Your Payment Plan </h3>  <br/>"; // check the default payment patterns of every mode
		echo " <h4> Mode of Payment: $modeofpayment </h4> ";
		if ($modeofpayment == "Full Payment") { // if the mode is full, the user must pay all the fees on down-payment only
			$downpaymentfee = $totalfee; // assign the totalfee to downpayment
			$prelimsfee = 0;
			$midtermsfee = 0;
			$prefinalsfee = 0;
			$finalsfee = 0;
			
			$_SESSION["downpaymentfee"] = $downpaymentfee;
			$_SESSION["prelimsfee"] = $prelimsfee;
			$_SESSION["midtermsfee"] = $midtermsfee;
			$_SESSION["prefinalsfee"] = $prefinalsfee;
			$_SESSION["finalsfee"] = $finalsfee;
			
			echo " <form method='post' action='enroll.php?page=5'>
			<table class='tbl'>
			<tr><td class='label'>Down-Payment Fee:</td> <td class='label1'> " , formatcurrency($downpaymentfee) , " </td></tr>
			<tr><td class='label'>Prelims Fee:</td> <td class='label1'> " , formatcurrency($prelimsfee) , " </td></tr>
			<tr><td class='label'>Midterms Fee:</td> <td class='label1'> " , formatcurrency($midtermsfee) , " </td></tr>
			<tr><td class='label'>Pre-Finals Fee:</td> <td class='label1'> " , formatcurrency($prefinalsfee) , " </td></tr>
			<tr><td class='label'>Finals Fee:</td> <td class='label1'> " , formatcurrency($finalsfee) , " </td></tr>
			<tr><td colspan=2><hr/></td></tr>
			<tr><td class='label'>Total Fee:</td> <td class='label1'> " , formatcurrency($totalfee) , " </td></tr>
			</table>"; // TODO: remove text inputs -- display text only
		}
		else if ($modeofpayment == "Standard Installment") { // the traditional payment pattern
		
		$course = $year = "";
		
		$course = $_SESSION["course"];
		$year = $_SESSION["year"];
		
		if (!empty($_SESSION["overloadfee"])) {
			$overloadfee = $_SESSION["overloadfee"];
		}
		
		$sql = "SELECT downpaymentfee, prelimsfee, midtermsfee, prefinalsfee, finalsfee FROM fees WHERE course = '$course' AND year = '$year' AND semester = '$semester'"; // the default payment pattern stored in 'fees' database table
		$result = $connection->query($sql);
		
		if ($result->num_rows == 1) {
			while($row = $result->fetch_object()) {
				$downpaymentfee = $row->downpaymentfee;
				$prelimsfee = $row->prelimsfee;
				$midtermsfee = $row->midtermsfee;
				$prefinalsfee = $row->prefinalsfee;
				$finalsfee = $row->finalsfee + $overloadfee; // assign to different variables
			}
				$_SESSION["downpaymentfee"] = $downpaymentfee;
				$_SESSION["prelimsfee"] = $prelimsfee;
				$_SESSION["midtermsfee"] = $midtermsfee;
				$_SESSION["prefinalsfee"] = $prefinalsfee;
				$_SESSION["finalsfee"] = $finalsfee;
		}
			echo " <form method='post' action='enroll.php?page=5'>
			<table class='tbl'>
			<tr><td class='label'>Down-Payment Fee:</td> <td class='label1'> " , formatcurrency($downpaymentfee) , " </td></tr>
			<tr><td class='label'>Prelims Fee:</td> <td class='label1'> " , formatcurrency($prelimsfee) , " </td></tr>
			<tr><td class='label'>Midterms Fee:</td> <td class='label1'> " , formatcurrency($midtermsfee) , " </td></tr>
			<tr><td class='label'>Pre-Finals Fee:</td> <td class='label1'> " , formatcurrency($prefinalsfee) , " </td></tr>
			<tr><td class='label'>Finals Fee:</td> <td class='label1'> " , formatcurrency($finalsfee) , " </td></tr>
			<tr><td colspan=2><hr/></td></tr>
			<tr><td class='label'>Total Fee:</td> <td class='label1'> " , formatcurrency($totalfee) , " </td></tr>
			</table>"; // TODO: remove text inputs -- display text only
		}
		else if ($modeofpayment == "Customized Installment") {
			
			
			if (!empty($_POST["modeofpayment"]) == "Customized Installment") {
				$downpaymentfee = 0;
				$prelimsfee = 0;
				$midtermsfee = 0;
				$prefinalsfee = 0;
				$finalsfee = 0;
			}
			else if (empty($_POST["modeofpayment"])) {
			
				if (!empty($_SESSION["downpaymentfee"])) {
					$downpaymentfee = $_SESSION["downpaymentfee"];
				}
				if (!empty($_SESSION["prelimsfee"])) {
					$prelimsfee = $_SESSION["prelimsfee"];
				}
				if (!empty($_SESSION["midtermsfee"])) {
					$midtermsfee = $_SESSION["midtermsfee"];
				}
				if (!empty($_SESSION["prefinalsfee"])) {
					$prefinalsfee = $_SESSION["prefinalsfee"];
				}
				if (!empty($_SESSION["finalsfee"])) {
					$finalsfee = $_SESSION["finalsfee"];
				}
				
			}
			
			echo " <form method='post' action='enroll.php?page=5'>
			<table class='tbl'>
			<tr><td class='label'>Down-Payment Fee:</td> <td class='label1'><input type='text' name='downpaymentfee' value='$downpaymentfee'></td></tr>
			<tr><td class='label'>Prelims Fee:</td> <td class='label1'><input type='text' name='prelimsfee' value='$prelimsfee'></td></tr>
			<tr><td class='label'>Midterms Fee:</td> <td class='label1'><input type='text' name='midtermsfee' value='$midtermsfee'></td></tr>
			<tr><td class='label'>Pre-Finals Fee:</td> <td class='label1'><input type='text' name='prefinalsfee' value='$prefinalsfee'></td></tr>
			<tr><td class='label'>Finals Fee:</td> <td class='label1'><input type='text' name='finalsfee' value='$finalsfee'></td></tr>
			<tr><td colspan=2><hr/></td></tr>
			<tr><td class='label'>Total Fee:</td> <td class='label1'> " , formatcurrency($totalfee) , " </td></tr>
			</table>"; //todo: verify that the sum of the values will be equal to $totalfee
			
			$_SESSION["downpaymentfee"] = $_SESSION["prelimsfee"] = $_SESSION["midtermsfee"] = $_SESSION["prefinalsfee"] = $_SESSION["finalsfee"] = "";
		}
		else if ($modeofpayment == "Cheque Payment") {
			
			
			if (!empty($_POST["modeofpayment"]) == "Cheque Payment") {
				$downpaymentfee = 0;
				$prelimsfee = 0;
				$midtermsfee = 0;
				$prefinalsfee = 0;
				$finalsfee = 0;
			}
			else if (empty($_POST["modeofpayment"])) {
			
				if (!empty($_SESSION["downpaymentfee"])) {
					$downpaymentfee = $_SESSION["downpaymentfee"];
				}
				if (!empty($_SESSION["prelimsfee"])) {
					$prelimsfee = $_SESSION["prelimsfee"];
				}
				if (!empty($_SESSION["midtermsfee"])) {
					$midtermsfee = $_SESSION["midtermsfee"];
				}
				if (!empty($_SESSION["prefinalsfee"])) {
					$prefinalsfee = $_SESSION["prefinalsfee"];
				}
				if (!empty($_SESSION["finalsfee"])) {
					$finalsfee = $_SESSION["finalsfee"];
				}
				
			}
			
			echo " <form method='post' action='enroll.php?page=5'>
			<table class='tbl'>
			<tr><td class='label'>Down-Payment Fee:</td> <td class='label1'><input type='text' name='downpaymentfee' value='$downpaymentfee'></td></tr>
			<tr><td class='label'>Prelims Fee:</td> <td class='label1'><input type='text' name='prelimsfee' value='$prelimsfee'></td></tr>
			<tr><td class='label'>Midterms Fee:</td> <td class='label1'><input type='text' name='midtermsfee' value='$midtermsfee'></td></tr>
			<tr><td class='label'>Pre-Finals Fee:</td> <td class='label1'><input type='text' name='prefinalsfee' value='$prefinalsfee'></td></tr>
			<tr><td class='label'>Finals Fee:</td> <td class='label1'><input type='text' name='finalsfee' value='$finalsfee'></td></tr>
			<tr><td colspan=2><hr/></td></tr>
			<tr><td class='label'>Total Fee:</td> <td class='label1'> " , formatcurrency($totalfee) , " </td></tr>
			</table> <br/>
			<table class='tbl' width=450>
			<tr><td align='center'><b>Note:</b> Cheque Payment Mode Only Adds The Feature To Pay With Cheque, Put An Exact Amount To The Slot(s) Where You Will Pay With Cheque. Cash Payment Is Still Accepted In This Mode, Put An Amount To The Slot(s) Where You Will Pay With Cash.</td></tr>
			</table>"; //todo: verify that the sum of the values will be equal to $totalfee
			
			$_SESSION["downpaymentfee"] = $_SESSION["prelimsfee"] = $_SESSION["midtermsfee"] = $_SESSION["prefinalsfee"] = $_SESSION["finalsfee"] = "";
		}
		echo "<br/><input type='submit' name='next3' value='Next'>
			</form>
			<br/>
			<form method='post' action='enroll.php?page=3'>
			<input type='submit' name='next1' value='Back'>
			</form> <br/>";
		}
	}
		else if (isset($_POST["next3"])) { // displays the enrollment results (success or fail). No more forms -- just database processes
			
			$studentnumber = $studentcourse = $studentyear = $studentsection = $studentenrollmenttype = $enrollsubjectids = $creditsubjectids = $modeofpayment = $referencenumber = "";
			$tuitionfee = $scholarshipdiscount = $miscellaneousfee = $overloadfee = $id = $graduationfee = $studentteaching = $firingfee = $downpaymentfee = $prelimsfee = $midtermsfee = $prefinalsfee = $finalsfee = $totalfee = $a = $b = $errorcount = 0;
			$result = $result1 = $result2 = $result3 = $result4 = $enrollmentresult = "";
			
			$downpaymentfeeerror = $prelimsfeeerror = $midtermsfeeerror = $prefinalsfeeerror = $finalsfeeerror = $totalfeeerror = "";
			$feesum = $errorcount = 0;
			$referencenumber = date("mdYHis");
			$studentnumber = $userid;
			$studentcourse = $_SESSION["course"];
			$studentyear = $_SESSION["year"];
			$studentsection = $_SESSION["section"];
			$studentenrollmenttype = $_SESSION["enrollmenttype"];
			$enrollsubjectids = $_SESSION["enrollsubjectids"];
			
			$tuitionfee = $_SESSION["tuitionfee"];
			$scholarshipdiscount = $_SESSION["scholarshipdiscount"];
			$miscellaneousfee = $_SESSION["miscellaneousfee"];
			$overloadfee = $_SESSION["overloadfee"];
			$id = $_SESSION["id"];
			$graduationfee = $_SESSION["graduationfee"];
			$studentteaching = $_SESSION["studentteaching"];
			$firingfee = $_SESSION["firingfee"];
			
			if (!empty($_SESSION["creditsubjectids"])) {
				$creditsubjectids = $_SESSION["creditsubjectids"];
			}
			
			$modeofpayment = $_SESSION["modeofpayment"];
			
			if ($modeofpayment == "Customized Installment") {
			
			$downpaymentfee = $_POST["downpaymentfee"];
			$prelimsfee = $_POST["prelimsfee"];
			$midtermsfee = $_POST["midtermsfee"];
			$prefinalsfee = $_POST["prefinalsfee"];
			$finalsfee = $_POST["finalsfee"];
			$totalfee = $_SESSION["totalfee"]; // prepares all the variables from $_POST
			
			// validation blocks
			$downpaymentfee = validateinput($downpaymentfee);
			$feesum += $downpaymentfee;
			if (!preg_match("/^[0-9.]*$/", $downpaymentfee)) {
				$downpaymentfeeerror = "<b>Error: </b>Please Enter A Valid Down-Payment Fee Amount!";
				$errorcount++;
			} 
			else {
				if ($downpaymentfee < 1000) { 
					$downpaymentfeeerror = "<b>Error: </b>Please Enter Down-Payment Fee Amount More Than ₱1,000.00!";
					$errorcount++;
				}
			}
			
			$prelimsfee = validateinput($prelimsfee);
			$feesum += $prelimsfee;
			if (!preg_match("/^[0-9.]*$/", $prelimsfee)) {
				$prelimsfeeerror = "<b>Error: </b>Please Enter A Valid Prelims Fee Amount!";
				$errorcount++;
			} 
			else {
				if ($prelimsfee < 1000) { 
					$prelimsfeeerror = "<b>Error: </b>Please Enter Prelims Fee Amount More Than ₱1,000.00!";
					$errorcount++;
				}
			}
			
			$midtermsfee = validateinput($midtermsfee);
			$feesum += $midtermsfee;
			if (!preg_match("/^[0-9.]*$/", $midtermsfee)) {
				$midtermsfeeerror = "<b>Error: </b>Please Enter A Valid Midterms Fee Amount!";
				$errorcount++;
			} 
			else {
				if ($midtermsfee < 1000) { 
					$midtermsfeeerror = "<b>Error: </b>Please Enter Midterms Fee Amount More Than ₱1,000.00!";
					$errorcount++;
				}
			}
			
			$prefinalsfee = validateinput($prefinalsfee);
			$feesum += $prefinalsfee;
			if (!preg_match("/^[0-9.]*$/", $prefinalsfee)) {
				$prefinalsfeeerror = "<b>Error: </b>Please Enter A Valid Pre-Finals Fee Amount!";
				$errorcount++;
			}
			
			$finalsfee = validateinput($finalsfee);
			$feesum += $finalsfee;
			if (!preg_match("/^[0-9.]*$/", $finalsfee)) {
				$finalsfeeerror = "<b>Error: </b>Please Enter A Valid Finals Fee Amount!";
				$errorcount++;
			}
			
			if ($totalfee == $feesum) {
				$totalfee = validateinput($totalfee);
			}
			else {
				$totalfeeerror = "<b>Error: </b>Please Enter Enough Amount Equal To Your Total Fee!";
				$errorcount++;
			}
			// validation blocks
			
			$_SESSION["downpaymentfee"] = $downpaymentfee;
			$_SESSION["prelimsfee"] = $prelimsfee;
			$_SESSION["midtermsfee"] = $midtermsfee;
			$_SESSION["prefinalsfee"] = $prefinalsfee;
			$_SESSION["finalsfee"] = $finalsfee;
			
			}
			else if ($modeofpayment == "Cheque Payment") {
			
			$downpaymentfee = $_POST["downpaymentfee"];
			$prelimsfee = $_POST["prelimsfee"];
			$midtermsfee = $_POST["midtermsfee"];
			$prefinalsfee = $_POST["prefinalsfee"];
			$finalsfee = $_POST["finalsfee"];
			$totalfee = $_SESSION["totalfee"]; // prepares all the variables from $_POST
			
			// validation blocks
			$downpaymentfee = validateinput($downpaymentfee);
			$feesum += $downpaymentfee;
			if (!preg_match("/^[0-9.]*$/", $downpaymentfee)) {
				$downpaymentfeeerror = "<b>Error: </b>Please Enter A Valid Down-Payment Fee Amount!";
				$errorcount++;
			} 
			else {
				if ($downpaymentfee < 3000) { 
					$downpaymentfeeerror = "<b>Error: </b>Please Enter Down-Payment Fee Amount More Than  ₱3,000.00!";
					$errorcount++;
				}
			}
			
			$prelimsfee = validateinput($prelimsfee);
			$feesum += $prelimsfee;
			if (!preg_match("/^[0-9.]*$/", $prelimsfee)) {
				$prelimsfeeerror = "<b>Error: </b>Please Enter A Valid Prelims Fee Amount!";
				$errorcount++;
			}
			
			$midtermsfee = validateinput($midtermsfee);
			$feesum += $midtermsfee;
			if (!preg_match("/^[0-9.]*$/", $midtermsfee)) {
				$midtermsfeeerror = "<b>Error: </b>Please Enter A Valid Midterms Fee Amount!";
				$errorcount++;
			}
			
			$prefinalsfee = validateinput($prefinalsfee);
			$feesum += $prefinalsfee;
			if (!preg_match("/^[0-9.]*$/", $prefinalsfee)) {
				$prefinalsfeeerror = "<b>Error: </b>Please Enter A Valid Pre-Finals Fee Amount!";
				$errorcount++;
			}
			
			$finalsfee = validateinput($finalsfee);
			$feesum += $finalsfee;
			if (!preg_match("/^[0-9.]*$/", $finalsfee)) {
				$finalsfeeerror = "<b>Error: </b>Please Enter A Valid Finals Fee Amount!";
				$errorcount++;
			}
			
			if ($totalfee == $feesum) {
				$totalfee = validateinput($totalfee);
			}
			else {
				$totalfeeerror = "<b>Error: </b>Please Enter Enough Amount Equal To Your Total Fee!";
				$errorcount++;
			}
			// validation blocks
			
			$_SESSION["downpaymentfee"] = $downpaymentfee;
			$_SESSION["prelimsfee"] = $prelimsfee;
			$_SESSION["midtermsfee"] = $midtermsfee;
			$_SESSION["prefinalsfee"] = $prefinalsfee;
			$_SESSION["finalsfee"] = $finalsfee;
			}
			else {
			$downpaymentfee = $_SESSION["downpaymentfee"];
			$prelimsfee = $_SESSION["prelimsfee"];
			$midtermsfee = $_SESSION["midtermsfee"];
			$prefinalsfee = $_SESSION["prefinalsfee"];
			$finalsfee = $_SESSION["finalsfee"];
			$totalfee = $_SESSION["totalfee"]; // prepares all the variables from $_SESSION
			
			$_SESSION["downpaymentfee"] = $_SESSION["prelimsfee"] = $_SESSION["midtermsfee"] = $_SESSION["prefinalsfee"] = $_SESSION["finalsfee"] = "";
			
			$feesum = $downpaymentfee + $prelimsfee + $midtermsfee + $prefinalsfee + $finalsfee; 
			
			if ($totalfee == $feesum) {
				$totalfee = validateinput($totalfee);
			}
			else {
				$totalfeeerror = "<b>Error: </b>Please Enter Enough Amount Equal To Your Total Fee!";
				$errorcount++;
			}
				
			}
			
			if ($errorcount > 0) {
				
				echo "<hr/> 
				 <h3> Step 4 out of 4 <br/> Check Your Payment Plan </h3>
				<form method='post' action='enroll.php?page=4'>
				<br/>
				<table class='tbl'>
				<tr><td> <span class='error'>$downpaymentfeeerror</span> </td></tr>
				<tr><td> <span class='error'>$prelimsfeeerror</span> </td></tr>
				<tr><td> <span class='error'>$midtermsfeeerror</span> </td></tr>
				<tr><td> <span class='error'>$prefinalsfeeerror</span> </td></tr>
				<tr><td> <span class='error'>$finalsfeeerror</span> </td></tr>
				<tr><td> <span class='error'>$totalfeeerror</span> </td></tr>
				</table>
				<br/> <br/>
				<b>Total Amount Entered:</b>  " , formatcurrency($feesum) , "
				<br/> <br/>
				<input type='submit' name='next2' value='Back'>
				</form><br/>
				";
				
			}
			else {
				
			$sql = "UPDATE users SET userstatus = 1 WHERE userstatus = 0 AND userid = $userid"; // set user status to 1 to prevent double enrollment
			$result = $connection->query($sql);

			$sqlenrolledstudents = "INSERT INTO enrolledstudents (referencenumber, studentnumber, academicyear, semester, studentcourse, studentyear, studentsection, enrollmenttype, studentstatus) VALUES ('$referencenumber', '$studentnumber', '$academicyear', '$semester', '$studentcourse', '$studentyear', '$studentsection', '$studentenrollmenttype', 'Pending')"; // SQL query to insert user information to 'enrolledstudents' table
			$result1 = $connection->query($sqlenrolledstudents);

			for ($i=0;$i<sizeof($enrollsubjectids);$i++) {
				$sqlenrolledsubjects = "INSERT INTO enrolledsubjects (referencenumber, studentnumber, subjectid, subjectstatus) VALUES ('$referencenumber', '$studentnumber', '$enrollsubjectids[$i]', 'Pending')"; // SQL query to insert subject id's stored in array from earlier form to 'enrolledsubjects' table
				$result2 = $connection->query($sqlenrolledsubjects);
				if ($result2 === true) {
					$a++; // to check if every record is successfully inserted
				}
			}
			
			if ($creditsubjectids > 0) {
				for ($j=0;$j<sizeof($creditsubjectids);$j++) {
				$sqlcreditedsubjects = "INSERT INTO creditedsubjects (referencenumber, studentnumber, subjectid, subjectstatus) VALUES ('$referencenumber', '$studentnumber', '$creditsubjectids[$j]', 'Pending')"; // SQL query to insert subject id's stored in array from earlier form to 'creditedsubjects' table
				$result3 = $connection->query($sqlcreditedsubjects);
				if ($result3 === true) {
					$b++; // to check if every record is successfully inserted
				}
			}
		}

			$sqlenrolledfees = "INSERT INTO enrolledfees (referencenumber, studentnumber, modeofpayment, tuitionfee, scholarshipdiscount, miscellaneousfee, overloadfee, id, graduationfee, studentteaching, firingfee, totalfee, pendingpayment, downpaymentfee, prelimsfee, midtermsfee, prefinalsfee, finalsfee, remainingbalance, balancestatus) VALUES ('$referencenumber', '$studentnumber', '$modeofpayment', '$tuitionfee', '$scholarshipdiscount', '$miscellaneousfee', '$overloadfee', '$id', '$graduationfee', '$studentteaching', '$firingfee', '$totalfee', 'downpaymentfee', '$downpaymentfee', '$prelimsfee', '$midtermsfee', '$prefinalsfee', '$finalsfee', '$totalfee', 'Pending')"; // SQL query to insert the payment patterns of every user
			$result4 = $connection->query($sqlenrolledfees);
			
			if ($result1 === true && $a == sizeof($enrollsubjectids) && $result4 === true) { // if every insertion is successful
				if ($creditsubjectids > 0) { // executes only if the enrollment is irregular
					if ($b == sizeof($creditsubjectids)) {
						$enrollmentresult = "Enrollment Successful! Settle Your Down-Payment Fee Before Payment Period Ends.";
						
						$sqlemail = "SELECT * FROM users WHERE userid = '$studentnumber' AND emailaddresscode = 0";
						$resultemail = $connection->query($sqlemail);
							
							if ($resultemail->num_rows == 1) {
								while ($row = $resultemail->fetch_object()) {
									
									$emailaddress = $row->emailaddress;
									$firstname = $row->firstname;
									$lastname = $row->lastname;
									
									$recipient = $emailaddress;
									$name = "$firstname $lastname";
									$subject = "Enrollment";
									$body = "Hello $firstname $lastname!\n\nYou have been successfully enrolled to $studentcourse: $studentyear Section $studentsection for Academic Year $academicyear: $semester\n\nThank You! Have a good day!";
									
									// sendemail($recipient, $name, $subject, $body);
								}
							}
					}
					else {
						$enrollmentresult = "Enrollment Unsuccessful! Please Try Again. Thank You!";
					}
				}
				else {
					$enrollmentresult = "Enrollment Successful! Settle Your Down-Payment Fee Before Payment Period Ends.";
				}
			}
			else {
				$enrollmentresult = "Enrollment Unsuccessful! Please Try Again. Thank You!";
			}
			
			echo "<script> 
				var x = messagealert('$enrollmentresult'); 
				if (x == true) {
					window.location = 'profile.php';
				}
			</script>"; 
			$enrollmentresult = "";
			
		}
	}
}
	$connection->close();
}
else { // if the user tries to enroll without ongoing enrollment
	echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
	There is No Ongoing Enrollment Right Now. <br/>
	Come Back Later For Upcoming Enrollments! <br/>
	<a href='profile.php'>Back</a>.";
}
		}
		else {
			echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
			Your Account is Already Enrolled! <br/> <a href='profile.php'>Back</a>."; // if the user tries to access enroll.php but is already enrolled
		}
}
	else if ($userlevel == "Admin"){
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		This Page is Restricted to Admins! <br/> <a href='admin.php'>Back</a>."; // if the admin tries to access enroll.php 
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
	else { // if the user tries to access a profile.php without logging-in
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