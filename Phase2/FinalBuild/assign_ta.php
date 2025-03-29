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
<title> Assign TA</title>
</head>
<body>
<center>
<h1>Assign TA</h1>
<p><a href="admin.php">Return to Admin Page</a>.</p>
</center>
<p><b>Assign TA</b></p>

<?php
	$section = "SELECT course_id, section_id FROM section s1 WHERE course_id not in (SELECT course_id from ta t1 WHERE t1.course_id = s1.course_id and t1.section_id = s1.section_id and t1.year = '$current_year' and t1.semester = '$current_semester') and(SELECT COUNT(DISTINCT student_id) FROM take t2 WHERE s1.course_id = t2.course_id and s1.section_id = t2.section_id and semester = 'Spring' and year = 2025 group by course_id, section_id, semester, year) > 10 group by course_id, section_id";
	$ta_section = $mysqli->query($section);
	$find_ta = "SELECT name, student.student_id FROM student, phd where student.student_id = phd.student_id and phd.student_id not in (SELECT student_id from ta)";
	$select_ta = $mysqli->query($find_ta);
?>

<form action = "" method ="POST">
	<select name="select_section" id="select_section" required>
		<option disabled selected value> -- select a section -- </option>
	<?php
		$prev_course = 'NULL';
		while ($sections= $ta_section->fetch_assoc()) {
			if ($sections['course_id'] != $prev_course) {
				echo "<option disabled value='".$sections['course_id']."'>".$sections['course_id']."</option>";
				$prev_course = $sections['course_id'];
			}
			echo "<option value='".$sections['course_id'].",".$sections['section_id']."'>".$sections['section_id']."</option>";
		}
	?>
	</select>
	<br><br>
	<select name="select_ta" id="select_ta" required>
		<option disabled selected value> -- select a phd student -- </option>
	<?php
		while ($ta= $select_ta->fetch_assoc()) {
			echo "<option value='".$ta['student_id'].",".$ta['name']."'>".$ta['name']."</option>";
		}
	?>
	</select>
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<br><br>
	<div>
		<input type="submit" name = "assign_ta" value = "Assign TA to Section">
	</div>
</form>

<?php
	if (isset($_POST['assign_ta']) && $_POST['assign_ta']) {
		$section = $_POST["select_section"];
		list($cid, $seid) = explode(',', $section);
		$chosen_ta = $_POST["select_ta"];
		list($stid, $name) = explode(',', $chosen_ta);
		$q1 = "SELECT student_id FROM ta WHERE course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year'";
		$check = $mysqli->query($q1);
		if ($check->num_rows != 0) {
			echo "Error: section $seid for course $cid already has ta $name";
		} else {
			echo "Chose PhD student $name for course $cid, section $seid";
			$query = "INSERT INTO ta VALUES('$stid','$cid','$seid','$current_semester','$current_year')";
			$mysqli->query($query);
		}
	}
?>
</body>
</html>
