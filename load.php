<?php

	
	IF(isset($_REQUEST['dir'])) 
		$dir = $_REQUEST['dir'];
	else
		die("No file dir specified");
	
	IF(isset($_REQUEST['filename'])) 
		$filename = $_REQUEST['filename'];
	else
		die("No file name specified");
	
	// check if file exists and then proceed.
	
	ini_set('max_execution_time', 30000); 
	require_once('vCard_2.php');
	require_once('to_database.php');
	require_once('functions.php');
	
	$conn = open_connection();
?>

File has been loaded successfully.<br>
<?php storeData($conn,$dir,$filename) . " "; ?>
<br>
<a href='table.php'><-- Return back to tables.</a>

<?php
	close_connection($conn);
	ini_set('max_execution_time', 300); 
?>