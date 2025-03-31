<?php
session_start();
include "config.php";
?>

<?php
$course = $_SESSION["course"];
$section = $_SESSION['selectedgradersection'];
$grader = $_SESSION['selectedundergradgrader'];
$graderid = $_SESSION['selectedgraderid'];
$year = $_SESSION['year'];
$semester = $_SESSION['semester'];

$query = "Select name from student where student_id = '$graderid'";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$grader = $row['name'];

?>

<?php
$conn = $mysqli;
mysqli_select_db($conn,"db2");
$searchgraderprevquery = "SELECT * FROM undergraduategrader WHERE student_id = '$graderid'";
$searchgraderprevresult = mysqli_query($conn,$searchgraderprevquery);
$row = mysqli_fetch_array($searchgraderprevresult);
if ($row != NULL){
    $prevcourseid = $row['course_id'];
    $prevsectionid = $row['section_id'];
    $prevsemester = $row['semester'];
    $prevyear = $row['year'];
    echo "<br>";
    echo "the selected undergraduate student is already grading $prevcourseid $prevsectionid $prevsemester $prevyear";
    echo "<br>";
    echo "<form action = 'admin.php'>";
    echo "<button type = 'submit'> Admin Homepage </button>";
    echo "</form>";
    echo "<br>";
    echo "<form action = 'selectuggrader.php'>";
    echo "<button type = 'submit'>  select a different grader </button>";
    echo "</form>";
    exit;
} else {
    $searchprevgraderquery = "SELECT * FROM undergraduategrader WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester' AND year = '$year'";
    $searchprevgraderqueryresult = mysqli_query($conn,$searchprevgraderquery);
    $prevgradertrue = 0;
    while($row = mysqli_fetch_array($searchprevgraderqueryresult)){
        $prevgraderid = $row['student_id'];
        $getprevgradernamequery = "SELECT name FROM student WHERE student_id = $prevgraderid";
        $getprevgradernamequeryresult = mysqli_query($conn,$getprevgradernamequery);
        $prevgradernamerow = mysqli_fetch_array($getprevgradernamequeryresult);
        $prevgradertrue = 1;
    }

$query = "Select distinct name, student.student_id from student, mastergrader where course_id = '$course' and section_id = '$section' and semester = '$semester' and year = '$year' and student.student_id = mastergrader.student_id";
$result = $mysqli->query($query);
if ($result->num_rows == 0)
	$prevmastergrader = 0;
else {
	$prevmastergrader = 1;
	$row = $result->fetch_assoc();
	$prevgrader = $row['name'];
}
$query = "Select student.student_id from student, undergraduate where student.student_id = '$graderid' and student.student_id = undergraduate.student_id";
$result = $mysqli->query($query);

if ($result->num_rows == 0) {
	if ($prevmastergrader == 1) {
		$query = "UPDATE mastergrader SET student_id = $graderid WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester' AND year = '$year'";
		mysqli_query($conn, $query);
		echo "<br>";
		echo "updated $course $section grader from $prevgrader to $grader";
	} else if ($prevgradertrue == 1) {
		$query = "Delete from undergraduategrader where course_id = '$course' and section_id = '$section' and semester = '$semester' and year = '$year'";
		mysqli_query($conn, $query);
		$query = "INSERT INTO mastergrader VALUES('$graderid','$course','$section','$semester','$year')";
		mysqli_query($conn, $query);
		echo "updated $course $section grader from $prevgradernamerow[name] to $grader";
	} else {
		$query = "INSERT INTO mastergrader VALUES('$graderid','$course','$section','$semester','$year')";
		mysqli_query($conn, $query);
		echo "master grader $grader has been assigned to $course $section for $semester $year";
	}
} else {
	if ($prevgradertrue == 1) {
		$query = "UPDATE undergraduategrader SET student_id = $graderid WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester' AND year = '$year'";
		mysqli_query($conn, $query);
		echo "<br>";
		echo "updated $course $section grader from $prevgradernamerow[name] to $grader";
	} else if ($prevmastergrader == 1) {
		$query = "Delete from mastergrader where course_id = '$course' and section_id = '$section' and semester = '$semester' and year = '$year'";
		mysqli_query($conn, $query);
		$query = "INSERT INTO undergraduategrader VALUES('$graderid','$course','$section','$semester','$year')";
		mysqli_query($conn, $query);
		echo "updated $course $section grader from $prevgrader to $grader";
	} else {
		$query = "INSERT INTO undergraduategrader VALUES('$graderid','$course','$section','$semester','$year')";
		mysqli_query($conn, $query);
		echo "undergraduate grader $grader has been assigned to $course $section for $semester $year";
	}
}



echo "<br>";
echo "<form action = 'admin.php'>";
echo "<input type = 'hidden' name = 'email' value='admin@uml.edu'>";
echo "<input type = 'hidden' name = 'password' value='123456'>";
echo "<button type = 'submit'> Admin Homepage </button>";
echo "</form>";
}
?>