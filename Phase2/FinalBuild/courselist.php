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
#echo "Today is " ,$monthint; 
#echo "<br>";
#echo "the current semester is ",$semester;
echo "<h1> Course List </h1>";
echo "<br>";

$db_connection = mysqli_connect("localhost","root","");
mysqli_select_db($db_connection,"db2");

$query = mysqli_query($db_connection,"SELECT * FROM course");
echo "<br>";
echo "<form action = 'sectionpage.php'>";
echo "<label for = 'course'> course: </label> <br> " ;
echo "<select method = 'post' name='course'>";
while($row = mysqli_fetch_array($query)){
    echo "<option value = '".$row['course_id']. "'>" . $row['course_id'] ."</option>";
}
echo "</select>";
echo "<input type = 'hidden' name = 'email' value='<?php echo $email;?>'>";
echo "<input type = 'hidden' name = 'password' value='<?php echo $password;?>'>";
echo "<button type = 'submit'>Submit </button>";
echo "</form>";

?>
<p><a href="admin.php">Return to Admin Page</a>.</p>