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
    #$query = "SELECT name FROM collegesystem.student INNER JOIN collegesystem.undergraduate AS student_info JOIN collegesystem.take ON student_info.student_id = collegesystem.take.student_id WHERE (collegesystem.take.course_id = '$course' AND collegesystem.take.grade = 'B')";
    $section = $_SESSION['selectedgradersection'];
    $semester = $_SESSION['semester'];
    $year = $_SESSION['year'];

    #echo  $course;
    #echo "<br>";
    #echo $section; 
    #echo "<br>";
    #echo $semester;
    #echo "<br>";
    #echo $year;
    #echo "<br>";

    $countstudentsquery = "SELECT COUNT(*) AS count FROM collegesystem.take WHERE collegesystem.take.course_id = '$course' AND collegesystem.take.section_id = '$section' AND collegesystem.take.semester = '$semester' AND collegesystem.take.year = '$year'";
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
        echo "<form action = 'index.php'>";
        echo "<button type = 'submit'> Admin Homepage </button>"; 
        echo "</form>"; 
        exit;
    }
    
    $query = "SELECT name FROM collegesystem.student INNER JOIN collegesystem.undergraduate ON collegesystem.student.student_id = collegesystem.undergraduate.student_id INNER JOIN collegesystem.take ON collegesystem.student.student_id = collegesystem.take.student_id WHERE collegesystem.take.course_id = '$course' AND (collegesystem.take.grade = 'A' OR collegesystem.take.grade = 'A-')";
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

#$query = "SELECT name FROM collegesystem.student INNER JOIN collegesystem.undergraduate AS student_info JOIN collegesystem.take ON student_info.student_id = collegesystem.take.student_id WHERE (collegesystem.take.course_id = '$course' AND collegesystem.take.grade = 'B')";
    $section = $_SESSION['selectedgradersection'];
    $semester = $_SESSION['semester'];
    $year = $_SESSION['year'];

    #echo  $course;
    #echo "<br>";
    #echo $section; 
    #echo "<br>";
    #echo $semester;
    #echo "<br>";
    #echo $year;
    #echo "<br>";

    $countstudentsquery = "SELECT COUNT(*) AS count FROM collegesystem.take WHERE collegesystem.take.course_id = '$course' AND collegesystem.take.section_id = '$section' AND collegesystem.take.semester = '$semester' AND collegesystem.take.year = '$year'";
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
        echo "<form action = 'index.php'>";
        echo "<button type = 'submit'> Admin Homepage </button>"; 
        echo "</form>"; 
        exit;
    }
    
    $query = "SELECT name FROM collegesystem.student INNER JOIN collegesystem.master ON collegesystem.student.student_id = collegesystem.master.student_id INNER JOIN collegesystem.take ON collegesystem.student.student_id = collegesystem.take.student_id WHERE collegesystem.take.course_id = '$course' AND (collegesystem.take.grade = 'A' OR collegesystem.take.grade = 'A-')";
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


#function getSubjDept($subject) {

#$meie = array("MECH","MTEC","IENG");
#    if (in_array($subject,$meie)){
#        return "Mechanical And Industrial Engineering";
#    }

 #   $cieve = array("CIVE","EEVE");

  #  if(in_array($subject,$cieve)){
   #     return "Civil And Environmental Engineering";
   # }

   # $cheg = array("CHEN","ENGY");

    #if (in_array($subject,$cheg)){
    #    return "Chemical Engineering";
    #}
    
    #$eece = array("EECE","ETEC");

    #if (in_array($subject,$eece)){
    #    return "Electrical And Computer Engineering";
    #}

    #$plen = array("PLAS","PTEC");
    #if (in_array($subject,$plen)){
    #    return "Plastics Engineering";
    #}

    #if (strcmp($subject,"CHEM") == 0){
    #    return "Chemistry";
    #}

    #if (strcmp($subject,"BIOL") == 0){
    #    return "Biological Sciences";
    #}

    #if (strcmp($subject,"COMP"))
    #{
    #    return "Miner School Of Computer And Information Sciences";
    #}


    #if (strcmp($subject,"ENVI") == 0){
    #    return "Environmental,Earth,And Atomospheric Sciences";
    #}

    #if (strcmp($subject,"MATH")== 0){
    #    return "Mathematics And Statistics";
    #}

    #if (strcmp($subject,"PHYS") == 0){
    #    return "Physics And Applied Physics";
    #}

    #if (strcmp($subject,"PUBH") == 0){
    #    return "Public Health";
    #}


    #if (strcmp($subject,"HCSI") == 0){
    #    return "Solomont School Of Nursing";
        
    #}


    #return "NULL";
#}



?>