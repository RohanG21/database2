<?php 
session_start();

?>
<?php
include "adminfunctions.php";
include "config.php";

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

$year = getCurYear();
echo "semester: ,$semesterstr";
echo "year: ",$year;

$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"db2");
$query = mysqli_query($db_connection, "SELECT * FROM section WHERE section.semester = '".$semesterstr."' AND section.year = $year AND section.course_id = '".$courseid."'");
#echo "<table border = 1px solid black>";
#echo "<th> section_id </th>";
#echo "<th>instructor_id </th>";
#echo "<th> classroom_id </th>";
#echo "<th> start time </th>";
#echo "<th> end time </th>";
echo "<form action  = 'selectuggrader.php'>";
echo "<select name = 'selectsection' method = 'post'>";
$sectionrowtrue = 0;
while($row = mysqli_fetch_array($query)){
    #$timeslot = $row['time_slot_id'];
    #$timestart = mysqli_query($db_connection,"SELECT start_time,end_time  FROM time_slot WHERE time_slot_id = '".$timeslot."'" );
    #$timerow = mysqli_fetch_array($timestart);
    echo "<option>$row[section_id]</option>";
	$sectionrowtrue = 1;
}
echo "</select>";
#echo "<p>";
#echo "<button type = 'submit'> select grader </button>";
#echo "</p>";
if ($sectionrowtrue == 1) {
	echo "<p>";
	echo "<button type = 'submit'> select grader </button>";
	echo "</p>";
}
echo "</form>";

echo "<form action = 'addsectionuggrader.php' method = 'post'>";
echo "<p align = right>";
echo "<input type = 'hidden' name = 'email' value='admin@uml.edu'>";
echo "<input type = 'hidden' name = 'password' value='123456'>";
echo "<button type = 'submit'> course page </button>";
echo "</p>";
echo "</form>";

    #echo "<td>$row[instructor_id]</td>";
    #echo "<td> $row[classroom_id] </td>";
    #echo "<td> $timerow[start_time] </td>";
    #echo "<td> $timerow[end_time] </td>";
    #echo "<form "
    #echo "<button> select grader </button>";
    #echo "</tr>";

#echo "</table>";

?>