<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include 'config.php';
		$email = $_POST["email"];
		$password = $_POST["password"];
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
include "adminfunctions.php";

$connection = $mysqli;


echo "<br>";
?>


<?php
mysqli_select_db($connection,"db2");

$timeslotquery = mysqli_query($connection,"SELECT start_time,end_time FROM time_slot");
$courseid = $_SESSION['course'];

echo "<h2> Selected Course: $courseid </h2>";
echo "<br>";
echo "<form action = 'validatesectionadd.php'>";
echo "<input type = 'hidden' name = 'email' value='<?php echo $email;?>'>";
echo "<input type = 'hidden' name = 'password' value='<?php echo $password;?>'>";
echo "<label for = 'section'> section number: </label>";
echo "<input type = 'text' name= 'section' method= 'post'>";
echo "<br>";
echo "<br>";
echo "<label for = 'timeslot'> time slot: </label>";
echo "<select name = 'timeslot' method = 'post'>";
while($row = mysqli_fetch_array($timeslotquery)){
    $timestr = $row['start_time']."-".$row['end_time'];
    echo "<option> '".$timestr."' </option>";
}
echo "</select>";
echo "<br>";
echo "<br>";

#echo "selected course id: ";
#echo $_SESSION["course"];
#echo "<br>";
echo "<label for = 'classroombuilding'> classroom building: </label>";

$classroomidquery = mysqli_query($connection,"SELECT classroom_id FROM classroom");

echo "<select name = 'classroomid' method = 'post'>";

while ($row = mysqli_fetch_array($classroomidquery)){
    echo "<option> $row[classroom_id] </option>";
}
echo "</select>";

#$courseid = $_SESSION["course"];
#$subjabbr = substr($courseid,0,4);
#echo "subject abbreviation: $subjabbr ";
#$department = getSubjDept($subjabbr);

#if ($department = "NULL"){
#    echo "no department found for abbr";
    
#}


#$query = mysqli_query($connection,"SELECT department FROM course WHERE course_id = '".$courseid."'");
#$row = mysqli_fetch_array($query);
#$department = $row['department'];
echo "<br>";
echo "<br>";
echo "<label for = 'instructor'> instructor: </label>";
echo "<select name = 'instructor' method = 'post'>";

$query = mysqli_query($connection,"SELECT instructor_name FROM instructor");
while ($row = mysqli_fetch_array($query)){
   echo "<option> $row[instructor_name] </option>";
}
echo "</select>";

echo "<button type = 'submit'> add section </button>";
echo "</form>";

?>
