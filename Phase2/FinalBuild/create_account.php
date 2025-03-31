<?php
	include 'config.php';
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
				echo "Error: an account for the student already exists";
				$url = "links.html";
				echo "<br><a href='$url'>Try Again</a>";
			}
			else {
				$add = "INSERT INTO account (email, password, type) VALUES ('$email', '$password','$type')";
				$mysqli->query($add);
				$update = "UPDATE student SET email = '$email' WHERE student_id = '$sid' and name = '$name'";
				$mysqli->query($update);
				echo "Account created successfully!";
				$url = "index.html";
				echo "<br><a href='$url'>Return to Login Page</a>";

			}
			
		} 
	} else {
			echo "Error: Invalid Credentials";
			$url = "links.html";
			echo "<br><a href='$url'>Try Again</a>";
	}
?>