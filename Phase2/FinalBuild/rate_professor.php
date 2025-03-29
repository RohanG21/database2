<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include 'config.php';
		$sid = $_POST["sid"];
		$email = $_POST["email"];
		$password = $_POST["email"];
		session_start();
		$_SESSION['email'] = $email;
		$_SESSION['password'] = $password;

	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
?>
<!DOCTYPE html>
<html>
<head>
<title> View Courses</title>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
</head>
<body>
<center>
<h1>Rate Professor</h1>
</center>

<?php
	$query = "SELECT instructor_name, instructor.instructor_id, section.course_id, section.section_id from instructor, section, take where instructor.instructor_id = section.instructor_id and section.section_id = take.section_id and section.course_id = take.course_id and section.semester = take.semester and section.year = take.year and student_id = '$sid' and take.year = '$current_year' and take.semester = '$current_semester' and section.instructor_id IS NOT NULL";
	$result = $mysqli->query($query);
	$day = date('w');
	$week_of = date('Y-m-d', strtotime('-'.$day.' days'));
	echo "<p><b>Rate a professor for the week of $week_of</b></p>";
?>


<form action = "" method ="POST">
	<select name="professor" id="professor" required>
		<option disabled selected value> -- select a professor -- </option>
	<?php
		while ($professors= $result->fetch_assoc()) {
			echo "<option value='".$professors['course_id'].",".$professors['section_id'].",".$professors['instructor_id'].",".$professors['instructor_name']."'>".$professors['instructor_name']." (".$professors['course_id'].")</option>";
		}
	?>
	</select>

	<div>
 		<label for="score">Rating (between 0 and 10):</label>
		<input type="range" id = "score" name="score" value="5" min="0" max="10" oninput="this.nextElementSibling.value = this.value">
		<output>5</output>
	</div>
	<input type = "hidden" name = "sid" value="<?php echo $sid;?>">
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<br><br>
	<div>
		<input type="submit" name = "rate_professor" value = "Rate Professor">
	</div>
	<br>
</form>

<?php
	if (isset($_POST['rate_professor']) && $_POST['rate_professor']) {
		$score = $_POST["score"];
		$professor = $_POST["professor"];
		list($cid, $seid, $pid, $name) = explode(',', $professor);
		$query = "SELECT score FROM rating WHERE student_id = '$sid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year' and week = '$week_of'";
		$check = $mysqli->query($query);
		if ($check->num_rows == 0) {
			$add = "INSERT INTO rating VALUES('$sid', '$cid', '$seid', '$current_semester', '$current_year', '$pid', '$week_of', '$score')";
			$mysqli->query($add);
			echo "Success! Rated $name a $score for $cid, week of $week_of";
		} else {
			$old_score = $check->fetch_assoc();
			$old_score = $old_score["score"];
			$update = "UPDATE rating SET score = '$score' WHERE student_id = '$sid' and course_id = '$cid' and section_id = '$seid' and semester = '$current_semester' and year = '$current_year' and instructor_id = '$pid' and week = '$week_of'";
			$mysqli->query($update);
			echo "Success! Changed the rating of $name from $old_score to $score for $cid, week of $week_of";
		}
	}
?>

</body>
</html>
<p><a href="student.php">Return to Student Page</a>.</p>