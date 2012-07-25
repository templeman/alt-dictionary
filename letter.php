<?php 

	### letter.php 
	### Dynamically presents all words for a particular letter at the letter level

	// setup file
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Some Letter | The Alternative Dictionary';
	include ('./includes/header.html');

	// get letter, $_GET['l'], accessed from URL
	if (isset($_GET['l'])) { 
		$l = (int) $_GET['l'];
		// $_SESSION['letter_id'] = $l;
	} else { // incorrect access - redirect user to login.php

		// start defining URL redirect
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		// check for trailing slash
		if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
			$url = substr ($url, 0, -1); // chop off slash
		}

		// add page
		$url .= '/login.php';

		ob_end_clean(); // delete buffer
		header("Location: $url");

		exit();
	} // end of main IS-LETTER conditional



include ('./includes/footer.html');
?>
