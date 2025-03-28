<?php
session_start();
include "conFunc.php";

?>
<?php
    
    $conn = openConnection();
    echo "<br>";
    mysqli_select_db($conn,"collegesystem");
    $grader = $_GET['selectundergradgrader'];
    $_SESSION['selectedundergradgrader'] = $grader;
    #echo $grader;
    #echo $graderarr[0];
    #echo $graderarr[1];
    #$grader = 'Baylor Hinsey';

    #$grader = (string)$grader;
    $course = $_SESSION['selectedgradercourse'];
    $query = "SELECT student.student_id, grade, class_standing FROM student INNER JOIN undergraduate ON student.student_id = undergraduate.student_id INNER JOIN take ON undergraduate.student_id = take.student_id WHERE student.name = '$grader' AND take.course_id = '$course' ";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_array($result);
    #echo "selected grader: $row[student_id]";
    $_SESSION['selectedgraderid'] = $row['student_id'];
    echo "<h2>selected grader details: </h2>";
    echo "<br>";
    echo "<table border black 1px>";
    echo "<th> Name </th>";
    echo "<th> $course Grade </th>";
    echo "<th> Current Class Year </th>";
    echo "<tr>";
    echo "<td> $grader</td>";
    echo "<td> $row[grade]</td>";
    echo "<td> $row[class_standing]</td>";
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