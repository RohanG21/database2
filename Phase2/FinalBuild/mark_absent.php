<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include 'config.php';
		$email = $_POST["email"];
		$password = $_POST["password"];
		session_start();
		$_SESSION['email'] = $email;
		$_SESSION['password'] = $password;

	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
?>

<!DOCTYPE HTML>
<html>
<head>
<title> Mark Absent</title>
</head>
<body>
<center>
<h1>Mark Absent</h1>
<p><a href="instructor.php">Return to Instructor Page</a>.</p>
</center>
<p><b>Select Course</b></p>

<?php
	$get_id = "SELECT instructor_id FROM instructor where email = '$email'";
	$result = $mysqli->query($get_id);
	$row = $result->fetch_assoc();
	$inid = $row['instructor_id'];
	$section = "SELECT course_id, section_id FROM section WHERE year = '$current_year' and semester = '$current_semester' and instructor_id = '$inid'";
	$current_sections = $mysqli->query($section);
?>

<form action = "" method ="POST">
	<select name="select_section" id="select_section" required>
		<option disabled selected value> -- select a section -- </option>
	<?php
		$prev_course = 'NULL';
		while ($sections= $current_sections->fetch_assoc()) {
			if ($sections['course_id'] != $prev_course) {
				echo "<option disabled value='".$sections['course_id']."'>".$sections['course_id']."</option>";
				$prev_course = $sections['course_id'];
			}
			echo "<option value='".$sections['course_id'].",".$sections['section_id']."'>".$sections['section_id']."</option>";
		}
	?>
	</select>
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<br><br>
	<div>
		<input type="submit" name = "show_students" value = "Select Section">
	</div>
	<br>
</form>

<?php
	if (isset($_POST['show_students']) && $_POST['show_students']) {
		$section = $_POST["select_section"];
		list($cid, $seid) = explode(',', $section);
		$students = "SELECT take.student_id, name FROM section, take, student WHERE instructor_id = '$inid' and take.section_id = '$seid' and take.course_id = '$cid' and take.year = '$current_year' and take.semester = '$current_semester' and take.student_id = student.student_id and take.section_id = section.section_id and take.course_id = section.course_id and take.year = section.year and take.semester = section.semester";
		$students = $mysqli->query($students);
?>

	<p><b>Select Student</b></p>

<form action = "" method ="POST">
	<select name="student[]" multiple="multiple" required>
	<?php
		while ($choose_student = $students->fetch_assoc()) {
			echo "<option value='".$choose_student['student_id'].",".$choose_student['name']."'>".$choose_student['name']."</option>";
		}
	?>
	</select>
	<br>
	<label for="day">Date Absent:</label>
	<input type = "date" name = "day" id = "day" required>
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<input type = "hidden" name = "course_id" value="<?php echo $cid;?>">
	<input type = "hidden" name = "section_id" value="<?php echo $seid;?>">
	<br><br>
	<div>
		<input type="submit" name = "mark_absent" value = "Mark Student Absent">
	</div>
</form>

<?php
	}
?>

<?php
	if (isset($_POST['mark_absent']) && $_POST['mark_absent']) {
		$day = $_POST["day"];
		$cid = $_POST["course_id"];
		$seid = $_POST["section_id"];
		foreach ($_POST['student'] as $student) {
			list($stid, $name) = explode(',', $student);
			$query = "SELECT student_id FROM attendance where student_id = '$stid' and day = '$day' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
			$check = $mysqli->query($query);
			if ($check->num_rows == 0) {
				$absent = "INSERT INTO attendance VALUES('$stid','$cid','$seid','$current_semester','$current_year','$day')";
				$mysqli->query($absent);
				echo "Success! $name marked absent on $day for course $cid, section $seid<br>";
				$query = "SELECT COUNT(distinct day) as count FROM attendance WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and year = '$current_year' and semester = '$current_semester' group by student_id, course_id, section_id, semester, year";
				$check = $mysqli->query($query);

				$row = $check->fetch_assoc();
				$count = $row['count'];
				if ($count % 2 == 0) {
					$query = "SELECT grade from take where student_id = '$stid' and section_id = '$seid' and course_id = '$cid' and semester = '$current_semester' and year = '$current_year'";
					$result = $mysqli->query($query);
					$row = $result->fetch_assoc();
					$change_grade = $row['grade'];
					switch($change_grade) {
						case 'A+':
							$update = "UPDATE take SET grade = 'A' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now an A";
							break;
						case 'A':
							$update = "UPDATE take SET grade = 'A-' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now an A-";
							break;
						case 'A-':
							$update = "UPDATE take SET grade = 'B+' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a B+";
							break;
						case 'B+':
							$update = "UPDATE take SET grade = 'B' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a B";
							break;
						case 'B':
							$update = "UPDATE take SET grade = 'B-' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a B-";
							break;
						case 'B-':
							$update = "UPDATE take SET grade = 'C+' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a C+";
							break;
						case 'C+':
							$update = "UPDATE take SET grade = 'C' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a C";
							break;
						case 'C':
							$update = "UPDATE take SET grade = 'C-' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a C-";
							break;
						case 'C-':
							$update = "UPDATE take SET grade = 'D+' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a D+";
							break;
						case 'D+':
							$update = "UPDATE take SET grade = 'D' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a D";
							break;
						case 'D':
							$update = "UPDATE take SET grade = 'D-' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now a D-";
							break;
						case 'D-':
							$update = "UPDATE take SET grade = 'F' WHERE student_id = '$stid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
							$mysqli->query($update);
							echo "Student has been absent $count times, student's grade is now an F";
							break;
						case 'F':
							echo "Student already has an F in the class<br>";
							break;
						default:
							echo "Student doesn't have a grade in the class<br>";
							break;
					}	
				}
				
			} else {
				echo "Error: $name already marked absent for course $cid, section $seid on $day<br>";
			}
		}			
?>

<?php
	}
?>

</body>
</html>