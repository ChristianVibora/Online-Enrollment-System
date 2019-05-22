<?php
	
#  © 2016-2017 →rEVOLution← Studios #	
		
	function validateinput($data) { // data validation function
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function getstudentsection($studentcourse, $studentyear) { // function to determine the section of the user. A section can only accept up to 30 students
		include 'connection.php';
		
		$studentcount = 0;
		
		for ($section='A';$section<'Z';$section++) { // a loop for the sections as letters from A-Z
			$sql = "SELECT COUNT(*) AS studentcount FROM enrolledstudents WHERE studentcourse = '$studentcourse' AND studentyear = '$studentyear' AND studentsection = '$section'";
			$result = $connection->query($sql);

			if ($result->num_rows == 1) {
				while ($row = $result->fetch_object()) {
				$studentcount = $row->studentcount;
				if ($studentcount < 30) { // if the studentcount of a section is less than 30, the funtion will return that section
				return $section;
				}
				}
			}
		}
		return "Pending";
		$connection->close();
	}
	
	function checkids($ids, $id) { // check if a subjectid is included in an array of subjectids
		for ($i=0;$i<sizeof($ids);$i++) {
			if ($ids[$i] == $id) {
				return true;
			}
		}
		return false;
	}
	
	function checkduplicatesubjectids($enrollsubjectids, $creditsubjectids) { // check if a subjectid is both present in two arrays
		$matched = 0;
		// check which array is smaller and which is larger
		if (sizeof($enrollsubjectids) > sizeof($creditsubjectids)) {
			$smaller = $creditsubjectids;
			$larger  = $enrollsubjectids;
		}
		else if (sizeof($creditsubjectids) > sizeof($enrollsubjectids)) {
			$smaller = $enrollsubjectids;
			$larger  = $creditsubjectids;
		}
		else if (sizeof($creditsubjectids) == sizeof($enrollsubjectids)) {
			$larger = $enrollsubjectids;
			$smaller = $creditsubjectids;
		}
		
		for ($i=0;$i<sizeof($larger);$i++) {
			for ($j=0;$j<sizeof($smaller);$j++) {
			if ($larger[$i] == $smaller[$j]) {
				$matched++;
			}
			}
		}
		
		if ($matched > 0) {
		return true;
		}
		else {
		return false;
		}
	}

function checkfullname($firstname, $middlename, $lastname) {
	include 'connection.php';
	
	$sql = "SELECT * FROM users WHERE firstname = '$firstname' AND middlename = '$middlename' AND lastname = '$lastname'";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		return true;
	}
	return false;
	$connection->close();
}

function checkmobilenumber($mobilenumber) {
	include 'connection.php';
	
	$sql = "SELECT * FROM users WHERE mobilenumber = '$mobilenumber'";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		return true;
	}
	return false;
	$connection->close();
}

function checkemailaddress($emailaddress) {
	include 'connection.php';
	
	$sql = "SELECT * FROM users WHERE emailaddress = '$emailaddress'";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		return true;
	}
	return false;
	$connection->close();
}

