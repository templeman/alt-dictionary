<?php 

	### dashboard.php 
	### Dashboard for logged-in user

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Your Dashboard | The Alternative Dictionary';
	include ('./includes/header.html');

	// validate user
	// first, $_GET['x'] if accessed from URL
	// This is pretty INSECURE!! Deal with this at some point.
	if (isset($_GET['x'])) { 
		$x = (int) $_GET['x'];
		$_SESSION['user_id'] = $x;

	} elseif (isset($_SESSION['user_id'])) {  // if not, is user logged in manually?
		$x = $_SESSION['user_id'];
	} else { // the page has been accessed incorrectly - redirect to login.php.

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

	require_once ('mysql_connect.php'); // connect to db
	$query = "SELECT first_name FROM users WHERE user_id = $x";
	$result = mysqli_query($dbc, $query);
	list($name) = mysqli_fetch_array($result, MYSQL_NUM);

	echo '<h2>'.$name.'\'s Definitions</h2>';
	echo '<div id="thumbnails">';

	$query = "SELECT filename, target FROM images WHERE user_id = $x ORDER BY target";
	$result = mysqli_query($dbc, $query);
	if (mysqli_num_rows($result) > 0) { // user has some images
		while ($row = mysqli_fetch_array ($result, MYSQL_ASSOC)) {
			echo '<figure><img src="thumbnails/'.$row['filename'].'.jpg">';
			echo '<figcaption>'.$row['target'].'</figcaption></figure>';
		}
	} else {
		echo '<p>No images found for this user.</p>';	
	}

	echo '</div>';


	include ('./includes/footer.html');
?>
