<?php
session_start();
include "conFunc.php";
include "adminfunctions.php";
?>

<?php
$conn = openConnection();
echo "<br>";
mysqli_select_db($conn,'db2');

$courseid = $_SESSION["course"];

if(!isset($_SESSION['selectedgradersection'])) {
    $section = $_GET['selectsection'];
    $_SESSION['selectedgradersection'] = $section;
}
$section = $_SESSION['selectedgradersection'];

unset($_SESSION['selectedundergradgrader']);
unset($_SESSION['selectedgraderid']);
unset($_SESSION['selectedmastergrader']);

echo "<h1> selected course: $courseid </h1>";
echo "<br>";
echo "<h2> selected section: $section </h2>";
echo "<br>";
echo "<h2> Eligible Graders </h2>";


getEligibleGraders($conn,$courseid);
getEligibleMasterGraders($conn,$courseid);

echo "<br>";

echo "<form action = 'selectsectionforgrader.php'>";
echo "<button type = 'submit'> section page </button>";
echo "</form>";

echo "<form action =  'addsectiongrader.php'>";
echo "<button type = 'submit'> course page </button>";
echo "</form>";

echo "<form action = 'admin.php'>";
echo "<button type = 'submit'> Admin Homepage </button>"; 
echo "</form>"; 
?>