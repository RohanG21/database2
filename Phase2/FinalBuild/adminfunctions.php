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
    #$query = "SELECT name FROM db2.student INNER JOIN db2.undergraduate AS student_info JOIN db2.take ON student_info.student_id = db2.take.student_id WHERE (db2.take.course_id = '$course' AND db2.take.grade = 'B')";
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
	$query = "SELECT distinct name, student.student_id FROM take, undergraduate, master, student where course_id = '$course' and student.student_id = take.student_id and (student.student_id = undergraduate.student_id or student.student_id = master.student_id) and (grade = 'A' or grade = 'A-' or grade = 'A+') and (take.year < '$current_year' or (take.year = '$current_year' and take.semester != 'Spring'))";

   # $query = "SELECT name FROM db2.student INNER JOIN db2.undergraduate ON db2.student.student_id = db2.undergraduate.student_id INNER #JOIN db2.take ON db2.student.student_id = db2.take.student_id WHERE db2.take.course_id = '$course' AND (db2.take.grade = 'A' OR #db2.take.grade = 'A-')";
    $result = mysqli_query($conn,$query); 
    echo "<form action = 'selecteduggraderinfo.php'>";
    echo "<select name = 'selectundergradgrader' method = 'get'>";
	$uggraderrowtrue = 0;

    while($row = mysqli_fetch_array($result)){
	echo "<option value='".$row['student_id']."'>".$row['name']."</option>";
        #echo "<option> $row[name] </option>";
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