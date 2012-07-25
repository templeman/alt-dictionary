<?php 

	### index.php 
	### Home page with register and login links

	// file setup
	require_once ('./includes/config.inc.php'); 
	$page_title = 'Welcome | The Alternative Dictionary';
	include ('./includes/header.html');

?>

<h1>[alternative] dic&bull;tion&bull;ar&bull;y |'dik sh,nere | (abbr.: dict.)</h1>
<h2>noun (pl. -ar&bull;ies)</h2>
<ul class="tagline">
	<li>a dictionary that uses images as descriptors</li>
	<li>a publicly-edited dictionary</li>
	<li>an online catalogue of all things, whose definitions are subjective, rather than objective</li>
	<li>whatever you want it to be</li>
</ul>
<div id="buttons">
	<a class="big button" href="login.php">Log In</a>
	or
	<a class="big button" href="register.php">Sign Up</a>
</div>

<?php
	include ('./includes/footer.html');
?>
