<?php
	include 'config.php';
	$email = $_POST['email'];
	$password = $_POST['password'];
	$find = "SELECT type FROM account where email = '$email' and password = '$password'";
	$result = $mysqli->query($find);
	if ($result->num_rows != 1) {
		$response["success"] = "false";
    	echo json_encode($response);
	} else {
		$row = $result->fetch_assoc();
		$type = $row["type"];
		$response["email"] = $email;
    	$response["password"] = $password;
    	$response["type"] = $type;
		if ($type == 'student') {
			$query = "SELECT student_id, name FROM student, account WHERE account.email = student.email and account.email = '$email' and password = '$password'";
			$result = $mysqli->query($query);
			$row = $result -> fetch_assoc();
			$sid = $row["student_id"];
			$name = $row["name"];
			$response["name"] = $name;
			$response["sid"] = $sid;

		} else if ($type == 'instructor') {
			$query = "SELECT instructor_id, instructor_name FROM instructor, account WHERE account.email = instructor.email and account.email = '$email' and password = '$password";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			$inid = $row["instructor_id"];
			$name = $row["name"];
			$response["name"] = $name;
			$response["inid"] = $inid;
		}

    	$response["success"] = "true";
    	echo json_encode($response);
	}
?>