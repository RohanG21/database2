
<?php 
session_start()
?>

<?php 
$month = date("m");
$monthint = (int)$month;
$semester = '';
if ($monthint <= 5) {
    $semester = 'Spring';
}
else {
    $semester = 'Fall';
}
$year = date("Y");

$_SESSION["semester"] = $semester;
$_SESSION["year"] = $year;
unset($_SESSION["course"]);

echo "<h1> Course List </h1>";
echo "<br>";

$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"db2");

$query = mysqli_query($db_connection,"SELECT * FROM course");
echo "<br>";
echo "<form action = 'sectionpage.php'>";
echo "<label for = 'course'> course: </label> <br> " ;
echo "<select method = 'post' name='course'>";
while($row = mysqli_fetch_array($query)){
    echo "<option value = '".$row['course_id']. "'>" . $row['course_id'] ."</option>";
}
echo "</select>";
echo "<button type = 'submit'>Submit </button>";
echo "</form>";

echo "<form action = 'admin.php'>";
echo "<button type = 'submit'>Admin Homepage </button>";
echo "</form>";

?>