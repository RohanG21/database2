<?php 
session_start();

?>

<?php
include "adminfunctions.php";
include "conFunc.php";

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

echo "<h1> Selected course: $courseid </h1>";

$year = getCurYear();
echo "<br>";
echo "select a section: ";
echo "<br>";

$conn = openConnection();
mysqli_select_db($conn,"db2");
$query = mysqli_query($conn, "SELECT * FROM section WHERE section.semester = '".$semesterstr."' AND section.year = $year AND section.course_id = '".$courseid."'");
echo "<form action  = 'selectgrader.php'>";
echo "<select method = 'post' name = 'selectsection'>";
$sectionrowtrue = 0;
while($row = mysqli_fetch_array($query)){
    echo "<option>$row[section_id]</option>";   
    $sectionrowtrue = 1; 
}
echo "</select>";
if ($sectionrowtrue == 1) {
    echo "<p>";
    echo "<button type = 'submit'> select a grader </button>"; 
    echo "</p>";
}

echo "</form>";

echo "<form action = 'addsectiongrader.php'>";
echo "<button type = 'submit'> course page </button>";
echo "</form>";

echo "<form action = 'admin.php'>";
echo "<button type = 'submit'> Admin Homepage </button>";
echo "</form>";

?>