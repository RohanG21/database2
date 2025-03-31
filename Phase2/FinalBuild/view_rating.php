<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include 'config.php';
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

<!DOCTYPE HTML>
<html>
<head>
<title> View Professor Rating</title>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
</head>
<body>
<center>
<h1>View Professor Rating</h1>
<p><a href="admin.php">Return to Admin Page</a>.</p>
</center>
<p><b>Select Professor and Course</b></p>
</center>
<?php
	$query = "SELECT distinct instructor_name, instructor.instructor_id, section.course_id, section.section_id, section.semester, section.year FROM instructor, section, rating where instructor.instructor_id = section.instructor_id and section.section_id = rating.section_id and section.course_id = rating.course_id and instructor.instructor_id = rating.instructor_id";
	$result = $mysqli->query($query);
?>


<form action = "" method ="POST">
	<select name="professor" id="professor" required>
		<option disabled selected value> -- select a professor -- </option>
	<?php
		while ($professors= $result->fetch_assoc()) {
			echo "<option value='".$professors['course_id'].",".$professors['section_id'].",".$professors['instructor_id'].",".$professors['instructor_name'].",".$professors['semester'].",".$professors['year']."'>".$professors['instructor_name']." (".$professors['course_id']." ".$professors['section_id'].", ".$professors['semester']." ".$professors['year'].")</option>";
		}
	?>
	</select>
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<br><br>
	<div>
		<input type="submit" name = "show_rating" value = "Show Ratings">
	</div>
	<br>
</form>

<?php
	if (isset($_POST['show_rating']) && $_POST['show_rating']) {
		$professor = $_POST["professor"];
		list($cid, $seid, $pid, $name, $chosen_semester, $chosen_year) = explode(',', $professor);
		echo "<p><b>Showing ratings for $name for $cid, $seid, $chosen_semester $chosen_year</b></p>";
		echo "<table><tr><th>Student Name</th><th>Week</th><th>Score</th></tr>";
		$search = "SELECT name, week, score FROM student, rating where student.student_id = rating.student_id and instructor_id = '$pid' and course_id = '$cid' and section_id = '$seid' and semester = '$chosen_semester' and year = '$chosen_year'";
		$result = $mysqli->query($search);
		while ($row = $result->fetch_assoc()) {
			$student_name = $row['name'];
			$week = $row['week'];
			$score = $row['score'];
			echo "<tr><td>$student_name</td><td>$week</td><td>$score</td></tr>";
		}
		echo "</table>";

		
	}
?>
