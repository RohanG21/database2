<?php
	$mysqli = new mysqli('localhost','root','','db2');
	if($mysqli->connect_error){
		echo "$mysqli->connect_error";
		die("Connection Failed : ". $mysqli->connect_error);
	}
	date_default_timezone_set('EST');
	$current_year = date("Y");
	$month = date("n");
	$day = date("j");
	switch($month) {
		case 1:
			if ($day < 21)
				$current_semester = 'Winter';
			else
				$current_semester = 'Spring';
			break;
		case 2:
			$current_semester = 'Spring';
			break;
		case 3:
			$current_semester = 'Spring';
			break;
		case 4:
			$current_semester = 'Spring';
			break;
		case 5:
			if ($day < 19)
				$current_semester = 'Spring';
			else
				$current_semester = 'Summer';
		case 6:
			$current_semester = 'Summer';
			break;
		case 7:
			$current_semester = 'Summer';
			break;
		case 8:
			$current_semester = 'Summer';
			break;
		case 9:
			if ($day >= 3)
				$current_semester = 'Fall';
			else
				$current_semester = 'Summer';
			break;
		case 10:
			$current_semester = 'Fall';
			break;
		case 11:
			$current_semester = 'Fall';
			break;
		case 12:
			if ($day > 30)
				$current_semester = 'Winter';
			else
				$current_semester = 'Fall';
			break;
		default:
			// Assume it's the Summer if month can't be found
			$current_semester = 'Summer';
	}
?> 