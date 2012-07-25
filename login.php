<?php

	### login.php
	### Login module for registered users 

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Login';
	include ('./includes/header.html');

	if (isset($_POST['submitted'])) { // is form submitted? if so, get started

		require_once ('mysql_connect.php');

		// validate email
		if (!empty($_POST['email'])) {
			$e = escape_data($_POST['email']);
		} else {
			echo '<p class="error">Please enter your email address.</p>';
			$e = FALSE;
		}

		// validate password
		if (!empty($_POST['pass'])) {
			$p = escape_data($_POST['pass']);
		} else {
			$p = FALSE;
			echo '<p class="error">You forgot to enter your password!</p>';
		}

		if ($e && $p) { // if everything's ok

			// query database
			$query = "SELECT user_id, first_name, admin FROM users WHERE (email = '$e' AND pass = SHA('$p')) AND active IS NULL";	
			$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));

			if (@mysqli_num_rows($result) == 1) { // if a match was made

				// register values and redirect to dashboard.php
				$row = mysqli_fetch_array($result, MYSQL_NUM);
				mysqli_free_result($result);
				mysqli_close($dbc); // close db connection
				$_SESSION['first_name'] = $row[1];
				$_SESSION['user_id'] = $row[0];
				$_SESSION['admin'] = $row[2];


				// start defining URL
				$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
				// check for a trailing slash
				if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
					$url = substr ($url, 0, -1); // chop off the slash
				}

				// add page
				$url .= '/dashboard.php';

				ob_end_clean(); // delete buffer
				header("Location: $url");
				exit(); // quit script

			} else { // no match was made
				echo '<p class="error">Either the email address and password entered do not match those on file or you have not yet activated your account.</p>'; 
			}

		} else { // if everything wasn't ok
			echo '<p class="error">Please try again.</p>';		
		}

		mysqli_close($dbc); // close db connection

	} // end of SUBMIT conditional

?>


<h2>Please login to participate.</h2>
<form action="login.php" id="login" method="post">
	<fieldset>
		<label for="email">Email Address:</label>
		<input type="email" name="email" id="email" placeholder="youraddress@example.com" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />

		<label for="pass">Password:</label>
		<input type="password" name="pass" id="pass" placeholder="yourpassword" />

		<input type="submit" name="submit" value="Login" />
		<input type="hidden" name="submitted" value="TRUE" />
	</fieldset>
</form>


<?php
	include ('./includes/footer.html');
?>
