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
    $query = "SELECT name FROM collegesystem.student INNER JOIN collegesystem.undergraduate ON collegesystem.student.student_id = collegesystem.undergraduate.student_id INNER JOIN collegesystem.take ON collegesystem.student.student_id = collegesystem.take.student_id WHERE collegesystem.take.course_id = '$course' AND (collegesystem.take.grade = 'A' OR collegesystem.take.grade = 'A-')";
    $result = mysqli_query($conn,$query); 
    echo "<form action = 'selecteduggraderinfo.php'>";
    echo "<select name = 'selectundergradgrader' method = 'post'>";
    while($row = mysqli_fetch_array($result)){
        echo "<option> $row[name] </option>";
    }
    echo "</select>";
    echo "<button type = 'submit'> Select </button>";
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