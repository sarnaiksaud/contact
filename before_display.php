<html>
<head><title>Choose file to display</title></head>
<?php
	require_once('vCard_2.php');
	require_once('to_database.php');
	require_once('functions.php');
	/* <Start Mod date="09-June-2015" >*/
	set_execution_time();
	/* <End Mod date="09-June-2015" >*/
	
	$conn = open_connection();
	$array = oci_parse($conn, "SELECT batch_no,file_name FROM batch_header where status = 'P'");

	if(oci_execute($array))
	{
		?>
		<table>
			<tr>
				<th>file name</th>
			</tr>
		<?php
		while($row=oci_fetch_array($array))
		{
		?>
			<tr>
				<?php echo "<td><a href='display.php?batch_no=".$row[0]."'>$row[1]</a></td>" ?>
			</tr>
		<?php
		}
		?>
		</table>
		<?php
	}
	close_connection($conn);
	/* <Start Mod date="09-June-2015" >*/
	reset_execution_time();
	/* <End Mod date="09-June-2015" >*/
?>
</html>