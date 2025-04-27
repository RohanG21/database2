<?php
	include 'config.php';	
	$ins_id = $_POST['inid'];
	
	// Sec. taught by instructor before Spring 2025 with the names + grades of students who took them
	$query = "SELECT St.name, T.grade, T.section_id, T.course_id, T.semester, T.year FROM student St, take T, section Se 
			WHERE St.student_id = T.student_id AND T.section_id = Se.section_id 
			AND T.course_id = Se.course_id
			AND T.semester = Se.semester AND T.year = Se.year
			AND Se.instructor_id = $ins_id AND ((Se.year <> '$current_year') OR (Se.semester <> '$current_semester'))
			ORDER BY T.year";
	
	$result = $mysqli->query($query);
	$courses = array();
	while($row = $result->fetch_assoc()) {
		$courses[] = $row;
	}
	echo json_encode($courses);
	
?>
