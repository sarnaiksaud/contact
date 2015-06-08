<?php

	
	IF(isset($_REQUEST['batch_no'])) 
		$batch_no = $_REQUEST['batch_no'];
	else
		die("No batch_no specified");
	
		
	ini_set('max_execution_time', 30000); 
	require_once('vCard_2.php');
	require_once('to_database.php');
	require_once('functions.php');
	
	$conn = open_connection();
	
	$status = process_batch($conn,$batch_no,'R');
	
	IF($status == 1)
	{
?>
Batch has been rejected succesfully. :'(
<?php
	}
else
{
	?>
	Batch Already Processed.
	<?php
}
?>
<br>
<a href='table.php'><-- Return back to tables.</a>

<?php
	close_connection($conn);
	ini_set('max_execution_time', 300); 
?>