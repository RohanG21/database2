<?php
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		include 'config.php';
		$email = $_GET["email"];
		$password = $_GET["password"];
		session_start();
		$_SESSION['email'] = $email;
		$_SESSION['password'] = $password;

	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
?>
<?php

    $timeslot = $_GET['timeslot'];
    $timeslotarr = explode("-",$timeslot);
    $semester = $_SESSION["semester"];
    $year = $_SESSION["year"];
    $starttime = (string)$timeslotarr[0];
    $endtime = (string)$timeslotarr[1];
 
    $timeslotid = "";
    $prevtimeslotnum = "";
    $alttimeslotnum = 0;
    $nexttimeslotnum = "";
    

    $conn = $mysqli;
    echo "<br>";
    mysqli_select_db($conn,"db2");
    $courseid = $_SESSION["course"];
    echo "<h2> Selected Course: $courseid </h2>";
    echo "<br>";

    $timeslotquery = mysqli_query($conn,"SELECT time_slot_id FROM time_slot WHERE (start_time = $starttime' AND end_time = '$endtime)");
    $row = mysqli_fetch_array($timeslotquery);

    if($row == NULL) {
        echo "no timeslot id found";
        echo "<form action = 'admin.php' name = 'goback'>";
	echo "<input type = 'hidden' name = 'email' value='<?php echo $email;?>'>";
	echo "<input type = 'hidden' name = 'password' value='<?php echo $password;?>'>";
        echo "<button type = 'submit' name = 'goback'>";
        echo "</form>";
        exit;
    }

    
    else{ 
        $timeslotid = ($row['time_slot_id']);
        echo "selected time slot id: ",$timeslotid;
        echo "<br>";
    }

    $classroom = $_GET['classroomid'];

    $conflictcoursesearchquery = mysqli_query($conn,"SELECT course_id,section_id FROM section WHERE section.year = '$year' AND semester = '$semester' AND classroom_id = '$classroom' AND time_slot_id = '$timeslotid'");

    $row = mysqli_fetch_array($conflictcoursesearchquery);
        
    $conflictingtslot = 0;

    if ($row != NULL) {
        echo "conflicting section found for that course Reason: same room, same time";
        echo "<br>";
        echo "$row[course_id]";
        echo "<br>";
        echo "$row[section_id]";
        echo "<form action = 'admin.php' name = 'goback'>";
	echo "<input type = 'hidden' name = 'email' value='<?php echo $email;?>'>";
	echo "<input type = 'hidden' name = 'password' value='<?php echo $password;?>'>";
        echo "<button type = 'submit'> Admin Homepage </button>";
        exit;
    }


    $sectionidcountquery = mysqli_query($conn,"SELECT COUNT(section_id) AS count,course_id FROM section WHERE course_id = '$courseid' AND semester = '$semester' AND year = '$year' AND time_slot_id = '$timeslotid'");

    $row = mysqli_fetch_array($sectionidcountquery);

    $sectioncount = 0;

    if ($row != NULL){
        $sectioncount = $row['count'];
        echo "Amount of sections for selected course with same time slot: $sectioncount";
        echo "<br>";
        $sectioncount = (int)$sectioncount;
    }

    if($sectioncount >= 2){
        echo "Amount of sections for selected course at same time limit reached";
        echo "<br>";
        echo "selected course: ";
        echo $row['course_id'];
        echo " ";
        #echo $row['section_id'];
        echo "<br>";
        echo "<form action = 'admin.php' name = 'goback'>";
	echo "<input type = 'hidden' name = 'email' value='<?php echo $email;?>'>";
	echo "<input type = 'hidden' name = 'password' value='<?php echo $password;?>'>";
        echo "<button type = 'submit'>  admin homepage </button>";
        echo "</form>";
        exit;
    }

   
    switch($timeslotid){
        case('TS4'):
            #echo "TS4 case";
            #echo "<br>";
            $conflicttslotquery = mysqli_query($conn,"SELECT course_id,section_id,classroom_id,time_slot_id,semester,year FROM section WHERE classroom_id = '$classroom' AND semester = '$semester' AND (year = '$year' AND (time_slot_id = 'TS1' OR time_slot_id = 'TS2'))");
            while($row = mysqli_fetch_array($conflicttslotquery)){
                if ($row != NULL) {
                    $conflictingtslot = 1; 
                    echo "conflicting course section found: ";
                    echo $row['course_id'];
                    echo " ";
                    echo $row['section_id'];
                }
            }
            break;
                    
        case('TS5'):
            #echo "TS5 case";
            #cho "<br>";
            $conflictcoursequery = mysqli_query($conn,"SELECT course_id,section_id,classroom_id,time_slot_id,semester,year FROM section WHERE classroom_id = '$classroom' AND semester = '$semester' AND year = '$year' AND (time_slot_id = 'TS2' OR time_slot_id = 'TS3')");
            while($row = mysqli_fetch_array($conflictcoursequery)){
                if ($row != NULL){
                    $conflictingtslot = 1;
                    echo "conflicting course section found";
                    echo "<br>";
                    echo $row['course_id'];
                    echo " ";
                    echo $row['section_id'];     
                }
            }
            break;
                

        case('TS1'):
           #echo "TS1 case";
           #echo "<br>";
            $conflictcoursequery = mysqli_query($conn,"SELECT course_id,section_id,classroom_id,time_slot_id,semester,year FROM section WHERE semester = '$semester' AND year = '$year' AND classroom_id = '$classroom' AND time_slot_id = 'TS4'");
            while($row = mysqli_fetch_array($conflictcoursequery)){
                if ($row != NULL){
                    $conflictingtslot = 1;
                    echo "conflicting course section found: ";
                    echo "<br>";
                    echo $row['course_id'];
                    echo " ";
                    echo $row['section_id'];
                    echo "<br>";
                }
            }
            break;


            case('TS2'):
                #echo "TS2 case";
                #echo "<br>";
                $conflictcoursequery = mysqli_query($conn,"SELECT course_id,section_id,classroom_id,semester,year,time_slot_id FROM section WHERE classroom_id = '$classroom' AND semester = '$semester' AND year = '$year' AND (time_slot_id = 'TS4' OR time_slot_id = 'TS5')");
                while($row = mysqli_fetch_array($conflictcoursequery)){
                    if($row != NULL){
                        $conflictingtslot = 1;
                        echo "conflicting course section found: ";
                        echo "<br>";
                        echo $row['course_id'];
                        echo " ";
                        echo $row['section_id'];
                        echo "<br>";
                    
                    }

                }             
                break;
                

            case('TS3'):
                #echo "TS3 case";
                #echo "<br>";
                $conflictcoursequery = mysqli_query($conn,"SELECT course_id,section_id,classroom_id,time_slot_id,semester,year FROM section WHERE semester = '$semester' AND year = '$year' AND classroom_id = '$classroom' AND time_slot_id = 'TS5'");
                while($row = mysqli_fetch_array($conflictcoursequery)){
                    if ($row != NULL){
                        $conflictingtslot = 1;
                        echo "conflicting course section found: ";
                        echo "<br>";
                        echo $row['course_id'];
                        echo " ";
                        echo $row['section_id'];
                        echo "<br>";
                    }
                }
                break;

    }

    if ($conflictingtslot == 1){
        echo "<form action = 'admin.php' name= 'goback'>";
	echo "<input type = 'hidden' name = 'email' value='<?php echo $email;?>'>";
	echo "<input type = 'hidden' name = 'password' value='<?php echo $password;?>'>";
        echo "<button type = 'submit' name = 'goback'> Admin Homepage </button>";
        echo "</form>";
        exit;
    }

    $newsection = $_GET['section'];
    $instructor = $_GET["instructor"];
    $findinstructorid = mysqli_query($conn,"SELECT instructor_id FROM instructor WHERE instructor_name = '$instructor'");
    $row = mysqli_fetch_array($findinstructorid);
    $instructorid = $row['instructor_id'];
    $instructorsamecoursesecquery = mysqli_query($conn,"SELECT COUNT(course_id) AS instructorsamecoursesecs,section_id,semester,year,instructor_id,time_slot_id FROM section WHERE course_id = '$courseid' AND semester = '$semester' AND year = '$year' AND instructor_id = '$instructorid'");
    $i = 0;
    $numprevcoursesecs = 0;
    
    $row = mysqli_fetch_array($instructorsamecoursesecquery);

    if ($row != NULL){
        $numprevcoursesecs = $row['instructorsamecoursesecs'];
    }
    
    if($numprevcoursesecs == 1){
        $numprevcoursesecs = $row['instructorsamecoursesecs'];
        $prevtimeslotid = $row['time_slot_id'];
        $prevtimeslotnum = substr($prevtimeslotid,2);
        $prevtimeslotnum = (int)$prevtimeslotnum;
                    
        if ($prevtimeslotnum == 5) {
                $nexttimeslotnum = 0;
        }
        else{
            $nexttimeslotnum = $prevtimeslotnum + 1;
        }

        $alttimeslotnum  = $prevtimeslotnum - 1;

        $selectedtimeslotnum = substr($timeslotid,2);
        $selectedtimeslotnum = (int)$selectedtimeslotnum;
        if ($selectedtimeslotnum != $nexttimeslotnum && ($selectedtimeslotnum != $alttimeslotnum )) {
            echo "time slot id is not consecutive to previous section for selected course taught by selected instructor";
            echo "<form action = 'admin.php' name = 'goback'>";
		echo "<input type = 'hidden' name = 'email' value='<?php echo $email;?>'>";
		echo "<input type = 'hidden' name = 'password' value='<?php echo $password;?>'>";
            echo "<button type = 'submit' name = 'goback'> Admin Homepage </button>";
            echo "</form>";
            exit;
        }
    }
    
    $newsection = 'Section'.(string)$newsection;
    $addsection = mysqli_query($conn,"INSERT INTO section VALUES('$courseid','$newsection','$semester','$year','$instructorid','$classroom','$timeslotid')");  
            
    echo "<br>";
    $query = mysqli_query($conn, "SELECT * FROM section WHERE section.semester = '$semester' AND section.year = '$year' AND section.course_id = '$courseid'");
            
    echo "<table border = 1px solid black>";
    echo "<th> section_id </th>";
    echo "<th>instructor_id </th>";
    echo "<th> classroom_id </th>";
    echo "<th> start time </th>";
    echo "<th> end time </th>";
    while($row = mysqli_fetch_array($query)){
        $timeslot = $row['time_slot_id'];
        $timestart = mysqli_query($conn,"SELECT start_time,end_time  FROM time_slot WHERE time_slot_id = '".$timeslot."'" );
        $timerow = mysqli_fetch_array($timestart);
        echo "<tr>";
        echo "<td>$row[section_id]</td>";    
        echo "<td>$row[instructor_id]</td>";
            
        echo "<td> $row[classroom_id] </td>";
        echo "<td> $timerow[start_time] </td>";
        echo "<td> $timerow[end_time] </td>";
        echo "</tr>";
    }
    
    echo "</table>";

    echo "<br>";

    echo "<form action = 'admin.php' name = 'goback'>";
	echo "<input type = 'hidden' name = 'email' value='<?php echo $email;?>'>";
	echo "<input type = 'hidden' name = 'password' value='<?php echo $password;?>'>";
    echo "<button type = 'submit' name = 'goback'> Admin Homepage </button>";
    echo "</form>";

?>