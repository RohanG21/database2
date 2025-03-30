<?php
	include 'config.php';
	session_start();
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$email = $_POST["email"];
		$password = $_POST["email"];
		$_SESSION['email'] = $email;
		$_SESSION['password'] = $password;

	} else if (isset($_SESSION['email'])) {
		$email = $_SESSION['email'];
		$password = $_SESSION['password'];
	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
?>

<?php
	
	// have to include this section because the page must be accesible by both instructors and admins
	$ins_id = '';
	$is_instructor = false;
	$is_admin = false;
	if((isset($_SESSION['email']))) {
		$email = $_SESSION['email'];
		$password = $_SESSION['password'];
		$find = "SELECT type FROM account where email = '$email'";//and password = '$password'";
		$result = $mysqli->query($find);
		if ($result->num_rows != 1) {
			echo "Error: invalid email";
			$url = "index.html";
			echo "<br><a href='$url'>Try Again</a>";
		} else {
			$row = $result->fetch_assoc();
			$type = $row["type"];
			if ($type == 'instructor') {
				$is_instructor = true;
				$is_admin = false;
			} else if ($type == 'admin') {
				$is_instructor = false;
				$is_admin = true;
			} else {
				echo "Error: unrecognized/wrong account type";
				$url = "index.html";
				echo "<br><a href='$url'>Return to Login Menu</a>";
			}
		}
	} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$email = $_POST["email"];
		$password = $_POST["email"];
		$_SESSION['email'] = $email;
		$_SESSION['password'] = $password;
		$find = "SELECT type FROM account where email = '$email'";//and password = '$password'";
		$result = $mysqli->query($find);
		if ($result->num_rows != 1) {
			echo "Error: invalid email";
			$url = "index.html";
			echo "<br><a href='$url'>Try Again</a>";
		} else {
			$row = $result->fetch_assoc();
			$type = $row["type"];
			if ($type == 'instructor') {
				$is_instructor = true;
				$is_admin = false;
			} else if ($type == 'admin') {
				$is_instructor = false;
				$is_admin = true;
			} else {
				echo "Error: unrecognized/wrong account type";
				$url = "index.html";
				echo "<br><a href='$url'>Return to Login Menu</a>";
			}
		}
	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
	
	if ($is_instructor == true) {
		$find_id_query = "SELECT instructor_id FROM instructor WHERE instructor.email = '$email'";
		$find_id_result = $mysqli->query($find_id_query);
		$row = $find_id_result->fetch_assoc();
		$ins_id = $row['instructor_id'];
	} else if ($is_admin == true) {
	} else {}  // this line should never be reached on this page
	
	$chosen_phd = "";
	
	// Following lines ensures neccesary info on the selected student persists between form usage
	if (isset($_POST['chosen_phd'])) {
		$chosen_phd = $_POST['chosen_phd'];
	} else if (isset($_POST['select_phd'])) {
		$chosen_phd = $_POST['select_phd'];
	}
	if (isset($chosen_phd) && !empty($chosen_phd)) {
		$phd_advisor = "SELECT instructor_id FROM advise WHERE student_id = '$chosen_phd' AND instructor_id = '$ins_id'"; 
		$chosen_phd_advisor = $mysqli->query($phd_advisor); 
	}
?>

<!DOCTYPE html>
<html>
<head>
<title>Advisor Controls</title>
</head>
<body>
<center>
<h1> Advisor Controls </h1>

<!--Display advisees of the currently logged on instructor-->
<?php
	if ($is_instructor == true) {
		echo "<h2>Current Advisees</h2>";
		$advisee_query = "SELECT S.student_id, S.name FROM student S, advise A WHERE S.student_id = A.student_id AND 
			A.instructor_id = $ins_id";
		$advisee_result = $mysqli->query($advisee_query);
		if ($advisee_result->num_rows > 0) {
				echo "<table border = '1' cellpadding = '4' cellspacing = '0'>";
				echo	"<tr><th>Student name</th>
						<th>Student ID</th>
						</tr>";
				while ($row = $advisee_result->fetch_assoc()) {
					$stu_name = $row['name'];
					$stu_id = $row['student_id'];
					echo "<tr>";
					echo "<td>$stu_name</td>";
					echo "<td>$stu_id</td>";
					echo "</tr>";
				}
				echo "</table>";
			} else {
				echo "<p>No current advisees found.</p>";
			}
	}
?>
<br>

<!--Pick a PhD student to enable the functionality in the rest of the page-->
<h2>PhD Student Selection</h2>
<form action = "" method ="POST">
	<select name="select_phd" id="select_phd" required>
		<option disabled selected value> Student ID, Name</option>
	<?php
		$sel_phd_query = "SELECT S.student_id, S.name FROM student S, PhD P WHERE S.student_id = P.student_id";
		$sel_phd_result = $mysqli->query($sel_phd_query);
		while ($phds = $sel_phd_result->fetch_assoc()) {
			echo "<option value='".$phds['student_id']."'>".$phds['student_id'].", ".$phds['name']."</option>";
		}
	?>
	</select>
	<br><br>
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<div>
		<input type="submit" name="select_phd_button" value="Select">
	</div>
</form>
<?php
	if (isset($_POST['select_phd_button']) && $_POST['select_phd_button']) {
		$chosen_phd = $_POST["select_phd"];
		if ($is_instructor == true) {
			$phd_advisor = "SELECT instructor_id FROM advise WHERE student_id = $chosen_phd AND instructor_id = $ins_id"; 
			$chosen_phd_advisor = $mysqli->query($phd_advisor);
		}
		echo "Chosen student's id: $chosen_phd";
	}
?>
	<br><br>
	
	
<!--View selected PhD's course history -->
<?php
	if ($is_instructor == true) {
		echo "<h2>Advisee Course History</h2>";
		if ($chosen_phd) {
			$ch_query = "SELECT * FROM take WHERE student_id = $chosen_phd ORDER BY year";
			$ch_result = $mysqli->query($ch_query);
			if (($ch_result->num_rows > 0) && ($chosen_phd_advisor->num_rows > 0)) {
				$ch_query = "SELECT * FROM take WHERE student_id = $chosen_phd ORDER BY year";
				$ch_result = $mysqli->query($ch_query);
				echo "<table border = '1' cellpadding = '4' cellspacing = '0'>";
					echo	"<tr><th>Course ID</th>
							<th>Section ID</th>
							<th>Semester</th>
							<th>Year</th>
							<th>Grade</th>
							</tr>";
				
					while ($row = $ch_result->fetch_assoc()) {
						$course_id = $row['course_id'];
						$section_id = $row['section_id'];
						$semester = $row['semester'];
						$year = $row['year'];
						$stu_grade = $row['grade'];
						echo "<tr>";
						echo "<td>$course_id</td>";
						echo "<td>$section_id</td>";
						echo "<td>$semester</td>";
						echo "<td>$year</td>";
						echo "<td>$stu_grade</td>";
						echo "</tr>";
					}
					echo "</table>";
			} else if (($ch_result->num_rows > 0) && ($chosen_phd_advisor->num_rows == 0)) {
				echo "<p>You are not currently authorized to view this student's course history.</p>";
			} else if (($ch_result->num_rows == 0) && ($chosen_phd_advisor->num_rows == 0)) {
				echo "<p>You are not currently authorized to view this student's course history.</p>";
			} else {
					echo "<p>No course history found.</p>";
				}
		} 
		else {
			echo "<p>Select a PhD student first.</p>";
		}
	}
?>

<br>

<!--Add advisors to selected PhD if number of current advisors < 2 -->
<?php
	echo "<h2>Appoint Instructors</h2>";
	if($chosen_phd) {

		$adv_cnt_query = "SELECT * FROM advise WHERE student_id = $chosen_phd";
		$adv_cnt_result = $mysqli->query($adv_cnt_query);
		if ($adv_cnt_result->num_rows >= 2) {
			echo "<p>Student already has maximum number of advisors.</p>";
		} 
		else { ?>
					
			<form action = "" method ="POST">
			<select name="select_ins" id="select_ins" required>
				<option disabled selected value> Instructor ID, Name</option>
				<?php
					$sel_ins_query = "SELECT instructor_id, instructor_name FROM instructor";
					$sel_ins_result = $mysqli->query($sel_ins_query);
					while ($ins = $sel_ins_result->fetch_assoc()) {
						$tmp_ins_id = $ins['instructor_id'];
						$prevent_dupe_query = "SELECT * FROM advise WHERE instructor_id = '$tmp_ins_id' AND student_id = '$chosen_phd'";
						$prevent_dupe_result = $mysqli->query($prevent_dupe_query);
						$row_cnt = $prevent_dupe_result->num_rows;
						if ($row_cnt == 0) {  // if != 0, offering the instructor as an option would cause duplicate entries
							echo "<option value='".$ins['instructor_id']."'>".$ins['instructor_id'].", ".$ins['instructor_name']."</option>";
						} else {
						}
					}
				?>
			</select>
			<br><br>
			<input type="hidden" name="chosen_phd" value="<?php echo $chosen_phd;?>">
			<div>
				<label for="start_date">Enter advising start date:</label>
				<input type = "date" name = "start_date" id = "start_date" required>
			</div>
			<br>
			<div>
				<label for="end_date">Optional: enter advising end date:</label>
				<input type = "date" name = "end_date" id = "end_date">
			</div>
			<br>
			<input type = "hidden" name = "email" value="<?php echo $email;?>">
			<input type = "hidden" name = "password" value="<?php echo $password;?>">
			<div>
				<input type="submit" name="new_advisor_button" value="Submit" required>
			</div>
			</form>
			<?php
			if (isset($_POST['new_advisor_button']) && $_POST['new_advisor_button']) {
				$chosen_phd = $_POST["chosen_phd"];
				$chosen_ins = $_POST["select_ins"];
				$start_date = $_POST["start_date"];
				if (empty($_POST["end_date"])) {
					$new_advisor = "INSERT INTO advise (instructor_id, student_id, start_date, end_date) VALUES ('$chosen_ins', '$chosen_phd', '$start_date', NULL)";
					$mysqli->query($new_advisor);
				} 
				else {
					$end_date = $_POST["end_date"];
					$new_advisor = "INSERT INTO advise (instructor_id, student_id, start_date, end_date) VALUES ('$chosen_ins', '$chosen_phd', '$start_date', '$end_date')";
					$mysqli->query($new_advisor);
				}
				echo "<p>New entry added to the advise table. If added as your advisee, re-select to see related options.</p>";
			}

		} ?>
	<?php
	}
	else {
		echo "<p>Select a PhD student first.</p>";
	}
?>
<br>


<!--Change qualifier, proposal defence date, dissertation date values of the PhD table -->
<?php
	if ($is_instructor == true) {
		echo "<h2>Update Advisee Information</h2>";
		if ($chosen_phd) {
			$phd_info_query = "SELECT * FROM PhD WHERE student_id = $chosen_phd";
			$phd_info_result = $mysqli->query($phd_info_query);
			if (($phd_info_result->num_rows == 1) && ($chosen_phd_advisor->num_rows > 0)) { 
				echo "<p>Current PhD information:</p>";
				echo "<table border = '1' cellpadding = '4' cellspacing = '0'>";
				echo	"<tr><th>Student ID</th>
						<th>Qualifier</th>
						<th>Proposal Defence Date</th>
						<th>Dissertation Defence Date</th>
						</tr>";
			
				while ($row = $phd_info_result->fetch_assoc()) {
					$stu_id = $row['student_id'];
					$qual = $row['qualifier'];
					$def_date = $row['proposal_defence_date'];
					$diss_date = $row['dissertation_defence_date'];
					echo "<tr>";
					echo "<td>$stu_id</td>";
					echo "<td>$qual</td>";
					echo "<td>$def_date</td>";
					echo "<td>$diss_date</td>";
					echo "</tr>";
				}
				echo "</table>"; ?>
				<br>
				<p>Leave blank/unselected any fields you do not wish to change.</p>
				<form action = "" method ="POST">
				<input type="hidden" name="chosen_phd" value="<?php echo $chosen_phd;?>">
				<div>
					<label for="new_qual">Select pass or fail for the qualifying exam:</label>
					<select name="new_qual" id="new_qual">
						<option disabled selected value> Please select an option</option>
						<option value = "Pass">Pass</option>
						<option value = "Fail">Fail</option>
					</select>
				</div>
				<br>
				<div>
					<label for="new_def">Enter new defence date:</label>
					<input type = "date" name = "new_def" id = "new_def">
				</div>
				<br>
				<div>
					<label for="new_diss">Enter new dissertation date:</label>
					<input type = "date" name = "new_diss" id = "new_diss">
				</div>
				<br>
				<input type = "hidden" name = "email" value="<?php echo $email;?>">
				<input type = "hidden" name = "password" value="<?php echo $password;?>">
				<div>
					<input type="submit" name="new_phd_info_button" value="Submit" required>
				</div>
				</form>
				<?php
				if (isset($_POST['new_phd_info_button']) && $_POST['new_phd_info_button']) {
					$chosen_phd = $_POST["chosen_phd"];
					if (!empty($_POST["new_qual"])) {$new_qual = $_POST["new_qual"];} // avoids a warning when empty
					$new_def_start_date = $_POST["new_def"];
					$new_diss_start_date = $_POST["new_diss"];
					$update_confirm = False;
					if (!empty($_POST["new_qual"])) {
						$new_qual_query = "UPDATE PhD SET qualifier = '$new_qual' WHERE student_id = '$chosen_phd'";
						$mysqli->query($new_qual_query);
						$update_confirm = True;
					}
					if (!empty($_POST["new_def"])) {
						$new_def_query = "UPDATE PhD SET proposal_defence_date = '$new_def_start_date' WHERE student_id = '$chosen_phd'";
						$mysqli->query($new_def_query);
						$update_confirm = True;
					}
					if (!empty($_POST["new_diss"])) {
						$new_diss_query = "UPDATE PhD SET dissertation_defence_date = '$new_diss_start_date' WHERE student_id = '$chosen_phd'";
						$mysqli->query($new_diss_query);
						$update_confirm = True;
					}
					if ($update_confirm == False) {
						echo "No changes made.";
					} else {
						echo "Changes to PhD confirmed. Reselect student at top of page to display changes.";
					}
				}
			} else if (($phd_info_result->num_rows == 1) && ($chosen_phd_advisor->num_rows == 0)) {
				echo "You are not currently authorized to alter this student's information.";
			} else if (($phd_info_result->num_rows == 0) && ($chosen_phd_advisor->num_rows == 0)) {
				echo "You are not currently authorized to alter this student's information.";
			} else {
				echo "No student info found.";
			}
		} else {
			echo "<p>Select a PhD student first.</p>";
		}
	}
?>

<?php
	if ($is_instructor == true) {
		echo "<br>";
		echo "<p><a href='instructor.php'>Return to instructor homepage</a>.</p>";
	} else if ($is_admin == true) {
		echo "<br>";
		echo "<p><a href='admin.php'>Return to admin homepage</a>.</p>";
	}
?>

</center>
</body>
</html>
