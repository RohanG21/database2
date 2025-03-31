<?php
session_start();
include "conFunc.php";
include "adminfunctions.php";
?>

<?php
$conn = openConnection();
echo "<br>";
mysqli_select_db($conn,'collegesystem');

#if (!isset($_SESSION["course"])){
#    exit;
#}

$courseid = $_SESSION["course"];

if(!isset($_SESSION['selectedgradersection'])) {
    $section = $_GET['selectsection'];
    echo $section;
    $_SESSION['selectedgradersection'] = $section;
}
$section = $_SESSION['selectedgradersection'];

unset($_SESSION['selectedundergradgrader']);
unset($_SESSION['selectedgraderid']);
unset($_SESSION['selectedmastergrader']);

echo "<h1> Eligible Graders </h1>";
echo "<h2> selected section: $section</h2>";

#echo "<table>";
#echo "<th> student_id </th>";
#echo "<th> student name </th>";
#echo "<th> grade in course </th>";
#echo "<th> current year </th>";

getEligibleGraders($conn,$courseid);
getEligibleMasterGraders($conn,$courseid);

echo "<br>";

echo "<form action = 'selectsectionforgrader.php'>";
echo "<button type = 'submit'> section page </button>";
echo "</form>";

echo "<form action =  'addsectiongrader.php'>";
echo "<button type = 'submit'> course page </button>";
echo "</form>";

echo "<form action = 'index.php'>";
echo "<button type = 'submit'> Admin Homepage </button>"; 
echo "</form>"; 







#$query = "SELECT student.name AS studentname FROM collegesystem.student JOIN collegesystem.take ON collegesystem.student.student_id = collegesystem.take.student_id WHERE course_id = '$courseid' AND (grade = 'A' OR grade = 'A-')";
#$result = mysqli_query($conn,$query);
#echo  "<select >";
#while($row = mysqli_fetch_array($result)) {
#    echo "<option> $row[studentname] </option>";
#}
?>