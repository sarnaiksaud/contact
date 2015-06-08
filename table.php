<html>
<head>
<title>Contacts to DATABASE</title>
</head>
<body>
<?php
	//ini_set('max_execution_time', 30000); 
	require_once('vCard_2.php');
	require_once('to_database.php');
	require_once('functions.php');

	IF(isset($_REQUEST['dir'])) 
		$dir = $_REQUEST['dir'];
	else
		$dir = "./allVCF/";	//default dir path
	
	$result = getFiles ($dir);
	if($result)
	{
?>
	<table border=1>
	<tr>
		<th>Batch Number</th>
		<th>File Name</th>
		<th>File MD5</th>
		<th>Loaded ? </th>
		<th>Process</th>
	</tr>
	<?php
		$conn = open_connection();
		
		foreach ($result as $filename)
		{
			$file_md5 = md5_file($dir.$filename);
			$ret = isLoaded($conn,$filename,$file_md5);
			echo "<tr>";
				IF($ret == -1)
				{					
					echo "<td><br></td>";
					echo "<td>$filename</td>";
					echo "<td>$file_md5</td>";
					echo "<td><a href='load.php?dir=$dir&filename=$filename'>Load this file</a></td>";
					echo "<td><br></td>";
				}
				ELSE
				{
					$status = getStatus($conn,$ret,$file_md5);
					
					echo "<td>$ret</td>";
					echo "<td><a href='stats.php?batch_no=$ret'>$filename</a></td>";
					echo "<td>$file_md5</td>";
					echo "<td><br></td>";
					IF ($status == 'T')
					{
						echo "<td>";
						echo "<a href='accept.php?batch_no=$ret'>Process</a>";
						echo "<br>";
						echo "<a href='reject.php?batch_no=$ret'>Reject</a>";
						echo "</td>";							
					}
					ELSE IF ($status == 'P')
					{
						echo "<td>";
						echo "Processed";
						echo "</td>";							
					}
					ELSE IF ($status == 'R')
					{
						echo "<td>";
						echo "Rejected";
						echo "</td>";							
					}
				}
			echo "</tr>";
		}
	?>
	</table>
<?php
	}
	else
	{
		echo "No VCF file found at location [<b>". $dir . "<b>]";
	}
	//echo storeData() . " ";
	
	
	/* echo "<pre>";
	print_r($name);
	print_r($number);
	print_r($email);
	print_r($bday);
	echo "<pre>"; */	
	
	/*echo "<pre>";
	print_r($result);
	echo "<pre>";
	*/
	//ini_set('max_execution_time', 300); 
?>
</body>
</html>