<html>
<head><title>Choose file to display</title></head>
<body>
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
	
	$status = getStatus($conn,$batch_no,'');
	if($status == 'R')
	{
		// echo 'This batch has not been processed yet !';
		echo 'Rejected batch';
	}
	else
	{
		if($status == 'T')
			echo '<h3>BATCH IS STILL IN TEST MODE</h3>';
		
		$name = oci_parse($conn, "SELECT id_no,first_name,last_name FROM name where batch_no = " . $batch_no . " order by 2");

		if(oci_execute($name))
		{
			?>
			<table border=1>
				<tr>
					<th>Name</th>
					<th>Numbers</th>
					<th>Emails</th>
					<th>Bday</th>
				</tr>
			<?php
			while($name_row=oci_fetch_array($name))
			{
			?>
				<tr>
					<td><?=$name_row[1]?> <?=$name_row[2]?></td>
					<td>
					<?php
						$number = oci_parse($conn, "SELECT NUMBER_FOR FROM numbers where name_id_no = ".$name_row[0]." and batch_no = " . $batch_no . "");
						if(oci_execute($number))
						{
							while($number_row=oci_fetch_assoc($number))
							{
								echo $number_row['NUMBER_FOR']."<br>";
							}
						}
					?>
					</td>
					<td>
					<?php
						$email = oci_parse($conn, "SELECT EMAIL_ORG FROM email where name_id_no = ".$name_row[0]." and batch_no = " . $batch_no . "");
						if(oci_execute($email))
						{
							while($email_row=oci_fetch_assoc($email))
							{
								echo $email_row['EMAIL_ORG']."<br>";
							}
						}
					?>
					</td>
					<td>
					<?php
						$bday = oci_parse($conn, "SELECT * FROM bday where name_id_no = ".$name_row[0]." and batch_no = " . $batch_no . "");
						if(oci_execute($bday))
						{
							while($bday_row=oci_fetch_assoc($bday))
							{
								echo $bday_row['bday_org']."<br>";
							}
						}
					?></td>
				</tr>
			<?php
			}
			?>
			</table>
			<?php
		}
	}
	close_connection($conn);
	/* <Start Mod date="09-June-2015" >*/
	reset_execution_time();
	/* <End Mod date="09-June-2015" >*/
?>
</body>
</html>