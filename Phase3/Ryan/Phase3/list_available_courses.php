<?php
	// For convenience, I decided to make it so that only the valid courses and sections will be displayed
	include 'config.php';
	$sid = $_POST['sid'];

	// Check the course has under 15 enrolled
	$cond1 = "(SELECT COUNT(distinct student_id) FROM take WHERE course_id = s1.course_id and
	 section_id = s1.section_id and year = '$current_year' and semester = '$current_semester')";
	
	// Check the course doesn't have a prereq (1 = no prereq, 0 = has a prereq)
	$cond2 = "(SELECT COUNT(DISTINCT course_id) from course where course_id = c1.course_id and course_id not in (select course_id from prereq))";

	// If course does have a prereq, check the student took it
	$cond3 = "(SELECT COUNT(DISTINCT student_id) from take, prereq where student_id = '$sid' and take.course_id = prereq.prereq_id and
	 ((take.year < '$current_year') or (take.year = '$current_year' and take.semester <> '$current_semester')) and prereq.course_id = c1.course_id)";

	// Check student hasn't already taken course
	$cond4 = "(SELECT COUNT(DISTINCT student_id) from take where student_id = '$sid' and take.course_id = c1.course_id)";

	$query = "SELECT c1.course_id, s1.section_id
     FROM course c1, section s1
     WHERE c1.course_id = s1.course_id
     and year = '$current_year' and
	 semester = '$current_semester' 
     and $cond1 < 15 and
     ($cond2 = 1 or $cond3 = 1)
     and $cond4 = 0";

	$result = $mysqli->query($query);
	$courses = array();
	while($row = $result->fetch_assoc()) {
		$courses[] = $row;
	}
	echo json_encode($courses);
?>