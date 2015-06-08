<?php

	IF(isset($_REQUEST['batch_no'])) 
		$batch_no = $_REQUEST['batch_no'];
	else
		die("no batch_id recieved");

	ini_set('max_execution_time', 30000); 
	require_once('vCard_2.php');
	require_once('to_database.php');
	require_once('functions.php');
	
	$conn = open_connection();
	validateAll($conn,$batch_no);
	close_connection($conn);
	ini_set('max_execution_time', 300); 
?>
<br>
<a href='table.php'><-- Return back to tables.</a>