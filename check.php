<?php

	IF(isset($_REQUEST['batch_no'])) 
		$batch_no = $_REQUEST['batch_no'];
	else
		die("no batch_id recieved");

	require_once('vCard_2.php');
	require_once('to_database.php');
	require_once('functions.php');
	/* <Start Mod date="09-June-2015" >*/
	set_execution_time();
	/* <End Mod date="09-June-2015" >*/
	
	$conn = open_connection();
	validateAll($conn,$batch_no);
	close_connection($conn);
	/* <Start Mod date="09-June-2015" >*/
	reset_execution_time();
	/* <End Mod date="09-June-2015" >*/
?>
<br>
<a href='table.php'><-- Return back to tables.</a>