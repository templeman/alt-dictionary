<?php #forgot_password.php
// This page allows a user to reset their password.

   // Include the configuration file for error management and such.
   require_once ('./includes/config.inc.php'); 

   // Set the page title and include the HTML header.
   $page_title = 'Forgot Your Password';
   include ('./includes/header.html');

   if (isset($_POST['submitted'])) { // Handle the form.

      require_once ('mysql_connect.php'); // Connect to the database.

      if (empty($_POST['email'])) { // Validate the email address.
	 $uid = FALSE;
	 echo '<p class="error">You forgot to enter your email address!</p>';
      } else {
	 // Check for the existence of that email address.
	 $query = "SELECT user_id FROM users WHERE email='".  escape_data($_POST['email']) . "'";		
	 $result = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	 if (mysqli_num_rows($result) == 1) {
	    // Retrieve the user ID.
	    list($uid) = mysqli_fetch_array ($result, MYSQL_NUM); 
	 } else {
	    echo '<p class="error">The submitted email address does not match those on file.</p>';
	    $uid = FALSE;
	 }
      }

      if ($uid) { // If everything's OK.
	 // Create a new, random password.
	 $p = substr ( md5(uniqid(rand(),1)), 3, 10);
	 // Make the query.
	 $query = "UPDATE users SET pass=SHA('$p') WHERE user_id=$uid";		
	 $result = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	 if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
	    // Send an email.
	    $body = "Your password to log into The Alternative Dictionary has been temporarily changed to '$p'. Please log in using this password and your username. At that time you may change your password to something more familiar.";
	    mail ($_POST['email'], 'Your temporary password.', $body, 'From: passwordHelper@altdictionary.com');
	    echo '<h3>Your password has been changed. You will receive the new, temporary password at the email address with which you registered. Once you have logged in with this password, you may change it by clicking on the "Change Password" link (at the bottom of the page).</h3>';
	    mysqli_close($dbc); // Close the database connection.
	    include ('./includes/footer.html'); // Include the HTML footer.
	    exit();				
	 } else { // If it did not run OK.
	    echo '<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>'; 
	 }		
      } else { // Failed the validation test.
	 echo '<p class="error">Please try again.</p>';		
      }

      mysqli_close($dbc); // Close the database connection.
   } // End of the main Submit conditional.
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
