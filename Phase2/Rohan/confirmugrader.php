<?php
session_start();
include "conFunc.php";
?>

<?php
$course = $_SESSION["course"];
$section = $_SESSION['selectedgradersection'];
$grader = $_SESSION['selectedundergradgrader'];
$graderid = $_SESSION['selectedgraderid'];
$year = $_SESSION['year'];
$semester = $_SESSION['semester'];
?>

<?php
$conn = openConnection();
mysqli_select_db($conn,"db2");
$searchgraderprevquery = "SELECT * FROM undergraduategrader  WHERE student_id = '$graderid'";
$searchgraderprevresult = mysqli_query($conn,$searchgraderprevquery);
$row = mysqli_fetch_array($searchgraderprevresult);
if ($row != NULL){
    $prevcourseid = $row['course_id'];
    $prevsectionid = $row['section_id'];
    $prevsemester = $row['semester'];
    $prevyear = $row['year'];
    echo "<br>";
    echo "the selected undergraduate student is already grading $prevcourseid $prevsectionid $prevsemester $prevyear";
    echo "<br>";
    echo "<form action = 'admin.php'>";
    echo "<button type = 'submit'> Admin Homepage </button>";
    echo "</form>";
    echo "<br>";
    echo "<form action = 'selectgrader.php'>";
    echo "<button type = 'submit'>  select a different grader </button>";
    echo "</form>";
    exit;
}
else {
    $searchprevgraderquery = "SELECT * FROM undergraduategrader WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester' AND year = '$year'";
    $searchprevgraderqueryresult = mysqli_query($conn,$searchprevgraderquery);
    $prevgradertrue = 0;
    while($row = mysqli_fetch_array($searchprevgraderqueryresult)){
        $prevgraderid = $row['student_id'];
        $getprevgradernamequery = "SELECT name FROM student WHERE student_id = $prevgraderid";
        $getprevgradernamequeryresult = mysqli_query($conn,$getprevgradernamequery);
        $prevgradernamerow = mysqli_fetch_array($getprevgradernamequeryresult);
        $prevgradertrue = 1;
    }

    if ($prevgradertrue == 1){
        $query = "UPDATE undergraduategrader SET student_id = $graderid WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester' AND year = '$year'";
        mysqli_query($conn,$query);
        
        echo "<br>";
        echo "updated $course $section grader from $prevgradernamerow[name] to $grader";
    }

    else{
        $query = "INSERT INTO undergraduategrader VALUES('$graderid','$course','$section','$semester','$year')";
        mysqli_query($conn,$query);
        echo "undergraduate grader $grader has been assigned to $course $section for $semester $year";
    }

    session_destroy();
        
    echo "<br>";
    echo "<form action = 'admin.php'>";
    echo "<button type = 'submit'> Admin Homepage </button>";
    echo "</form>";
}
?>