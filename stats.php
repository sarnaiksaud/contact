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
	
	get_stats($conn,$batch_no,$filename,$total_rec,$empty_rec,$success_rec,$error_rec,$dup_rec);
	
	close_connection($conn);
	
?>
<html>
	<head>
		<title>Statistics of batch no. <?=$batch_no?></title>
	</head>
	<body>
		<table border=1>
			<tr>
				<td colspan=10>File Name : <?=$filename?></td>
			</tr>
			<tr>
				<td>Total Records</td>
				<td><?=$total_rec?></td>
			</tr>
			<tr>
				<td>Empty records</td>
				<td><?=$empty_rec?></td>
			</tr>
			<tr>
				<td>In Success</td>
				<td><?=$success_rec?></td>
			</tr>
			<tr>
				<td>In Error</td>
				<td><?=$error_rec?></td>
			</tr>
			<tr>
				<td>Duplicate</td>
				<td><?=$dup_rec?></td>
			</tr>
		</table>
		<a href="display.php?batch_no=<?=$batch_no?>">View File</a>
	</body>
</html>