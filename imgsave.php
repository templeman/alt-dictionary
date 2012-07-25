<?php

	### imgsave.php
	### write image to local folder and save uri reference in db 
	### also create thumbnail version

	session_start();

	// include the class
	include('Resize.php');

	// define('UPLOAD_DIR', 'userimages/');
	$upload_dir_reg = 'userimages/';
	$img_raw = $_POST['img'];
	$target = $_POST['target'];
	$img = str_replace('data:image/png;base64,', '', $img_raw);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$number = uniqid();
	$file = $upload_dir_reg . $number . '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file.';	

	if ($target) {
		$letter = substr($target, 0, 1);
	}

	if ($success) {
		if (isset($_SESSION['user_id'])) {
			$x = $_SESSION['user_id'];
			require_once ('mysql_connect.php'); // connect to db

			// insert filename in db
			$query = "INSERT INTO images (user_id, filename, target, letter) VALUES ('$x', '$number', '$target', '$letter')";
			$result = mysqli_query($dbc, $query);
		} // else {
			//		echo'<p class="error">You must be logged in to contribute.</p>';
			// }
	}


	// add thumbnail
	$upload_dir_thumb = 'thumbnails/';

	// initialize and load image
	$resize_obj = new resize($file);

	// resize image (options: exact, portrait, landscape, auto)
	$resize_obj -> resizeImage(180, 180);

	// save image
	$resize_obj -> saveImage($upload_dir_thumb . $number . '.jpg', 100);
?>