function checkusername($getusername) {
	include 'connection.php';
	
	$sql = "SELECT * FROM users WHERE username = '$getusername'";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0) {
		return true;
	}
	return false;
	$connection->close();
}

		function getcredits($userfirstname, $userlastname, $userlevel) {
			include 'connection.php';
				
				$userid = $firstname = $lastname = $studentcourse = $studentyear = $studentsection = $creditvalue = $totalcredits = "";
				
				echo "<div class='pagebreak'></div>
				<br/><h4>Discounts</h4>";
				
				$sql = "SELECT * FROM studentcredits INNER JOIN users ON users.userid = studentcredits.studentnumber LEFT JOIN enrolledstudents ON enrolledstudents.studentnumber = users.userid LEFT JOIN curriculum ON curriculum.subjectcourse = enrolledstudents.studentcourse GROUP BY studentcredits.studentnumber";
				$result = $connection->query($sql);
				
				if ($result->num_rows > 0) {
					
					echo "<br/ class='printignore'>
					<table class='prof' border=1>
					<tr class='curriculum-header'>
						<th> User ID </th>
						<th> Full Name </th>
						<th> Course </th> 
						<th> Year </th>
						<th> Section </th>
						<th> Discount Value </th>
					</tr>";
					
					while ($row = $result->fetch_object()) {
						$userid = $row->userid;
						$firstname = $row->firstname;
						$lastname = $row->lastname;
						$studentcourse = $row->coursecode;
						$studentyear = $row->studentyear;
						$studentsection = $row->studentsection;
						$creditvalue = $row->creditvalue;
						$totalcredits += $row->creditvalue;
						
						echo "
						<tr class='curriculum'>
							<td> $userid </td>
							<td> $firstname $lastname </td>
							<td> $studentcourse </td>
							<td> $studentyear </td>
							<td> $studentsection </td>
							<td> " , formatcurrency($creditvalue) , " </td>
						</tr>";
					}
				echo "<tr class='units'>
					<td colspan=6 align='center'> Total Discounts: " , formatcurrency($totalcredits) , " </td> 
					</tr></table><br/>";
				
					echo "<table class='tbl' width='1000'>
					<tr><td colspan=4 class='label'><br/></td></tr>
					<tr><td class='label'>Prepared by: </td><td class='label' align='right'>$userlevel:</td> <td class='label1' align='left'> <u>$userfirstname $userlastname</u> </td><td class='label' align='right'>Checked by:</td> <td class='label1' align='left'><u>Accounting Department</u></td></tr>
					</table>";
				}
				else {
					echo "<br/>No Results Found. Please Try Another Input.";
				}
				
		$connection->close();
	
		}
		
		function getdebits($userfirstname, $userlastname, $userlevel) {
			include 'connection.php';
			
				$userid = $firstname = $lastname = $studentcourse = $studentyear = $studentsection = $debitvalue = $totaldebits = "";
				
				echo "<div class='pagebreak'></div>
				<br/><h4>Debits</h4>";
				
				$sql = "SELECT DISTINCT studentnumber FROM studentdebits";
				$result = $connection->query($sql);
				
				if ($result->num_rows > 0) {
					
					echo "<br/ class='printignore'>
					<table class='prof' border=1>
					<tr class='curriculum-header'>
						<th> User ID </th>
						<th> Full Name </th>
						<th> Course </th> 
						<th> Year </th>
						<th> Section </th>
						<th> Debit Value </th>
					</tr>";

					while ($row = $result->fetch_object()) {
						$userid = $row->studentnumber;
						
						$sql1 = "SELECT * FROM users LEFT JOIN enrolledstudents ON enrolledstudents.studentnumber = users.userid LEFT JOIN curriculum ON curriculum.subjectcourse = enrolledstudents.studentcourse WHERE userid = '$userid' LIMIT 1";
						$result1 = $connection->query($sql1);
						
						if ($result1->num_rows == 1) {
							while ($row = $result1->fetch_object()) {
								$firstname = $row->firstname;
								$lastname = $row->lastname;
								$studentcourse = $row->coursecode;
								$studentyear = $row->studentyear;
								$studentsection = $row->studentsection;
							}
						}
						
						$sql2 = "SELECT SUM(debitvalue) AS debitvalue FROM studentdebits WHERE studentnumber = '$userid'";
						$result2 = $connection->query($sql2);
						
						if ($result2->num_rows == 1) {
							while ($row = $result2->fetch_object()) {
								$debitvalue = $row->debitvalue;
								$totaldebits += $row->debitvalue;
							}
						}
							echo "
								<tr class='curriculum'>
									<td> $userid </td>
									<td> $firstname $lastname </td>
									<td> $studentcourse </td>
									<td> $studentyear </td>
									<td> $studentsection </td>
									<td> " , formatcurrency($debitvalue) , " </td>
								</tr>";
					}
					
										
					echo "<tr class='units'>
					<td colspan=6 align='center'> Total Debits: " , formatcurrency($totaldebits) , " </td> 
					</tr></table><br/>";
					
					echo "<table class='tbl' width='1000'>
					<tr><td colspan=4 class='label'><br/></td></tr>
					<tr><td class='label'>Prepared by: </td><td class='label' align='right'>$userlevel:</td> <td class='label1' align='left'> <u>$userfirstname $userlastname</u> </td><td class='label' align='right'>Checked by:</td> <td class='label1' align='left'><u>Accounting Department</u></td></tr>
					</table>";
				}
				else {
					echo "<br/>No Results Found. Please Try Another Input.";
				}
			$connection->close();	
		}

		function getrefunds($userfirstname, $userlastname, $userlevel) {
			include 'connection.php';
			
				$userid = $firstname = $lastname = $studentcourse = $studentyear = $studentsection = $refunvalue = $totalrefunds = "";
				
				echo "<div class='pagebreak'></div>
				<br/><h4>Refunds</h4>";
				
				$sql = "SELECT DISTINCT studentnumber FROM studentrefunds";
				$result = $connection->query($sql);
				
				if ($result->num_rows > 0) {
					
					echo "<br/ class='printignore'>
					<table class='prof' border=1>
					<tr class='curriculum-header'>
						<th> User ID </th>
						<th> Full Name </th>
						<th> Course </th> 
						<th> Year </th>
						<th> Section </th>
						<th> Refund Value </th>
					</tr>";

					while ($row = $result->fetch_object()) {
						$userid = $row->studentnumber;
						
						$sql1 = "SELECT * FROM users LEFT JOIN enrolledstudents ON enrolledstudents.studentnumber = users.userid LEFT JOIN curriculum ON curriculum.subjectcourse = enrolledstudents.studentcourse WHERE userid = '$userid' LIMIT 1";
						$result1 = $connection->query($sql1);
						
						if ($result1->num_rows == 1) {
							while ($row = $result1->fetch_object()) {
								$firstname = $row->firstname;
								$lastname = $row->lastname;
								$studentcourse = $row->coursecode;
								$studentyear = $row->studentyear;
								$studentsection = $row->studentsection;
							}
						}
						
						$sql2 = "SELECT SUM(refundvalue) AS refundvalue FROM studentrefunds WHERE studentnumber = '$userid'";
						$result2 = $connection->query($sql2);
						
						if ($result2->num_rows == 1) {
							while ($row = $result2->fetch_object()) {
								$refundvalue = $row->refundvalue;
								$totalrefunds += $row->refundvalue;
							}
						}
							echo "
								<tr class='curriculum'>
									<td> $userid </td>
									<td> $firstname $lastname </td>
									<td> $studentcourse </td>
									<td> $studentyear </td>
									<td> $studentsection </td>
									<td> " , formatcurrency($refundvalue) , " </td>
								</tr>";
					}
					
										
					echo "<tr class='units'>
					<td colspan=6 align='center'> Total Refunds: " , formatcurrency($totalrefunds) , " </td> 
					</tr></table><br/>";
					
					echo "<table class='tbl' width='1000'>
					<tr><td colspan=4 class='label'><br/></td></tr>
					<tr><td class='label'>Prepared by: </td><td class='label' align='right'>$userlevel:</td> <td class='label1' align='left'> <u>$userfirstname $userlastname</u> </td><td class='label' align='right'>Checked by:</td> <td class='label1' align='left'><u>Accounting Department</u></td></tr>
					</table>";
				}
				else {
					echo "<br/>No Results Found. Please Try Another Input.";
				}
			$connection->close();	
		}
		
		function sendemail($recipient, $name, $subject, $body) {
			/**
			 * This example shows settings to use when sending via Google's Gmail servers.
			 */
			//SMTP needs accurate times, and the PHP time zone MUST be set
			//This should be done in your php.ini, but this is how to do it if you don't have access to that
			date_default_timezone_set('Asia/Manila');
			require 'PHPMailer/PHPMailerAutoload.php';
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 0;
			//Ask for HTML-friendly debug output
			$mail->Debugoutput = 'html';
			//Set the hostname of the mail server
			$mail->Host = 'smtp.gmail.com';
			// use
			// $mail->Host = gethostbyname('smtp.gmail.com');
			// if your network does not support SMTP over IPv6
			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			
			$mail->Port = 587;
			//Set the encryption system to use - ssl (deprecated) or tls
			$mail->SMTPSecure = 'tls';
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			//Username to use for SMTP authentication - use full email address for gmail
			$mail->Username = "enrollment.tanauaninstitute@gmail.com";
			//Password to use for SMTP authentication
			$mail->Password = "enrollment.tanauaninstitute.com";
			//Set who the message is to be sent from
			$mail->setFrom('enrollment.tanauaninstitute@gmail.com', 'Tanauan Institute, Inc.');
			//Set an alternative reply-to address
			// $mail->addReplyTo('kristinemaedesagun13@gmail.com', 'Kristine Mae');
			//Set who the message is to be sent to
			$mail->addAddress($recipient, $name);
			//Set the subject line
			$mail->Subject = $subject;
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->Body = $body;

			//send the message, check for errors
			 if ($mail->send()) {
				 return true;
			 }
			 else {
				 return false;
			 }
		}
		
		function formatcurrency($number) {
			
			if (preg_match("/^[0-9.]*$/", $number)) {
				
				$number = number_format($number, 2);
				
				echo "₱$number";
			}
			else {
				echo $number;
			}
		}
		
		function menu($userlevel) {
		
			if ($userlevel == "Admin") {
				echo "<li><a href='admin.php'>Admin</a></li>";
			}
			else if ($userlevel == "Cashier") {
				echo "<li><a href='payment.php'>Payment</a></li>";
			}
			else if ($userlevel == "Registrar Staff") {
				echo "<li><a href='registrar.php'>Registrar</a></li>";
			}
			else if ($userlevel == "Student") {
				echo "<li><a href='profile.php'>Profile</a></li>";
			}
		}
		
		function menubackpage() {
		
			if (!empty($_SESSION["backpage"])) {
				$backpage = $_SESSION["backpage"];
				
				if ($backpage == "Class Lists") {
					echo "<li><a href='classlist.php'>Class Lists</a></li>";
				}
				else if ($backpage == "Search") {
					echo "<li><a href='search.php'>Search</a></li>";
				}
				
			}
		}
	
		function menubackpage1() {
			
		$viewuserid = "";
		
			if (!empty($_SESSION["backpage1"]) && !empty($_GET["viewuserid"])) {
				$backpage = $_SESSION["backpage1"];
				$viewuserid = $_GET["viewuserid"];
				
				if ($backpage == "Profile") {
					
				}
				else if ($backpage == "View Profile") {
					echo "<li><a href='viewprofile.php?viewuserid=$viewuserid'>View Profile</a></li>";
				}
				
			}
		}
		
?>