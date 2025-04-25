<?php
	include 'config.php';
	$sid = $_POST['sid'];
	$course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $query = "INSERT INTO take VALUES('$sid', '$course_id', '$section_id', '$current_semester','$current_year', NULL)";
	$result = $mysqli->query($query);
	if ($result == false) {
		$response["success"] = "false";
    	echo json_encode($response);
	} else {
    	$response["success"] = "true";
    	echo json_encode($response);
	}
?>