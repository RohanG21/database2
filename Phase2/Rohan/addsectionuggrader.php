
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
#echo "Today is " ,$monthint; 
echo "<br>";
echo "the current semester is ",$semester;
$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"collegesystem");

$query = mysqli_query($db_connection,"SELECT * FROM course");
echo "<br>";
echo "<form action = 'selectsectionforuggrader.php'>";
echo "<label for = 'selectgradercourse'> select a course: </label> <br> " ;
echo "<select method = 'post' name='selectgradercourse'>";
while($row = mysqli_fetch_array($query)){
    echo "<option value = '".$row['course_id']. "'>" . $row['course_id'] ."</option>";
}
echo "</select>";
echo "<button type = 'submit'>Submit </button>";
echo "</form>";

?>
