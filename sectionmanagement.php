<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Section Management</title>
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
				   <li class="active"><a href='sectionmanagement.php'><span>Section Management</span></a></li>
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
							<li><a href="sectionmanagement.php">Section Management</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing:2px;font-weight: 500;l">Section Management</h1>
						</div>
						</div>
					</div>
				
<center>
<?php

$academicyear = $semester = "";

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
	
	echo "<br/><h3> Academic Year $academicyear <br/>
			$semester </h3><br/>";
	
	$sqlcourse = "SELECT DISTINCT studentcourse, coursecode FROM enrolledstudents INNER JOIN curriculum ON curriculum.subjectcourse = enrolledstudents.studentcourse ORDER BY coursecode";
	$sqlyear = "SELECT year FROM sequenceyear";
	$sqlsection = "SELECT DISTINCT studentsection FROM enrolledstudents ORDER BY studentsection";
	
	$resultcourse = $connection->query($sqlcourse);
	$resultyear = $connection->query($sqlyear);
	$resultsection = $connection->query($sqlsection);
	
	$valuecourse = $valuecoursecode = $valueyear = $valuesection = $arraycourse = $arrayyear = $arraysection = "";
	$arraycourse[0] = $arrayyear[0] = $arraysection[0] = " ";
	$course = $year = $section = "";
	$a = $b = $c = 0; // sets the first index of arrays to 0
	
			echo "<form method='post' action='sectionmanagement.php'>
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
						$arraycoursecode[$a] = $rowcourse->coursecode;
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
						$arrayyearcode[$b] = $b + 1;
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
					<input type='submit' name='search' value='Search'>
					
					</form>
					<br/><br/><hr/>"; // make the form containing three combo boxes for subjectcourse, subjectyear, and subjectsemester; and a search button
						
