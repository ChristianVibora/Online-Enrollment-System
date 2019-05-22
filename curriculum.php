<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Curriculum</title>
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
				   <li class="active"><a href='curriculum.php'><span>Curriculum</span></a></li>
				   <li><a href='register.php'><span>Register</span></a></li>
				   <li class='last'><a href='login.php'><span>Log-In</span></a></li>
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
							<li><a href="home.php">Home</a></li>
							<li><a href="curriculum.php">Curriculum</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Curriculum</h1>
							</div>
						</div>
					</div>
<center>
<?php

	$sqlcourse = "SELECT DISTINCT subjectcourse, coursecode FROM curriculum WHERE subjectactivated = 1 ORDER BY coursecode"; // SQL query to retrieve all subjectcourse
	$sqlyear = "SELECT year FROM sequenceyear"; // SQL query to retrieve all subjectyear
	$sqlsemester = "SELECT semester FROM sequencesemester";// SQL query to retrieve all subjectsemester
	
	$resultcourse = $connection->query($sqlcourse);
	$resultyear = $connection->query($sqlyear);
	$resultsemester = $connection->query($sqlsemester);
	
	$valuesubjectcourse = $valuecoursecode = $valueyear = $valuesemester = $arraycourse = $arrayyear = $arraysemester ="";
	$a = $b = $c = 0; // sets the first index of arrays to 0
		echo "<div class='printignore'>
				<br/>
				<form method='post' action='curriculum.php'>
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
					</table>
					<br/>
					<input type='submit' name='submit' value='Search'>
					
					</form>
					<br/><br/><hr/></div>"; // make the form containing three combo boxes for subjectcourse, subjectyear, and subjectsemester; and a search button
					
					echo "<h3 class='pagebreak'> <span class='printonly'> Curriculums </span> </h3>";
						

$course = $year = $semester = $subjectid = $subjectcode = $subjectdescription = $subjectunits = $totalsubjectunits = $arraycoursesize = $arrayyearsize = $arraysemestersize = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
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
			$semester Semester </h3>
			<button onclick='window.print()'>Print</button> <hr/>
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
		
		$totalsubjectunits = 0; // totalsubjectunits is reset every loop -- also applied below
		
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$arraycourse[$i]' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$arraysemester[$k]'"; // SQL query for 'all course - all year - all semester' search combination that uses the values of arraycourse[], arrayyear[], and arraysemester[] as parameters as demoed above
			
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo " <br/> <h4>
					$arraycourse[$i] <br/>
					$arrayyear[$j] <br/>
					$arraysemester[$k] <br/> </h4>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
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
						$totalsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
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
			$semester </h3> 
			<button onclick='window.print()'>Print</button>   <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($j=0;$j<$arrayyearsize;$j++) {
		
		$totalsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$arraycourse[$i]' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$semester'"; // SQL query for 'all course - all year - one semester' search combination that uses the values of arraycourse[], arrayyear[], and $semester (contains the value from the semester combo box) as parameters -- this process is also applied in the queries below
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo " <br/> <h4>
					$arraycourse[$i] <br/>
					$arrayyear[$j] <br/>
					$semester <br/> </h4>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
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
						$totalsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
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
			$semester Semester </h3> 
			<button onclick='window.print()'>Print</button>   <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		for ($k=0;$k<$arraysemestersize;$k++) {
		
		$totalsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$arraycourse[$i]' AND subjectyear = '$year' AND subjectsemester = '$arraysemester[$k]'";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$year <br/>
					$arraysemester[$k] <br/> </h4>
				<table  class='curriculum'>
					<tr class='curriculum-header'>
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
						$totalsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
					</tr>
					</table>";
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
			$semester </h3> 
			<button onclick='window.print()'>Print</button>   <hr/>
			</div>";
		
		for ($i=0;$i<$arraycoursesize;$i++) {
		
		$totalsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$arraycourse[$i]' AND subjectyear = '$year' AND subjectsemester = '$semester'";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$arraycourse[$i] <br/>
					$year <br/>
					$semester <br/> </h4>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
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
						$totalsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
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
			$semester Semester </h3> 
			<button onclick='window.print()'>Print</button>   <hr/>
			</div>";
		
		for ($j=0;$j<$arrayyearsize;$j++) {
		for ($k=0;$k<$arraysemestersize;$k++) {
	
		$totalsubjectunits = 0;
	
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$course' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$arraysemester[$k]'";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$arrayyear[$j] <br/>
					$arraysemester[$k] <br/> </h4>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
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
						$totalsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
					</tr>
					</table>";
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
			$semester </h3> 
			<button onclick='window.print()'>Print</button>   <hr/>
			</div>";
		
		for ($j=0;$j<$arrayyearsize;$j++) {
		
		$totalsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$course' AND subjectyear = '$arrayyear[$j]' AND subjectsemester = '$semester'";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$arrayyear[$j] <br/>
					$semester <br/> </h4>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
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
						$totalsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
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
			$semester Semester </h3> 
			<button onclick='window.print()'>Print</button>   <hr/>
			</div>";
		
		for ($k=0;$k<$arraysemestersize;$k++) {
		
		$totalsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$course' AND subjectyear = '$year' AND subjectsemester = '$arraysemester[$k]'";
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo "	 <br/> <h4>
					$course <br/>
					$year <br/>
					$arraysemester[$k] <br/> </h4>
					<table  class='curriculum'>
					<tr class='curriculum-header'>
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
						$totalsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
						</tr>";
				}
				echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
					</tr>
					</table>";
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
			$semester </h3> 
			<button onclick='window.print()'>Print</button>   <hr/>
			</div>";
		
		$totalsubjectunits = 0;
		
			$sql = "SELECT * FROM curriculum WHERE subjectactivated = 1 AND subjectcourse = '$course' AND subjectyear = '$year' AND subjectsemester = '$semester'";
			$result = $connection->query($sql);
			
			if ($result->num_rows > 0) {
			echo " <br/> <h4>
				$course <br/>
				$year <br>
				$semester <br/> </h4> 
				<table  class='curriculum'>
					<tr class='curriculum-header'>
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
						$totalsubjectunits += $row->subjectunits; // update: totalsubjectunits of every output is displayed -- also applied below
						
						echo "
						<tr class='curriculum-row'>
							<td> $subjectid </td>
							<td> $subjectcode </td>
							<td> $subjectdescription </td>
							<td> $subjectunits </td>
						</tr>";
			}
			echo "<tr class='units'>
					<td colspan=5 align='center'> Total Subject Units: $totalsubjectunits </td> 
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
$connection->close();
?>
</div>
</div>
</section>
<table class='footer printignore'><tr><td align='center'>© 2016-2017 →rEVOLution← Studios</td></tr></table>
</body>
</html>