<?php #change_password.php 
// Allows a logged-in user to change their password.

   // Include the configuration file for error management and such.
   require_once ('./includes/config.inc.php'); 

   // Set the page title and include the HTML header.
   $page_title = 'Change Your Password';
   include ('./includes/header.html');

   // If no first_name variable exists, redirect the user.
   if (!isset($_SESSION['first_name'])) {

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

      if (isset($_POST['submitted'])) { // Handle the form.

	 require_once ('mysql_connect.php'); // Connect to the database.

	 // Check for a new password and match against the confirmed password.
	 if (eregi ('^[[:alnum:]]{4,20}$', stripslashes(trim($_POST['password1'])))) {
	    if ($_POST['password1'] == $_POST['password2']) {
	       $p = escape_data($_POST['password1']);
	    } else {
	       $p = FALSE;
	       echo '<p class="error">Your password did not match the confirmed password!</p>';
	    }
	 } else {
	    $p = FALSE;
	    echo '<p class="error">Please enter a valid password!</p>';
	 }

	 if ($p) { // If everything's OK.

	    // Make the query.
	    $query = "UPDATE users SET pass=SHA('$p') WHERE user_id={$_SESSION['user_id']}";		
	    $result = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	    if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

	       // Send an email, if desired.
	       echo '<h3>Your password has been changed.</h3>';
	       mysqli_close($dbc); // Close the database connection.
	       include ('./includes/footer.html'); // Include the HTML footer.
	       exit();				

	    } else { // If it did not run OK.
	       // Send a message to the error log.
	       echo '<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>'; 
	    }		

	 } else { // Failed the validation test.
	    echo '<p class="error">Please try again.</p>';		
	 }

	 mysqli_close($dbc); // Close the database connection.  
      } // End of the main Submit conditional.
   ?>

	<h1>Change Your Password</h1>
	<form class="clearfix" action="change_password.php" method="post">
		<fieldset>
			<label for="password1">New Password:</label>
			<input type="password" name="password1" size="20" maxlength="20" />
			<small>Use only letters and numbers. Must be between 4 and 20 characters long.</small>
			<label for="password2">Confirm New Password:</label>
			<input type="password" name="password2" size="20" maxlength="20" />
		</fieldset>
		<input type="submit" name="submit" value="Change My Password" />
		<input type="hidden" name="submitted" value="TRUE" />
	</form>

<?php
} // End of the !isset($_SESSION['first_name']) ELSE.
   include ('./includes/footer.html');
?>
