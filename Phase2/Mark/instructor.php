<?php
	include 'config.php';
	session_start();
	
	$current_semester = "Spring";
	$current_year = 2025;
	#echo $_SESSION['email'];
	$email = $_SESSION['email'];
	#echo $_SESSION['password'];
	$password = $_SESSION['password'];
	#echo $_SESSION['ins_id'];
	$ins_id = $_SESSION['ins_id'];
	
#1.Instructors can see all course sections they have taught

#2.Instructors can see the names of the students and grades that are in the sections mentioned in 1. 

#3. Instructors can see course sections they are teaching

#4. Instructors can see the student names and their grades in the sections mentioned in 3. 
	
?>

<!DOCTYPE html>
<html>
<head>
<title> Instructor Access </title>
</head>
<body>
<center>
<h1> Instructor </h1>
<h2>  </h2>
<?php
	$query = "SELECT * FROM section WHERE instructor_id = $ins_id";
	$result = $mysqli->query($query);
	# Displays all sections being taught or previously taught
	if ($result->num_rows > 0) {
		echo "<h2>All taught sections:</h2>";
		echo "<table>";
		echo "<tr>
				<th>Course ID</th>
				<th>Section ID</th>
				<th>Semester</th>
				<th>Year</th>
				<th>Instructor ID</th>
				<th>Classroom ID</th>
				<th>Timeslot ID</th>";
	
	while($row = $result->fetch_assoc()) {
		$course_id = $row['course_id'];
		$section_id = $row['section_id'];
		$semester = $row['semester'];
		$year = $row['year'];
		$inst_id = $row['instructor_id'];
		$room_id = $row['classroom_id'];
		$timeslot = $row['time_slot_id'];
		echo "<tr>";
		echo "<td>$course_id</td>";
		echo "<td>$section_id</td>";
		echo "<td>$semester</td>";
		echo "<td>$year</td>";
		echo "<td>$inst_id</td>";
		echo "<td>$room_id</td>";
		echo "<td>$timeslot</td>";
	}
	echo "</table>";
	} else {
		echo "<p>No sections found.</p>";
	}
	
	
	# Displays sections taught by instructor before Spring 2025 with the names + grades of students who took them
	$query = "SELECT St.name, T.grade, T.section_id, T.course_id FROM student St, take T, section Se 
		WHERE St.student_id = T.student_id AND T.section_id = Se.section_id 
		AND T.course_id = Se.course_id
		AND T.semester = Se.semester AND T.year = Se.year
		AND Se.instructor_id = $ins_id AND ((Se.year <> $current_year) OR (Se.semester <> '$current_semester'))";
	$result = $mysqli->query($query);
	if ($result->num_rows > 0) {
		echo "<h2>Sections taught before Spring 2025 with names + grades of students:</h2>";
		echo "<table>";
		echo "<tr>
				<th>Course ID</th>
				<th>Section ID</th>
				<th>Student name</th>
				<th>Student grade</th>";
		
		while ($row = $result->fetch_assoc()) {
			$course_id = $row['course_id'];
			$section_id = $row['section_id'];
			$stu_name = $row['name'];
			$stu_grade = $row['grade'];
			echo "<tr>";
			echo "<td>$course_id</td>";
			echo "<td>$section_id</td>";
			echo "<td>$stu_name</td>";
			echo "<td>$stu_grade</td>";
		}
		echo "</table>";
	} else {
		echo "<p>No student names + grades found.</p>";
	}
	
	# Displays sections currently being taught by instructor with only the names of current enrolled students
	$query = "SELECT S.name, T.section_id, T.course_id FROM student S, take T, section Se
		WHERE S.student_id = T.student_id AND T.course_id = Se.course_id 
		AND T.section_id = Se.section_id AND T.semester = Se.semester
		AND T.year = Se.year AND T.year = $current_year
		AND T.semester = '$current_semester' AND Se.instructor_id = $ins_id";
	$result = $mysqli->query($query);
	if ($result->num_rows > 0) {
		echo "<h2>Sections taught during the current semester with the names of students:</h2>";
		echo "<table>";
		echo "<tr>
				<th>Course ID</th>
				<th>Section ID</th>
				<th>Student name</th>";
		
		while ($row = $result->fetch_assoc()) {
			$course_id = $row['course_id'];
			$section_id = $row['section_id'];
			$stu_name = $row['name'];
			echo "<tr>";
			echo "<td>$course_id</td>";
			echo "<td>$section_id</td>";
			echo "<td>$stu_name</td>";
		}
		echo "</table>";
	} else {
		echo "<p>No student names + grades found.</p>";
	}
?>

</center>
</body>
</html>