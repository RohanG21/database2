<?php 
session_start();

?>
<?php
include "adminfunctions.php";
include "config.php";

if(!isset($_SESSION["course"])){
    $_SESSION["course"] = $_GET['selectgradercourse'];
}
unset($_SESSION['selectedgradersection']);

$courseid = $_SESSION["course"];

$semester = getCurSemester();
$semesterstr = '';

if($semester = 'Spring') {
    $semesterstr = 'Spring';
}

else {
    $semesterstr = 'Fall';
}

$year = getCurYear();
echo "semester: ,$semesterstr";
echo "year: ",$year;

$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"db2");
$query = mysqli_query($db_connection, "SELECT * FROM section WHERE section.semester = '".$semesterstr."' AND section.year = $year AND section.course_id = '".$courseid."'");
echo "<form action  = 'selectuggrader.php'>";
echo "<select name = 'selectsection' method = 'post'>";
$sectionrowtrue = 0;
while($row = mysqli_fetch_array($query)){
    echo "<option>$row[section_id]</option>";
	$sectionrowtrue = 1;
}
echo "</select>";
if ($sectionrowtrue == 1) {
	echo "<p>";
	echo "<button type = 'submit'> select grader </button>";
	echo "</p>";
}
echo "</form>";

echo "<form action = 'addsectionuggrader.php' method = 'post'>";
echo "<p align = right>";
echo "<input type = 'hidden' name = 'email' value='admin@uml.edu'>";
echo "<input type = 'hidden' name = 'password' value='123456'>";
echo "<button type = 'submit'> course page </button>";
echo "</p>";
echo "</form>";
?>