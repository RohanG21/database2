<?php
	include 'config.php';
	session_start();
	if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
		$email = $_SESSION['email'];
		$password = $_SESSION['password'];
		session_unset();
		session_destroy();
	} else if(isset($_POST['email']) && isset($_POST['password'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];
	} else {
		$url = "index.html";
		echo "<a href='$url'>Return to Login Menu</a><br>";
		exit("Error: Invalid Credentials");
	}
?>

<!DOCTYPE HTML>
<html>
<head>
<title> Admin Account</title>
</head>
<body>
<center>
<h1>Welcome Admin</h1>
<p><a href="index.html">Return to Login Menu</a>.</p>
</center>

<form action = 'courselist.php' method = 'post' >
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<button type = 'submit'> see course list  </button>	
</form>
<br>
<form method = "POST" action = "assign_ta.php">
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<input type = "submit" value = "Assign TA">
</form>
<br>
<form action = 'addsectionuggrader.php' method = 'post'>
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<button type = 'submit'>select a grader for a section</button>
</form>
<br>
<form method = "POST" action = "advisor_controls.php">
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<input type = "submit" value = "Appoint Advisors">
</form>
<br>
<form method = "POST" action = "view_rating.php">
	<input type = "hidden" name = "email" value="<?php echo $email;?>">
	<input type = "hidden" name = "password" value="<?php echo $password;?>">
	<input type = "submit" value = "View Professor Ratings">
</form>
<br>
</body>
</html>