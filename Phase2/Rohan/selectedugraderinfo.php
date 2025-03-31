<?php
session_start();
include "conFunc.php";

?>

<?php
    
    $conn = openConnection();
    echo "<br>";
    mysqli_select_db($conn,"db2");
   
    $_SESSION['selectedundergradgrader'] = $_GET['selectundergradgrader'];
    $grader = $_SESSION['selectedundergradgrader'];

    $course = $_SESSION["course"];
    $section = $_SESSION["selectedgradersection"];
    echo "<h1> selected course: $course </h1>";
    echo "<br>";
    echo "<h2> selected section: $section </h2>";
    echo "<br>";
    echo "<h2> selected grader details: </h2>";

    $query = "SELECT student.student_id AS student_id, grade, class_standing FROM student INNER JOIN undergraduate ON student.student_id = undergraduate.student_id INNER JOIN take ON undergraduate.student_id = take.student_id WHERE student.name = '$grader' AND take.course_id = '$course' ";
    $result = mysqli_query($conn,$query);
    

    $row = mysqli_fetch_array($result);
    $_SESSION['selectedgraderid'] = $row['student_id'];
    echo "selected student id: $_SESSION[selectedgraderid]";
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
    
    
    echo "<form action = 'confirmugrader.php' >";
    echo "<button> confirm grader </button>";
    echo "</form>";

    echo "<form action = 'selectgrader.php'>";
    echo "<button type = 'submit'> select a different grader </button>";
    echo "</form>";

    echo "<form action = 'admin.php'>";
    echo "<button type = 'submit'> Admin Homepage </button>"; 
    echo "</form>"; 
?>