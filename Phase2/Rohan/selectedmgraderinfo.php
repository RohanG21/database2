<?php
session_start();
include "conFunc.php";

?>
<?php
    
    $conn = openConnection();
    echo "<br>";
    mysqli_select_db($conn,"collegesystem");
    #unset($_SESSION['selectedgraderid']);
    #unset($_SESSION['selectedundergradgrader']);
    $_SESSION['selectedmastergrader'] = $_GET['selectmastergrader'];
    $grader = $_SESSION['selectedmastergrader'];

    #echo $grader;
    #echo $graderarr[0];
    #echo $graderarr[1];
    #$grader = 'Baylor Hinsey';

    #$grader = (string)$grader;
    $course = $_SESSION["course"];
    $query = "SELECT student.student_id AS student_id,grade,master.total_credits FROM student INNER JOIN master ON student.student_id = master.student_id  INNER JOIN take ON master.student_id = take.student_id WHERE student.name = '$grader' AND take.course_id = '$course' AND (take.grade = 'A' or take.grade = 'A-')";
    $result = mysqli_query($conn,$query);
    

    $row = mysqli_fetch_array($result);
    $_SESSION['selectedgraderid'] = $row['student_id'];
    echo "selected student id: $_SESSION[selectedgraderid]";
    #echo "selected grader: $row[student_id]";
    echo "<h2>selected grader details: </h2>";
    echo "<br>";
    echo "<table border black 1px>";
    echo "<th> Name </th>";
    echo "<th> $course Grade </th>";
    echo "<th> credits taken </th>";
    echo "<tr>";
    echo "<td> $grader</td>";
    echo "<td> $row[grade]</td>";
    echo "<td> $row[total_credits]</td>";
    echo "</tr>";
    echo "</table>";
    echo "<br>";
    
    
    echo "<form action = 'confirmmgrader.php' >";
    echo "<button> confirm grader </button>";
    echo "</form>";

    echo "<form action = 'selectgrader.php'>";
    echo "<p>";
    echo "<button type = 'submit'> select a different grader </button>";
    echo "</p>";
    echo "</form>";
?>