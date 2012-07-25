<?php 

	### logout.php
	### User logout - kills all sessions

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Logout';
	include ('./includes/header.html');

	// If no session exists, redirect home
	if (!isset($_SESSION['user_id'])) {

		// start defining URL
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		// check for trailing slash
		if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
			$url = substr ($url, 0, -1); // chop slash
		}

		// add page
		$url .= '/index.php';
		
		ob_end_clean(); // delete buffer and quit
		header("Location: $url");
		exit();
	
	} else { // if session exists, go ahead and logout

		$_SESSION = array(); // destroy session variables
		session_destroy(); // destroy session itself
		setcookie (session_name(), '', time()-300, '/', '', 0); // destroy session cookie

	}

	// print confirmation message and end page
	echo "<h3>You are now logged out.</h3>";
	include ('./includes/footer.html');

?>
