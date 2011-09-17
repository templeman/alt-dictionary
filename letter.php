<?php #letter.php 
// Letter page dynamically presents all words for a particular letter at the letter level.

   // Include the configuration file for error management and such.
   require_once ('./includes/config.inc.php'); 

   // Set the page title and include the HTML header.
   $page_title = 'Some Letter | The Alternative Dictionary';
	include ('./includes/header.html');

   // Get letter, $_GET['l'], accessed from URL.
   if (isset($_GET['l'])) { 
      $l = (int) $_GET['l'];
     // $_SESSION['letter_id'] = $l;

   } else { // The page has been accessed incorrectly. Redirect user to login.php.

      // Start defining the URL.
      $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
      // Check for a trailing slash.
      if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
	 $url = substr ($url, 0, -1); // Chop off the slash.
      }
      // Add the page.
      $url .= '/login.php';

      ob_end_clean(); // Delete the buffer.
      header("Location: $url");

      exit();
   } // End of main IS-LETTER conditional

?>


<?php
   include ('./includes/footer.html');
?>
