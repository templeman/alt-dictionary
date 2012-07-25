<?php 

	### forgot_password.php
	### Allows a user to reset their password

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Forgot Your Password';
	include ('./includes/header.html');

	if (isset($_POST['submitted'])) { // handle form

		require_once ('mysql_connect.php'); // connect to db

		if (empty($_POST['email'])) { // validate email
			$uid = FALSE;
			echo '<p class="error">You forgot to enter your email address!</p>';
		} else {
			// check email against db
			$query = "SELECT user_id FROM users WHERE email='".  escape_data($_POST['email']) . "'";		
			$result = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
			if (mysqli_num_rows($result) == 1) { // if there was a match, retrieve the user ID.
				list($uid) = mysqli_fetch_array ($result, MYSQL_NUM); 
			} else {
				echo '<p class="error">The submitted email address does not match those on file.</p>';
				$uid = FALSE;
			}
		}

		if ($uid) { // if everything's ok

			// generate new password
			$p = substr ( md5(uniqid(rand(),1)), 3, 10);

			// make the query
			$query = "UPDATE users SET pass=SHA('$p') WHERE user_id=$uid";		
			$result = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
			if (mysqli_affected_rows($dbc) == 1) { // if password updated successfully
				// send an email
				$body = "Your password to log into The Alternative Dictionary has been temporarily changed to '$p'. Please log in using this password and your username. At that time you may change your password to something more familiar.";
				mail ($_POST['email'], 'Your temporary password.', $body, 'From: passwordHelper@altdictionary.com');
				echo '<h3>Your password has been changed. You will receive the new, temporary password at the email address with which you registered. Once you have logged in with this password, you may change it by clicking on the "Change Password" link (at the bottom of the page).</h3>';

				mysqli_close($dbc); // close db connection and quit
				include ('./includes/footer.html');
				exit();				
			} else { // if it did not run ok
				echo '<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>'; 
			}		
		} else { // failed to find user
			echo '<p class="error">Please try again.</p>';		
		}

		mysqli_close($dbc); // close db connection
	} // end of the main SUBMIT conditional

?>


<h2>Reset Your Password</h2>
<p>Enter your email address below and your password will be reset. The new temporary password will be emailed to you, along with instructions for changing it.</p> 
<form id="passforgot" class="clearfix" action="forgot_password.php" method="post">
	<fieldset>
		<label for="email">Email Address:</label>
		<input type="email" name="email" id="email" placeholder="youraddress@example.com" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
	</fieldset>
	<input type="submit" name="submit" value="Reset My Password" />
	<input type="hidden" name="submitted" value="TRUE" />
</form>


<?php
	include ('./includes/footer.html');
?>
