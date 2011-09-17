<?php #logout.php
// User logout. Kills all sessions. 

// Include the configuration file for error management and such.
require_once ('./includes/config.inc.php'); 

// Set the page title and include the HTML header.
$page_title = 'Logout';
include ('./includes/header.html');

// If no first_name variable exists, redirect the user.
//if (!isset($_SESSION['first_name'])) {
if (!isset($_SESSION['user_id'])) {

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1); // Chop off the slash.
	}
	// Add the page.
	$url .= '/index.php';
	
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
} else { // Logout the user.

	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie (session_name(), '', time()-300, '/', '', 0); // Destroy the cookie.

}

// Print a customized message.
echo "<h3>You are now logged out.</h3>";

include ('./includes/footer.html');
?>
