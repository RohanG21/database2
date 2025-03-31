<?php
session_start();
include "conFunc.php";
?>

<?php
$course = $_SESSION["course"];
$section = $_SESSION['selectedgradersection'];
$grader = $_SESSION['selectedmastergrader'];
$graderid = $_SESSION['selectedgraderid'];
$year = $_SESSION['year'];
$semester = $_SESSION['semester'];

echo $year;
echo "<br>";
echo $semester;
echo "<br>";
echo $graderid;
echo "<br>";
echo $course;
echo "<br>";
echo $section; 
echo "<br>";

?>

<?php
$conn = openConnection();
echo "<br>";
mysqli_select_db($conn,"collegesystem");
$searchgraderprevquery = "SELECT * FROM mastergrader  WHERE student_id = '$graderid'";
$searchgraderprevresult = mysqli_query($conn,$searchgraderprevquery);
$row = mysqli_fetch_array($searchgraderprevresult);
if ($row != NULL){
    $prevcourseid = $row['course_id'];
    $prevsectionid = $row['section_id'];
    $prevsemester = $row['semester'];
    $prevyear = $row['year'];
    echo "<br>";
    echo "the selected masters student is already grading $prevcourseid $prevsectionid $prevsemester $prevyear";
    echo "<br>";
    echo "<form action = 'index.php'>";
    echo "<button type = 'submit'> Admin Homepage </button>";
    echo "</form>";
    echo "<br>";
    echo "<form action = 'selectgrader.php'>";
    echo "<button type = 'submit'>  select a different grader </button>";
    echo "</form>";
    exit;
}
else {
    $searchprevgraderquery = "SELECT * FROM mastergrader WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester' AND year = '$year'";
    $searchprevgraderqueryresult = mysqli_query($conn,$searchprevgraderquery);
    $prevgradertrue = 0;
    while($row = mysqli_fetch_array($searchprevgraderqueryresult)){
        $prevgraderid = $row['student_id'];
        $getprevgradernamequery = "SELECT name FROM student WHERE student_id = $prevgraderid";
        $getprevgradernamequeryresult = mysqli_query($conn,$getprevgradernamequery);
        $prevgradernamerow = mysqli_fetch_array($getprevgradernamequeryresult);
        $prevgradertrue = 1;
    }
    #if ( $prevgradertrue == 1) {
    #    $deleteprevgraderquery = "DELETE FROM undergraduategrader WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester'AND year = '$year'";
    #    mysqli_query($conn,$deleteprevgraderquery);
    #    echo "<br>";
    #    echo "deleted undergraduate grader $prevgradernamerow[name] and replacing them with $grader"; 
    #}
    

    #$query = "INSERT INTO undergraduategrader VALUES('$graderid','$course','$section','$semester','$year')";
    #mysqli_query($conn,$query);
    if ($prevgradertrue == 1){
        $query = "UPDATE mastergrader SET student_id = $graderid WHERE course_id = '$course' AND section_id = '$section' AND semester = '$semester' AND year = '$year'";
        mysqli_query($conn,$query);
        
        echo "<br>";
        echo "updated $course $section master grader from $prevgradernamerow[name] to $grader";
    }

    else{
        $query = "INSERT INTO mastergrader VALUES('$graderid','$course','$section','$semester','$year')";
        mysqli_query($conn,$query);
        echo "master grader $grader has been assigned to $course $section for $semester $year";
    }

    session_destroy();
    #echo "undergraduate grader $grader has been assigned to $course $section for $semester $year";
    
    echo "<br>";
    echo "<form action = 'index.php'>";
    echo "<button type = 'submit'> Admin Homepage </button>";
    echo "</form>";
}
?>