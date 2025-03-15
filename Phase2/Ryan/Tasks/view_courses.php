<?php
	$current_year = "2025";
	$current_semester = "Spring";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include 'config.php';
		$sid = $_POST["sid"];
		$email = $_POST["email"];
		$password = $_POST["email"];
		session_start();
		$_SESSION['email'] = $email;
		$_SESSION['password'] = $password;

	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
?>
<!DOCTYPE html>
<html>
<head>
<title> View Courses</title>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
</head>
<body>
<center>
<h1>View Courses</h1>
</center>

<?php
	//change to be previous classes
	
	$query = "SELECT SUM(credits) as amount from course, take WHERE ((year < '$current_year') or (year = '$current_year' and semester <> 'Spring')) and course.course_id = take.course_id and student_id = '$sid' GROUP BY student_id";
	$credits = $mysqli->query($query);
	if ($credits->num_rows == 0) {
		$credits = 0;
	} else {
		$num_credits = $credits->fetch_assoc();
		$credits = $num_credits['amount'];
	}
	echo "Total Number of Credits Earned: $credits<br>";
	$gpa = "SELECT grade FROM take WHERE student_id = $sid";
	$query = $mysqli->query($gpa);
	$num_rows = $query->num_rows;
	$total_grade = 0.0;
	while ($row = $query->fetch_assoc()) {
		$letter_grade = $row['grade'];
		switch($letter_grade) {
			case 'A+':
				$total_grade += 4.0;
				break;
			case 'A':
				$total_grade += 4.0;
				break;
			case 'A-':
				$total_grade += 3.7;
				break;
			case 'B+':
				$total_grade += 3.3;
				break;
			case 'B':
				$total_grade += 3.0;
				break;
			case 'B-':
				$total_grade += 2.7;
				break;
			case 'C+':
				$total_grade += 2.3;
				break;
			case 'C':
				$total_grade += 2.0;
				break;
			case 'C-':
				$total_grade += 1.7;
				break;
			case 'D+':
				$total_grade += 1.3;
				break;
			case 'D':
				$total_grade += 1.0;
				break;
			case 'F':
				$total_grade += 0.0;
				break;
			default:
				$total_grade += 0.0;
				break;
		}
	}
	if ($num_rows != 0) {
		$gpa = $total_grade / $num_rows;
		echo "Cumulative GPA: $gpa<br>";
	} else {
		echo "Student Hasn't Taken Any Courses";
	}
?>
<p><b>Select Course Year</b></p>
<form method = "POST" action="">
	<select multiple="multiple" name="year[]">
		<option selected value="All">Select All</option>
		<option value="2020">2020</option>
		<option value="2021">2021</option>
		<option value="2022">2022</option>
		<option value="2023">2023</option>
		<option value="2024">2024</option>
		<option value="2025">2025</option>
	</select>
	
	<div>
		<label for="semester">Choose Semester:</label>
        	<label for="Fall">Fall</label>
        	<input type ="radio" id="Fall" name="semester" value="Fall" required>

       		<label for="Spring">Spring</label>
        	<input type="radio" id="Spring" name="semester" value="Spring">

        	<label for="All">All</label>
        	<input type="radio" id="All" name="semester" value="All">
    	</div>

	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<input type = "hidden" name = "sid" value="<?php echo $sid;?>">

	<br>
	<div>
		<input type="submit" name="show_courses" value="Show Courses">
	</div>
</form>
<?php
	if (isset($_POST['show_courses']) && $_POST['show_courses']) {
		$semester = $_POST["semester"];
		echo "<table><tr><th>Course</th><th>Semester</th><th>Year</th><th>Grade</th></tr>";
		if ($_POST['year'][0] == "All") {
			if ($semester == "All") {
				$search = "SELECT * FROM take WHERE student_id = '$sid'";
				$result = $mysqli->query($search);
				while ($row = $result->fetch_assoc()) {
					$course = $row['course_id'];
					$course_year = $row['year'];
					$course_semester = $row['semester'];
					$course_grade = $row['grade'];
					echo "<tr><td>$course</td><td>$course_semester</td><td>$course_year</td><td>$course_grade</td></tr>";
				}
			} else {
				$search = "SELECT * FROM take where student_id = '$sid' and semester = '$semester'";
				$result = $mysqli->query($search);
				while ($row = $result->fetch_assoc()) {
					$course = $row['course_id'];
					$course_year = $row['year'];
					$course_semester = $row['semester'];
					$course_grade = $row['grade'];
					echo "<tr><td>$course</td><td>$course_semester</td><td>$course_year</td><td>$course_grade</td></tr>";				
				}
			}
		} else {
			if ($semester == "All") {
				foreach ($_POST['year'] as $year) {
					$search = "SELECT * FROM take where student_id = '$sid' and year = '$year'";
					$result = $mysqli->query($search);
					while ($row = $result->fetch_assoc()) {
						$course = $row['course_id'];
						$course_year = $row['year'];
						$course_semester = $row['semester'];
						$course_grade = $row['grade'];
						echo "<tr><td>$course</td><td>$course_semester</td><td>$course_year</td><td>$course_grade</td></tr>";	
					}
				}
			} else {
				foreach ($_POST['year'] as $year) {
					$search = "SELECT * FROM take where student_id = '$sid' and year = '$year' and semester = '$semester'";
					$result = $mysqli->query($search);
					while ($row = $result->fetch_assoc()) {
						$course = $row['course_id'];
						$course_year = $row['year'];
						$course_semester = $row['semester'];
						$course_grade = $row['grade'];
						echo "<tr><td>$course</td><td>$course_semester</td><td>$course_year</td><td>$course_grade</td></tr>";	
					}
				}
			}
		}
		echo "</table>";
	}
?>

</body>
</html>
<p><a href="student.php">Return to Student Page</a>.</p>