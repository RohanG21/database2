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
        echo "no undergraduate grader can be assigned because there needs to be 5-10 students in the class section";
        echo "<br>";
        echo "<form action = 'selectsectionforgrader.php'>";
        echo "<button type = 'submit'> section page </button>";
        echo "</form>";
        echo "<br>";
        echo "<form action = 'admin.php'>";
        echo "<button type = 'submit'> Admin Homepage </button>"; 
        echo "</form>"; 
        exit;
    }
    
    $query = "SELECT name FROM db2.student INNER JOIN db2.undergraduate ON db2.student.student_id = db2.undergraduate.student_id INNER JOIN db2.take ON db2.student.student_id = db2.take.student_id WHERE db2.take.course_id = '$course' AND (db2.take.grade = 'A' OR db2.take.grade = 'A-')";
    $result = mysqli_query($conn,$query); 
    echo "<form action = 'selectedugraderinfo.php'>";
    echo "select an undergraduate grader: ";
    echo "<br>"; 
    echo "<br>";
    echo "<select name = 'selectundergradgrader' method = 'post'>";
    $uggraderrowtrue = 0; 


    while($row = mysqli_fetch_array($result)){
        echo "<option> $row[name] </option>";
        $uggraderrowtrue = 1;
    }
    echo "</select>";
    if ($uggraderrowtrue == 1){
        echo "<button type = 'submit'> Select </button>";
        echo "<br>";
    }
    echo "</form>";

    if ($uggraderrowtrue == 0){
        echo "no undergraduate graders available to be selected for $course with the listed section number in the current semester";
        echo "<br>";
    }
    

}

function getEligibleMasterGraders($conn,$course){
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
        echo "no master grader can be assigned because there needs to be 5-10 students in the class section";
        echo "<br>";
        echo "<form action = 'selectsectionforgrader.php'>";
        echo "<button type = 'submit'> section page </button>";
        echo "</form>";
        echo "<br>";
        echo "<br>";
        echo "<form action = 'admin.php'>";
        echo "<button type = 'submit'> Admin Homepage </button>"; 
        echo "</form>"; 
        exit;
    }
    
    $query = "SELECT name FROM db2.student INNER JOIN db2.master ON db2.student.student_id = db2.master.student_id INNER JOIN db2.take ON db2.student.student_id = db2.take.student_id WHERE db2.take.course_id = '$course' AND (db2.take.grade = 'A' OR db2.take.grade = 'A-')";
    $result = mysqli_query($conn,$query); 
    echo "Select a master grader: ";
    echo "<br>";
    echo "<br>";
    echo "<form action = 'selectedmgraderinfo.php'>";
    echo "<select name = 'selectmastergrader' method = 'post'>";
    $mggraderrowtrue = 0;


    while($row = mysqli_fetch_array($result)){
        echo "<option> $row[name] </option>";
        $mggraderrowtrue = 1;
    }
    echo "</select>";
    if ($mggraderrowtrue == 1){
        echo "<button type = 'submit'> Select </button>";
        echo "<br>";
    }
    echo "</form>";

    if ($mggraderrowtrue == 0){
        echo "no master graders available to be selected for $course with the listed section number in the current semester";
        echo "<br>";
    }
}

?>