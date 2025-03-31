<?php
session_start();
?>
<?php 
unset($_SESSION["course"]);
unset($_SESSION["section"]);
unset($_SESSION['selectedgradersection']);
unset($_SESSION['selectedmastergrader']);
unset($_SESSION['selectedundergradgrader']);
unset($_SESSION['selectedgraderid']);
unset($_SESSION['year']);
unset($_SESSION['semester']);


?>
<html>
	<h1> Admin Homepage</h1>
	<form action = 'courselist.php' method = 'post' >
		<button type = 'submit'> add a course section  </button>	
	</form>

	<form action = 'addsectiongrader.php' method = 'post'>
		<button type = 'submit'>select a grader for a section</button>
	</form>
</html>