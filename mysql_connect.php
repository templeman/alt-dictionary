<?php 

	### mysql_connect.php
	### Database config

	// set database access information as constants
	DEFINE ('DB_USER', '');
	DEFINE ('DB_PASSWORD', '');
	DEFINE ('DB_HOST', '');
	DEFINE ('DB_NAME', '');

	// make connection and select database
	$dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	// check connection
	if (mysqli_connect_errno()) {
		print "Connect failed: ". mysqli_connect_error(); 
		exit();
	}

	// Improved MySQL Version:
	//$dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

	// utility function for escaping data
	function escape_data ($data) {

		// address magic quotes
		if (ini_get('magic_quotes_gpc')) {
			$data = stripslashes($data);
		}
		
		// improved MySQL version
		global $dbc;
		$data = mysqli_real_escape_string($dbc, trim($data));

		// return escaped value
		return $data;

	} // end of data escape function

?>
