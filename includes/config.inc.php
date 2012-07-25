<?php #config.inc.php

// This script determines how errors are handled.

// Flag variable for site status:
$live = FALSE;

// Error log email address:
$email = 'templeman@me.com';

// Create the error handler.
function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {

	global $live, $email;

	// Build the error message.
	$message = "An error occurred in script '$e_file' on line $e_line: \n<br />$e_message\n<br />";
	
	// Add the date and time.
	$message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n<br />";
	
	// Append $e_vars to the $message.
	$message .= "<pre>" . print_r ($e_vars, 1) . "</pre>\n<br />";
	
	if ($live) { // Don't show the specific error.
	
		error_log ($message, 1, $email); // Send email.
		
		// Only print an error message if the error isn't a notice.
		if ($e_number != E_NOTICE) {
			echo '<div id="Error">A system error occurred. We apologize for the inconvenience.</div><br />';
		}
		
	} else { // Development (print the error).
		echo '<div id="Error">' . $message . '</div><br />';
	}

} // End of my_error_handler() definition.

// Use my error handler.
set_error_handler ('my_error_handler');
?>