$course = $year = $section = $studentnumber = $firstname = $lastname = $enrollmenttype = $studentstatus = $arraycoursesize = $arrayyearsize =  $arraysectionsize = $checked = $movesectionvalue = $coursecode = $yearcode = $studentstatus = "";
$totalstudents = $studentnumbers = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST["search"])) {
	
	if (empty($_POST["course"])) {
		if (!empty($_SESSION["course"])) {
			$course = $_SESSION["course"];
			$_SESSION["course"] = "";
		}
	}
	else {
		$course = $_POST["course"];
	}
	
	if (empty($_POST["year"])) {
		if (!empty($_SESSION["year"])) {
			$year = $_SESSION["year"];
			$_SESSION["year"] = "";
		}
	}
	else {
		$year = $_POST["year"];
	}
	
	if (empty($_POST["year"])) {
		if (!empty($_SESSION["section"])) {
			$section = $_SESSION["section"];
			$_SESSION["section"] = "";
		}
	}
	else {
		$section = $_POST["section"];
	}
	
	$studentstatus = $_POST["status"];
	
	if ($studentstatus == "All") { $sqlextension = ""; }
	else { $sqlextension = "AND studentstatus = '$studentstatus'"; }
	
	if (!empty($_SESSION["studentnumbers"])) {
		$studentnumbers = $_SESSION["studentnumbers"];
	}
	
	$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$course' LIMIT 1";
	$result = $connection->query($sql);
	
	if ($result->num_rows == 1) {
		while($row = $result->fetch_object()) {
			$coursecode = $row->coursecode;
		}
	}
	
	if ($year == "First Year") { $yearcode = 1; }
	if ($year == "Second Year") { $yearcode = 2; }
	if ($year == "Third Year") { $yearcode = 3; }
	if ($year == "Fourth Year") { $yearcode = 4; }
	
	$_SESSION["studentnumbers"] = "";
	$_SESSION["course"] = $course;
	$_SESSION["year"] = $year;
	$_SESSION["section"] = $section;
	
	$arraycoursesize = sizeof($arraycourse);
	$arrayyearsize = sizeof($arrayyear);
	$arraysectionsize = sizeof($arraysection);

	if ($course == "All" && $year == "All" && $section == "All") { // if the search contains 'all course - all year - all section' combination
		
		echo "<h3> $course Course <br/>
			$year Year <br/>
			$section Section <br/>
			$studentstatus Status</h3>  <hr/>"; // displays the search details -- this is repeated for all the blocks below
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($j=0;$j<$arrayyearsize;$j++) {
		for ($k=0;$k<$arraysectionsize;$k++) { // nested for loops through the needed values of arraycourse[], arrayyear[], and arraysemester[] -- this process is repeated for all the blocks below
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM enrolledstudents INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND studentcourse = '$arraycourse[$i]' AND studentyear = '$arrayyear[$j]' AND studentsection = '$arraysection[$k]' $sqlextension"; // SQL query for 'all course - all year - all section search combination that uses the values of arraycourse[], arrayyear[], and arraysemester[] as parameters as demoed above
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				
				echo "<script>
						function checkall$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k](ischecked) {
							if(ischecked) {
							$('input[name="; ?>"<?php echo "$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k][]"; ?>"<?php echo "]').each(function() { 
								
									this.checked = true; 
								});
							} else {
								$('input[name="; ?>"<?php echo "$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k][]"; ?>"<?php echo "]').each(function() {
									this.checked = false;
								});
							}
						}
					</script>";
				
				echo " <br/> <h4>
					$arraycourse[$i] <br/>
					$arrayyear[$j] <br/>
					Section $arraysection[$k] <br/> </h4>
					<form method='post' action='sectionmanagement.php?course=$arraycourse[$i]&year=$arrayyear[$j]&section=$arraysection[$k]&name=$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k]'>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Check </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus; // update: totalsubjectunits of every output is displayed -- also applied below
						$totalstudents++;
						echo "
						<tr class='curriculum'>"; 
?>
							<td> <input type="checkbox" value="<?php echo $studentnumber; ?>" name="<?php echo "$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k][]"; ?>" <?php if (checkids($studentnumbers, $studentnumber) === true) { echo "checked"; } ?> > Check </input> </td>
<?php
							echo "<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<br/>"; 
					
					if (!empty($_SESSION["checkall"])) {
						if ($_SESSION["checkall"] == "$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k]") {
							$checked = "checked";
							$_SESSION["checkall"] = "";
						}
					}
					else {
						$checked = "";
					}
					
					if (!empty($_SESSION["movesection"])) {
						if ($_SESSION["movesection"] == "$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k]") {
							$movesectionvalue = $_SESSION["movesectionvalue"];
							$_SESSION["movesection"] = "";
							$_SESSION["movesectionvalue"] = "";
						}
					}
					else {
						$movesectionvalue = "";
					}
					
					echo "<input type='checkbox' name='checkall' onclick='checkall$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k](this.checked)' $checked> Check All </input> <br/>
		
					Move to Section (A - Z Only): <input type='text' name='movesection$arraycoursecode[$i]$arrayyearcode[$j]$arraysection[$k]' maxlength='1' size='1' autocomplete='off' value='$movesectionvalue'>
					<input type='submit' name='move' value='Move'>
					</form>
					"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
				}
				else { // if a specific combination do not return a result
				echo " "; // prints whitespace -- this is repeated for all the blocks below
				}				
			}
		}
	}
}

	else if ($course == "All" && $year == "All" && $section != "All") { // if the search contains 'all course - all year - one section' combination
		
		echo "<h3> $course Course <br/>
			$year Year <br/>
			Section $section <br/>
			$studentstatus Status</h3>  <hr/>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($j=0;$j<$arrayyearsize;$j++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM enrolledstudents INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND studentcourse = '$arraycourse[$i]' AND studentyear = '$arrayyear[$j]' AND studentsection = '$section' $sqlextension"; // SQL query for 'all course - all year - one section' search combination that uses the values of arraycourse[], arrayyear[], and $section (contains the value from the section combo box) as parameters -- this process is also applied in the queries below
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
					
				echo "<script>
						function checkall$arraycoursecode[$i]$arrayyearcode[$j]$section(ischecked) {
							if(ischecked) {
							$('input[name="; ?>"<?php echo "$arraycoursecode[$i]$arrayyearcode[$j]$section" . "[]"; ?>"<?php echo "]').each(function() { 
								
									this.checked = true; 
								});
							} else {
								$('input[name="; ?>"<?php echo "$arraycoursecode[$i]$arrayyearcode[$j]$section" . "[]"; ?>"<?php echo "]').each(function() {
									this.checked = false;
								});
							}
						}
					</script>";
					
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$arrayyear[$j] <br/>
					Section $section <br/> </h4>
					<form method='post' action='sectionmanagement.php?course=$arraycourse[$i]&year=$arrayyear[$j]&section=$section&name=$arraycoursecode[$i]$arrayyearcode[$j]$section'>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Check </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus; // update: totalsubjectunits of every output is displayed -- also applied below
						$totalstudents++;
						echo "
						<tr class='curriculum'>"; 
?>
							<td> <input type="checkbox" value="<?php echo $studentnumber; ?>" name="<?php echo "$arraycoursecode[$i]$arrayyearcode[$j]$section" . "[]"; ?>" <?php if (checkids($studentnumbers, $studentnumber) === true) { echo "checked"; } ?> > Check </input> </td>
<?php
							echo "
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<br/>"; 
					
					if (!empty($_SESSION["checkall"])) {
						if ($_SESSION["checkall"] == "$arraycoursecode[$i]$arrayyearcode[$j]$section") {
							$checked = "checked";
							$_SESSION["checkall"] = "";
						}
					}
					else {
						$checked = "";
					}
					
					if (!empty($_SESSION["movesection"])) {
						if ($_SESSION["movesection"] == "$arraycoursecode[$i]$arrayyearcode[$j]$section") {
							$movesectionvalue = $_SESSION["movesectionvalue"];
							$_SESSION["movesection"] = "";
							$_SESSION["movesectionvalue"] = "";
						}
					}
					else {
						$movesectionvalue = "";
					}
					
					echo "<input type='checkbox' name='checkall' onclick='checkall$arraycoursecode[$i]$arrayyearcode[$j]$section(this.checked)' $checked> Check All </input> <br/>
		
					Move to Section (A - Z Only): <input type='text' name='movesection$arraycoursecode[$i]$arrayyearcode[$j]$section' maxlength='1' size='1' autocomplete='off' value='$movesectionvalue'>
					<input type='submit' name='move' value='Move'>
					</form>
					";
				}
				else {
				echo " ";
			}								
		}
	}
}
	
	else if ($course == "All" && $year != "All" && $section == "All") {  // if the search contains 'all course - one year - all section' combination
		
		echo "<h3> $course Course <br/>
			$year <br/>
			$section Section <br/>
			$studentstatus Status</h3>  <hr/>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($k=0;$k<$arraysectionsize;$k++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM enrolledstudents INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND studentcourse = '$arraycourse[$i]' AND studentyear = '$year' AND studentsection = '$arraysection[$k]' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
					
					echo "<script>
						function checkall$arraycoursecode[$i]$yearcode$arraysection[$k](ischecked) {
							if(ischecked) {
							$('input[name="; ?>"<?php echo "$arraycoursecode[$i]$yearcode$arraysection[$k][]"; ?>"<?php echo "]').each(function() { 
								
									this.checked = true; 
								});
							} else {
								$('input[name="; ?>"<?php echo "$arraycoursecode[$i]$yearcode$arraysection[$k][]"; ?>"<?php echo "]').each(function() {
									this.checked = false;
								});
							}
						}
					</script>";
					
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$year <br/>
					Section $arraysection[$k] <br/> </h4>
					<form method='post' action='sectionmanagement.php?course=$arraycourse[$i]&year=$year&section=$arraysection[$k]&name=$arraycoursecode[$i]$yearcode$arraysection[$k]'>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Check </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus; // update: totalsubjectunits of every output is displayed -- also applied below
						$totalstudents++;
						echo "
						<tr class='curriculum'>"; 
?>
							<td> <input type="checkbox" value="<?php echo $studentnumber; ?>" name="<?php echo "$arraycoursecode[$i]$yearcode$arraysection[$k][]"; ?>" <?php if (checkids($studentnumbers, $studentnumber) === true) { echo "checked"; } ?> > Check </input> </td>
<?php
							echo "
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<br/>"; 
					
					if (!empty($_SESSION["checkall"])) {
						if ($_SESSION["checkall"] == "$arraycoursecode[$i]$yearcode$arraysection[$k]") {
							$checked = "checked";
							$_SESSION["checkall"] = "";
						}
					}
					else {
						$checked = "";
					}
					
					if (!empty($_SESSION["movesection"])) {
						if ($_SESSION["movesection"] == "$arraycoursecode[$i]$yearcode$arraysection[$k]") {
							$movesectionvalue = $_SESSION["movesectionvalue"];
							$_SESSION["movesection"] = "";
							$_SESSION["movesectionvalue"] = "";
						}
					}
					else {
						$movesectionvalue = "";
					}
					
					echo "<input type='checkbox' name='checkall' onclick='checkall$arraycoursecode[$i]$yearcode$arraysection[$k](this.checked)' $checked> Check All </input> <br/>
		
					Move to Section (A - Z Only): <input type='text' name='movesection$arraycoursecode[$i]$yearcode$arraysection[$k]' maxlength='1' size='1' autocomplete='off' value='$movesectionvalue'>
					<input type='submit' name='move' value='Move'>
					</form>
					";
				}
				else {
				echo " ";
			}				 
		}
	}
}
	
	else if ($course == "All" && $year != "All" && $section != "All") { // if the search contains 'all course - one year - one section' combination
		
		echo "<h3> $course Course <br/>
			$year <br/>
			Section $section <br/>
			$studentstatus Status</h3>  <hr/>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM enrolledstudents INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND studentcourse = '$arraycourse[$i]' AND studentyear = '$year' AND studentsection = '$section' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
					
					echo "<script>
						function checkall$arraycoursecode[$i]$yearcode$section(ischecked) {
							if(ischecked) {
							$('input[name="; ?>"<?php echo "$arraycoursecode[$i]$yearcode$section" . "[]"; ?>"<?php echo "]').each(function() { 
								
									this.checked = true; 
								});
							} else {
								$('input[name="; ?>"<?php echo "$arraycoursecode[$i]$yearcode$section" . "[]"; ?>"<?php echo "]').each(function() {
									this.checked = false;
								});
							}
						}
					</script>";
					
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$year <br/>
					Section $section <br/> </h4>
					<form method='post' action='sectionmanagement.php?course=$arraycourse[$i]&year=$year&section=$section&name=$arraycoursecode[$i]$yearcode$section'>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Check </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus; // update: totalsubjectunits of every output is displayed -- also applied below
						$totalstudents++;
						echo "
						<tr class='curriculum'>"; 
?>
							<td> <input type="checkbox" value="<?php echo $studentnumber; ?>" name="<?php echo "$arraycoursecode[$i]$yearcode$section" . "[]"; ?>" <?php if (checkids($studentnumbers, $studentnumber) === true) { echo "checked"; } ?> > Check </input> </td>
<?php
							echo "
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<br/>"; 
					
					if (!empty($_SESSION["checkall"])) {
						if ($_SESSION["checkall"] == "$arraycoursecode[$i]$yearcode$section") {
							$checked = "checked";
							$_SESSION["checkall"] = "";
						}
					}
					else {
						$checked = "";
					}
					
					if (!empty($_SESSION["movesection"])) {
						if ($_SESSION["movesection"] == "$arraycoursecode[$i]$yearcode$section") {
							$movesectionvalue = $_SESSION["movesectionvalue"];
							$_SESSION["movesection"] = "";
							$_SESSION["movesectionvalue"] = "";
						}
					}
					else {
						$movesectionvalue = "";
					}
					
					echo "<input type='checkbox' name='checkall' onclick='checkall$arraycoursecode[$i]$yearcode$section(this.checked)' $checked> Check All </input> <br/>
		
					Move to Section (A - Z Only): <input type='text' name='movesection$arraycoursecode[$i]$yearcode$section' maxlength='1' size='1' autocomplete='off' value='$movesectionvalue'>
					<input type='submit' name='move' value='Move'>
					</form>
					";
				}
				else {
				echo " ";
		}
	}
}
	
	else if ($course != "All" && $year == "All" && $section == "All") { // if the search contains 'one course - all year - all section' combination
		
		echo "<h3> $course <br/>
			$year Year <br/>
			$section Section <br/>
			$studentstatus Status</h3>  <hr/>";
		
		for ($j=0;$j<$arrayyearsize;$j++) {
		for ($k=0;$k<$arraysectionsize;$k++) {
	
		$totalstudents = 0;
		
			$sql = "SELECT * FROM enrolledstudents INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND studentcourse = '$course' AND studentyear = '$arrayyear[$j]' AND studentsection = '$arraysection[$k]' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
					
					echo "<script>
						function checkall$coursecode$arrayyearcode[$j]$arraysection[$k](ischecked) {
							if(ischecked) {
							$('input[name="; ?>"<?php echo "$coursecode$arrayyearcode[$j]$arraysection[$k][]"; ?>"<?php echo "]').each(function() { 
								
									this.checked = true; 
								});
							} else {
								$('input[name="; ?>"<?php echo "$coursecode$arrayyearcode[$j]$arraysection[$k][]"; ?>"<?php echo "]').each(function() {
									this.checked = false;
								});
							}
						}
					</script>";
					
				echo "	 <br/> <h4>
					$course <br/>
					$arrayyear[$j] <br/>
					Section $arraysection[$k] <br/> </h4>
					<form method='post' action='sectionmanagement.php?course=$course&year=$arrayyear[$j]&section=$arraysection[$k]&name=$coursecode$arrayyearcode[$j]$arraysection[$k]'>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Check </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus; // update: totalsubjectunits of every output is displayed -- also applied below
						$totalstudents++;
						echo "
						<tr class='curriculum'>"; 
?>
							<td> <input type="checkbox" value="<?php echo $studentnumber; ?>" name="<?php echo "$coursecode$arrayyearcode[$j]$arraysection[$k][]"; ?>" <?php if (checkids($studentnumbers, $studentnumber) === true) { echo "checked"; } ?> > Check </input> </td>
<?php
							echo "
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<br/>"; 
					
					if (!empty($_SESSION["checkall"])) {
						if ($_SESSION["checkall"] == "$coursecode$arrayyearcode[$j]$arraysection[$k]") {
							$checked = "checked";
							$_SESSION["checkall"] = "";
						}
					}
					else {
						$checked = "";
					}
					
					if (!empty($_SESSION["movesection"])) {
						if ($_SESSION["movesection"] == "$coursecode$arrayyearcode[$j]$arraysection[$k]") {
							$movesectionvalue = $_SESSION["movesectionvalue"];
							$_SESSION["movesection"] = "";
							$_SESSION["movesectionvalue"] = "";
						}
					}
					else {
						$movesectionvalue = "";
					}
					
					echo "<input type='checkbox' name='checkall' onclick='checkall$coursecode$arrayyearcode[$j]$arraysection[$k](this.checked)' $checked> Check All </input> <br/>
		
					Move to Section (A - Z Only): <input type='text' name='movesection$coursecode$arrayyearcode[$j]$arraysection[$k]' maxlength='1' size='1' autocomplete='off' value='$movesectionvalue'>
					<input type='submit' name='move' value='Move'>
					</form>
					";
				}
				else {
				echo " ";
			}	
		}
	}	
}
	
	else if ($course != "All" && $year == "All" && $section != "All") { // if the search contains 'one course - all year - one section' combination
		
		echo "<h3> $course<br/>
			$year Year <br/>
			Section $section <br/>
			$studentstatus Status</h3>  <hr/>";
		
		for ($j=0;$j<$arrayyearsize;$j++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM enrolledstudents INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND studentcourse = '$course' AND studentyear = '$arrayyear[$j]' AND studentsection = '$section' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
					
					echo "<script>
						function checkall$coursecode$arrayyearcode[$j]$section(ischecked) {
							if(ischecked) {
							$('input[name="; ?>"<?php echo "$coursecode$arrayyearcode[$j]$section" . "[]"; ?>"<?php echo "]').each(function() { 
								
									this.checked = true; 
								});
							} else {
								$('input[name="; ?>"<?php echo "$coursecode$arrayyearcode[$j]$section" . "[]"; ?>"<?php echo "]').each(function() {
									this.checked = false;
								});
							}
						}
					</script>";
					
				echo "	 <br/> <h4>
					$course <br/>
					$arrayyear[$j] <br/>
					Section $section <br/> </h4>
					<form method='post' action='sectionmanagement.php?course=$course&year=$arrayyear[$j]&section=$section&name=$coursecode$arrayyearcode[$j]$section'>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Check </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus; // update: totalsubjectunits of every output is displayed -- also applied below
						$totalstudents++;
						echo "
						<tr class='curriculum'>"; 
?>
							<td> <input type="checkbox" value="<?php echo $studentnumber; ?>" name="<?php echo "$coursecode$arrayyearcode[$j]$section" . "[]"; ?>" <?php if (checkids($studentnumbers, $studentnumber) === true) { echo "checked"; } ?> > Check </input> </td>
<?php
							echo "
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<br/>"; 
					
					if (!empty($_SESSION["checkall"])) {
						if ($_SESSION["checkall"] == "$coursecode$arrayyearcode[$j]$section") {
							$checked = "checked";
							$_SESSION["checkall"] = "";
						}
					}
					else {
						$checked = "";
					}
					
					if (!empty($_SESSION["movesection"])) {
						if ($_SESSION["movesection"] == "$coursecode$arrayyearcode[$j]$section") {
							$movesectionvalue = $_SESSION["movesectionvalue"];
							$_SESSION["movesection"] = "";
							$_SESSION["movesectionvalue"] = "";
						}
					}
					else {
						$movesectionvalue = "";
					}
					
					echo "<input type='checkbox' name='checkall' onclick='checkall$coursecode$arrayyearcode[$j]$section(this.checked)' $checked> Check All </input> <br/>
		
					Move to Section (A - Z Only): <input type='text' name='movesection$coursecode$arrayyearcode[$j]$section' maxlength='1' size='1' autocomplete='off' value='$movesectionvalue'>
					<input type='submit' name='move' value='Move'>
					</form>
					";
				}
				else {
				echo " ";
			}				
		}
	}
	
	else if ($course != "All" && $year != "All" && $section == "All") { // if the search contains 'one course - one year - all section' combination
		
		echo "<h3> $course <br/>
			$year <br/>
			$section Section <br/>
			$studentstatus Status</h3>  <hr/>";
		
		for ($k=0;$k<$arraysectionsize;$k++) {
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM enrolledstudents INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND studentcourse = '$course' AND studentyear = '$year' AND studentsection = '$arraysection[$k]' $sqlextension";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
					
					echo "<script>
						function checkall$coursecode$yearcode$arraysection[$k](ischecked) {
							if(ischecked) {
							$('input[name="; ?>"<?php echo "$coursecode$yearcode$arraysection[$k][]"; ?>"<?php echo "]').each(function() { 
								
									this.checked = true; 
								});
							} else {
								$('input[name="; ?>"<?php echo "$coursecode$yearcode$arraysection[$k][]"; ?>"<?php echo "]').each(function() {
									this.checked = false;
								});
							}
						}
					</script>";
					
				echo "	 <br/> <h4>
					$course <br/>
					$year <br/>
					Section $arraysection[$k] <br/> </h4>
					<form method='post' action='sectionmanagement.php?course=$course&year=$year&section=$arraysection[$k]&name=$coursecode$yearcode$arraysection[$k]'>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Check </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus; // update: totalsubjectunits of every output is displayed -- also applied below
						$totalstudents++;
						echo "
						<tr class='curriculum'>"; 
?>
							<td> <input type="checkbox" value="<?php echo $studentnumber; ?>" name="<?php echo "$coursecode$yearcode$arraysection[$k][]"; ?>" <?php if (checkids($studentnumbers, $studentnumber) === true) { echo "checked"; } ?> > Check </input> </td>
<?php
							echo "
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<br/>"; 
					
					if (!empty($_SESSION["checkall"])) {
						if ($_SESSION["checkall"] == "$coursecode$yearcode$arraysection[$k]") {
							$checked = "checked";
							$_SESSION["checkall"] = "";
						}
					}
					else {
						$checked = "";
					}
					
					if (!empty($_SESSION["movesection"])) {
						if ($_SESSION["movesection"] == "$coursecode$yearcode$arraysection[$k]") {
							$movesectionvalue = $_SESSION["movesectionvalue"];
							$_SESSION["movesection"] = "";
							$_SESSION["movesectionvalue"] = "";
						}
					}
					else {
						$movesectionvalue = "";
					}
					
					echo "<input type='checkbox' name='checkall' onclick='checkall$coursecode$yearcode$arraysection[$k](this.checked)' $checked> Check All </input> <br/>
		
					Move to Section (A - Z Only): <input type='text' name='movesection$coursecode$yearcode$arraysection[$k]' maxlength='1' size='1' autocomplete='off' value='$movesectionvalue'>
					<input type='submit' name='move' value='Move'>
					</form>
					";
				}
				else {
				echo " ";
			}
		}
	}
	
	else if ($course != "All" && $year != "All" && $section != "All"){ // if the search contains 'one course - one year - one section' combination
		
		echo "<h3> $course <br/>
			$year <br/>
			Section $section <br/>
			$studentstatus Status</h3>  <hr/>";
		
		$totalstudents = 0;
		
			$sql = "SELECT * FROM enrolledstudents INNER JOIN users ON users.userid = enrolledstudents.studentnumber WHERE academicyear = '$academicyear' AND semester = '$semester' AND studentcourse = '$course' AND studentyear = '$year' AND studentsection = '$section' $sqlextension";
			$result = $connection->query($sql);
			
			if ($result->num_rows > 0) {
				
				echo "<script>
						function checkall$coursecode$yearcode$section(ischecked) {
							if(ischecked) {
							$('input[name="; ?>"<?php echo "$coursecode$yearcode$section" . "[]"; ?>"<?php echo "]').each(function() { 
								
									this.checked = true; 
								});
							} else {
								$('input[name="; ?>"<?php echo "$coursecode$yearcode$section" . "[]"; ?>"<?php echo "]').each(function() {
									this.checked = false;
								});
							}
						}
					</script>";
				
			echo "	 <br/> <h4>
					$course <br/>
					$year <br/>
					Section $section <br/> </h4>
					<form method='post' action='sectionmanagement.php?course=$course&year=$year&section=$section&name=$coursecode$yearcode$section'>
					<table border=1 class='prof'>
					<tr class='curriculum-header'>
						<th> Check </th>
						<th> Student Number</th> 
						<th> Full Name </th>
						<th> Enrollment Type </th>
						<th> Student Status </th>
					</tr>";
		
					while($row = $result->fetch_object()) {
						$studentnumber = $row->studentnumber;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$enrollmenttype = $row->enrollmenttype;
						$studentstatus = $row->studentstatus; // update: totalsubjectunits of every output is displayed -- also applied below
						$totalstudents++;
						echo "
						<tr class='curriculum'>"; 
?>
							<td> <input type="checkbox" value="<?php echo $studentnumber; ?>" name="<?php echo "$coursecode$yearcode$section" . "[]"; ?>" <?php if (checkids($studentnumbers, $studentnumber) === true) { echo "checked"; } ?> > Check </input> </td>
<?php
							echo "
							<td> $studentnumber </td>
							<td> $firstname $lastname </td>
							<td> $enrollmenttype </td>
							<td> $studentstatus </td>
						</tr>";
			}
			echo "<tr class='units'>
					<td colspan=5 align='center'> Total Students: $totalstudents </td> 
					</tr> </table> 
					<br/>"; 
					
					if (!empty($_SESSION["checkall"])) {
						if ($_SESSION["checkall"] == "$coursecode$yearcode$section") {
							$checked = "checked";
							$_SESSION["checkall"] = "";
						}
					}
					else {
						$checked = "";
					}
					
					if (!empty($_SESSION["movesection"])) {
						if ($_SESSION["movesection"] == "$coursecode$yearcode$section") {
							$movesectionvalue = $_SESSION["movesectionvalue"];
							$_SESSION["movesection"] = "";
							$_SESSION["movesectionvalue"] = "";
						}
					}
					else {
						$movesectionvalue = "";
					}
					
					echo "<input type='checkbox' name='checkall' onclick='checkall$coursecode$yearcode$section(this.checked)' $checked> Check All </input> <br/>
		
					Move to Section (A - Z Only): <input type='text' name='movesection$coursecode$yearcode$section' maxlength='1' size='1' autocomplete='off' value='$movesectionvalue'>
					<input type='submit' name='move' value='Move'>
					</form>
					";
			} 
			else {
			echo "<br/><br/>  Course / Year / Section Did Not Matched Any Students. Please Try Other Input. Thank You! ";
			}
	}
	else {
			echo " Invalid Input ";
}
}
		else if (isset($_POST["move"])) {
			
			$course = $year = $section = $studentnumbers = $movesection = $studentnumberserror = $movesectionerror = $checkboxname = $movesectionresult = "";
			$errorcount = $i = $results = 0;
			
			$course = $_GET["course"];
			$year = $_GET["year"];
			$section = $_GET["section"];
			$name = $_GET["name"];
			
			if (empty($_POST["$name"])) {
				if (!empty($_SESSION["studentnumbers"])) {
					$studentnumbers = $_SESSION["studentnumbers"];
				}
				else {
				$studentnumberserror = "<b>Error:</b> Please Choose A Student To Move Section!";
				$errorcount++;
				}
			} 
			else {
				$studentnumbers = $_POST["$name"];
			}
			
		if (empty($_POST["movesection$name"])) {
			if (!empty($_SESSION["movesectionvalue"])) {
				$movesection = $_SESSION["movesectionvalue"];
			}
			else {
			$movesectionerror = "<b>Error: </b>Please Enter a Move-To Section!";
			$errorcount++;
			$_SESSION["movesectionvalue"] = "";
			}
		}
		else {
			$movesection = validateinput($_POST["movesection$name"]);
			$movesection = validateinput(strtoupper($movesection));
			$_SESSION["movesectionvalue"] = $movesection;
			$_SESSION["movesection"] = $name;
			if (!preg_match("/^[a-zA-Z ]*$/", $movesection)) {
				$movesectionerror = "<b>Error: </b>Please Enter a Letter From A to Z Only!";
				$errorcount++;
			}
		}
		
		if (!empty($_POST["checkall"])) {
			$_SESSION["checkall"] = $name;
		}
			
			$_SESSION["studentnumbers"] = $studentnumbers;
			
			if ($errorcount > 0) {
			
			echo "<br/>
				<hr/>
				<br/>
				
				<table class='tbl'>
				<tr><td><span class='error'>$studentnumberserror</span></td></tr>
				<tr><td><span class='error'>$movesectionerror</span></td></tr>
				</table>
				<form method='post' action='sectionmanagement.php'>
				<br/>
				<input type='submit' name='submit' value='Back'>
				</form><br/>
				";
		}
		else {
			
			$_SESSION["studentnumbers"] = "";
			$_SESSION["checkall"] = "";
			$_SESSION["movesection"] = "";
			$_SESSION["movesectionvalue"] = "";
			
			for ($i=0;$i<sizeof($studentnumbers);$i++) {
				
				$sql = "UPDATE enrolledstudents SET studentsection = '$movesection' WHERE studentcourse = '$course' AND studentyear = '$year' AND studentsection = '$section' AND academicyear = '$academicyear' AND semester = '$semester' AND studentnumber = '$studentnumbers[$i]'";
				$result = $connection->query($sql);
				
				if ($result === true) {
					
					$results++;
					
					$firstname = $lastname = $emailaddress = "";
					
					$sql = "SELECT * FROM users WHERE userid = '$studentnumbers[$i]' and emailaddresscode = 0";
					$result = $connection->query($sql);
					
					if ($result->num_rows == 1) {
						while ($row = $result->fetch_object()) {
							$firstname = $row->firstname;
							$lastname = $row->lastname;
							$emailaddress = $row->emailaddress;
							$emailaddresscode = $row->emailaddresscode;
								
								$recipient = $emailaddress;
								$name = "$firstname $lastname";
								$subject = "Section Move";
								$body = "Hello $firstname $lastname!\n\nYou had been moved from Section $section to Section $movesection of the $course - $year class.\n\nThank You! Have a good day!";
											
						// sendemail($recipient, $name, $subject, $body);

						}
					}
					
				}
			}
			
				if (sizeof($studentnumbers) == $results) {
					$movesectionresult = "Student(s) Move Section Successful!";
				}
				else {
					$movesectionresult = "Student(s) Move Section Unsuccessful! Please Try Again.";
				}
				
			echo "<script> 
						var x = messagealert('$movesectionresult'); 
						if (x == true) {
							window.location = 'sectionmanagement.php';
						}
					</script>"; 
					$movesectionresult = "";
			
		}
	}
}
$connection->close();
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