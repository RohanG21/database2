<?php
session_start();
include "conFunc.php";
include "adminfunctions.php";
?>

<?php
$conn = openConnection();
mysqli_select_db($conn,'collegesystem');
if (!isset($_SESSION["course"])){
    exit;
}
$courseid = $_SESSION["course"];

if(!isset($_SESSION['selectedgradersection'])) {
    $_SESSION['selectedgradersection'] = $_GET['selectsection'];
}

$section = $_SESSION['selectedgradersection'];

echo "<h1> Eligible Graders </h1>";
echo "<h2> selected section: $section </h2>";

#echo "<table>";
#echo "<th> student_id </th>";
#echo "<th> student name </th>";
#echo "<th> grade in course </th>";
#echo "<th> current year </th>";

getEligibleGraders($conn,$courseid);

echo "<form action = 'selectsectionforuggrader.php'>";
echo "<button type = 'submit'> select a different section </button>";
echo "</form>";

echo "<br>";
echo "<form action =  'addsectionuggrader.php'>";
echo "<button type = 'submit'> select a different course </button>";




#$query = "SELECT student.name AS studentname FROM collegesystem.student JOIN collegesystem.take ON collegesystem.student.student_id = collegesystem.take.student_id WHERE course_id = '$courseid' AND (grade = 'A' OR grade = 'A-')";
#$result = mysqli_query($conn,$query);
#echo  "<select >";
#while($row = mysqli_fetch_array($result)) {
#    echo "<option> $row[studentname] </option>";
#}
?>