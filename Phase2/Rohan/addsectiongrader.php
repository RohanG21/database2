
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
unset($_SESSION["selectgradersection"]);

#echo "Today is " ,$monthint; 
echo "<br>";
echo "the current semester is ",$semester;
echo "year: $year";
$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"collegesystem");

$query = mysqli_query($db_connection,"SELECT * FROM course");
$courserowtrue = 0;
echo "<br>";
echo "<form action = 'selectsectionforgrader.php'>";
echo "<label for = 'selectgradercourse'> select a course: </label> <br> " ;
echo "<select method = 'post' name='selectgradercourse'>";
while($row = mysqli_fetch_array($query)){
    echo "<option value = '".$row['course_id']. "'>" . $row['course_id'] ."</option>";
    $courserowtrue = 1;
}
echo "</select>";

if($courserowtrue == 1){
    echo "<br>";
    echo "<br>";
    echo "<button type = 'submit'>Submit </button>";
    echo "</form>";
}

if ($courserowtrue == 0){
    echo "<br>";
    echo "no courses to register a grader for in the current semester";
    echo "<br>";
}


echo "<form action = 'index.php'>";
echo "<button type = 'submit'> Admin Homepage </button>";
echo "</form>";
exit;

?>
