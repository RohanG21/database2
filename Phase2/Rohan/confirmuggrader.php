<?php
session_start();
include "conFunc.php";
?>

<?php
$course = $_SESSION['selectedgradercourse'];
$section = $_SESSION['selectedgradersection'];
$grader = $_SESSION['selectedundergradgrader'];
$graderid = $_SESSION['selectedgraderid'];
$year = $_SESSION['year'];
$semester = $_SESSION['semester'];

#echo $year;
#echo "<br>";
#echo $semester;
#echo "<br>";
#echo $graderid;
#echo "<br>";
#echo $course;
#echo "<br>";
#echo $section; 
#echo "<br>";

?>

<?php
$conn = openConnection();
mysqli_select_db($conn,"collegesystem");
$query = "INSERT INTO undergraduategrader VALUES('$graderid','$course','$section','$semester',$year)";
mysqli_query($conn,$query);
echo "undergraduate grader $grader has been assigned to $course $section for $semester $year";
echo "<br>";
echo "<form action = 'index.php'>";
echo "<button type = 'submit'> Admin Homepage </button>";
echo "</form>";
?>