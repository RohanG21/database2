<?php
	include 'config.php';
	session_start();
	if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
		$email = $_SESSION['email'];
		$password = $_SESSION['password'];
		$find = "SELECT name, student_id FROM student, account where student.email = account.email and account.email = '$email'";
		$result = $mysqli->query($find);
		if ($result->num_rows != 1) {
			echo "Error: invalid email";
			$url = "index.html";
			echo "<br><a href='$url'>Try Again</a>";
		} else {
			$row = $result->fetch_assoc();
			$name = $row["name"];
			$sid = $row["student_id"];
		}
		session_unset();
		session_destroy();
	} else if(isset($_POST['email']) && isset($_POST['password'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];
		$name = $_POST['name'];
		$sid = $_POST['sid'];
	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
?>

<!DOCTYPE HTML>
<html>
<head>
<title> Student Account</title>
</head>
<body>
<center>
<h1>Welcome <?php echo $name;?></h1>
<p><a href="index.html">Return to Login Menu</a>.</p>
</center>

<form method = "POST" action = "view_courses.php">
<input type = "hidden" name = "sid" value="<?php echo $sid;?>">
<input type = "hidden" name = "email" value="<?php echo $email;?>">
<input type = "hidden" name = "password" value="<?php echo $password;?>">
<input type = "hidden" name = "name" value="<?php echo $name;?>">
<input type = "submit" value = "View All Courses Taken">
</form>
<br>
<form method = "POST" action = "rate_professor.php">
<input type = "hidden" name = "sid" value="<?php echo $sid;?>">
<input type = "hidden" name = "email" value="<?php echo $email;?>">
<input type = "hidden" name = "password" value="<?php echo $password;?>">
<input type = "hidden" name = "name" value="<?php echo $name;?>">
<input type = "submit" value = "Rate Professor">
</form>

<p><b>Change Password</b></p>
<form method = "POST" action="">
	<div>
		<label for="old_password">Enter Old Password:</label>
		<input type="text" id="old_password" name="old_password" maxlength="12" required>
	</div>

	<br>

	<div>
		<label for="new_password">Enter New Password:</label>
		<input type="text" id="new_password" name="new_password" maxlength="12" required>
	</div>

	<br><br>
		<input type = "hidden" name = "email" value="<?php echo $email;?>">
		<input type = "hidden" name = "password" value="<?php echo $password;?>">
		<input type = "hidden" name = "name" value="<?php echo $name;?>">
		<input type = "hidden" name = "sid" value="<?php echo $sid;?>">

	<div>
		<input type="submit" name="change_password" value="Change Password">
	</div>
</form>
<br>
</body>
</html>

<?php
	if (isset($_POST['change_password']) && $_POST['change_password']) {
		$oldp = $_POST["old_password"];
		$newp = $_POST["new_password"];
		$valid = "SELECT password from account where email = '$email' and password = '$oldp'";
		$result = $mysqli->query($valid);
		if ($result->num_rows == 1) {
			$update = "UPDATE account SET password = '$newp' WHERE email = '$email' and password = '$oldp'";
			$mysqli->query($update);
			echo "Password was successfully changed.";
			$password = $newp;
		} else {
			echo "Error: invalid old password. Please try again.";
		}
		
	}
	$course = "SELECT course.course_id FROM course, section WHERE course.course_id = section.course_id and year = '$current_year' and semester = '$current_semester'";
	$choose_course = $mysqli->query($course);
?>
<br>

<?php
	if (isset($_POST['enroll']) && $_POST['enroll']) {
		$chosen_section = $_POST["select_section"];
		$chosen_course = $_POST["chosen_course"];
		$section_size = "SELECT COUNT(distinct student_id) as space FROM take WHERE course_id = '$chosen_course' and section_id = '$chosen_section' and year = '$current_year' and semester = '$current_semester'";
		$currently_enrolled = $mysqli->query($section_size);
		$available_space = $currently_enrolled->fetch_assoc();
		//printf("Currently Enrolled: %s", $available_space['space']);

// have to check if the prereq exists
//1st condition: if the course has a prerequisite
		$prereq_cond1 = "SELECT course_id from course where course_id = '$chosen_course' and course_id not in (select course_id from prereq)";
//2nd condition: if the student has taken the prerequisite

		$prereq_cond2 = "SELECT student_id from take, prereq where student_id = '$sid' and take.course_id = prereq.prereq_id and ((take.year < '$current_year') or (take.year = '$current_year' and take.semester <> 'Spring')) and prereq.course_id = '$chosen_course'";

//3rd condition: if the student isn't trying to take a class they have already took (add retaking classes later)
		$prereq_cond3 = "SELECT student_id from take where student_id = '$sid' and take.course_id = '$chosen_course'";

		$valid1 = $mysqli->query($prereq_cond1);
		$valid2 = $mysqli->query($prereq_cond2);
		$valid3 = $mysqli->query($prereq_cond3);
		if (($valid1->num_rows == 1 or $valid2->num_rows == 1) and $valid3->num_rows == 0 and $available_space['space'] < 15) {
			$add_course = "INSERT INTO take VALUES('$sid', '$chosen_course', '$chosen_section', '$current_semester','$current_year', NULL)";
			$mysqli->query($add_course);
			echo "Success! Added course to the current semester";
		} else {
			if ($valid3->num_rows != 0) {
				echo "<br>Error: Student is or has already taken the selected course.";
			} else if ($available_space['space'] >= 15) {
				echo "<br>Error: Section is full";
			} else {
				echo "<br>Error: Did not meet the prerequisite";
			}
		}
	}
?>

<html>
<body>
<p><b>Enroll In Course</b></p>
<form action = "" method ="POST">
	<select name="select_course" id="select_course" required>
		<option disabled selected value> -- select an option -- </option>
	<?php
		while ($courses= $choose_course->fetch_assoc()) {
			echo "<option value='".$courses['course_id']."'>".$courses['course_id']."</option>";
		}
	?>
	</select>
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<input type = "hidden" name = "name" value="<?php echo $name;?>">
	<input type = "hidden" name = "sid" value="<?php echo $sid;?>">
	<br><br>
	<div>
		<input type="submit" name = "find_section" value = "Find A Section">
	</div>
</form>
</body>
</html>
<br>

<?php
	if (isset($_POST['find_section']) && $_POST['find_section']) {
		$chosen_course = $_POST["select_course"];
		$section = "SELECT section_id FROM section WHERE course_id = '$chosen_course' and year = '$current_year' and semester = '$current_semester'";
		$choose_section = $mysqli->query($section);
		echo "Chosen Course: $chosen_course";
	}
?>
<html>
<body>
<p><b>Select Course Section</b></p>
<form action = "" method = "POST">
	<select name="select_section" id="select_section" required>
		<option disabled selected> -- select a course first -- </option>
	<?php
		while ($sections= $choose_section->fetch_assoc()) {
			echo "<option value='".$sections['section_id']."'>".$sections['section_id']."</option>";
		}
	?>
	</select>
	<input type = "hidden" name = "chosen_course" value="<?php echo $chosen_course;?>">
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<input type = "hidden" name = "name" value="<?php echo $name;?>">
	<input type = "hidden" name = "sid" value="<?php echo $sid;?>">

	<br><br>
	<input type="submit" name = "enroll" value = "Enroll in Section">
</form>
</body>
</html>
