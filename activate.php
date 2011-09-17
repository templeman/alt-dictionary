<?php #activate.php
// Activates the user's account and forwards user to dashboard.

// Include the configuration file for error management and such.
require_once ('./includes/config.inc.php'); 

// Set the page title and include the HTML header.
$page_title = 'Dashboard';
include ('./includes/header.html');

// Validate $_GET['x'] and $_GET['y'].
if (isset($_GET['x'])) {
	$x = (int) $_GET['x'];
} else {
	$x = 0;
}
if (isset($_GET['y'])) {
	$y = $_GET['y'];
} else {
	$y = 0;
}

// If $x and $y aren't correct, redirect the user.
if ( ($x > 0) && (strlen($y) == 32)) {

	require_once ('mysql_connect.php'); // Connect to the database.
	$query = "UPDATE users SET active=NULL, reading_ready = 1 WHERE (user_id=$x AND active='" . escape_data($y) . "') LIMIT 1";		
	$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	// If record was updated.
	if (mysqli_affected_rows($dbc) == 1) {
	
		// Set the values & redirect.
			mysqli_close($dbc); // Close the database connection.
			$_SESSION['user_id'] = $x;
							
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
				
		} else {
		echo '<p class="error">Your account could not be activated. Please re-check the link or try re-registering.</p>'; 
	}
	
	mysqli_close($dbc);

} else { // Redirect.

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1); // Chop off the slash.
	}
	// Add the page.
	$url .= '/register.php';
	
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

} // End of main IF-ELSE.

include ('./includes/footer.html');
?>
