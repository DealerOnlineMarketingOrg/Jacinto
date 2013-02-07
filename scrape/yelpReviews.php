<?php 
	$url = $_POST['url'];
	$yahoopage = file_get_contents($url);
	echo $yahoopage;
?>
