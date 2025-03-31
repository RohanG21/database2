<?php
session_start();
include "config.php";
include "adminfunctions.php";
?>

<?php
$conn = $mysqli;
mysqli_select_db($conn,'db2');
$courseid = $_SESSION["course"];

$section = $_GET['selectsection'];

if(!isset($_SESSION['selectedgradersection'])) {
    $_SESSION['selectedgradersection'] = $section;
}

unset($_SESSION['selectedundergradgrader']);
unset($_SESSION['selectedgraderid']);

echo "<h1> Eligible Graders </h1>";
echo "<h2> selected section: $section</h2>";

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

?>