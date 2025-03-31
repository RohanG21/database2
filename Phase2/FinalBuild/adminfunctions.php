<?php
function getCurSemester():string{
    $month = date("m");
    if ($month <= 5){
        return 'Spring';
    }
    else{
        return 'Fall';
    }
}   

function getCurYear():int{
    return date("Y");
}


function getEligibleGraders($conn,$course):void{
 $section = $_SESSION['selectedgradersection'];
    $semester = $_SESSION['semester'];
    $year = $_SESSION['year'];
    $countstudentsquery = "SELECT COUNT(*) AS count FROM db2.take WHERE db2.take.course_id = '$course' AND db2.take.section_id = '$section' AND db2.take.semester = '$semester' AND db2.take.year = '$year'";
    $countstudentsresult = mysqli_query($conn,$countstudentsquery);
    $countstudentsrow = mysqli_fetch_array($countstudentsresult);
    $countstudents = $countstudentsrow['count'];
    $countstudents = (int)$countstudents;
    if ($countstudents > 10 || $countstudents < 5){
        echo "$countstudents students found taking this course section in $semester $year";
        echo "<br>";
        echo "no grader can be assigned because there needs to be 5-10 students in the class section";
        echo "<br>";
        echo "<form action = 'selectsectionforuggrader.php'>";
        echo "<button type = 'submit'> section page </button>";
        echo "</form>";
        echo "<br>";
        echo "<br>";
        echo "<form action = 'admin.php'>";
        echo "<button type = 'submit'> Admin Homepage </button>"; 
        echo "</form>"; 
        exit;
    }
	$current_year = date("Y");
	$query = "SELECT distinct name, student.student_id FROM take, undergraduate, master, student where course_id = '$course' and student.student_id = take.student_id and (student.student_id = undergraduate.student_id or student.student_id = master.student_id) and (grade = 'A' or grade = 'A-' or grade = 'A+') and (take.year < '$current_year' or (take.year = '$current_year' and take.semester != 'Spring')) and not exists (Select student_id from undergraduategrader where undergraduategrader.student_id = student.student_id) and not exists (Select student_id from mastergrader where mastergrader.student_id = student.student_id)";

    $result = mysqli_query($conn,$query); 
    echo "<form action = 'selecteduggraderinfo.php'>";
    echo "<select name = 'selectundergradgrader' method = 'get'>";
	$uggraderrowtrue = 0;

    while($row = mysqli_fetch_array($result)){
	echo "<option value='".$row['student_id']."'>".$row['name']."</option>";
	$uggraderrowtrue = 1;
    }
    echo "</select>";
	if ($uggraderrowtrue == 1) {
		echo "<button type = 'submit'> Select </button>";
		echo "<br>";
	}
    echo "</form>";
	if ($uggraderrowtrue == 0) {
		echo "no graders available to be selected for $course with the listed section number in the current semester";
		echo "<br>";
	}
	echo "<form action = 'admin.php'>";
	echo "<button type = 'submit'> Admin Homepage </button>";
	echo "</form>";

}
?>