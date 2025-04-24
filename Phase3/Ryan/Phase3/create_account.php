<?php
	include 'config.php';
	$sid = $_POST["student_id"];
	$name = $_POST["name"];
	$email = $_POST["email"];
	$password = $_POST["password"];
	$type = "student";
	$find = "SELECT student_id from student where student_id = '$sid' and name = '$name'";
	$result = $mysqli->query($find);
	if ($result->num_rows == 1) {
		$exists = "SELECT email FROM account WHERE email = '$email'";
		$result = $mysqli->query($exists);
		if ($result->num_rows > 0) {
			$response["success"] = "false";
    		echo json_encode($response);
		}
		else {
			$add = "INSERT INTO account (email, password, type) VALUES ('$email', '$password','$type')";
			$mysqli->query($add);
			$update = "UPDATE student SET email = '$email' WHERE student_id = '$sid' and name = '$name'";
			$mysqli->query($update);
			$response["success"] = "true";
    		echo json_encode($response);

		}
	} else {
		$response["success"] = "false";
    	echo json_encode($response);
	}
?>