<?php
	include 'config.php';	
	$ins_id = $_POST['inid'];
	
	// Displays all sections being taught or previously taught
	$query = "SELECT * FROM section WHERE instructor_id = $ins_id ORDER BY year";
	
	$result = $mysqli->query($query);
	$courses = array();
	while($row = $result->fetch_assoc()) {
		$courses[] = $row;
	}
	echo json_encode($courses);
	
?>
