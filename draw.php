<?php 

	### draw.php 
	### Drawing module for user submissions. Uses HTML5 Canvas element.

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Contribute | The Alternative Dictionary';
	include ('./includes/header.html');

	// validate user
	// first, $_GET['x'] if accessed from URL
	// This is pretty INSECURE!! Deal with this at some point.
	if (isset($_GET['x'])) { 
		$x = (int) $_GET['x'];
		$_SESSION['user_id'] = $x;
	} elseif (isset($_SESSION['user_id'])) {  // if not, is user logged in manually?
		$x = $_SESSION['user_id'];
	} else { // illegal access - redirect to login.php

		echo'<p>You must be logged in to view this page. Redirecting.</p>';
		// start defining URL
		$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		// check for trailing slash
		if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
			$url = substr ($url, 0, -1); // chop slash
		}

		// add page
		$url .= '/login.php';

		ob_end_clean(); // delete buffer and quit
		header("Location: $url");
		exit();

	} // end of main IS-USER-VALID conditional

	if(isset($_GET['target'])) {
		$z = ($_GET['target']);
		echo'<h2>Defining: '.$z.'</h2><p>or <a href="dashboard.php">Go Back</a></p>';
	} else {
		$z = FALSE;
		echo'<input id="target" name="target" type="text" placeholder="what you\'re defining" />';
	}

?>

<canvas id="stage" width="600" height="600">
	Sorry, this browser doesn't support canvas.
</canvas>
<div id="buttons" class="clearfix">
	<label for="lineColor">Line Color #</label>
	<input type="text" id="lineColor" size="34" name="lineColor" />

	<input id="clearStage" type="button" value="Clear Canvas">
	<input id="save" type="button" value="Save As Image" />
</div>

<?php
	include ('./includes/footer.html');
?>
