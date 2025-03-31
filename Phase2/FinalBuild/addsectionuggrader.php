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
$month = date("m");
$monthint = (int)$month;
$semester = '';
if ($monthint <= 5) {
    $semester = 'Spring';
}

else {
    $semester = 'Fall';
}

$year = date("Y");

$_SESSION["semester"] = $semester;
$_SESSION["year"] = $year;
unset($_SESSION["course"]);
echo "<br>";
echo "the current semester is ",$semester;
echo "year: $year";
$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"db2");

$query = mysqli_query($db_connection,"SELECT * FROM course");
$courserowtrue = 0;
echo "<br>";
echo "<form action = 'selectsectionforuggrader.php'>";
echo "<label for = 'selectgradercourse'> select a course: </label> <br> " ;
echo "<select method = 'post' name='selectgradercourse'>";
while($row = mysqli_fetch_array($query)){
    echo "<option value = '".$row['course_id']. "'>" . $row['course_id'] ."</option>";
	$courserowtrue = 1;
}
echo "</select>";

if($courserowtrue == 1){
    echo "<br>";
    echo "<br>";
    echo "<button type = 'submit'>Submit </button>";
    echo "</form>";
}
if ($courserowtrue == 0){
    echo "<br>";
    echo "no courses to register a grader for in the current semester";
    echo "<br>";
}
echo "<form action = 'admin.php'>";
echo "<button type = 'submit'> Admin Homepage </button>";
echo "</form>";
exit;
?>
<p><a href="admin.php">Return to Admin Page</a>.</p>