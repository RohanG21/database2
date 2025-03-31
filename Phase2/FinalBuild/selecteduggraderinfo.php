<?php
session_start();
include "config.php";

?>
<?php
    
    $conn = $mysqli;
    echo "<br>";
    mysqli_select_db($conn,"db2");

    $_SESSION['selectedundergradgrader'] = $_GET['selectundergradgrader'];
    $graderstudentid = $_SESSION['selectedundergradgrader'];
#echo $graderstudentid;
	$query = "Select name from student where student_id = '$graderstudentid'";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$grader = $row['name'];

    #echo $grader;
    #echo $graderarr[0];
    #echo $graderarr[1];
    #$grader = 'Baylor Hinsey';

    #$grader = (string)$grader;
    $course = $_SESSION["course"];
	$query = "Select distinct student.student_id, grade, class_standing FROM student, take, undergraduate, master where student.student_id = '$graderstudentid' and take.student_id = student.student_id and take.course_id = '$course' and (student.student_id = undergraduate.student_id or student.student_id = master.student_id)";

    #$query = "SELECT student.student_id AS student_id, grade, class_standing FROM student INNER JOIN undergraduate ON student.student_id #= undergraduate.student_id INNER JOIN take ON undergraduate.student_id = take.student_id WHERE student.name = '$grader' AND #take.course_id = '$course' ";
    $result = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($result);
	$_SESSION['selectedgraderid'] = $row['student_id'];
	echo "selected student id: $_SESSION[selectedgraderid]";
$query = "Select student.student_id from student, undergraduate where student.student_id = '$graderstudentid' and student.student_id = undergraduate.student_id";
$result = $mysqli->query($query);
if ($result->num_rows == 0)
	$class_standing = "Master";
else
	$class_standing = $row['class_standing'];
	#echo "selected grader: $row[student_id]";
    echo "<h2>selected grader details: </h2>";
    echo "<br>";
    echo "<table border black 1px>";
    echo "<th> Name </th>";
    echo "<th> $course Grade </th>";
    echo "<th> Current Class Year </th>";
    echo "<tr>";
    echo "<td> $grader</td>";
    echo "<td> $row[grade]</td>";
    echo "<td> $class_standing</td>";
    echo "</tr>";
    echo "</table>";
    echo "<br>";
    
    
    echo "<form action = 'confirmuggrader.php' >";
    echo "<button> confirm grader </button>";
    echo "</form>";

    echo "<form action = 'selectuggrader.php'>";
    echo "<p align = right>";
    echo "<button type = 'submit'> select a different grader </button>";
    echo "</p>";
    echo "</form>";
?>