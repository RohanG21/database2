<?php
	include 'config.php';	
	$ins_id = $_POST['inid'];
	
	// Sec. currently being taught by instructor with only the names of current enrolled students
		$query = "SELECT S.name, T.section_id, T.course_id, T.semester, T.year, T.grade FROM student S, take T, section Se
			WHERE S.student_id = T.student_id AND T.course_id = Se.course_id 
			AND T.section_id = Se.section_id AND T.semester = Se.semester
			AND T.year = Se.year AND T.year = '$current_year'
			AND T.semester = '$current_semester' AND Se.instructor_id = $ins_id
			ORDER BY T.year";
	
	$result = $mysqli->query($query);
	$courses = array();
	while($row = $result->fetch_assoc()) {
		$courses[] = $row;
	}
	echo json_encode($courses);
	
?>
