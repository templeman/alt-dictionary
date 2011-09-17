<?php #dashboard.php 
// Dashboard for logged-in user.

   // Include the configuration file for error management and such.
   require_once ('./includes/config.inc.php'); 

   // Set the page title and include the HTML header.
   $page_title = 'Your Dashboard | The Alternative Dictionary';
	include ('./includes/header.html');

   // Validate user. First, $_GET['x'] if accessed from URL.
	// This is pretty INSECURE!! Deal with this at some point.
   if (isset($_GET['x'])) { 
      $x = (int) $_GET['x'];
      $_SESSION['user_id'] = $x;

   } elseif (isset($_SESSION['user_id'])) {  // If not, check if user is logged in manually.
      $x = $_SESSION['user_id'];
   } else { // The page has been accessed incorrectly. Redirect user to login.php.

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
	require_once ('mysql_connect.php'); // Connect to the database.
	$query = "SELECT first_name FROM users WHERE user_id = $x";
	$result = mysqli_query($dbc, $query);
	list($name) = mysqli_fetch_array($result, MYSQL_NUM);

	echo '<h2>'.$name.'\'s Definitions</h2>';

	$query = "SELECT filename, target FROM images WHERE user_id = $x ORDER BY target";
	$result = mysqli_query($dbc, $query);
	if (mysqli_num_rows($result) > 0) { // User has some images
		while ($row = mysqli_fetch_array ($result, MYSQL_ASSOC)) {
			echo '<img src="userimages/'.$row['filename'].'.png">';
			echo '<h3>'.$row['target'].'</h3>';
		}
	} else {
		echo '<p>No images found for this user.</p>';	
	}
?>


<?php
   include ('./includes/footer.html');
?>
