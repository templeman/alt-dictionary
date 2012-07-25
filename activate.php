<?php 

	### activate.php
	### Activates the user's account and forwards to user dashboard

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Dashboard';
	include ('./includes/header.html');

	// validate $_GET['x'] and $_GET['y']
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

	// If $x and $y aren't kosher, redirect the user
	if ( ($x > 0) && (strlen($y) == 32)) {

		require_once ('mysql_connect.php');
		$query = "UPDATE users SET active=NULL, reading_ready = 1 WHERE (user_id=$x AND active='" . escape_data($y) . "') LIMIT 1";		
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
		if (mysqli_affected_rows($dbc) == 1) { // if record was updated
	
			// set the values and redirect
			mysqli_close($dbc);
			$_SESSION['user_id'] = $x;
							
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
				
		} else {
			echo '<p class="error">Your account could not be activated. Please re-check the link or try re-registering.</p>'; 
		}
	
		mysqli_close($dbc);

	} else { // redirect

		// start defining URL
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		// check for trailing slash
		if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
			$url = substr ($url, 0, -1); // chop slash
		}

		// add page
		$url .= '/register.php';
		
		ob_end_clean(); // delete buffer and quit
		header("Location: $url");
		exit();

	} // end of main IF-ELSE

	include ('./includes/footer.html');

?>
