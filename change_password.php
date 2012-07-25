<?php 

	### change_password.php 
	### Allows a logged-in user to change their password

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Change Your Password';
	include ('./includes/header.html');

	// if no first_name variable exists, redirect user
	if (!isset($_SESSION['first_name'])) {

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

	} else { // session exists

		if (isset($_POST['submitted'])) { // handle the form

			require_once ('mysql_connect.php'); // connect to db

			// check for new password and match against confirmed password
			if (preg_match ('/^[[:alnum:]]{4,20}$/', stripslashes(trim($_POST['password1'])))) {
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

			if ($p) { // if everything's ok

				// make the query
				$query = "UPDATE users SET pass=SHA('$p') WHERE user_id={$_SESSION['user_id']}";		
				$result = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));

				if (mysqli_affected_rows($dbc) == 1) { // if it ran ok

					// send an email, if desired
					echo '<h3>Your password has been changed.</h3>';
					mysqli_close($dbc); // close db connection
					include ('./includes/footer.html'); // include HTML footer
					exit();				

				} else { // if it did not run ok
					// send a message to the error log
					echo '<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>'; 
				}

			} else { // failed the validation test
				echo '<p class="error">Please try again.</p>';		
			}

			mysqli_close($dbc); // close db connection
		} // end of main SUBMIT conditional

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
	} // end of ISSET-SESSION-ELSE
	include ('./includes/footer.html');
?>
