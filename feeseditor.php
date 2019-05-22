<?php
include 'connection.php';
include 'settings.php';
include 'functions.php';
include 'sessiontimer.php';
?>
<html>

<!-- © 2016-2017 →rEVOLution← Studios -->

<head>
<title>Fees Editor</title>
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
				   <li class="active"><a href='feeseditor.php'><span>Fees Editor</span></a></li>
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
							<li><a href="feeseditor.php">Fees Editor</a></li>
						</ul>
					</div>
					</div>
					<div class="zerogrid">
						<div class="wrap-content">
						<div class='printignore'>
							<h1 class="t-center" style="margin: 15px 0;color: #212121;letter-spacing: 2px;font-weight: 500;">Fees Editor</h1>
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
	
	$sqlcourse = "SELECT DISTINCT subjectcourse, coursecode FROM fees INNER JOIN curriculum ON curriculum.subjectcourse = fees.course WHERE subjectactivated = 1 ORDER BY coursecode"; // SQL query to retrieve all subjectcourse
	$resultcourse = $connection->query($sqlcourse);
	
	$valuesubjectcourse = $valuecoursecode = "";
	
		echo "<div class='printignore'>
				<form method='post' action='feeseditor.php'>
				<table class='tbl'>
				<tr>
					<td class='label'>Course:</td>
					<td><select name='course'>
					<option value='' disabled selected> Select: </option>";
					
				if ($resultcourse->num_rows > 0) {	
					while($rowcourse = $resultcourse->fetch_object()) {
						$valuesubjectcourse = $rowcourse->subjectcourse;
						$valuecoursecode = $rowcourse->coursecode;
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

	$course = $year = $semester = $costperunit = $miscellaneousfee = $id = $graduationfee = $studentteaching = $firingfee = $downpaymentfee = $prelimsfee = $midtermsfee = $prefinalsfee = $finalsfee = $courseerror = $arrayyear = $arraysemester = "";
	$errorcount = $a = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (isset($_POST["search"])) {
	
	$_SESSION["subjectcode"] = "";
	$_SESSION["subjectdescription"] = "";
	$_SESSION["subjectunits"] = "";
	
	if (empty($_POST["course"])) {
		if (!empty($_SESSION["course"])) {
			$course = $_SESSION["course"];
		}
		else {
		$courseerror = "<b>Error: </b> Please Select Course!";
		$errorcount++;
		}
	}
	else {
		$course = validateinput($_POST["course"]);
	}
	if (!empty($_SESSION["arrayyear"])) {
		$year = $_SESSION["arrayyear"];
		
		for ($i=0;$i<sizeof($year);$i++) {
				$miscellaneousfee[$i] = "";
				$id[$i] = "";
				$graduationfee[$i] = "";
				$studentteaching[$i] = "";
				$firingfee[$i] = "";
				$downpaymentfee[$i] = "";
				$prelimsfee[$i] = "";
				$midtermsfee[$i] = "";
				$prefinalsfee[$i] = "";
				$finalsfee[$i] = "";
			}
			
			if (!empty($_SESSION["miscellaneousfee"])) {
				$miscellaneousfee = $_SESSION["miscellaneousfee"];
			}
			
			if (!empty($_SESSION["id"])) {
				$id = $_SESSION["id"];
			}
			
			if (!empty($_SESSION["graduationfee"])) {
				$graduationfee = $_SESSION["graduationfee"];
			}
			
			if (!empty($_SESSION["studentteaching"])) {
				$studentteaching = $_SESSION["studentteaching"];
			}
			
			if (!empty($_SESSION["firingfee"])) {
				$firingfee = $_SESSION["firingfee"];
			}
			
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
			
			if (!empty($_SESSION["costperunit"])) {
				$costperunit = $_SESSION["costperunit"];
			}
		}
	if ($errorcount > 0) {
		
		echo "<br/>
			<form method='post' action='feeseditor.php>
			<table class='tbl'>
			<tr><td><span class='error'>$courseerror</span></td></tr>
			</table>
			</form><br/>";
	}
	else {
		
		echo "<div class='printignore'>
			<h3> $course </h3> <hr/>
			</div>";
		
			$sql = "SELECT * FROM fees WHERE course = '$course'"; // SQL query for 'all course - all year - all semester' search combination that uses the values of arraycourse[], arrayyear[], and arraysemester[] as parameters as demoed above
			
			$result = $connection->query($sql);
	
				if ($result->num_rows > 0) {
				echo " <br/> <h4>
					Edit Fees <br/> </h4>
					<form method='post' action='feeseditor.php?course=$course'>
					<table  class='paymentlog'>
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
						$arrayyear[$a] = $year;
						$semester = $row->semester;
						$arraysemester[$a] = $semester;
						
						if (empty($miscellaneousfee[$a])) {
							$miscellaneousfee[$a] = $row->miscellaneousfee;
						} 
						if (empty($id[$a])) {
							$id[$a] = $row->id;
						} 
						if (empty($graduationfee[$a])) {
							$graduationfee[$a] = $row->graduationfee;
						} 
						if (empty($studentteaching[$a])) {
							$studentteaching[$a] = $row->studentteaching;
						} 
						if (empty($firingfee[$a])) {
							$firingfee[$a] = $row->firingfee;
						} 
						if (empty($downpaymentfee[$a])) {
							$downpaymentfee[$a] = $row->downpaymentfee;
						} 
						if (empty($prelimsfee[$a])) {
							$prelimsfee[$a] = $row->prelimsfee;
						} 
						if (empty($midtermsfee[$a])) {
							$midtermsfee[$a] = $row->midtermsfee;
						} 
						if (empty($prefinalsfee[$a])) {
							$prefinalsfee[$a] = $row->prefinalsfee;
						} 
						if (empty($finalsfee[$a])) {
							$finalsfee[$a] = $row->finalsfee;
						}
						if (empty($costperunit)) {
							$costperunit = $row->costperunit;
						} 
						
					echo "<tr class='curriculum-row'>
							<td> $year </td>
							<td> $semester</td>
							<td> <input type='text' name='miscellaneousfee[]' size='3' value='$miscellaneousfee[$a]'> </td>
							<td> <input type='text' name='id[]' size='3' value='$id[$a]'> </td>
							<td> <input type='text' name='graduationfee[]' size='3' value='$graduationfee[$a]'> </td>";
							
							if ($course == "Bachelor of Elementary Education Major in Early Childhood/Pre-School Education" || $course == "Bachelor of Secondary Education Major in English" || $course == "Bachelor of Secondary Education Major in Mathematics") {
								echo "<td> <input type='text' name='studentteaching[]' size=3 value='$studentteaching[$a]' </td>";
							}
							else if ($course == "Bachelor of Science in Criminology") {
								echo "<td> <input type='text' name='firingfee[]' size=3 value='$firingfee[$a]' </td>";
							}
							
							echo "<td> <input type='text' name='downpaymentfee[]' size='3' value='$downpaymentfee[$a]'> </td>
							<td> <input type='text' name='prelimsfee[]' size='3' value='$prelimsfee[$a]'> </td>
							<td> <input type='text' name='midtermsfee[]' size='3' value='$midtermsfee[$a]'> </td>
							<td> <input type='text' name='prefinalsfee[]' size='3' value='$prefinalsfee[$a]'> </td>
							<td> <input type='text' name='finalsfee[]' size='3' value='$finalsfee[$a]'> </td>
						</tr>";
						
						$a++;
				}
				echo "<tr class='units'>
					<td colspan=14 align='center'> Cost Per Unit: <input type='text' name='costperunit' size='3' value='$costperunit'> </td> 
					</tr>
					</table> <br/>
					<input type='submit' name='updatefees' value='Submit'>
					</form>"; // displays the data from the result of loops over and over until the last loop -- this process is repeated for all the blocks below
					
					$_SESSION["arrayyear"] = $arrayyear;
					$_SESSION["arraysemester"] = $arraysemester;
					
				}

			$_SESSION["course"] = "";
			$_SESSION["miscellaneousfee"] = "";
			$_SESSION["id"] = "";
			$_SESSION["graduationfee"] = "";
			$_SESSION["downpaymentfee"] = "";
			$_SESSION["prelimsfee"] = "";
			$_SESSION["midtermsfee"] = "";
			$_SESSION["prefinalsfee"] = "";
			$_SESSION["finalsfee"] = "";
			$_SESSION["costperunit"] = "";
				
			}
		}
		else if (isset($_POST["updatefees"])) {
			
			$course = $year = $semester = $miscellaneousfee = $id = $graduationfee = $studentteaching = $firingfee = $downpaymentfee = $prelimsfee = $midtermsfee = $prefinalsfee = $finalsfee = $costperunit = $year = $semester = ""; 
			$costperuniterror = "";
			
			$course = $_GET["course"];
			$miscellaneousfee = $_POST["miscellaneousfee"];
			$id = $_POST["id"];
			$graduationfee = $_POST["graduationfee"];
			$downpaymentfee = $_POST["downpaymentfee"];
			$prelimsfee = $_POST["prelimsfee"];
			$midtermsfee = $_POST["midtermsfee"];
			$prefinalsfee = $_POST["prefinalsfee"];
			$finalsfee = $_POST["finalsfee"];
			$costperunit = $_POST["costperunit"];
			$year = $_SESSION["arrayyear"];
			$semester = $_SESSION["arraysemester"];
			
			$_SESSION["course"] = $course;
			$_SESSION["miscellaneousfee"] = $miscellaneousfee;
			$_SESSION["id"] = $id;
			$_SESSION["graduationfee"] = $graduationfee;
			$_SESSION["downpaymentfee"] = $downpaymentfee;
			$_SESSION["prelimsfee"] = $prelimsfee;
			$_SESSION["midtermsfee"] = $midtermsfee;
			$_SESSION["prefinalsfee"] = $prefinalsfee;
			$_SESSION["finalsfee"] = $finalsfee;
			$_SESSION["costperunit"] = $costperunit;
			
			for ($i=0;$i<sizeof($year);$i++) {
				
			$miscellaneousfeeerror[$i] = $iderror[$i] = $graduationfeeerror[$i] = $firingfeeerror[$i] = $studentteachingerror[$i] = $downpaymentfeeerror[$i] = $prelimsfeeerror[$i] = $midtermsfeeerror[$i] = $prefinalsfeeerror[$i] = $finalsfeeerror[$i] = "";
				
			if (empty($miscellaneousfee[$i])) {
				$miscellaneousfee[$i] = 0;
			} 
			else {
			$miscellaneousfee[$i] = validateinput($miscellaneousfee[$i]);
				if (!preg_match("/^[0-9.]*$/", $miscellaneousfee[$i])) {
					$miscellaneousfeeerror[$i] = "<b>Error at $year[$i] - $semester[$i] Miscellaneous Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
			
			if (empty($id[$i])) {
				$id[$i] = 0;
			} 
			else {
			$id[$i] = validateinput($id[$i]);
				if (!preg_match("/^[0-9.]*$/", $id[$i])) {
					$iderror[$i] = "<b>Error at $year[$i] - $semester[$i] ID Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
			
			if (empty($graduationfee[$i])) {
				$graduationfee[$i] = 0;
			} 
			else {
			$graduationfee[$i] = validateinput($graduationfee[$i]);
				if (!preg_match("/^[0-9.]*$/", $graduationfee[$i])) {
					$graduationfeeerror[$i] = "<b>Error at $year[$i] - $semester[$i] Graduation Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
			
			if (empty($_POST["studentteaching"][$i])) {
				$studentteaching[$i] = 0;
			} 
			else {
			$studentteaching[$i] = validateinput($_POST["studentteaching"][$i]);
				if (!preg_match("/^[0-9.]*$/", $studentteaching[$i])) {
					$studentteachingerror[$i] = "<b>Error at $year[$i] - $semester[$i] Student Teaching Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
			
			if (empty($_POST["firingfee"][$i])) {
				$firingfee[$i] = 0;
			} 
			else {
			$firingfee[$i] = validateinput($_POST["firingfee"][$i]);
				if (!preg_match("/^[0-9.]*$/", $firingfee[$i])) {
					$firingfeeerror[$i] = "<b>Error at $year[$i] - $semester[$i] Firing Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
			
			if (empty($downpaymentfee[$i])) {
				$downpaymentfee[$i] = 0;
			} 
			else {
			$downpaymentfee[$i] = validateinput($downpaymentfee[$i]);
				if (!preg_match("/^[0-9.]*$/", $downpaymentfee[$i])) {
					$downpaymentfeeerror[$i] = "<b>Error at $year[$i] - $semester[$i] Down-Payment Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
			
			if (empty($prelimsfee[$i])) {
				$prelimsfee[$i] = 0;
			} 
			else {
			$prelimsfee[$i] = validateinput($prelimsfee[$i]);
				if (!preg_match("/^[0-9.]*$/", $prelimsfee[$i])) {
					$prelimsfeeerror[$i] = "<b>Error at $year[$i] - $semester[$i] Prelims Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
			
			if (empty($midtermsfee[$i])) {
				$midtermsfee[$i] = 0;
			} 
			else {
			$midtermsfee[$i] = validateinput($midtermsfee[$i]);
				if (!preg_match("/^[0-9.]*$/", $midtermsfee[$i])) {
					$midtermsfeeerror[$i] = "<b>Error at $year[$i] - $semester[$i] Midterms Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
				
			if (empty($prefinalsfee[$i])) {
				$prefinalsfee[$i] = 0;
			} 
			else {
			$prefinalsfee[$i] = validateinput($prefinalsfee[$i]);
				if (!preg_match("/^[0-9.]*$/", $prefinalsfee[$i])) {
					$prefinalsfeeerror[$i] = "<b>Error at $year[$i] - $semester[$i] Pre-Finals Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}
			
			if (empty($finalsfee[$i])) {
				$finalsfee[$i] = 0;
			} 
			else {
			$finalsfee[$i] = validateinput($finalsfee[$i]);
				if (!preg_match("/^[0-9.]*$/", $finalsfee[$i])) {
					$finalsfeeerror[$i] = "<b>Error at $year[$i] - $semester[$i] Finals Fee:</b> Please Enter A Valid Amount!";
					$errorcount++;
				}
			}

			$_SESSION["studentteaching"] = $studentteaching;
			$_SESSION["firingfee"] = $firingfee;			
		}
		
		if (empty($costperunit)) {
			$costperunit = 0;
		} 
		else {
		$costperunit = validateinput($costperunit);
			if (!preg_match("/^[0-9.]*$/", $costperunit)) {
				$costperuniterror = "<b>Error at Cost Per Unit:</b> Please Enter A Valid Amount!";
				$errorcount++;
			}
		}
		
		if ($errorcount > 0) {
			
			echo "<div class='printignore'>
			<h3> $course </h3> <hr/>
			</div><br/> <h4>
			Edit Fees </h4>
			<form method='post' action='feeseditor.php?'>
			<table class='tbl'>";
			
			for ($i=0;$i<sizeof($year);$i++) {
				echo "<tr><td><span class='error'>$miscellaneousfeeerror[$i]</span></td></tr>
					<tr><td><span class='error'>$iderror[$i]</span></td></tr>
					<tr><td><span class='error'>$graduationfeeerror[$i]</span></td></tr>
					<tr><td><span class='error'>$studentteachingerror[$i]</span></td></tr>
					<tr><td><span class='error'>$firingfeeerror[$i]</span></td></tr>
					<tr><td><span class='error'>$downpaymentfeeerror[$i]</span></td></tr>
					<tr><td><span class='error'>$prelimsfeeerror[$i]</span></td></tr>
					<tr><td><span class='error'>$midtermsfeeerror[$i]</span></td></tr>
					<tr><td><span class='error'>$prefinalsfeeerror[$i]</span></td></tr>
					<tr><td><span class='error'>$finalsfeeerror[$i]</span></td></tr>";
			}
			
			echo "<tr><td><span class='error'>$costperuniterror</span></td></tr>
				</table><br/>
				<input type='submit' name='search' value='Back'>
				</form>";
			
		}
		else {
			
			$resultsuccess = 0;
			$updatefeesresult = "";
			
			$_SESSION["course"] = "";
			$_SESSION["arrayyear"] = "";
			$_SESSION["arraysemester"] = "";
			$_SESSION["miscellaneousfee"] = "";
			$_SESSION["id"] = "";
			$_SESSION["graduationfee"] = "";
			$_SESSION["downpaymentfee"] = "";
			$_SESSION["prelimsfee"] = "";
			$_SESSION["midtermsfee"] = "";
			$_SESSION["prefinalsfee"] = "";
			$_SESSION["finalsfee"] = "";
			$_SESSION["costperunit"] = "";
			
			for ($i=0;$i<sizeof($year);$i++) {
				
				$sql = "UPDATE fees SET miscellaneousfee = '$miscellaneousfee[$i]', id = '$id[$i]', graduationfee = '$graduationfee[$i]', studentteaching = '$studentteaching[$i]', firingfee = '$firingfee[$i]', downpaymentfee = '$downpaymentfee[$i]', prelimsfee = '$prelimsfee[$i]', midtermsfee = '$midtermsfee[$i]', prefinalsfee = '$prefinalsfee[$i]', finalsfee = '$finalsfee[$i]', costperunit = '$costperunit' WHERE course = '$course' AND year = '$year[$i]' AND semester = '$semester[$i]'";
				$result = $connection->query($sql);
				
				if ($result === true) {
					$resultsuccess++;
				}
			}
			
			if ($resultsuccess == sizeof($year)) {
				$updatefeesresult = "Update Fees Successful! Updated Fees Will Be Implemented Next Enrollment Period.";	
			}
			else {
				$updatefeesresult = "Update Fees Unsuccessful! Please Try Again.";	
			}
			
				echo "<script> 
					var x = messagealert('$updatefeesresult'); 
					if (x == true) {
						window.location = 'feeseditor.php';
					}
				</script>"; 
				$updatefeesresult = "";	
			
		}
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