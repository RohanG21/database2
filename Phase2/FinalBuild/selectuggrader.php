<?php
session_start();
include "config.php";
include "adminfunctions.php";
?>

<?php
$conn = $mysqli;
mysqli_select_db($conn,'db2');
#if (!isset($_SESSION["course"])){
 #   exit;
#}
$courseid = $_SESSION["course"];

#echo $_GET['selectsection'];
$section = $_GET['selectsection'];

if(!isset($_SESSION['selectedgradersection'])) {
    $_SESSION['selectedgradersection'] = $section;
}

#$section = $_SESSION['selectedgradersection'];

unset($_SESSION['selectedundergradgrader']);
unset($_SESSION['selectedgraderid']);

echo "<h1> Eligible Graders </h1>";
echo "<h2> selected section: $section</h2>";

#echo "<table>";
#echo "<th> student_id </th>";
#echo "<th> student name </th>";
#echo "<th> grade in course </th>";
#echo "<th> current year </th>";

getEligibleGraders($conn,$courseid);

echo "<form action = 'selectsectionforuggrader.php' method = 'POST'>";
echo "<input type = 'hidden' name = 'email' value='admin@uml.edu'>";
echo "<input type = 'hidden' name = 'password' value='123456'>";
echo "<button type = 'submit'> section page </button>";
echo "</form>";

echo "<br>";
echo "<form action =  'addsectionuggrader.php' method = 'POST'>";
echo "<input type = 'hidden' name = 'email' value='admin@uml.edu'>";
echo "<input type = 'hidden' name = 'password' value='123456'>";
echo "<button type = 'submit'> course page </button>";




#$query = "SELECT student.name AS studentname FROM db2.student JOIN db2.take ON db2.student.student_id = db2.take.student_id WHERE #course_id = '$courseid' AND (grade = 'A' OR grade = 'A-')";
#$result = mysqli_query($conn,$query);
#echo  "<select >";
#while($row = mysqli_fetch_array($result)) {
#    echo "<option> $row[studentname] </option>";
#}
?>