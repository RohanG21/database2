<?php 
session_start();
?>

<?php
include "adminfunctions.php";
include "conFunc.php";

$_SESSION['selectedgradercourse'] = $_GET['selectgradercourse'];
$courseid = $_GET['selectgradercourse'];
echo "<h2> SELECTED COURSE: $courseid </h2>";
echo "<br>";
$_SESSION['selectedgradercourse'] = $courseid;

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
$_SESSION['year'] = (int)$year;
$SESSION['semester'] = $semesterstr;
echo "<br>";

$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"collegesystem");
$query = mysqli_query($db_connection, "SELECT * FROM section WHERE section.semester = '".$semesterstr."' AND section.year = $year AND section.course_id = '".$courseid."'");
#echo "<table border = 1px solid black>";
#echo "<th> section_id </th>";
#echo "<th>instructor_id </th>";
#echo "<th> classroom_id </th>";
#echo "<th> start time </th>";
#echo "<th> end time </th>";
echo "<form action = 'selectgrader.php'>";
echo "<select name = 'selectsection' method = 'post'>";
while($row = mysqli_fetch_array($query)){
    $timeslot = $row['time_slot_id'];
    $timestart = mysqli_query($db_connection,"SELECT start_time,end_time  FROM time_slot WHERE time_slot_id = '".$timeslot."'" );
    $timerow = mysqli_fetch_array($timestart);
    echo "<option>$row[section_id]</option>";   
}
echo "</select>";
echo "<p align = left right>";
echo "<button type = 'submit'> select a grader </button>";
echo "</p>";
echo "</form>";

    #echo "<td>$row[instructor_id]</td>";
    #echo "<td> $row[classroom_id] </td>";
    #echo "<td> $timerow[start_time] </td>";
    #echo "<td> $timerow[end_time] </td>";
    #echo "<form "
    #echo "<button> select undergrad grader </button>";
    #echo "</tr>";
    #echo "</table>";

?>