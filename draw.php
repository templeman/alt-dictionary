<?php #draw.php 
// Drawing module for user submissions. Uses HTML5 Canvas element.

   // Include the configuration file for error management and such.
   require_once ('./includes/config.inc.php'); 

   // Set the page title and include the HTML header.
   $page_title = 'Contribute | The Alternative Dictionary';
	include ('./includes/header.html');

   // Validate user. First, $_GET['x'] if accessed from URL.
	// This is pretty INSECURE!! Deal with this at some point.
   if (isset($_GET['x'])) { 
      $x = (int) $_GET['x'];
      $_SESSION['user_id'] = $x;


   } elseif (isset($_SESSION['user_id'])) {  // If not, check if user is logged in manually.
      $x = $_SESSION['user_id'];
	} else { // The page has been accessed incorrectly. Redirect user to login.php.

		echo'<p>You must be logged in to view this page. Redirecting.</p>';
      // Start defining the URL.
      $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
      // Check for a trailing slash.
      if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
	 $url = substr ($url, 0, -1); // Chop off the slash.
      }
      // Add the page.
      $url .= '/login.php';

      ob_end_clean(); // Delete the buffer.
      header("Location: $url");

      exit();
   } // End of main IS-USER-VALID conditional

	if(isset($_GET['target'])) {
		$z = ($_GET['target']);
		echo'<h2>Defining: '.$z.'</h2>';
	} else {
		$z = FALSE;
	}
?>

		<input id="target" name="target" type="text" placeholder="what you're defining" />
	 <canvas id="stage" width="600" height="400">This browser doesn't support canvas.</canvas>
	 <div id="buttons" class="clearfix">
	    <label for="lineColor">Line Color #</label>
	    <input type="text" id="lineColor" size="34" name="lineColor" />
	    <input id="clearStage" type="button" value="Clear Canvas">
	    <input id="save" type="button" value="Save As Image" />
	 </div>

<?php
   include ('./includes/footer.html');
?>
