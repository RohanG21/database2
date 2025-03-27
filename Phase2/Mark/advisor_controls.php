<?php
	include 'config.php';
	session_start();
	
	$current_semester = "Spring";
	$current_year = 2025;
	$email = $_SESSION['email'];
	$password = $_SESSION['password'];
	$ins_id = $_SESSION['ins_id'];
	$chosen_phd = "";
	
	if (isset($_POST['chosen_phd'])) {
    $chosen_phd = $_POST['chosen_phd'];
	} else if (isset($_POST['select_phd'])) {
    $chosen_phd = $_POST['select_phd'];
	}
	if (isset($chosen_phd) && !empty($chosen_phd)) {
    $phd_advisor = "SELECT instructor_id FROM advise WHERE student_id = '$chosen_phd' AND instructor_id = '$ins_id'"; 
    $chosen_phd_advisor = $mysqli->query($phd_advisor);
    echo "Chosen student's id: $chosen_phd";
	} else {
    // Initialize to avoid undefined variable
    $chosen_phd_advisor = null;
}
/*
 The instructor can appoint instructors as advisor(s) for PhD students, up to 2 per student,
 including a start date, and optional end date. The advisor will be able to view
 the course history of their advisees, and update their advisee's information.

 each student_id in advise can have only 1 or 2 different instructor_ids
 instructor view will need to be split up by advisee/student
 "update advisee info" = qualifier, proposal defence date, dissertation date

 Advise: instructor_id, student_id, start_date, end_date(optional)
 INSERT INTO advise (instructor_id, student_id, start_date, end_date) VALUES (2, 106, 2024-11-08, 2028-01-19
*/

?>

<!DOCTYPE html>
<html>
<head>
<title>Advisor Controls</title>
</head>
<body>
<center>
<h1> Advisor Controls </h1>

<?php
	echo "<h2>Current Advisees</h2>";
	$advisee_query = "SELECT S.student_id, S.name FROM student S, advise A WHERE S.student_id = A.student_id AND 
		A.instructor_id = $ins_id";
	$advisee_result = $mysqli->query($advisee_query);
	if ($advisee_result->num_rows > 0) {
			//echo "<p>Current advisee names and IDs:</p>";
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
	<div>
		<input type="submit" name="select_phd_button" value="Select">
	</div>
</form>
<?php
	if (isset($_POST['select_phd_button']) && $_POST['select_phd_button']) {
		$chosen_phd = $_POST["select_phd"];
		$phd_advisor = "SELECT instructor_id FROM advise WHERE student_id = $chosen_phd AND instructor_id = $ins_id"; 
		$chosen_phd_advisor = $mysqli->query($phd_advisor);
		echo "Chosen student's id: $chosen_phd";
	}
?>
	<br><br>
	
	
<!--View selected PhD's course history -->
<?php
	echo "<h2>Advisee course history</h2>";
	if ($chosen_phd) {
		$ch_query = "SELECT * FROM take WHERE student_id = $chosen_phd ORDER BY year";
		$ch_result = $mysqli->query($ch_query);
		if (($ch_result->num_rows > 0) && ($chosen_phd_advisor->num_rows > 0)) {
			//echo "<h2>Advisee course history</h2>";
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
?>

<br>

<!--Add advisors to selected PhD if number of current advisors < 2 -->
<?php
	echo "<h2>Appoint instructors</h2>";
	if($chosen_phd) {

		$adv_cnt_query = "SELECT * FROM advise WHERE student_id = $chosen_phd";
		$adv_cnt_result = $mysqli->query($adv_cnt_query);
		if ($adv_cnt_result->num_rows >= 2) { // setting == 1 correctly triggers message
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
						echo "<option value='".$ins['instructor_id']."'>".$ins['instructor_id'].", ".$ins['instructor_name']."</option>";
					}
				?>
			</select>
			<br><br>
			<input type="hidden" name="chosen_phd" value="<?php echo $chosen_phd;?>">
			<div>
				<label for="start_date">Enter advising start date YYYY-MM-DD:</label>
				<input type="text" id="start_date" name="start_date" maxlength="10" required>
			</div>
			<br>
			<div>
				<label for="end_date">Optional: enter advising end date YYYY-MM-DD:</label>
				<input type="text" id="end_date" name="end_date" maxlength="10">
			</div>
			<br>
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
					//INSERT INTO advise (instructor_id, student_id, start_date, end_date) VALUES (3, 106, '20230516', '20250516'); works when entered directly
					//INSERT INTO advise (instructor_id, student_id, start_date, end_date) VALUES (3, 106, 20230516, 20250516); also works
					//INSERT INTO advise (instructor_id, student_id, start_date, end_date) VALUES (3, 106, '2023-05-16', '2025-05-16'); also works
					$mysqli->query($new_advisor);
				}
				echo "<p>New entry added to the advise table.</p>";
				
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
<h2>Update Advisee Information</h2>
<?php
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
			<form action = "" method ="POST">
			<input type="hidden" name="chosen_phd" value="<?php echo $chosen_phd;?>">
			<div>
				<label for="new_qual">Select pass or fail for the qualifying exam:</label>
				<select name=>
				<input type="text" id="new_qual" name="new_qual" maxlength="10">
			</div>
			<br>
			<div>
				<label for="new_def">Enter new defence date YYYY-MM-DD:</label>
				<input type="text" id="new_def" name="new_def" maxlength="10">
			</div>
			<br>
			<div>
				<label for="new_diss">Enter new dissertation date YYYY-MM-DD:</label>
				<input type="text" id="new_diss" name="new_diss" maxlength="10">
			</div>
			<br>
			<div>
				<input type="submit" name="new_advisor_button" value="Submit" required>
			</div>
			</form>
			<?php
			
			
			
			
		
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


?>

<br>
<p><a href="instructor.php">Return to instructor homepage</a>.</p>

</center>
</body>
</html>
