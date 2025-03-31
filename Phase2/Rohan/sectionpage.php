<?php 
session_start();
if (!isset($_SESSION["course"])){
    $_SESSION["course"] = $_GET['course'];
}
?>

<?php
include "adminfunctions.php";
include "conFunc.php";

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
$_SESSION['year'] = $year;
$_SESSION['semester'] = $semesterstr;

$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"db2");
echo "<h2> Selected Course: $courseid </h2>";
echo "<br>";

$query = mysqli_query($db_connection, "SELECT * FROM section WHERE section.semester = '".$semesterstr."' AND section.year = $year AND section.course_id = '".$courseid."'");
echo "<table border = 1px solid black>";
echo "<th> section_id </th>";
echo "<th>instructor_id </th>";
echo "<th> classroom_id </th>";
echo "<th> start time </th>";
echo "<th> end time </th>";
while($row = mysqli_fetch_array($query)){
    $timeslot = $row['time_slot_id'];
    $timestart = mysqli_query($db_connection,"SELECT start_time,end_time  FROM time_slot WHERE time_slot_id = '".$timeslot."'" );
    $timerow = mysqli_fetch_array($timestart);
    echo "<tr>";
    echo "<td>$row[section_id]</td>";    
    echo "<td>$row[instructor_id]</td>";
    echo "<td> $row[classroom_id] </td>";
    echo "<td> $timerow[start_time] </td>";
    echo "<td> $timerow[end_time] </td>";
    echo "</tr>";
}

echo "</table>";

?>
<html>
    <form action = 'addsection.php'>
    <button type = 'submit' > add section </button>
    </form>

    <form action = 'sectionpage.php'>
        <button type = 'submit'> section page</button>
    </form>

    <form action = 'courselist.php'>
        <button type = 'submit'> course page </button>
    </form>

    <form action = 'admin.php'>
        <button type = 'submit'> Admin Homepage </button>";
    </form>

</html>
