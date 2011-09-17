<?php #login.php
// Login module for registered users 

   // Include the configuration file for error management and such.
   require_once ('./includes/config.inc.php'); 

   // Set the page title and include the HTML header.
   $page_title = 'Login';
   include ('./includes/header.html');

	if (isset($_POST['submitted'])) { // Check if the form has been submitted.

		require_once ('mysql_connect.php'); // Connect to the database.

      // Validate the email address.	
      if (!empty($_POST['email'])) {
			$e = escape_data($_POST['email']);
      } else {
			echo '<p class="error">Please enter your email address.</p>';
			$e = FALSE;
      }

      // Validate the password.
      if (!empty($_POST['pass'])) {
			$p = escape_data($_POST['pass']);
      } else {
			$p = FALSE;
			echo '<p class="error">You forgot to enter your password!</p>';
      }

      if ($e && $p) { // If everything's OK.

		// Query the database.
		$query = "SELECT user_id, first_name, admin FROM users WHERE (email = '$e' AND pass = SHA('$p')) AND active IS NULL";	
		$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));

		if (@mysqli_num_rows($result) == 1) { // A match was made.

			// Register the values & redirect.
			$row = mysqli_fetch_array($result, MYSQL_NUM); 
			mysqli_free_result($result);
			mysqli_close($dbc); // Close the database connection.
			$_SESSION['first_name'] = $row[1];
			$_SESSION['user_id'] = $row[0];
			$_SESSION['admin'] = $row[2];


			// Start defining the URL.
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
			// Check for a trailing slash.
			if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
				$url = substr ($url, 0, -1); // Chop off the slash.
			}

			// Add the page.
			$url .= '/dashboard.php';

			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.

		} else { // No match was made.
			echo '<p class="error">Either the email address and password entered do not match those on file or you have not yet activated your account.</p>'; 
		}

		} else { // If everything wasn't OK.
			echo '<p class="error">Please try again.</p>';		
		}

		mysqli_close($dbc); // Close the database connection.

	} // End of SUBMIT conditional.
?>

<h2>Please login to participate.</h2>
<form action="login.php" id="login" method="post">
   <fieldset>
      <label for="email">Email Address:</label><input type="email" name="email" id="email" placeholder="youraddress@example.com" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
      <label for="pass">Password:</label><input type="password" name="pass" id="pass" placeholder="yourpassword" />
      <input type="submit" name="submit" value="Login" />
      <input type="hidden" name="submitted" value="TRUE" />
   </fieldset>
</form>


<?php // Include the HTML footer.
   include ('./includes/footer.html');
?>
