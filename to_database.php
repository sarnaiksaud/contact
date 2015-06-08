<?php
	function createBatch($conn,$filename,$file_md5)
	{
		if (!$conn) {
			exit ("Connection failed.");
		}
	
		$stmt = oci_parse($conn, 
		"begin 
		create_batch(
		:filename,
		:file_md5,
		:batch_no
		);
		end;");

	oci_bind_by_name($stmt, "filename", $filename);
	oci_bind_by_name($stmt, "batch_no", $batch_no,-1);
	oci_bind_by_name($stmt, "file_md5", $file_md5);

	if (!oci_execute($stmt)) {
		return -1;
	}
	else
	{
		return $batch_no;
	}
	
	}
	
	function updateBatch($conn,$batch_no,$total_rec,$null_rec,$error_rec,$duplicate_rec,$success_rec)
	{
		if (!$conn) {
			exit ("Connection failed.");
		}
		$stmt = oci_parse($conn, 
		"begin 
		update_batch(
		:batch_no,
		:total_rec,
		:null_rec,
		:error_rec,
		:duplicate_rec,
		:success_rec
		);
		end;");

		oci_bind_by_name($stmt, "batch_no", $batch_no);
		oci_bind_by_name($stmt, "total_rec", $total_rec);
		oci_bind_by_name($stmt, "null_rec", $null_rec);
		oci_bind_by_name($stmt, "error_rec", $error_rec);
		oci_bind_by_name($stmt, "duplicate_rec", $duplicate_rec);
		oci_bind_by_name($stmt, "success_rec", $success_rec);
		
		
		if (!oci_execute($stmt)) {
		return -1;
		}
		else
		{
			return 1;
		}
	}
?>
<?php
function insert_to_db($conn,$name,$number,$email,$bday,$filename,$batch_no)
{
	// Connect to database
	if (!$conn) {
		exit ("Connection failed.");
	}
	
	if($name != null) $name = json_encode($name); else $name = null;
	if($number != null) $number = json_encode($number); else $number = null;
	if($email != null) $email = json_encode($email); else $email = null;
	if($bday != null) $bday = json_encode($bday); else $bday = null;
	
/*	echo $name;
 	echo "<br>";
	echo $number;
	echo "<br>";
	echo $email;
	echo "<br>";
	echo $bday;
	echo "-----------------------"; 
	echo $bday;*/
	
	$stmt = oci_parse($conn, 
		"begin 
		load_all_data(
		:name,
		:number,
		:email,
		:bday,
		:filename,
		:batch_no);
		end;");

	oci_bind_by_name($stmt, "name", $name);
	oci_bind_by_name($stmt, "number", $number);
	oci_bind_by_name($stmt, "email", $email);
	oci_bind_by_name($stmt, "bday", $bday);
	oci_bind_by_name($stmt, "filename", $filename);
	oci_bind_by_name($stmt, "batch_no", $batch_no);

	if (!oci_execute($stmt)) {
		return -1;
	}
	else
	{
		return 1;
	}
}
	?>
<?php
	function open_connection()
	{
		return oci_connect('Saud', 'saud', 'localhost');
	}
?>
<?php
	function close_connection($conn)
	{
		oci_close($conn);
	}
?>


<?php
	function isLoaded($conn,$filename,$file_md5)
	{
		if (!$conn) {
			exit ("Connection failed.");
		}
		$stmt = oci_parse($conn, 
		"begin 
		is_loaded(
		:filename,
		:file_md5,
		:batch_no);
		end;");

		oci_bind_by_name($stmt, "filename", $filename);
		oci_bind_by_name($stmt, "file_md5", $file_md5);
		oci_bind_by_name($stmt, "batch_no", $batch_no);
		
		
		if (!oci_execute($stmt)) {
			return -1;
		}
		else
		{
			return $batch_no;
		}
		
		}
?>
<?php
	function getStatus($conn,$batch_no,$file_md5)
	{
		if (!$conn) {
			exit ("Connection failed.");
		}
		$stmt = oci_parse($conn, 
		"begin 
		get_status(
		:file_md5,
		:batch_no,
		:status);
		end;");

		oci_bind_by_name($stmt, "file_md5", $file_md5);
		oci_bind_by_name($stmt, "batch_no", $batch_no);
		oci_bind_by_name($stmt, "status", $status);
		
		if (!oci_execute($stmt)) {
			return -1;
		}
		else
		{
			return $status;
		}
	}
?>
<?php
	function check_batch($conn,$file_md5)
	{
		if (!$conn) {
			exit ("Connection failed.");
		}
		
		$stmt = oci_parse($conn, 
		"begin 
		check_batch(
		:file_md5,
		:status);
		end;");

		oci_bind_by_name($stmt, "file_md5", $file_md5);
		oci_bind_by_name($stmt, "status", $status);
		
		if (!oci_execute($stmt)) {
			return false;
		}
		else
		{
			return $status;
		}
	}
?>
<?php
	function validateAll($conn,$batch_no,&$null_rows,&$error_rows,&$duplicate_rows)
	{
		
		if (!$conn) {
			exit ("Connection failed.");
		}
			$null_removal = oci_parse($conn,'begin remove_null_data.main(:null_rows); end;');
			$validation = oci_parse($conn,'begin validate_all_data.main(:batch_no,:error_rows,:duplicate_rows); end;');
			
			oci_bind_by_name($validation, "batch_no", $batch_no);
			oci_bind_by_name($null_removal, "null_rows", $null_rows);
			oci_bind_by_name($validation, "error_rows", $error_rows);
			oci_bind_by_name($validation, "duplicate_rows", $duplicate_rows);
			
			IF(oci_execute($null_removal))
			{
				
			}
		
			IF(oci_execute($validation))
			{
				
			}
			
	}
?>
<?php
	function load_tables($conn,$batch_no,&$rows_added)
	{
		
		if (!$conn) {
			exit ("Connection failed.");
		}
			$stmt = oci_parse($conn,'begin load_tables(:batch_no,:rows_added); end;');
			
			oci_bind_by_name($stmt, "batch_no", $batch_no);
			oci_bind_by_name($stmt, "rows_added", $rows_added);
			
			oci_execute($stmt);		
	}
?>
<?php
	function process_batch($conn,$batch_no,$status)
	{
		
		if (!$conn) {
			exit ("Connection failed.");
		}
		
		IF (!isset($status))
			die('Error processing file');
		
	
		IF($status == 'P')
			$process = oci_parse($conn,'begin process_batch.accept_batch(:batch_no,:status); end;');
		IF ($status == 'R')
			$process = oci_parse($conn,'begin process_batch.reject_batch(:batch_no,:status); end;');
		
		oci_bind_by_name($process, "batch_no", $batch_no);
		oci_bind_by_name($process, "status", $status);
		
		IF(oci_execute($process) == 1)
			return $status;
		 
	}
?>
<?php
	function get_stats($conn,$batch_no,&$filename,&$total_rec,&$null_rec,&$success_rec,&$error_rec,&$dup_rec)
	{
		if (!$conn) {
			exit ("Connection failed.");
		}
		
		$stmt = oci_parse($conn,'begin get_stats(
		:batch_no,
		:filename,
		:total_rec,
		:null_rec,
		:error_rec,
		:dup_rec,
		:success_rec); end;');
		
		
		
		oci_bind_by_name($stmt, "batch_no", $batch_no);
		oci_bind_by_name($stmt, "filename", $filename,200);
		oci_bind_by_name($stmt, "total_rec", $total_rec);
		oci_bind_by_name($stmt, "null_rec", $null_rec);
		oci_bind_by_name($stmt, "success_rec", $success_rec);
		oci_bind_by_name($stmt, "error_rec", $error_rec);
		oci_bind_by_name($stmt, "dup_rec", $dup_rec);
		
		oci_execute($stmt);
	}
?>
