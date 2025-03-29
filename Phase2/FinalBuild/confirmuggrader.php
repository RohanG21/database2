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
$conn = $mysqli;
mysqli_select_db($conn,"db2");
$searchprevgraderquery = "SELECT * FROM undergraduategrader WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester' AND year = '$year'";
$searchprevgraderqueryresult = mysqli_query($conn,$searchprevgraderquery);
while($row = mysqli_fetch_array($searchprevgraderqueryresult)){
    $prevgraderid = $row['student_id'];
    $getprevgradernamequery = "SELECT name FROM student WHERE student_id = $prevgraderid";
    $getprevgradernamequeryresult = mysqli_query($conn,$getprevgradernamequery);
    $prevgradernamerow = mysqli_fetch_array($getprevgradernamequeryresult);
    
    $deleteprevgraderquery = "DELETE FROM undergraduategrader WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester'AND year = '$year'";
    mysqli_query($conn,$deleteprevgraderquery);
    echo "<br>";
    echo "deleted undergraduate grader $prevgradernamerow[name] and replacing them with $grader"; 
}

$query = "INSERT INTO undergraduategrader VALUES('$graderid','$course','$section','$semester',$year)";
mysqli_query($conn,$query);
echo "<br>";
echo "undergraduate grader $grader has been assigned to $course $section for $semester $year";
echo "<br>";
echo "<form action = 'admin.php'>";
echo "<input type = 'hidden' name = 'email' value='admin@uml.edu'>";
echo "<input type = 'hidden' name = 'password' value='123456'>";
echo "<button type = 'submit'> Admin Homepage </button>";
echo "</form>";
?>