<?php
	session_start();

	define('UPLOAD_DIR', 'userimages/');
	$img = $_POST['img'];
	$target = $_POST['target'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$number = uniqid();
	$file = UPLOAD_DIR . $number . '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file.';	

	if ($target) {
		$letter = substr($target, 0, 1);
	}

	if ($success) {
		if (isset($_SESSION['user_id'])) {
			$x = $_SESSION['user_id'];
   require_once ('mysql_connect.php'); // Connect to the database.

		// Insert the filename in the db
		$query = "INSERT INTO images (user_id, filename, target, letter) VALUES ('$x', '$number', '$target', '$letter')";
		$result = mysqli_query($dbc, $query); // Run query
		} // else {
	//		echo'<p class="error">You must be logged in to contribute.</p>';
	// }
		}	
?>
