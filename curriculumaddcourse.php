<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Add Course</title>
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
				   <li class="active"><a href='curriculumaddcourse.php'><span>Add Course</span></a></li>
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
							<li><a href="curriculumaddcourse.php">Add Course</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Add Course</h1>
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
	
	$coursename = $coursecode = $coursenameerror = $coursecodeerror = "";
	$errorcount = 0;
	
	if (!empty($_GET["coursename"]) && !empty($_GET["coursecode"])) {
		
	$coursename = $_GET["coursename"];
	$coursecode = $_GET["coursecode"];
	
	$_SESSION["coursename"] = $coursename;
	$_SESSION["coursecode"] = $coursecode;
	
    $coursename = validateinput($coursename);
		if (!preg_match("/^[a-zA-Z 0-9]*$/", $coursename)) {
		  $coursenameerror = "<b>Error: </b> Invalid Characters in Course Name!"; 
		  $errorcount++;
		}
	
    $coursecode = strtoupper(validateinput($coursecode));
		if (!preg_match("/^[a-zA-Z]*$/", $coursecode)) {
			$coursecodeerror = "<b>Error: </b> Invalid Characters in Course Code!"; 
			$errorcount++;
		}
	
	if ($errorcount > 0) {
				
				echo "<hr/><br/>
				<form method='post' action='curriculumeditor.php'>
				<table class='tbl'>
				<tr><td><span class='error'>$coursenameerror</span></td></tr>
				<tr><td><span class='error'>$coursecodeerror</span></td></tr>
				</table>
				<br/>
				<input type='submit' name='addcourse' value='Back'>
				</form><br/>";
				
	}
	else {
		$_SESSION["coursename"] = "";
		$_SESSION["coursecode"] = "";
		
	echo "<br/><h3>Course to Add: $coursename</h3><br/>
		<form method='post' action='curriculumaddcourse.php?coursename=$coursename&coursecode=$coursecode'>
		<table class='tbl'>
		<tr><td class='label'>Select Year:</td>
		<td>
		<select name='year'>
		<option disabled selected value=''>Year:</option>
		<option value='First Year'>First Year</option>
		<option value='Second Year'>Second Year</option>
		<option value='Third Year'>Third Year</option>
		<option value='Fourth Year'>Fourth Year</option>
		</td></tr>
		<tr><td class='label'>Select Semester:</td>
		<td>
		<select name='semester'>
		<option disabled selected value=''>Semester:</option>
		<option value='First Semester'>First Semester</option>
		<option value='Second Semester'>Second Semester</option>
		<option value='Summer'>Summer</option>
		</td></tr>
		<tr>
		<td class='label'>Select Number:</td> <td><input type='number' class='addsubjects' name='numberofsubjects' min=1 max=15 value=1></td>
		</tr>
		</table>
		<br/>
		<input type='submit' name='addsubjects' value='Add Subjects'>
		</form>
		<br/><br/><hr/>";
	}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
		if (isset($_POST["addsubjects"])) {
		
			$year = $semester = $numberofsubjects = $subjectcode = $subjectdescription = $subjectunits = $yearerror = $semestererror = "";
			$errorcount = 0;
			$numberofsubjects = $_POST["numberofsubjects"];
			
			if (empty($_POST["year"])) {
				if (!empty($_SESSION["year"])) {
					$year = $_SESSION["year"];
				}
				else {
					$yearerror = "<b>Error:</b> Please Select Year!";
					$errorcount++;
				}
			} 
			else {
				$year = validateinput($_POST["year"]);
			}
			
			if (empty($_POST["semester"])) {
				if (!empty($_SESSION["semester"])) {
					$semester = $_SESSION["semester"];
				}
				else {
					$semestererror = "<b>Error:</b> Please Select Semester!";
					$errorcount++;
				}
			} 
			else {
				$semester = validateinput($_POST["semester"]);
			}
			
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
			
			if ($errorcount > 0) {
				echo "
					<br/>
					<form method='post' action='curriculumaddcourse.php?coursename=$coursename&coursecode=$coursecode'>
					<table class='tbl'>
					<tr><td><span class='error'>$yearerror</span></td></tr>
					<tr><td><span class='error'>$semestererror</span></td></tr>
					</table>
					</form><br/>
					";
			}
			else {
							
			echo "<br/><h3>Add Subjects</h3><br/>
				<form method='post' action='curriculumaddcourse.php?coursename=$coursename&coursecode=$coursecode&numberofsubjects=$numberofsubjects&course=$coursename&year=$year&semester=$semester'>
				<table class='tbl'>
				<tr><td class='label'>Subject Course: </td><td class='label1'>$coursename</td></tr>
				<tr><td class='label'>Subject Year: </td><td class='label1'>$year</td></tr>
				<tr><td class='label'>Subject Semester: </td><td class='label1'>$semester</td></tr>
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
			$_SESSION["year"] = "";
			$_SESSION["semester"] = "";
			$_SESSION["subjectcode"] = "";
			$_SESSION["subjectdescription"] = "";
			$_SESSION["subjectunits"] = "";
		}
	else if (isset($_POST["submitaddsubjects"])) {
		
		$numberofsubjects = $subjectcourse = $subjectyear = $subjectsemester = $subjectcode = $subjectdescription = $subjectunits = "";
		$errorcount = 0;
		$subjectcodeerror = $subjectdescriptionerror = $subjectunitserror = "";
		
		$subjectcourse = $_GET["course"];
		$subjectyear = $_GET["year"];
		$subjectsemester = $_GET["semester"];
		$numberofsubjects = $_GET["numberofsubjects"];
		$subjectcode = $_POST["subjectcode"];
		$subjectdescription = $_POST["subjectdescription"];
		$subjectunits = $_POST["subjectunits"];
		
		$_SESSION["year"] = $subjectyear;
		$_SESSION["semester"] = $subjectsemester;
		$_SESSION["subjectcode"] = $subjectcode;
		$_SESSION["subjectdescription"] = $subjectdescription;
		$_SESSION["subjectunits"] = $subjectunits;
		
		for ($i=0;$i<$numberofsubjects;$i++) {
		
		$subjectcodeerror[$i] = $subjectdescriptionerror[$i] = $subjectunitserror[$i] = "";
		
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
			
			echo "<form method='post' action='curriculumaddcourse.php?coursename=$coursename&coursecode=$coursecode&course=$subjectcourse&year=$subjectyear&semester=$subjectsemester'>
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
			$resultsuccess = $resultsuccess1 = 0;
			$addsubjectresult = "";
			
			$_SESSION["year"] = "";
			$_SESSION["semester"] = "";
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
					
					$sql2 = "SELECT * FROM fees WHERE course = '$subjectcourse' AND year = '$subjectyear' AND semester = '$subjectsemester'";
					$result2 = $connection->query($sql2);
					
					if ($result2->num_rows == 0) {
						$sql1 = "INSERT INTO fees (course, year, semester) VALUES ('$subjectcourse', '$subjectyear', '$subjectsemester')";
						$result1 = $connection->query($sql1);
					}
					else {
						$result1 = true;
					}
					
					if ($result1 === true) {
						$addsubjectresult = "Add Subjects Successful! Subjects Will Be Available Next Enrollment Period.";
					}
					else {
						$addsubjectresult = "Add Subjects Unsuccessful! Please Try Again.";
					}
				}
				else {
					$addsubjectresult = "Add Subjects Unsuccessful! Please Try Again.";
				}
				
					echo "<script> 
						var x = messagealert('$addsubjectresult'); 
						if (x == true) {
							window.location = 'curriculumaddcourse.php?coursename=$coursename&coursecode=$coursecode&course=$subjectcourse&year=$subjectyear&semester=$subjectsemester';
						}
					</script>"; 
					$addsubjectresult = "";	
			
		}
	}
}

$connection->close();
	}
	else {
		echo "<img src='images/lock.png' alt='Lock.png' height='150px' width='150px'><br/><br/>
		No Course To Add! Please Try Again.<br/> <a href='curriculumeditor.php'>Back</a>"; // if the user tries to access cashier.php with student userlevel
	}
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