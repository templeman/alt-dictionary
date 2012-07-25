<?php 

	### register.php
	### Registration page for new users only

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Register';
	include ('./includes/header.html');

	if (isset($_POST['submitted'])) { // handle the form

		require_once ('mysql_connect.php');
	
		// check for first name
		$namepattern = '/^[[:alpha:]\.\' \-]{2,15}$/i';
		if (preg_match($namepattern, stripslashes(trim($_POST['first_name'])))) {
			// make sure to capitalize the first letter of the name
			// the strtok() funtion truncates any extra words
			// (if user entered both first and last names)
			$fn = ucfirst(strtolower(strtok((escape_data($_POST['first_name'])), " ")));
		} else {
			$fn = FALSE;
			echo '<p class="error">Please enter your first name.</p>';
		}

		// check for email address
		$emailpattern = '/^[[:alnum:]][a-z0-9_\.\-\+]*@[a-z0-9\.\-]+\.[a-z]{2,4}$/i';
		if (preg_match($emailpattern, stripslashes(trim($_POST['email'])))) {
			$e = escape_data($_POST['email']);
		} else {
			$e = FALSE;
			echo '<p class="error">Please enter a valid email address.</p>';
		}

		// check for a password and match against the confirmed password
		$passpattern = '/^[[:alnum:]]{4,20}$/i';
		if (preg_match($passpattern, stripslashes(trim($_POST['password1'])))) {
			if ($_POST['password1'] == $_POST['password2']) {
				$p = escape_data($_POST['password1']);
			} else {
				$p = FALSE;
				echo '<p class="error">Your password did not match the confirmed password.</p>';
			}
		} else {
			$p = FALSE;
			echo '<p class="error">Please enter a valid password.</p>';
		}
	
		// check that Terms have been accepted
		if (isset($_POST['terms'])) {
			$t = escape_data($_POST['terms']);
		} else {
			$t = FALSE;
			echo '<p class="error">You must accept the terms of use before continuing.</p>';
		}
	
		if ($fn && $e && $p && $t) { // if everything's ok

			// is the email address available?
			$query = "SELECT user_id FROM users WHERE email='$e'";		
			$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		
			if (mysqli_num_rows($result) == 0) { // available
		
				// create activation code
				$a = md5(uniqid(rand(), true));
		
				// add the user
				$query = "INSERT INTO users (email, pass, first_name, active, registration_date) VALUES ('$e', SHA('$p'), '$fn', '$a', NOW())";
				$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));

				if (mysqli_affected_rows($dbc) == 1) { // if it ran ok
			
					// send the email
					$body = "Thank you for your interest in The Alternative Dictionary! To get started, please click on this link:\n\n";
					$body .= "http://www.altdictionary.samueltempleman.com/wdim387/altdictionary/activate.php?x=" . mysqli_insert_id($dbc) . "&y=$a";
					mail($_POST['email'], 'Registration Confirmation', $body, 'From: welcome_gnome@altdictionary.com');
				
					// print message and finish the page
					echo '<h3>Thank you for registering!</h3>
					<p>There is just one final step and you will be finished. A confirmation email has been sent to your address. Please click on the link in that email in order to activate your account. If you do not receive your confirmation within 15 minutes, it may have been marked as Spam. Check your Spam or Junk folder.</p>
					<p>Thank you and have an alternative day.</p>';
					include ('./includes/footer.html');
					exit();				
				
				} else { // if it did not run ok
					echo '<p class="error">Dag nabbit. You could not be registered due to a system error. You are just too alternative for your own good.</p>'; 
				}
			
			} else { // the email address is unavailable
				echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link above to have your password sent to you.</p>'; 
			}
		
		} else { // one of the data tests failed
			echo '<p class="error">Please try again.</p>';		
		}

		mysqli_close($dbc); // close db connection

	} // end of main SUBMIT conditional

?>
	

<h2>Please help make the Alt Dictionary better with your contributions.</h2>
<form id="register" class="clearfix" action="register.php" method="post">
	<fieldset>
		<label for="first_name">First Name:</label>
		<input type="text" id="first_name" name="first_name" placeholder="your alternative first name" value="<?php if (isset($_POST['first_name'])) 	echo $_POST['first_name'];
		else echo '' ?>"/> 

		<label for="email">Email Address:</label>	
		<input type="email" id="email" name="email" placeholder="youraddress@example.com" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />

		<label for="password1">Password:</label>
		<input type="password" id="password1" name="password1" />

		<label for="password2">Confirm Password:</label>	
		<input type="password" id="password2" name="password2" />

		<div id="termBox"><input type="checkbox" name="terms" />
		Yes, I agree to the <span id="terms"><a href="#">terms</a></span>.</div>

		<input type="submit" name="submit" value="Register" />
		<input type="hidden" name="submitted" value="TRUE" />
	</fieldset>
</form>

<script>
	$(function() {
	
	var $dialog = $('<div></div>')
		.html('<h4>Terms of Use</h4><p>We are thrilled that you are interested in The Alternative Dictionary (The Website). Use of The Website and the services offered require your agreement to the following terms. By registering and using The Website services you have agreed to the terms below.</p><h4>Privacy Policy</h4><p>By contributing to The Website, either by submitting digital drawings or leaving feedback such as comments or votes, you waive the right to any claim of copyright over these contributions. Any such contributions, once submitted, may be publicly viewable on The Website. However, any personal information you share on this site, such as email addresses or passwords, will be divulged to no one else whomsoever, neither individual nor corporate. We (The Developers) would also like to remind you that these services are intended solely for your amusement and diversion, and nothing more. The Developers reserve the right to remove any user contributions to the site at any time, for any reason, without seeking the consent of the contributor.</p>')
		.dialog({
			autoOpen: false,
			resizable: false,
			dialogClass: "termsDialog", 
			modal: true,
			width: 700,
			title: 'Terms and Privacy'
		});
	
	$('#terms').click(function() {
		$dialog.dialog('open');
		// prevent the default action, e.g., following a link
		return false;
	});
});
	

	$(".ui-widget-overlay").live("click", function (){
		$("div:ui-dialog:visible").dialog("close");
	});

</script>


<?php
	include ('./includes/footer.html');
?>
