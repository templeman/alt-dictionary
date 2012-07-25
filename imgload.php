<?php

	### imgload.php
	### retrieves images from db and returns them as JSON object

	if(!empty($_GET['letter'])) {
		$letter = $_GET['letter'];
	}

	require_once ('mysql_connect.php');

	// get filename from db 
	$query = "SELECT filename, target, letter, first_name FROM images AS i, users AS u WHERE letter = '$letter' AND i.user_id = u.user_id";
	$result = mysqli_query($dbc, $query);
	$num = mysqli_num_rows($result);

	if ($num > 0) { // if some filenames were retrieved
		$filenames = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$filenames[] = $row;
			// array_push($filenames, $row['filename']);
		}
		$JSONobject = json_encode($filenames);
		echo $JSONobject;
	}	

?>
