<?php
	include 'config.php';
	if (isset($_POST['login']) && $_POST['login']) {
		$email = $_POST["email"];
		$password = $_POST["password"];
		$find = "SELECT type FROM account where email = '$email' and password = '$password'";
		$result = $mysqli->query($find);
		if ($result->num_rows != 1) {
			echo "Error: invalid email or password";
			$url = "index.html";
			echo "<br><a href='$url'>Try Again</a>";
		} else {
			session_start();
			$_SESSION['email'] = $email;
			$_SESSION['password'] = $password;
			$row = $result->fetch_assoc();
			$type = $row["type"];
			switch ($type) {
			case "student":
				header("Location: student.php");
				break;
			case "admin":
				header("Location: admin.php");
				break;
			case "instructor":
				$find_id = "SELECT instructor_id FROM instructor WHERE email = '$email'";
				$result = $mysqli->query($find_id);
				$row = $result->fetch_assoc();
				$ins_id = $row["instructor_id"];
				$_SESSION['ins_id'] = $ins_id;
				header("Location: instructor.php");
				break;
			default:
				echo "Error: unrecognized account type";
				$url = "index.html";
				echo "<br><a href='$url'>Return to Login Menu</a>";
				break;
			}
		}
		
	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
	exit(0);
?>