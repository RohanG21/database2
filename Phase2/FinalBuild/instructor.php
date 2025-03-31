<?php
	include 'config.php';
	session_start();
	if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
		$email = $_SESSION['email'];
		$password = $_SESSION['password'];
		//session_unset();
		//session_destroy();
	} else if(isset($_POST['email']) && isset($_POST['password'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];
	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
	$find_id = "SELECT instructor_id FROM instructor WHERE email = '$email'";
	$result = $mysqli->query($find_id);
	$row = $result->fetch_assoc();
	$ins_id = $row["instructor_id"];
?>

<!DOCTYPE html>
<html>
<head>
<title> Instructor Access </title>
</head>
<body>
<center>
<h1> Instructor Homepage </h1>

<form method = "POST" action = "mark_absent.php">
<input type = "hidden" name = "email" value="<?php echo $email;?>">
<input type = "hidden" name = "password" value="<?php echo $password;?>">
<input type = "submit" value = "Take Attendance">

<details>
	<summary><h2>View taught section information</h2></summary>
	<details>
		<summary><h3>All taught sections</h3></summary>
		<?php
			$query = "SELECT * FROM section WHERE instructor_id = $ins_id ORDER BY year";
			$result = $mysqli->query($query);
			// Displays all sections being taught or previously taught
			if ($result->num_rows > 0) {
				echo "<table border = '1' cellpadding = '4' cellspacing = '0'>";
				echo	"<tr><th>Course ID</th>
						<th>Section ID</th>
						<th>Semester</th>
						<th>Year</th>
						<th>Instructor ID</th>
						</tr>";
	
			while($row = $result->fetch_assoc()) {
				$course_id = $row['course_id'];
				$section_id = $row['section_id'];
				$semester = $row['semester'];
				$year = $row['year'];
				$inst_id = $row['instructor_id'];
				echo "<tr>";
				echo "<td>$course_id</td>";
				echo "<td>$section_id</td>";
				echo "<td>$semester</td>";
				echo "<td>$year</td>";
				echo "<td>$inst_id</td>";
				echo "</tr>";
			}
			echo "</table>";
			} else {
				echo "<p>No sections found.</p>";
			}
		?>
	</details>
	<details>
		<summary><h3>Sections taught before Spring 2025 with names + grades of students</h3></summary>
		<?php
	
		// Displays sections taught by instructor before Spring 2025 with the names + grades of students who took them
		$query = "SELECT St.name, T.grade, T.section_id, T.course_id, T.semester, T.year FROM student St, take T, section Se 
			WHERE St.student_id = T.student_id AND T.section_id = Se.section_id 
			AND T.course_id = Se.course_id
			AND T.semester = Se.semester AND T.year = Se.year
			AND Se.instructor_id = $ins_id AND ((Se.year <> $current_year) OR (Se.semester <> '$current_semester'))
			ORDER BY T.year";
		$result = $mysqli->query($query);
		if ($result->num_rows > 0) {
			echo "<table border = '1' cellpadding = '4' cellspacing = '0'>";
			echo	"<tr><th>Course ID</th>
					<th>Section ID</th>
					<th>Semester</th>
					<th>Year</th>
					<th>Student name</th>
					<th>Grade</th>
					</tr>";
		
			while ($row = $result->fetch_assoc()) {
				$course_id = $row['course_id'];
				$section_id = $row['section_id'];
				$semester = $row['semester'];
				$year = $row['year'];
				$stu_name = $row['name'];
				$stu_grade = $row['grade'];
				echo "<tr>";
				echo "<td>$course_id</td>";
				echo "<td>$section_id</td>";
				echo "<td>$semester</td>";
				echo "<td>$year</td>";
				echo "<td>$stu_name</td>";
				echo "<td>$stu_grade</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "<p>No student names + grades found.</p>";
		}
		?>
	</details>
	<details>
		<summary><h3>Sections taught during the current semester with the names of students</h3></summary>
		<?php
	
		// Displays sections currently being taught by instructor with only the names of current enrolled students
		$query = "SELECT S.name, T.section_id, T.course_id, T.semester, T.year, T.grade FROM student S, take T, section Se
			WHERE S.student_id = T.student_id AND T.course_id = Se.course_id 
			AND T.section_id = Se.section_id AND T.semester = Se.semester
			AND T.year = Se.year AND T.year = $current_year
			AND T.semester = '$current_semester' AND Se.instructor_id = $ins_id
			ORDER BY T.year";
		$result = $mysqli->query($query);
		if ($result->num_rows > 0) {
			echo "<table border = '1' cellpadding = '4' cellspacing = '0'>";
			echo	"<tr><th>Course ID</th>
					<th>Section ID</th>
					<th>Semester</th>
					<th>Year</th>
					<th>Student name</th>
					//<th>Grade</th>
					</tr>";
		
			while ($row = $result->fetch_assoc()) {
				$course_id = $row['course_id'];
				$section_id = $row['section_id'];
				$semester = $row['semester'];
				$year = $row['year'];
				$stu_name = $row['name'];
				//$stu_grade = $row['grade'];
				echo "<tr>";
				echo "<td>$course_id</td>";
				echo "<td>$section_id</td>";
				echo "<td>$semester</td>";
				echo "<td>$year</td>";
				echo "<td>$stu_name</td>";
				//echo "<td>$stu_grade</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "<p>No student names found.</p>";
		}
		?>
	</details>
</details>

<p><a href="advisor_controls.php">Manage advisor appointments and advisee info</a>.</p>
<p><a href="index.html">Log out and return to the homepage</a>.</p>

</center>
</body>
</html>