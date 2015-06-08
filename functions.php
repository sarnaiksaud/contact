<?php
	function getFiles($dir)
	{
		$dir = rtrim($dir, '\\/');
		$result = array();
		$h = opendir($dir);
		while (($f = readdir($h)) !== false) 
		{
			if ($f !== '.' and $f !== '..') 
			{
					if (!is_dir("$dir/$f")) 
					{
						$path_parts = pathinfo($f);
						if(strtolower($path_parts['extension']) == "vcf")
							$result[] = $f;
					}
			}
		}
		closedir($h);
		return $result;
	}
?>
<?php
	function getName(vCard $vCard)
	{
		$name = array();
		$fname = null;
		$lname = null;
		foreach ($vCard -> N as $Name)
		{
			$fname = rTrim(lTrim($Name['FirstName']));
			$lname = rTrim(ltrim($Name['LastName']));
		}
		$name['fname'] = $fname;
		$name['lname'] = $lname;
		return $name;
	}
	
	function getNumbers(vCard $vCard)
	{
		$number = array();
		if ($vCard -> TEL)
		{
			foreach ($vCard -> TEL as $Tel)
			{
				array_push($number,$Tel);
			}
		}
		if(empty($number))
			return null;
		else
		{
			$number1['number'] = array_unique($number);
			return $number1;
		}
	}
	function getEmail(vCard $vCard)
	{
		$email = array();
		if ($vCard -> EMAIL)
		{
			foreach ($vCard -> EMAIL as $Email)
			{
				array_push($email,$Email);
			}
		}
		if(empty($email))
			return null;
		else
		{
			$email1['email'] = array_unique($email);
			return $email1;
		}
	}
	function getBday(vCard $vCard)
	{
		$bday = array();
		if($vCard -> BDAY)
		{
			foreach ($vCard -> BDAY as $Bday)
			{
				$originalDate = $Bday;
				$newDate = date("d-m-Y", strtotime($originalDate));
				if($newDate != date("d-m-Y", strtotime("01-01-1970")))
					array_push($bday,$newDate);
			}
		}
		if(empty($bday))
			return null;
		else
		{
			$bday1['bday'] = array_unique($bday);
			return $bday1;
		}
	}
?>
	
<?php
function storeData($conn,$dir,$filename)
	{
		$cnt = 0;
		//$dir = "../allVCF/";
		$name = array();
		$number = array();
		$email = array();
		$bday = array();
		
		//$result = getFiles ($dir);
		$file_md5 = md5_file($dir.$filename);
		if(check_batch($conn,$file_md5))
		{
		//foreach ($result  as $filename)
		//{
			
			$vCard = new vCard($dir.$filename,	false,array('Collapse' => false));
		
			if (count($vCard) == 0)
				throw new Exception('vCard test: empty vCard!');
				
			elseif (count($vCard) == 1)
			{
				$batch_no = createBatch($conn,$filename,$file_md5);
				if($batch_no == -1)
					die('Error adding batch');
				
					$name = getName($vCard);
					$number = getNumbers($vCard);
					$email = getEmail($vCard);
					$bday = getBday($vCard);
					if(insert_to_db($conn,$name,$number,$email,$bday,$filename,$batch_no) == 1)
				
				updateBatch($conn,$batch_no,1);
			}
			else
			{
				$batch_no = createBatch($conn,$filename,$file_md5);
				if($batch_no == -1)
					die('Error adding batch');
				
				$total_rec = 0;
				foreach ($vCard as $Index => $vCardPart)
				{
					$name = getName($vCardPart);
					$number = getNumbers($vCardPart);
					$email = getEmail($vCardPart);
					$bday = getBday($vCardPart);
					
					if(insert_to_db($conn,$name,$number,$email,$bday,$filename,$batch_no) == 1)
						$total_rec++;
				}
				
				validateAll($conn,$batch_no,$null_rec,$error_rec,$duplicate_rec);
				load_tables($conn,$batch_no,$success_rec);
				updateBatch($conn,$batch_no,$total_rec,$null_rec,$error_rec,$duplicate_rec,$success_rec);
			}
			//$cnt = $cnt + $i;
		//}
		}
		echo "<br>Records found : $total_rec";
		echo "<br>After check result<br>--------------------------------";
		echo "<br>Null Records : $null_rec";
		echo "<br>Error Records : $error_rec";
		echo "<br>Duplicate Records : $duplicate_rec";
		echo "<br>Success Records : $success_rec";;

		

			
	}
	?>


