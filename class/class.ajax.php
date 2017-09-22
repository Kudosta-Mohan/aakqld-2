<?php
require_once '../dbconfig.php';
	
$action = $_REQUEST['action'];
//	$actionss = $_POST['action'];
	
	
if($action === 'onchangecustomerjobform')
{

	$cust_id = $_POST['cust_id'];
	$customers = $customer->customerdata($cust_id);
	$contacts = $customer->getcustomerscontactlist($cust_id);
	$address = $customers['cust_address'];
	$flag = $customers['cust_flaggedpayer'];
	$rporder = $customers['cust_rporder'];
	$contactstring = '';
	$contactstring .= '<option value="" >Select Contact</option>';
		foreach($contacts as $contact)
			{
			$contactstring .= '<option value="'.$contact['cont_id'].'" > '.$contact['cont_name'].'</option>';
			}
	
	echo $output = json_encode( array('address' => $address, 'contact' => $contactstring, 'flag' => $flag, 'rporder' => $rporder) );
	exit;	
}
	
	
if($action === 'onchangecranetype')
{

	$equi_id = $_POST['equi_id'];
	$equiid_ss = explode(',',$equi_id);
	$equipmentchild = array();
		for($c = 0; $c < count($equiid_ss); $c++)
			{
				$equipments = $equipment->equipmentdata($equiid_ss[$c]);
				
				$equipmentsize = $equipment->equimentsize($equiid_ss[$c]);
				
				$quiid = $equipments['equi_id'];
				$quiname = $equipments['equi_name'];
				$type =$equipments['equi_type'];
				$sizes = json_decode($equipments['equi_size']);
				$contactstring = '';
				$contactstring .= '<select name="size_'.$quiid.'" id='.$quiid.' class="form-control" style="margin-top:10px;"><option value="" >'.$quiname.'</option>';
					foreach($equipmentsize as $equipmentsizes)
						{
						$contactstring .= '<option value="'.$equipmentsizes['id'].'" > '.$equipmentsizes['equipments_size'].'</option>';
						}
					$contactstring .= '</select>';
					$equipmentchild[] =array('size' => $contactstring, 'type' => $type,);
			}
	
	echo  $output = json_encode($equipmentchild);
	exit;	
}
	
	
if($action === 'getcustomerlist')
{

	$search = $_POST['search'];
	
	$customers = $customer->getcustomersnamewiselist($search);
	$output = '';
	
		if(is_array($customers))
			{
				$output .='<ul class="dropdown-menu clientsearchresultboxinner" role="menu">';
					foreach($customers as $customer)
					{
						$cust_id = $customer['cust_id'];
						$cust_name = $customer['cust_name'];
						$cust_flag = $customer['cust_flaggedpayer'];
						$cust_rporder = $customer['cust_rporder'];
						$output .='<li><a class="getclientid" data_reorder="'.$cust_rporder.'" data_flag="'.$cust_flag.'" data-id="'.$cust_id.'" data-name="'.$cust_name.'"><span class="text">'.$cust_name.'</span></a></li>';
					}
				$output .='</ul>';	
			} 
			else 
			{
			$output = '2';	
			}
	
	echo $output;
	exit;	
}
	
if($action === 'getcustomercontactlist')
{
	$clientid = $_POST['clientid'];
	$search = $_POST['search'];
	$contactsdata = $customer->getcustomerscontactnamewiselist($search,$clientid);
	$output = '';
		if(is_array($contactsdata))
			{
				$output .='<ul class="dropdown-menu clientsearchresultboxinner" role="menu">';
					foreach($contactsdata as $contact)
						{
							$cont_id = $contact['cont_id'];
							$cont_name = $contact['cont_name'];
							$output .='<li><a class="getcontactid" data-id="'.$cont_id.'" data-name="'.$cont_name.'"><span class="text">'.$cont_name.'</span></a></li>';
						}
				$output .='</ul>';	
			} 
			else 
			{
			$output = '2';	
			}
	
	echo $output;
	exit;	
}
	
	
if($action === 'changejobprocess')
{
	$job_id = $_REQUEST['jobid'];
	$editby = $_REQUEST['editby'];
	$job_status_processed = '1';
	$jobidsequence = $_REQUEST['jobidsequence'];
	if($jobidsequence != 0)
		{
		$jobs = $job->updatedjobstatusprocesssequence($job_status_processed,$editby,$jobidsequence);
		}
		else 
		{
		$jobs = $job->updatedjobstatusprocess($job_id,$job_status_processed,$editby);
		}
	exit;	
}
	
if($action === 'changejobbilled')
{
	$job_id = $_REQUEST['jobid'];
	$editby = $_REQUEST['editby'];
	$jobinvoice = $_REQUEST['jobinvoice'];
	$jobidsequencebilled = $_REQUEST['jobidsequencebilled'];
	$job_status_billed = '1';
	if($jobidsequencebilled != 0)
	{
	
	$jobs = $job->updatedjobstatusprocesssequencebilled($job_status_billed,$editby,$jobinvoice,$jobidsequencebilled);
	
	}
	else 
	{
	$jobs = $job->updatedjobstatus($job_id,$job_status_billed,$editby,$jobinvoice);	
	}
exit;	
}
		
if($action === 'changejobunprocess')
{
	$job_id = $_REQUEST['jobid'];
	$editby = $_REQUEST['editby'];
	$job_status_processed = '0';
	$jobidsequence = $_REQUEST['jobidsequence'];
	if($jobidsequence != 0)
		{
			$jobs = $job->updatedjobstatusprocesssequence($job_status_processed,$editby,$jobidsequence);
		}
	else 
		{
			$jobs = $job->updatedjobstatusprocess($job_id,$job_status_processed,$editby);
		}
exit;	
}
if($action === 'changejobunbilled')
{
	$job_id = $_REQUEST['jobid'];
	$editby = $_REQUEST['editby'];
	$jobinvoice = $_REQUEST['jobinvoice'];
	$jobidsequencebilled = $_REQUEST['jobidsequencebilled'];
	$job_status_billed = '0';
	if($jobidsequencebilled != 0)
		{
			$jobs = $job->updatedjobstatusprocesssequencebilled($job_status_billed,$editby,$jobinvoice,$jobidsequencebilled);
		}
	else 
		{
			$jobs = $job->updatedjobstatus($job_id,$job_status_billed,$editby,$jobinvoice);	
		}
exit;	
}
	
if($action === 'onchangecranetypess')
{
	$job_id = $_REQUEST['jobid'];
	$selectval = $_REQUEST['selectval'];
	$cranesizes = $_REQUEST['cranesizes'];
	$jobs = $job->updatedcranesizes($job_id,$selectval,$cranesizes);
	exit;	
}
	
if($action === 'equiuploadimages')
{
$upload_dir = '../attachment/';
$uploaddir = 'attachment/';

if(isset($_FILES["myfile"]))
	{
	$imagepaths = '';
	
	//	This is for custom errors;	
	/*	$custom_error= array();
	$custom_error['jquery-upload-file-error']="File already exists";
	echo json_encode($custom_error);
	
	*/
	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
		$t=time();
		chmod($upload_dir, 777);
		$eid=$_REQUEST['eid'];
		$fileName = $_FILES["myfile"]["name"];
		$replacefor = array('-');
		$replacewith = array('#',' ');
		$fileName = str_replace($replacewith, $replacefor, $fileName);
		move_uploaded_file($_FILES["myfile"]["tmp_name"],$upload_dir.$t.'-'.$fileName);
		//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
		if($eid)
		{
			$imagepaths .='<input type="hidden" class="form-control" id="picedit" name="equipmentsize['.$eid.'][]"  value="'.$uploaddir.$t.'-'.$fileName.'">
			<input type="hidden" class="form-control" id="picedit" name="equipmentsize['.$eid.'][]"  value="'.$fileName.'">
			<input type="hidden" class="form-control" id="picedit_id" name="picedit_id[]"  value="'.$eid.'">';
		}
		else
		{
			$imagepaths .='<input type="hidden" class="form-control" id="picedit" name="equipmentimagename[image][]"  value="'.$uploaddir.$t.'-'.$fileName.'">
			<input type="hidden" class="form-control" id="picedit" name="equipmentimagename[imagename][]"  value="'.$fileName.'">';
		}
	}
	else  //Multiple files, file[]
	{
		$fileCount = count($_FILES["myfile"]["name"]);
		for($i=0; $i < $fileCount; $i++)
		{
		$t=time();
		chmod($upload_dir, 777);
		$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$upload_dir.$t.'-'.$fileName);
		//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
		if($eid){
		$imagepaths .='<input type="hidden" class="form-control" id="picedit" name="equipmentsize['.$eid.'][]"  value="'.$uploaddir.$t.'-'.$fileName.'">
		<input type="hidden" class="form-control" id="picedit" name="equipmentsize['.$eid.'][]"  value="'.$fileName.'">
		<input type="hidden" class="form-control" id="picedit_id" name="picedit_id[]"  value="'.$eid.'">';
		}
		else{
		$imagepaths .='<input type="hidden" class="form-control" id="picedit" name="equipmentimagename[image][]"  value="'.$uploaddir.$t.'-'.$fileName.'">
		<input type="hidden" class="form-control" id="picedit" name="equipmentimagename[imagename][]"  value="'.$fileName.'">';
		}
		}
		
		}
	echo $imagepaths;
	}

exit;	
}
	
	if($action === 'attachuploadimages')
		{
		
		$upload_dir = '../attachment/';
		$uploaddir = 'attachment/';
		
		if(isset($_FILES["myfile"]))
			{	
			$imagepaths = '';
			
			//	This is for custom errors;	
			/*	$custom_error= array();
			$custom_error['jquery-upload-file-error']="File already exists";
			echo json_encode($custom_error);
			
			*/
			$error =$_FILES["myfile"]["error"];
			//You need to handle  both cases
			//If Any browser does not support serializing of multiple files using FormData() 
			if(!is_array($_FILES["myfile"]["name"])) //single file
				{
				$t=time();
				chmod($upload_dir, 777);
				
				$fileName = $_FILES["myfile"]["name"];
				$replacefor = array('-');
				$replacewith = array('#',' ');
				$fileName = str_replace($replacewith, $replacefor, $fileName);
				move_uploaded_file($_FILES["myfile"]["tmp_name"],$upload_dir.$t.'-'.$fileName);
				//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
				$imagepaths .='<input type="hidden" class="form-control" id="picedit1" name="picedit1[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
				<input type="hidden" class="form-control" id="picedit1" name="piceditname1[]"  value="'.$fileName.'">';
				}
			else  //Multiple files, file[]
				{
				$fileCount = count($_FILES["myfile"]["name"]);
				for($i=0; $i < $fileCount; $i++)
				{
				$t=time();
				chmod($upload_dir, 777);
				$fileName = $_FILES["myfile"]["name"][$i];
				move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$upload_dir.$t.'-'.$fileName);
				//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
				$imagepaths .='<input type="hidden" class="form-control" id="picedit1" name="picedit1[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
				<input type="hidden" class="form-control" id="picedit1" name="piceditname1[]"  value="'.$fileName.'">';
				}
				
				}
			echo $imagepaths;
			}
		
		exit;	
		}
	if($action === 'uploadimages')
		{
		
		$upload_dir = '../attachment/';
		$uploaddir = 'attachment/';
		
		if(isset($_FILES["myfile"]))
			{
			$imagepaths = '';
			
			//	This is for custom errors;	
			/*	$custom_error= array();
			$custom_error['jquery-upload-file-error']="File already exists";
			echo json_encode($custom_error);
			
			*/
			$error =$_FILES["myfile"]["error"];
			//You need to handle  both cases
			//If Any browser does not support serializing of multiple files using FormData() 
			if(!is_array($_FILES["myfile"]["name"])) //single file
				{
				$t=time();
				chmod($upload_dir, 777);
				
				$fileName = $_FILES["myfile"]["name"];
				$replacefor = array('-');
				$replacewith = array('#',' ');
				$fileName = str_replace($replacewith, $replacefor, $fileName);
				move_uploaded_file($_FILES["myfile"]["tmp_name"],$upload_dir.$t.'-'.$fileName);
				//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
				$imagepaths .='<input type="hidden" class="form-control" id="picedit" name="picedit[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
				<input type="hidden" class="form-control" id="picedit" name="piceditname[]"  value="'.$fileName.'">';
				}
			else  //Multiple files, file[]
				{
				$fileCount = count($_FILES["myfile"]["name"]);
				for($i=0; $i < $fileCount; $i++)
				{
				$t=time();
				chmod($upload_dir, 777);
				$fileName = $_FILES["myfile"]["name"][$i];
				move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$upload_dir.$t.'-'.$fileName);
				//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
				$imagepaths .='<input type="hidden" class="form-control" id="picedit" name="picedit[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
				<input type="hidden" class="form-control" id="picedit" name="piceditname[]"  value="'.$fileName.'">';
				}
				
				}
			echo $imagepaths;
			}
		
		exit;	
		}

	if($action === 'uploadfruitimages')
		{
		
		$upload_dir = '../attachment/';
		$uploaddir = 'attachment/';
		
		if(isset($_FILES["myfile"]))
			{
			$imagepaths = '';
			
			//	This is for custom errors;	
			/*	$custom_error= array();
			$custom_error['jquery-upload-file-error']="File already exists";
			echo json_encode($custom_error);
			
			*/
			$error =$_FILES["myfile"]["error"];
			//You need to handle  both cases
			//If Any browser does not support serializing of multiple files using FormData() 
			if(!is_array($_FILES["myfile"]["name"])) //single file
				{
				$t=time();
				chmod($upload_dir, 777);
				
				$fileName = $_FILES["myfile"]["name"];
				$replacefor = array('-');
				$replacewith = array('#',' ');
				$fileName = str_replace($replacewith, $replacefor, $fileName);
				move_uploaded_file($_FILES["myfile"]["tmp_name"],$upload_dir.$t.'-'.$fileName);
				//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
				$imagepaths .='<input type="hidden" class="form-control" id="fruitimgpath" name="fruitimgpath"  value="'.$uploaddir.$t.'-'.$fileName.'">
				<input type="hidden" class="form-control" id="fruitimgname" name="fruitimgname"  value="'.$fileName.'">';
				}
			else  //Multiple files, file[]
				{
				$fileCount = count($_FILES["myfile"]["name"]);
				for($i=0; $i < $fileCount; $i++)
				{
				$t=time();
				chmod($upload_dir, 777);
				$fileName = $_FILES["myfile"]["name"][$i];
				move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$upload_dir.$t.'-'.$fileName);
				//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
				$imagepaths .='<input type="hidden" class="form-control" id="fruitimgpath" name="fruitimgpath"  value="'.$uploaddir.$t.'-'.$fileName.'">
				<input type="hidden" class="form-control" id="fruitimgname" name="fruitimgname"  value="'.$fileName.'">';
				}
				
				}
			echo $imagepaths;
			}
		
		exit;	
		}	
	
	if($action === 'mailuploadimages')
		{
		
		$upload_dir = '../attachment/';
		$uploaddir = 'attachment/';
		
		if(isset($_FILES["mailfile"]))
			{
			$imagepaths = '';
			
			//	This is for custom errors;	
			/*	$custom_error= array();
			$custom_error['jquery-upload-file-error']="File already exists";
			echo json_encode($custom_error);
			
			*/
			$error =$_FILES["mailfile"]["error"];
			//You need to handle  both cases
			//If Any browser does not support serializing of multiple files using FormData() 
			if(!is_array($_FILES["mailfile"]["name"])) //single file
				{
				$t=time();
				chmod($upload_dir, 777);
				
				$fileName = $_FILES["mailfile"]["name"];
				$replacefor = array('-');
				$replacewith = array('#',' ');
				$fileName = str_replace($replacewith, $replacefor, $fileName);
				move_uploaded_file($_FILES["mailfile"]["tmp_name"],$upload_dir.$t.'-'.$fileName);
				//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
				$imagepaths .='<input type="hidden" class="form-control maiilpdfpath" id="picedit" name="mailattachmentpath[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
				<input type="hidden" class="form-control mailimages" id="picedit" name="mailattachmentname[]"  value="'.$fileName.'">';
				}
			else  //Multiple files, file[]
				{
				$fileCount = count($_FILES["mailfile"]["name"]);
				for($i=0; $i < $fileCount; $i++)
					{
					$t=time();
					chmod($upload_dir, 777);
					$fileName = $_FILES["mailfile"]["name"][$i];
					move_uploaded_file($_FILES["mailfile"]["tmp_name"][$i],$upload_dir.$t.'-'.$fileName);
					//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
					$imagepaths .='<input type="hidden" class="form-control" id="picedit" name="mailattachmentpath[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
					<input type="hidden" class="form-control" id="picedit" name="mailattachmentname[]"  value="'.$fileName.'">';
					}
				
				}
			echo $imagepaths;
			}
		
		exit;	
		}
	
	if($action === 'uploadimagessecure')
		{
		$upload_dir = '../attachment/';
		$uploaddir = 'attachment/';
		
		if(isset($_FILES["myfilesecure"]))
			{
			$imagepaths1 = '';
			
			//	This is for custom errors;	
			/*	$custom_error= array();
			$custom_error['jquery-upload-file-error']="File already exists";
			echo json_encode($custom_error);
			
			*/
			$error =$_FILES["myfilesecure"]["error"];
			//You need to handle  both cases
			//If Any browser does not support serializing of multiple files using FormData() 
			if(!is_array($_FILES["myfilesecure"]["name"])) //single file
				{
				$t=time();
				chmod($upload_dir, 777);
				
				$fileName = $_FILES["myfilesecure"]["name"];
				$replacefor = array('-');
				$replacewith = array('#',' ');
				$fileName = str_replace($replacewith, $replacefor, $fileName);
				move_uploaded_file($_FILES["myfilesecure"]["tmp_name"],$upload_dir.$t.'-'.$fileName);
				//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
				$imagepaths1 .='<input type="hidden" class="form-control" id="picedit" name="securepicedit[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
				<input type="hidden" class="form-control" id="picedit" name="securepiceditname[]"  value="'.$fileName.'">';
				}
			else  //Multiple files, file[]
				{
				$fileCount = count($_FILES["myfilesecure"]["name"]);
				for($i=0; $i < $fileCount; $i++)
					{
					$t=time();
					chmod($upload_dir, 777);
					$fileName = $_FILES["myfilesecure"]["name"][$i];
					move_uploaded_file($_FILES["myfilesecure"]["tmp_name"][$i],$upload_dir.$t.'-'.$fileName);
					//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
					$imagepaths1 .='<input type="hidden" class="form-control" id="securepicedit" name="securepicedit[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
					<input type="hidden" class="form-control" id="securepicedit" name="securepiceditname[]"  value="'.$fileName.'">';
					}
				}
			echo $imagepaths1;
			}
		exit;	
		}
	
	if($action === 'notificationupdate')
		{
		$userid = $_REQUEST['userid'];
		$notification_status = 'Seen';
		$jobs = $job->updatednotificationstatus($userid,$notification_status);
		//print_r($jobs);
		exit;	
		}
	
	if($action === 'notiautoupdate')
		{
		
		$appendjobrow = '';
		$tbc_notificationdsf = 1;
		
		$datefd = isset($_GET['date']) ? $_GET['date'] : date('d/m/Y'); 
		$datefd = str_replace('/', '-', $datefd);
		$todaydatesd = date("Y-m-d");	
		$atodaydategf = strtotime($todaydatesd);
		$tbc_datesd = date('d/m/Y', strtotime($datefd .' +3 day'));
		$tbc_jobnotificationsa = $job->tbcnotification($atodaydategf,$tbc_notificationdsf);
			foreach($tbc_jobnotificationsa as $tbc_jobnotificationsssa)
				{
				$customerdatassssa = $customer->customerdata($tbc_jobnotificationsssa['job_clie_id']);
				$userfieldsssa = $user->loginuserdata($tbc_jobnotificationsssa['job_createdby']);
				$notificatiionslkj = $job->getnotificationalltbcjob($tbc_jobnotificationsssa['id'],$_SESSION['user_session']);
					foreach($notificatiionslkj as $notificatiionsasmnb)
						{
							if($tbc_jobnotificationsssa['job_date'] <= $tbc_datesd)
								{
								
								$appendjobrow .= '<a href="'.home_base_url().'job/view_job.php?id='.$tbc_jobnotificationsssa["id"].'" class="joblising '.$notificatiionsasmnb['noti_status'].'"><div class="jobs"><strong>'.$userfieldsssa['first_name'].'</strong>&nbsp; Added a new job in TBC &nbsp;<strong>'.$customerdatassssa['cust_name'].'</strong>&nbsp; on &nbsp;<strong>'. $tbc_jobnotificationsssa['job_date'].'&nbsp;&nbsp;'.$tbc_jobnotificationsssa['job_time'].'</strong> </div></a>';   
								} 
						}
				}
		$userid = $_REQUEST['userid'];
		$notificationjobs = $job->getnotificationjob($userid);
		$countnotijob = count($notificationjobs);
		$notificationalljobs = $job->getnotificationalljob($_SESSION['user_session']);
		
		foreach($notificationalljobs as $notificationjob)
				{ 
				$jobdata = $job->getjobbyid($notificationjob['noti_job_id']);
				$equipments = $equipment->equipmentdata($jobdata['job_equi_id']);
				$customers = $customer->customerdata($jobdata['job_clie_id']);
				$userfield = $user->loginuserdata($jobdata['job_createdby']);
				$noti_status = $notificationjob['noti_status'];
				$noti_type = $notificationjob['noti_type'];
				$eq_id = $notificationjob['eq_id'];
				$noti_date = $notificationjob['updated_date'];
				$sequence_id = $notificationjob['sequence_id'];
				$job_date_change = $notificationjob['job_date_change'];
				$job_crn_change = $notificationjob['job_crn_change'];
				$ext_mgs_edit = $notificationjob['ext_mgs_edit'];
				$tbc_notification = $notificationjob['tbc_notification'];
				$noti_date_array = explode(" ",$noti_date);
				$date_in_12_hour_format = date("Y/m/d", strtotime($noti_date_array[0]));
				$time_in_12_hour_format = date("g:i a", strtotime($noti_date_array[1]));
				//print_r($noti_date_array);
					if($eq_id != '' && $eq_id != 0) 
						{
						$eq_equipment = $job->equipmentdata_value($eq_id);
						$equi_name11 = $eq_equipment['equi_name'];
						}
					if($noti_type == 'Added a new equipment' || $noti_type == 'Changed equipment')
						{
						
						$appendjobrow .= '<a href="'.home_base_url().'equipment/equipments.php" class="joblising '. $noti_status.'"><div class="jobs"><strong>'.$noti_type.':'.$equi_name11.'</strong></div></a>';	
						} else {
				if($tbc_notification == 0)
					{
					$appendjobrow .= '<a href="'.home_base_url().'job/view_job.php?id='.$jobdata["id"].'" class="joblising '. $noti_status.'">
					<div class="jobs"><strong>' . $time_in_12_hour_format.'</strong> - <strong>'.$userfield["first_name"].'</strong> ';
					if($job_crn_change == 1 && $job_date_change == 1 && $ext_mgs_edit == 0)
						 { 
						$appendjobrow .= 'changed Crane Type and Date on job';
						}
						 else if($job_crn_change == 1 && $job_date_change == '0' && $ext_mgs_edit == 0)
							{
							$appendjobrow .= 'changed the Crane Type on job';	
							}
					 	else if($job_crn_change == '0' && $job_date_change == 1 && $ext_mgs_edit == 0)
							 { 
							$appendjobrow .= 'changed the Date on job';	
							}
					 	else if($noti_type == 'Added a new job' && $sequence_id == 0)
							 {
							$appendjobrow .= 'added a new job';
							} 
						else if($noti_type == 'Added a new job' && $sequence_id == 1)
							{
							$appendjobrow .= 'added a new Sequence Job';
							} 
						else if($noti_type == 'Changed Job')
							{
							$appendjobrow .= 'updated job';
							} 
						else
							{	
							$appendjobrow .= 'removed job';
							}
					$appendjobrow .=' for <strong>'. $customers["cust_name"] .'</strong></div>
					
					</a>';			
					}
				}
			}
			echo $output = json_encode( array('conutnoti' => $countnotijob, 'appenddata' => $appendjobrow,) );
			exit;	
		}
		
	/*if($actionss === 'addnewjob')
		{
			$startdate = $_POST['sequencejobstart'];
			$enddate = $_POST['sequencejobend'];
			$startdate = str_replace('/', '-', $startdate);
			$enddate = str_replace('/', '-', $enddate);
			$start = strtotime($startdate);
			$end = strtotime($enddate);
				if($startdate)
					{
					$weekdays = array();
					if($_POST['sundays'] == '' && $_POST['saturdays'] == '')
						{
							while ($start <= $end)
								 {
									if (date('N', $start) <= 5) 
										{
										$current = date('d/m/Y', $start);
										//$result[$current] = 7.5;
										$weekdays[] = $current;
										}
								$start += 86400;
								}
						}
					if($_POST['sundays'] == 'sundays' && $_POST['saturdays'] == '')
						{
							while ($start <= $end) 
								{
								$chk = date('D', $start);
									if (date('N', $start) <= 7)
										 {
											if($chk != 'Sat')
												{
												$current = date('d/m/Y', $start);
												//$result[$current] = 7.5;
												$weekdays[] = $current;
												}
										}
								$start += 86400;
								}
						}
					if($_POST['sundays'] == '' && $_POST['saturdays'] == 'saturdays')
							{
							
								while ($start <= $end)
									 {
									$chk = date('D', $start);
										if (date('N', $start) <= 7)
											 {
												if($chk != 'Sun')
													{
													$current = date('d/m/Y', $start);
													//$result[$current] = 7.5;
													$weekdays[] = $current;
													}
											}
									$start += 86400;
									}
							}
					if($_POST['sundays'] == 'sundays' && $_POST['saturdays'] == 'saturdays')
							{
							
								while ($start <= $end) 
									{
									
										if (date('N', $start) <= 7) 
											{
											
											$current = date('d/m/Y', $start);
											//$result[$current] = 7.5;
											$weekdays[] = $current;
											}
									$start += 86400;
									}
							}
					}
			$job_crosshire = '0';
			$job_tbc = '0';
			$job_meetings = '0';
			$cross = $_POST['sports'];
			if($cross == 'cross')
				{
				
				$job_crosshire = '1';
				}
			if($cross == 'tbc')
				{
				
				$job_tbc = '1';
				}
			if($cross == 'meetings')
				{
				
				$job_meetings = '1';
				}
			if($_POST['ewp'])
				{
				 $ewp_billing = $_POST['ewp'];
				}
			else
				{
				 $ewp_billing = "0";
				}
				
			$attachmenturl = $_POST['picedit'];
			$attachmentname = $_POST['piceditname'];
			$secureattachmenturl = $_POST['securepicedit'];
			$secureattachmentname = $_POST['securepiceditname'];
			$addcrane='';
			$addcranesize= '';
			$addclient = $_POST['addclientes'];
			$createbyjobid = $_POST['createbyjobids'];
			$job_client = trim($_POST['clientes']); 
			$job_client_id = trim($_POST['client_ids']);
			//$job_crane = trim($_POST['craneus']);
			//$job_cranesize = $_POST['cranesizeus'];
			$job_date = trim($_POST['dates']);
			
			
			$job_time = trim($_POST['times']);
			
			$jobaddress = $_POST['address'];
			if($jobaddress)
				{
				$job_address = $jobaddress;
				}
			else 
				{
				$job_address = $_POST['searchaddress'];   
				}
		
			$job_lift = trim($_POST['lifts']);
			$job_rporder = isset($_POST['rporder']) ? $_POST['rporder'] : '';
			$job_dogman = isset($_POST['dogmans']) ? $_POST['dogmans'] : '';
			$job_operator = isset($_POST['operators']) ? $_POST['operators'] : '';
			$job_auxequipment = isset($_POST['aux']) ? $_POST['aux'] : '';
			$job_contact = $_POST['searchcontactus'];
			
			$addcontact = $_POST['contactus'];
			$addcontact_id = $_POST['contact_ids'];
			
			$job_detail = trim($_POST['details']);
			
			$job_securenotes = trim($_POST['securenotess']);
			$job_cranetype = trim($_POST['cranes']);
			$job_crane_size = trim($_POST['cranesizess']);
			$job_crane = isset($_POST['craneus']) ? $_POST['craneus'] : ''; 
			if(is_array($weekdays))
				{
				$sequence_id = 1;
					for($d = 0; $d < count($weekdays); $d++)
						{
							for($c = 0; $c < count($job_crane); $c++)
								{
								$job_cranesize = $_POST['size_'.$job_crane[$c]];
								$job_time = trim($_POST['sequencejobtime']);
								$job_date = $weekdays[$d];
								
								$jobss = $job->insert($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane[$c],$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id,$ewp_billing);
								$sequence_id++;}
						
						}
				}
			else
				{
				for($c = 0; $c < count($job_crane); $c++)
					{
					$job_cranesize = $_POST['size_'.$job_crane[$c]];
					$sequence_id = 0;
					$jobss = $job->insert($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane[$c],$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id,$ewp_billing);
					}
				
				}
			exit;
		
		}*/
		
	if($action === 'getaddresslist')
		{
			$clientidaddress = $_POST['clientid'];
			$searchaddress = $_POST['search'];
			$contactaddress = $_POST['contact_id'];
			$addressdata = $customer->getaddressnamewiselist($clientidaddress,$searchaddress,$contactaddress);
			$output = '';
			
				if(is_array($addressdata))
					{
					$output .='<ul class="dropdown-menu clientsearchresultboxinner" role="menu">';
						foreach($addressdata as $address)
							{
							$job_clie_id = $address['job_clie_id'];
							$job_address = $address['job_address'];
							$output .='<li><a class="getaddress" data-id="'.$job_clie_id.'" data-name="'.$job_address.'"><span class="text">'.$job_address.'</span></a></li>';
							}
					$output .='</ul>';	
					} 
				else 
					{
					$output = '2';	
					}
		
			echo $output;
			exit;	
		}
		
	/// tbc result
	
	if($action === 'tbc_search')
		{
			$csearch = $_REQUEST['tbc_stxt'];
			$tbc_result = $customer->searchjobslisttbc($csearch);
			$tbc_count = count($tbc_result);
			$output1 = '';
				if($tbc_count > 0)
					{
						$output1 .= '<ul class="bxslider_tbc">';
						foreach($tbc_result as $tbc_r)
								{
									$tbc_job_id = $tbc_r['id'];
									$editby = $tbc_r['job_completedby'];
									$output1 .= '<li>';
									$output1 .= '<div class="jobcontentbox clearfix';
									if($tbc_r['job_status'] == 'Billed')
										{
										$output1 .='billeddiv'; 
										}
									$output1 .=  '">';
									if($tbc_r['job_status_billed'] == '1')
										{ 
										$output1 .= '<div class="billledleftbox">BILLED</div>';
										}  
								
									if($tbc_r['job_status_processed'] == '1')
										{
										
										$output1 .='<div class="processedleftbox">PROCESSED</div>';
										} 
								
								$output1 .='<div><b>Client:</b>' .$tbc_r['cust_name'].'</div>
								<div><b>Time:</b>'. $tbc_r['job_time'] .'</div>
								<div><b>Address:</b>'.substr($tbc_r['job_address'], 0,18).'...</div>
								<div><b>Crane Type:</b>'. $tbc_r['job_equi_size'].'</div>
								<div><b>Contact:</b>'.$tbc_r['cont_name'].'</div>
								<div><b>Details:</b>'.substr($tbc_r['job_detail'], 0, 18).'...</div>
								<div class="text-right jobmenubottom clearfix">
								<div class="viewjobbox pull-left hideprint"><a href="'.home_base_url().'job/view_job.php?id='. $tbc_job_id.'">View</a></div>';
									if($tbc_r['job_status_billed'] == '1')
										{ 
										$usereditby = $user->loginuserdata($editby);
										$fletter = substr($usereditby['first_name'], 0, 1);
										$lletter = substr($usereditby['last_name'], 0, 1);
										
										$output1 .= '<div class="ahbox">'. $fletter.$lletter.'</div>';
										} 
								$output1 .='<div class="menujoblist">=</div>
								<div class="dropmenuouter">
								<div class=""><a href="#markProcessed" class="markprocessedbtn" data-toggle="modal" data-jobid="'.$tbc_job_id.'">Mark as Processed</a></div>';
								if($tbc_r['job_status'] != 'Billed')
									{ 
									$output1 .= '<div class=""><a href="#markBilled" class="markprocessedbtnbilled" data-toggle="modal" data-jobid="'. $tbc_job_id.'">Mark as Billed</a></div>';
									}
								$output1 .='<div class="hideprint"><a href="'. home_base_url().'job/view_job.php?id='.$tbc_job_id.'">Print Job</a></div>';
								$output1 .='</div></div></div></li>';
								
								} 
					$output1 .= '</ul>';  
					echo $output1;
					}
				else 
					{ 
					echo $output1 .= '<div class="jobcontentbox clearfix">
					<div>No Data Available</div>
					</div>';
					}
			exit;	
		
		}
	
	/*if($actionss === 'sequnecejobs')
		{
			$startdate = $_POST['sequencejobsstart'];
			$enddate = $_POST['sequencejobsend'];
			$startdate = str_replace('/', '-', $startdate);
			$enddate = str_replace('/', '-', $enddate);
			$start = strtotime($startdate);
			$end = strtotime($enddate);
		
				if($startdate)
					{
						$weekdays = array();
							if($_POST['sunday'] == '' && $_POST['saturday'] == '')
								{
									while ($start <= $end)
										{
											if (date('N', $start) <= 5) 
												{
												$current = date('d/m/Y', $start);
												//$result[$current] = 7.5;
												$weekdays[] = $current;
												}
										$start += 86400;
										}
								}
					if($_POST['sunday'] == 'sunday' && $_POST['saturday'] == '')
						{
							while ($start <= $end)
								 {
								$chk = date('D', $start);
									if (date('N', $start) <= 7)
										 {
											if($chk != 'Sat')
												{
												$current = date('d/m/Y', $start);
												//$result[$current] = 7.5;
												$weekdays[] = $current;
												}
										}
								$start += 86400;
								}
						}
					if($_POST['sunday'] == '' && $_POST['saturday'] == 'saturday')
						{
						
							while ($start <= $end) 
								{
								$chk = date('D', $start);
									if (date('N', $start) <= 7) 
										{
											if($chk != 'Sun')
												{
												$current = date('d/m/Y', $start);
												//$result[$current] = 7.5;
												$weekdays[] = $current;
												}
										}
								$start += 86400;
								}
						}
					if($_POST['sunday'] == 'sunday' && $_POST['saturday'] == 'saturday')
						{
						
							while ($start <= $end) 
								{
								
									if (date('N', $start) <= 7)
										{
										
										$current = date('d/m/Y', $start);
										//$result[$current] = 7.5;
										$weekdays[] = $current;
										}
								
								$start += 86400;
								}
						}
					}
			$job_crosshire = '0';
			$job_tbc = '0';
			$job_meetings = '0';
			$cross = $_POST['sports'];
			if($cross == 'cross')
				{
				
				$job_crosshire = '1';
				}
			if($cross == 'tbc')
				{
				
				$job_tbc = '1';
				}
			if($cross == 'meetings')
				{
				
				$job_meetings = '1';
				}
			if($_POST['ewp'])
				{
				 $ewp_billing = $_POST['ewp'];
				}
			else
				{
				 $ewp_billing = "0";
				}
			$attachmenturl = $_POST['picedit'];
			$attachmentname = $_POST['piceditname'];
			$secureattachmenturl = $_POST['securepicedit'];
			$secureattachmentname = $_POST['securepiceditname'];
			$addcrane='';
			$addcranesize= '';
			$addclient = $_POST['addclient'];
			$createbyjobid = $_POST['createbyjobid'];
			$job_client = trim($_POST['client']); 
			$job_client_id = trim($_POST['client_id']);
			//$job_crane = trim($_POST['craneus']);
			//$job_cranesize = $_POST['cranesizeus'];
			$job_date = trim($_POST['date']);
			
			
			$job_time = trim($_POST['time']);
			
			$jobaddress = $_POST['address'];
			if($jobaddress)
				{
				$job_address = $jobaddress;
				} 
			else 
				{
				$job_address = $_POST['searchaddress'];   
				}
		
			$job_lift = trim($_POST['lift']);
			$job_rporder = isset($_POST['rporder']) ? $_POST['rporder'] : '';
			$job_dogman = isset($_POST['dogman']) ? $_POST['dogman'] : '';
			$job_operator = isset($_POST['operator']) ? $_POST['operator'] : '';
			$job_auxequipment = isset($_POST['aux']) ? $_POST['aux'] : '';
			$job_contact = $_POST['searchcontact'];
			
			$addcontact = $_POST['contact'];
			$addcontact_id = $_POST['contact_id'];
			
			$job_detail = trim($_POST['detail']);
			
			$job_securenotes = trim($_POST['securenotes']);
			$job_cranetype = trim($_POST['cranes']);
			$job_crane_size = trim($_POST['cranesizess']);
			$job_crane = isset($_POST['craneus']) ? $_POST['craneus'] : ''; 
				if(is_array($weekdays))
						{
							$sequence_id = 1;
							for($d = 0; $d < count($weekdays); $d++)
								{
									for($c = 0; $c < count($job_crane); $c++)
										{
										$job_cranesize = $_POST['size_'.$job_crane[$c]];
										$job_time = trim($_POST['sequencejobstime']);
										$job_date = $weekdays[$d];
										
										$jobss = $job->insert($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane[$c],$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id,$ewp_billing);
										$sequence_id++;}
								
								}
						}
					else
						{
						for($c = 0; $c < count($job_crane); $c++)
							{
							$job_cranesize = $_POST['size_'.$job_crane[$c]];
							$sequence_id = 0;
							$jobss = $job->insert($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane[$c],$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id,$ewp_billing);
							}
						
						}
			exit;
		
		}
	*/
	
	if($action === 'onchangecranetypeedit')
		{
		
			$equi_id = $_POST['equi_id'];
			$equipments = $equipment->equipmentdata($equi_id);
			$equipmentsize = $equipment->equimentsize($equi_id);
			$type =$equipments['equi_type'];
			$contactstring = '';
			$contactstring .= '<option value="" >Select Size</option>';
				foreach($equipmentsize as $equipmentsizess)
					{
					$contactstring .= '<option value="'.$equipmentsizess['id'].'" > '.$equipmentsizess['equipments_size'].'</option>';
					}
			echo $output = json_encode( array('size' => $contactstring, 'type' => $type,) );
			exit;	
		}

	if($action === 'equinotificationupdate')
			{
				$equi_userid = $_REQUEST['userid'];
				$equi_notification_status = 'Seen';
				$equipment_update = $equipment->equipmentupdatednotificationstatus($equi_userid,$equi_notification_status);
				//print_r($equipment_update);
				exit;	
			}
	if($action === 'equinotiautoupdate')
		{
			$appendjobrow = '';
			$equiuserid = $_REQUEST['userid'];
			
			$equipmentvaluedata = $equipment->equipmentvaluedata();
		
			foreach($equipmentvaluedata as $equipmentvalue)
				{
					if($equipmentvalue['logbook']!=0 && $equipmentvalue['date_difference'] == 'kilometers')
						{
						$logbookdiff = ($equipmentvalue['equipment_license_date'])-($equipmentvalue['logbook']);
							if($logbookdiff <= 500)
								{ 
								$equipmentsizenamelogbook = $equipment->equiattachment($equipmentvalue['equipment_size_id']);
								$equipmentnamelogbook = $equipment->equipmentdata($equipmentsizenamelogbook['equipment_id']);
								$equipmentsizeeditssdfgsd = 'equipment/editsize_equipment.php?equi_id='.$equipmentsizenamelogbook['equipment_id'].'&equisize_id='.$equipmentvalue['equipment_size_id'].'';
								
								$appendjobrow .= '<a href="'.home_base_url().$equipmentsizeeditssdfgsd.'&action=edit" class="joblising"> <div class="jobs"> <strong>'.$equipmentnamelogbook['equi_name'].' '.$equipmentsizenamelogbook['equipments_size'].'</strong> <strong>'.$equipmentvalue['equipment_license_name'].'</strong> Logbook Service is due in 500 Klms </div></a>';
								
								}
						}
					if($equipmentvalue['logbook']!=0 && $equipmentvalue['date_difference'] == 'hourly')
						{
							$logbookdiff = ($equipmentvalue['equipment_license_date'])-($equipmentvalue['logbook']);
							if($logbookdiff <= 40)
								{ 
								$equipmentsizenamelogbook = $equipment->equiattachment($equipmentvalue['equipment_size_id']);
								$equipmentnamelogbook = $equipment->equipmentdata($equipmentsizenamelogbook['equipment_id']);
								$equipmentsizeeditssdfgsd = 'equipment/editsize_equipment.php?equi_id='.$equipmentsizenamelogbook['equipment_id'].'&equisize_id='.$equipmentvalue['equipment_size_id'].'';
								
								$appendjobrow .= '<a href="'.home_base_url().$equipmentsizeeditssdfgsd.'&action=edit" class="joblising"> <div class="jobs"> <strong>'.$equipmentnamelogbook['equi_name'].' '.$equipmentsizenamelogbook['equipments_size'].'</strong> <strong>'.$equipmentvalue['equipment_license_name'].'</strong> Logbook Service is due in 40 Hours </div></a>';
								
								}
						}
				}
		$auxequipmentvaluedatamap = $auxequipment->auxequipmentvaluedata();
			foreach($auxequipmentvaluedatamap as $auxequipmentvalue)
					{
						if($auxequipmentvalue['logbook']!=0 && $auxequipmentvalue['date_difference'] == 'kilometers')
							{
							$logbookdiff = ($auxequipmentvalue['equipment_license_date'])-($auxequipmentvalue['logbook']).'<br>';
								if($logbookdiff <= 500)
									{ 
									$auxequipmentsizenamelogbook = $auxequipment->auxequiattachment($auxequipmentvalue['auxequipment_size_id']);
									$auxequipmentnamelogbook = $auxequipment->auxequipmentdata($auxequipmentsizenamelogbook['auxequipment_id']);
									$servicelogbook = 'Logbook Service is due in 500 Klms';
									$equipmentsizeeditssdfgsd = 'auxequipment/editsize_auxequipment.php?auxequi_id='.$auxequipmentsizenamelogbook['auxequipment_id'].'&auxequisize_id='.$auxequipmentvalue['auxequipment_size_id'].'';
									$appendjobrow .= '<a href="'.home_base_url().$equipmentsizeeditssdfgsd.'&action=edit" class="joblising">
									<div class="jobs"> <strong>'.$auxequipmentnamelogbook['auxe_name'].' '.$auxequipmentsizenamelogbook['auxequipment_size'].'</strong> <strong> '.$auxequipmentvalue['equipment_license_name'].'</strong> Logbook Service is due in less than 500 Klms </div>
									</a>';
									
									}
							}
						if($auxequipmentvalue['logbook']!=0 && $auxequipmentvalue['date_difference'] == 'hourly')
							{
							$logbookdiff = ($auxequipmentvalue['equipment_license_date'])-($auxequipmentvalue['logbook']).'<br>';
								if($logbookdiff <= 40)
									{ 
									$auxequipmentsizenamelogbook = $auxequipment->auxequiattachment($auxequipmentvalue['auxequipment_size_id']);
									$auxequipmentnamelogbook = $auxequipment->auxequipmentdata($auxequipmentsizenamelogbook['auxequipment_id']);
									$servicelogbook = 'Logbook Service is due in 500 Klms';
									$equipmentsizeeditssdfgsd = 'auxequipment/editsize_auxequipment.php?auxequi_id='.$auxequipmentsizenamelogbook['auxequipment_id'].'&auxequisize_id='.$auxequipmentvalue['auxequipment_size_id'].'';
									$appendjobrow .= '<a href="'.home_base_url().$equipmentsizeeditssdfgsd.'&action=edit" class="joblising">
									<div class="jobs"> <strong>'.$auxequipmentnamelogbook['auxe_name'].' '.$auxequipmentsizenamelogbook['auxequipment_size'].'</strong> <strong> '.$auxequipmentvalue['equipment_license_name'].'</strong> Logbook Service is due in less than 40 Hours </div>
									</a>';
									
									}
							}
					}
		
		
			$userdatadate_head = $user->getuserslistcrti();
		
		
			foreach($userdatadate_head as $userdatadate_heads)
					{
						$current_date = date("d-m-Y");
						$next_date = str_replace('/','-',$userdatadate_heads['expire_date']);
						$datess = date_create($current_date);
						$datess1 = date_create($next_date);
						$today_time = strtotime($current_date);
						$expire_time = strtotime($next_date);
						$diff = $datess->diff($datess1);
						$userid_expire = $userdatadate_heads['user_id'];
							if($diff->days < 15 && $expire_time > $today_time)
									{
										if(!isset($_SESSION['usernotificationsess']))
											{
											$notification_users = $user->usernotification($_SESSION['user_session'],$userid_expire);
											}
									$appendjobrow .= '<a href="'.home_base_url().'users/edit_users.php?id='.$userdatadate_heads['user_id'].'&action=edit" class="joblising" >
									<div class="jobs"> <strong>'.$userdatadate_heads['user_name'].'</strong> 
									'.$userdatadate_heads['licenses_name'].' is due to expire on  '.$userdatadate_heads['expire_date'].' </div>
									</a>';
									}
					}
			$_SESSION['usernotificationsess'] = 'Seen'; 
			
			$equipmentnotificationjobscount = $equipment->getequipmentnotificationjob($_SESSION['user_session']);
			$equipmentcountnotijob = count($equipmentnotificationjobscount);
			$equipmentnotificationjobs = $equipment->getallequipmentnotificationjob($_SESSION['user_session']);
			$equipmentexpirydate = $equipment->equipmentexpirydate();
				foreach($equipmentexpirydate as $equipmentexpirydates)
						{
							if($equipmentexpirydates['date_difference']=='1month' || $equipmentexpirydates['date_difference']=='3month' || $equipmentexpirydates['date_difference']=='6month' || $equipmentexpirydates['date_difference']=='yearly')
								{
								$current_date = date("d-m-Y");
								$next_date = str_replace('/','-',$equipmentexpirydates['equipment_license_date']);
								$today_time = strtotime($current_date);
								$expire_time = strtotime($next_date);
								$datess = date_create($current_date);
								$datess1 = date_create($next_date);
								$diff = $datess->diff($datess1);
								$userid_expire = $equipmentexpirydates['user_id'];
								
									if($diff->days < 15 && $expire_time > $today_time)
										{
										$equipmentsizename = $equipment->equiattachment($equipmentexpirydates['equipment_size_id']);
										$equipmentnamesdsds = $equipment->equipmentdata($equipmentsizename['equipment_id']);
										$equipmentsizeurl = 'equipment/editsize_equipment.php?equi_id='.$equipmentsizename['equipment_id'].'&equisize_id='.$equipmentexpirydates['equipment_size_id'].'';
											if(!isset($_SESSION['equipmentnotificationsess']))
													{
													
													$notification_users = $equipment->equipmentexpirydatenotification($_SESSION['user_session'],$equipmentexpirydates['equipment_size_id']);
													}
										
										
										$appendjobrow .= '<a href="'.home_base_url().$equipmentsizeurl.'&action=edit" class="joblising" ><div class="jobs equi '.$equipmentnotificationjobexpire['equi_noti_status'].'"> <strong>'.$equipmentnamesdsds['equi_name'].'&nbsp;'.$equipmentsizename['equipments_size'].'</strong>'.' '.$equipmentexpirydates['equipment_license_name'].'&nbsp;expires on '.$equipmentexpirydates['equipment_license_date'].'</div></a>';
										}
								}
						
						
						}
			$_SESSION['equipmentnotificationsess'] = 'expirydate';
			
			$auxequipmentexpirydate = $auxequipment->auxequipmentexpirydate();
			foreach($auxequipmentexpirydate as $auxequipmentexpirydates)
					{
						if($auxequipmentexpirydates['date_difference'] != 'kilometers' || $auxequipmentexpirydates['date_difference'] != 'hourly')
								{
								$current_date = date("d-m-Y");
								$next_date = str_replace('/','-',$auxequipmentexpirydates['auxequipment_license_date']);
								$timestamp = strtotime(str_replace('/','-',$auxequipmentexpirydates['auxequipment_license_date'])); 
								$auxequipment_license_date = date("Y-m-d", $timestamp);
								$datess = date_create($current_date);
								$datess1 = date_create($next_date);
								$today_time = strtotime($current_date);
								$expire_time = strtotime($next_date);
								$diff = $datess->diff($datess1);
								$userid_expire = $auxequipmentexpirydates['user_id'];
									if($diff->days < 15 && $expire_time > $today_time)
											{
											$auxequipmentsizename = $auxequipment->auxequiattachment($auxequipmentexpirydates['auxequipment_size_id']);
											$auxequipmentnamesdsds = $auxequipment->auxequipmentdata($auxequipmentsizename['auxequipment_id']);
											$auxequipmentsizeurl = 'auxequipment/editsize_auxequipment.php?auxequi_id='.$auxequipmentsizename['auxequipment_id'].'&auxequisize_id='.$auxequipmentexpirydates['auxequipment_size_id'].'';
											
											$appendjobrow .= '<a href="'.home_base_url().$auxequipmentsizeurl.'&action=edit" class="joblising" ><div class="jobs equi '.$equipmentnotificationjobexpire['equi_noti_status'].'"> <strong>'.$auxequipmentnamesdsds['auxe_name'].' '.$auxequipmentsizename['auxequipment_size'].'</strong>'.' '.$auxequipmentexpirydates['auxequipment_license_name'].'&nbsp;expires on '.$auxequipmentexpirydates['auxequipment_license_date'].'</div></a>';
											}
								}
					}
		
			foreach($equipmentnotificationjobs as $equipmentnotificationjob)
					{
					$username = $user->loginuserdata($equipmentnotificationjob['noti_user_name']);
					$equipmentname = $equipment->equipmentdata($equipmentnotificationjob['equipment_id']);
					$equimentsizedata = $equipment->jobsequisize($equipmentnotificationjob['equipments_size_id']);
					$auxequipmentnamea = $auxequipment->auxequipmentdata($equipmentnotificationjob['auxequipment_id']);
					$auxequipmentsizenamea = $auxequipment->auxequipsize($equipmentnotificationjob['auxequipments_size_id']);
					$noti_date_equi = $equipmentnotificationjob['updated_date'];
					$equi_time_array = explode(" ",$noti_date_equi);
					$date_in_12_hour_format = date("Y/m/d", strtotime($equi_time_array[0]));
					$equi_time = date("g:i a", strtotime($equi_time_array[1]));
						foreach($equimentsizedata as $equimentsized)
							{
							$equipmentsizeedit = 'equipment/editsize_equipment.php?equi_id='.$equimentsized['equipment_id'].'&equisize_id='.$equipmentnotificationjob['equipments_size_id'].'';
							}
							foreach($auxequipmentsizenamea as $auxequipmentsizeid)
								{
								$auxequipmentsizeedit = 'auxequipment/editsize_auxequipment.php?auxequi_id='.$auxequipmentsizeid['auxequipment_id'].'&auxequisize_id='.$equipmentnotificationjob['auxequipments_size_id'].'';
								}
					$equipmentedit = 'equipment/edit_equipment.php?id='.$equipmentnotificationjob['equipment_id'].'&action=edit';
					$auxequipmentedit = 'auxequipment/edit_auxequipment.php?id='.$equipmentnotificationjob['auxequipment_id'].'&action=edit';
						if ($equipmentnotificationjob['equi_noti_type']!='Expiry date' || $equipmentnotificationjob['equi_noti_type'] != 'Equipment Expiry Date')
								{
									if($username['first_name'])
											{
											$appendjobrow .= ' <a href="'.home_base_url().'';
												if($equipmentnotificationjob['equi_noti_type'] == 'Edited equipment size')
													{
													
													$appendjobrow .=''.$equipmentsizeedit.''; 
													
													}
												elseif($equipmentnotificationjob['equi_noti_type'] == 'Added a new auxequipment' || $equipmentnotificationjob['equi_noti_type'] == 'Edited auxequipment')
													{
													
													$appendjobrow .=''.$auxequipmentedit.''; 
													
													}
												elseif($equipmentnotificationjob['equi_noti_type'] == 'Edited auxequipment size')
													{
													
													$appendjobrow .=''.$auxequipmentsizeedit.''; 
													
													}
												else
													{
													
													$appendjobrow .=''.$equipmentedit.'';
													
													}
											
											$appendjobrow .= '" class="joblising '.$equipmentnotificationjob['equi_noti_status'].'">
											<div class="jobs a"><strong>'.$equi_time.'</strong> - <strong>'.$username['first_name'].'</strong>&nbsp;';
											if ($equipmentnotificationjob['equi_noti_type'] == 'Added a new equipment')
												{
													//echo 'Added a new equipment';
													$appendjobrow .= '<span>Added a new equipment </span><strong>'.$equipmentname['equi_name'].'</strong>';
												}
											
											if ($equipmentnotificationjob['equi_noti_type'] == 'Edited equipment')
												{
													//echo 'Edited equipment';
													$appendjobrow .= '<span>Edited equipment </span> <strong>'.$equipmentname['equi_name'].'</strong>';
												} 
											foreach($equimentsizedata as $equimentsizedatass)
												{
													$equipmentsize_name = $equipment->equipmentdata($equimentsizedatass['equipment_id']);
													if ($equipmentnotificationjob['equi_noti_type'] == 'Edited equipment size')
														{
															//echo 'Edited equipment size';
															$appendjobrow .= '<span>Edited equipment size </span> <strong>'.$equipmentsize_name['equi_name'].' - '.$equimentsizedatass['equipments_size'].'</strong>'; 
														}
												}
											
											if ($equipmentnotificationjob['equi_noti_type'] == 'Expiry date' && $equipmentnotificationjob['userid_expire'] == 0)
												{
													//echo 'Expiry date';
													$appendjobrow .= '<span>Expiry date: </span> <strong>'.$equipmentname['equi_name'].'</strong>';
												}
											
											if ($equipmentnotificationjob['equi_noti_type'] == 'Added a new auxequipment')
												{ 
													$appendjobrow .= '<span>Added a new auxequipment</span> 
													<strong>'.$auxequipmentnamea['auxe_name'].'</strong>'; 
												}
											if ($equipmentnotificationjob['equi_noti_type'] == 'Edited auxequipment')
												{
													$appendjobrow .= '<span>Edited auxequipment</span><strong> '.$auxequipmentnamea['auxe_name'].'</strong>';
												}
											if ($equipmentnotificationjob['equi_noti_type'] == 'Edited auxequipment size')
												{
													$appendjobrow .= '<span>Edited auxequipment size</span>'; 
													foreach($auxequipmentsizenamea as $auxequipmentsizenamesa)
														{ 
															$appendjobrow .= ' <strong>'.$auxequipmentsizenamesa['auxequipment_size'].'</strong>'; 
														} 
												}
											$appendjobrow .= '</div>
											</a>';
											}
								
								}
					}
		echo $output = json_encode( array('conutnoti' => $equipmentcountnotijob, 'appenddata' => $appendjobrow,) );
		exit;
		}
		
	if($action === 'deleteequiattachment')
		{
			$equisizeda = $_REQUEST['equisize'];
			$equisizedatasize = $equipment->deleteequipmentsizeattach($equisizeda);
			exit;	
		}
		
	if($action === 'deletesize')
		{
			$equisize = $_POST['equisize'];
			$equisizedata = $equipment->deleteequipmentsize($equisize);
			exit;
		}
		
	if($action === 'auxdeletesize')
		{
			 $auxequisize = $_POST['auxequisize'];
			
			$equisizedata = $auxequipment->deleteauxequipmentsize($auxequisize);
			exit;
		}
	
	if($action === 'previewsizeattach')
		{
			$attachment_preview = '';
			$attachequisize_id = $_REQUEST['attachequisize'];
			$attachequi = $equipment->attachequisize($attachequisize_id);
			$attachment_preview .= '  <ul>';
				foreach($attachequi as $attachequis)
					{
						if($attachequis['attachment'])
							{ 
								$info = new SplFileInfo($attachequis['attachment']);
								$extension = $info->getExtension();
								if($extension == 'msg')
									{
										$imagepath = home_base_url().'images/msg.png';
									}
								elseif($extension == 'pdf' || $extension == 'PDF')
									{
										$imagepath = home_base_url().'images/pdf.png';
									}
							elseif($extension == 'zip')
									{
										$imagepath = home_base_url().'images/zip.png';
									}
							elseif($extension == 'docx' || $extension == 'doc')
									{
										$imagepath = home_base_url().'images/doc.png';
									}
							elseif($extension == 'mov' || $extension == 'MOV')
									{
										$imagepath = home_base_url().'images/mov.png';
									}
							elseif($extension == 'xls' || $extension =='xlsx')
									{
										$imagepath = home_base_url().'images/excel.png';
									}
							else
									{
										$imagepath = home_base_url().$attachequis['attachment'];
									}
							
							
								$asd = explode('/',$attachequis['attachment']);
								
								$last_name = end($asd);
								$first_name = explode('-',$last_name);
								array_splice($first_name, 0, 1);
								$image_name = implode('-',$first_name);
								
								$attachment_preview .= ' <li class="list-group-item"> <a href="'.home_base_url().''.$attachequis['attachment'].'" target="_blank"> <img width="60" height="60" src="'.$imagepath.'" alt="'.$image_name.'"  /> <span class="imagenametext"> '.substr($image_name, 0 , 30).'</span></a>
								
								</li>';
							}
					
					}
			$attachment_preview .= ' </ul>';
			echo $output = json_encode( array('appenddata' => $attachment_preview,) );
			exit;
		}
	
	if($action === 'customermail')
			{ 
				$messagebody = $_POST['endLetterSequenceNos'];
				$mail_id = $_POST['mail_id'];
				$subject = $_POST['emailsub'];
				$attachmenturl = $_POST['mailattachmentpath'];
				$attachmentname = $_POST['mailattachmentname'];
				$email_per = $_POST['email_send_personally'];
				$preview_mail = $_POST['preview_email'];
					if( $mail_id )
						{
							$mailid = $mail_id; 
						} 
						else
						{
							$mailid = 2; 	   
						}
				try
					{
						if(empty($email_per))
							{	
								if($mailbody->updated($mailid,$messagebody,$attachmenturl,$attachmentname,$subject))
									{
									/*** mail send code here ****/
									$cust_name = array();
									$cust_names = array();
									$cont = array();
									$conts = array();
									$mailemailids = array();
									$custfields = $customer->getcustomerslist();
										foreach($custfields as $custfield)
											{
											$cust_id = $custfield['cust_id'];
											
											$contactfileds = $customer->getcustomerscontactlist($cust_id);
												foreach($contactfileds as $contactfiled)
													{	
													$cont_email = $contactfiled['cont_email'];
													$mailemailids[] = array("cont_name" => $contactfiled['cont_name'], "cont_email" => $cont_email,"cust_name" => $custfield['cust_name'], "cust_email" => 			    $custfield['cust_email']);
													}
											}
									include_once 'class/class.phpmailer.php';
									$mailbodydata = $mailbody->mailbodydata(2);
									$messagebody = $mailbodydata['mail_body'];
									$pdfattachments = json_decode($mailbodydata['mail_pdflink']);
									$subject = $mailbodydata['subject'];
									// remove comment after live 
									if(is_array($mailemailids))
										{
											foreach($mailemailids as $mailemailid)
												{
													$customer_email = $mailemailid["cont_email"];
													$customer_name = $mailemailid["cont_name"];
													$contact_name = $mailemailid["cust_name"];
													$contact_email = $mailemailid["cust_email"];
													/**** If we are live code then both live comment and upper both line comment remove *****/
													
													//PHPMailer Object
													$mail = new PHPMailer;
													$mailmd = '<a href="http://ablworks.com.au/elsjobs/unsubscribecustomers.php?email='.$mailemailid['md5_email'].'"> Unsubscribe</a>';
													//From email address and name
													$mail->From = "jeff@1800welift.com.au";
													$mail->FromName = "Equipment & Lifting Solutions Pty Ltd";
													if(is_array($pdfattachments)){ 
														foreach($pdfattachments as $pdfattachment)
															{ 
															$info = new SplFileInfo($pdfattachment->imagepath);
															$extension = $info->getExtension();
															$attachpath = 'C:\wamp\www\\elsjobs'.'\\'.$pdfattachment->imagepath;
															$attachname = $pdfattachment->imagename;
															$mail->AddAttachment($attachpath, $attachname,  $encoding = 'base64', $type = 'application/'.$extension);		
															} 
														}
												//To address and name
												$mail->addAddress($customer_email,$customer_name);
												//Send HTML or Plain Text email
												$mail->isHTML(true);
												$footer = '<br>
												<style type="text/css">
												.tg  {border-collapse:collapse;border-spacing:0;border:none;width:80%;}
												.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
												.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
												.tg .tg-baqh{text-align:center;vertical-align:top}
												</style>
												<table class="tg">
												<tr>
												<th class="tg-wxgh" style="text-align:left;"><strong>Access &amp; EWP</strong></th>
												<th class="tg-wxgh" style="display: block; margin-left: 10px;"><strong>Logistics and Sales</strong></th>
												</tr>
												<tr>
												<td class="tg-yw4l"><strong>Jeff Facoory</strong><br>0437 776 926<br><a href="mailto:jeff@1800welift.com.au">jeff@1800welift.com.au</a></td>
												<td class="tg-yw4l" style="display: block; margin-left: 50px;"><strong>Mark Harrison Logistics and Sales</strong><br>0427 288 886<br><a href="sales@1800welift.com.au">sales@1800welift.com.au</a></td>
												</tr>
												
												<tr>
												<td class="tg-baqh" colspan="2"><a href="http://www.1800welift.com.au">www.1800welift.com.au</a><br>
												<br>
												<a href="https://www.facebook.com/Equipment-Lifting-Solutions-616074868448237/?fref=ts"><img src="http://dispatch.1800welift.com.au/elsjobs/images/fb.jpg" style="width:120px;" align="left"></a> <a href="https://instagram.com/ELS_1800welift"><img src="http://dispatch.1800welift.com.au/elsjobs/images/ig.jpg" style="width:120px;" align="right"></a></td>
												</tr>
												</table>
												<br>
												<img src="http://dispatch.1800welift.com.au/elsjobs/images/els-trucks.jpg" style="max-width:800px;">
												<img src="cid:1001" style="max-width:800px;" />';
												$mail->AddEmbeddedImage('C:\wamp\www\\elsjobs\images\maillogo.png', '1001', 'maillogo.png'); 
												//Message Html Body
												$mail->Subject = $subject;
												$mail->Body = $messagebody.$footer.'<br><br>'.$mailmd;
												if(!$mail->send()) 
													{
													echo "Mailer Error: " . $mail->ErrorInfo;
													} 
												else 
													{
													
													//echo "Message has been sent successfully<br>";
													$mail->ClearAddresses();
													$mail->ClearAttachments();
													
													//$mailbody->redirect('emailtoallcustomers.php?id=2&joined');
													
													}
												if($customer_email != '')
													{
													$cust_name[] = $customer_name;
													$cont[] = $contact_name;
													
													}
												else
													{
													
													$conts[] = $contact_name;
													$cust_names[] = $customer_name;
													
													}
												// remove commect after live 
												}
										}
									/*** mail send code end ***/
									}
							
							$summarymails = new PHPMailer;
							//From email address and name
							$summarymails->From = "jeff@1800welift.com.au";
							$summarymails->FromName = "Dispatch - Equipment & Lifting Solutions Pty Ltd";
							//To address and name
							//$mails->addAddress("*@gmail.com"); //Recipient name is optional
							$summarymails->addAddress("dispatch@1800welift.com.au", "ELS Dispatch");
							$summarymails->addAddress("cranes@1800welift.com.au", "ELS Cranes");
							$summarymails->addAddress("andrew@ablit.com.au", "ABL IT");
							//Address to which recipient will reply
							//$mail->addReplyTo("reply@yourdomain.com", "Reply");
							//CC and BCC
							//Send HTML or Plain Text email
							$summarymails->isHTML(true);
							$footer = '<img src="cid:1001" />';
							$summarymails->AddEmbeddedImage('C:\wamp\www\\elsjobs\images\maillogo.png', '1001', 'maillogo.png'); 
							//Message Html Body
							$summarymails->Subject = "Equipment & Lifting Solutions";
							$summarymails->Body .= '<br><p><strong>Failed Messages:</strong></p>';
							$k = 1;
							for($i=0;$i<count($cust_names);$i++)
								{
									$summarymails->Body .= $k.'.<strong>'.$conts[$i].'</strong>&nbsp;&nbsp;<strong>'.$cust_names[$i].'</strong>&nbsp;'." was missing an email address or failed to send <br>";
									$k++;
								}
							$summarymails->Body .='<p><strong>Successful Messages:</strong></p>';
							$j = 1;
							for($i=0;$i<count($cust_name);$i++)
								{
									$summarymails->Body .=$j.'.<strong>'.$cont[$i].'</strong>&nbsp;&nbsp;<strong>'.$cust_name[$i].'</strong>&nbsp;'." was emailed successfully <br>";
									$j++;
								}
							$summarymails->Body .= '<br>'.$footer;
							//$mail->AltBody = "This is the plain text version of the email content"
							if(!$summarymails->send()) 
								{
									echo "Mailer Error: " . $summarymails->ErrorInfo;
								} 
							else 
								{
									echo "Message has been sent successfully<br>";
									$summarymails->ClearAddresses();
									$summarymails->ClearAttachments();
								}
							
							}
					else
						{
							if($mailbody->updated($mailid,$messagebody,$attachmenturl,$attachmentname,$subject))
								{
									include_once 'class/class.phpmailer.php';
									
									$mail = new PHPMailer;
									
									$mail->From = "dispatch@1800welift.com.au";
									
									$mail->FromName = "Equipment & Lifting Solutions Pty Ltd";
									
									//$mail->addAddress("andrew@ablit.com.au", "ABL IT");
									//$mail->addAddress("rohitnehra0@gmail.com", "rohit");
									$mail->addAddress($preview_mail, "rohit");
									$mailbodydata = $mailbody->mailbodydata(2);
									$messagebody = $mailbodydata['mail_body'];
									$pdfattachments = json_decode($mailbodydata['mail_pdflink']);
									
									//Provide file path and name of the attachments
									if(is_array($pdfattachments)){ foreach($pdfattachments as $pdfattachment)
										{ 
										$info = new SplFileInfo($pdfattachment->imagepath);
										$extension = $info->getExtension();
										$attachpath = 'C:\wamp\www\\elsjobs'.'\\'.$pdfattachment->imagepath;
										//$attachpath = 'C:\wamp\www\\elsjobs'.'\\/1464097420-doc-flat.png';
										$attachname = $pdfattachment->imagename;
										
										$mail->AddAttachment($attachpath, $attachname,  $encoding = 'base64', $type = 'application/'.$extension);		
										}
								}
								
								
								//$mail->addAttachment("file.txt", "File.txt");        
								//$mail->addAttachment("images/profile.png"); //Filename is optional
								
								$mail->isHTML(true);
								
								$footer = '<br>
								<style type="text/css">
								.tg  {border-collapse:collapse;border-spacing:0;border:none;width:80%;}
								.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
								.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
								.tg .tg-baqh{text-align:center;vertical-align:top}
								</style>
								<table class="tg">
								<tr>
								<th class="tg-wxgh" style="text-align:left;"><strong>Access &amp; EWP</strong></th>
								<th class="tg-wxgh" style="display: block; margin-left: 10px;"><strong></strong></th>
								</tr>
								<tr>
								<td class="tg-yw4l"><strong>Jeff Facoory</strong><br>0437 776 926<br><a href="mailto:jeff@1800welift.com.au">jeff@1800welift.com.au</a></td>
								<td class="tg-yw4l" style="display: block; margin-left: 50px;"><strong></strong><br><br></td>
								</tr>
								<tr>
								<td class="tg-yw4l"><strong>Mark Harrison
Logistics and Sales</strong><br>0427 288 886<br><a href="sales@1800welift.com.au">sales@1800welift.com.au</a></td>
								<td class="tg-yw4l" style="display: block; margin-left: 50px;"><strong></strong><br><br></td>
								</tr>
								<tr>
								<td class="tg-baqh" colspan="2"><a href="http://www.1800welift.com.au">www.1800welift.com.au</a><br>
								<br>
								<a href="https://www.facebook.com/Equipment-Lifting-Solutions-616074868448237/?fref=ts"><img src="http://dispatch.1800welift.com.au/elsjobs/images/fb.jpg" style="width:120px;" align="left"></a> <a href="https://instagram.com/ELS_1800welift"><img src="http://dispatch.1800welift.com.au/elsjobs/images/ig.jpg" style="width:120px;" align="right"></a></td>
								</tr>
								</table>
								<br>
								<img src="http://dispatch.1800welift.com.au/elsjobs/images/els-trucks.jpg" style="max-width:800px;">
								<img src="cid:1001" style="max-width:800px;" />';
								$mail->AddEmbeddedImage('C:\wamp\www\\elsjobs\images\maillogo.png', '1001', 'maillogo.png'); 
								//Message Html Body
								$mail->Subject = $subject;
								$mail->Body = $messagebody.$footer.'<br><br>';
								
								if(!$mail->send()) 
								{
								echo "Mailer Error: " . $mail->ErrorInfo;
								} 
								else 
								{
								echo "Message has been sent successfully";
								$mail->ClearAddresses();
								$mail->ClearAttachments();
								}
								}
						}
					}
			catch(PDOException $e)
				{
				echo $e->getMessage();
				}
			exit;
			}
			
	if($action === 'deleteattachmentequipment')
		{
			$attachid = $_POST['attchid'];
			$attch = $_POST['attach'];
			//echo $attachid.'<br>'.$attch;
			$equiattach = $equipment->previousattach($attachid,$attch);
		}
	
	if($action === 'deleteuserattachment')
		{
			$deleteuserattachment_id = $_POST['userattachid'];
			$userattach = $user->deleteuserattachment($deleteuserattachment_id);
		}
	
	if($action === 'auxpreviewsizeattach')
		{
			$auxattachment_preview = '';
			$auxattachequisize_id = $_REQUEST['auxattachequisize'];
			$auxattachequi = $auxequipment->auxattachequisize($auxattachequisize_id);
			
			$auxattachment_preview .= '  <ul>';
			foreach($auxattachequi as $attachequis)
				{
					if($attachequis['attachment'])
					{ 
						$info = new SplFileInfo($attachequis['attachment']);
						$extension = $info->getExtension();
						if($extension == 'msg')
							{
								$imagepath = home_base_url().'images/msg.png';
							}
						elseif($extension == 'pdf' || $extension == 'PDF')
							{
								$imagepath = home_base_url().'images/pdf.png';
							}
						elseif($extension == 'zip')
							{
								$imagepath = home_base_url().'images/zip.png';
							}
						elseif($extension == 'docx' || $extension == 'doc')
							{
								$imagepath = home_base_url().'images/doc.png';
							}
						elseif($extension == 'mov' || $extension == 'MOV')
							{
								$imagepath = home_base_url().'images/mov.png';
							}
						elseif($extension == 'xls' || $extension =='xlsx')
							{
								$imagepath = home_base_url().'images/excel.png';
							}
						else
							{
								$imagepath = home_base_url().$attachequis['attachment'];
							}
				
					$asd = explode('/',$attachequis['attachment']);
					$last_name = end($asd);
					$first_name = explode('-',$last_name);
					array_splice($first_name, 0, 1);
					$image_name = implode('-',$first_name);
					$auxattachment_preview .= ' <li class="list-group-item"> <a href="'.home_base_url().''.$attachequis['attachment'].'" target="_blank"> <img width="60" height="60" src="'.$imagepath.'" alt="'.$image_name.'"  /> <span class="imagenametext"> '.substr($image_name, 0 , 30).'</span></a>
							
						  </li>';
					}
				}
			$auxattachment_preview .= ' </ul>';
			echo $output = json_encode( array('appenddata' => $auxattachment_preview,) );
		
			exit;
		}
	
	if($action === 'deleteauxequisize')
		{
			$auxequisizeda = $_REQUEST['auxequisize'];
			$auxequisizedatasize = $auxequipment->auxdeleteequipmentsizeattach($auxequisizeda);
			exit;	
		}
	if($action === 'auxdeleteattachmentequipment')
		{
			$auxattachid = $_POST['auxattchid'];
			$auxattch = $_POST['auxattach'];
			//echo $attachid.'<br>'.$attch;
			$auxequiattach = $auxequipment->previousattach($auxattachid,$auxattch);
		}
	if($action === 'logbookaction')
		{
			$logbook_id = $_POST['logbook'];
			$logbook = $_POST['logbookvalue'];
			$logbookequi = $equipment->equisizelogbook($logbook_id,$logbook);
			exit;
		}
	
	if($action === 'auxlogbookaction')
		{
			$logbook_id = $_POST['logbook'];
			$logbook = $_POST['logbookvalue'];
			$logbookequi = $auxequipment->auxequisizelogbook($logbook_id,$logbook);
			exit;
		}
	
	/*if($actionss === 'extend_sequnecejob')
		{
		
			$startdate = $_POST['sequencejobstart'];
			$enddate = $_POST['sequencejobsend'];
			$startdate = str_replace('/', '-', $startdate);
			$enddate = str_replace('/', '-', $enddate);
			$start = strtotime($startdate);
			$end = strtotime($enddate);
		
			if($startdate)
				{
			$weekdays = array();
					if($_POST['sunday'] == '' && $_POST['saturday'] == '')
						{
							while ($start <= $end)
								 {
									if (date('N', $start) <= 5)
										{
											$current = date('d/m/Y', $start);
											//$result[$current] = 7.5;
											$weekdays[] = $current;
										}
								$start += 86400;
								}
						}
				if($_POST['sunday'] == 'sunday' && $_POST['saturday'] == '')
					{
						while ($start <= $end)
							 {
								$chk = date('D', $start);
								if (date('N', $start) <= 7) 
									{
										if($chk != 'Sat')
											{
												$current = date('d/m/Y', $start);
												//$result[$current] = 7.5;
												$weekdays[] = $current;
											}
									}
							$start += 86400;
							}
					
					
					}
				if($_POST['sunday'] == '' && $_POST['saturday'] == 'saturday')
					{
					
						while ($start <= $end)
							 {
								$chk = date('D', $start);
									if (date('N', $start) <= 7) 
										{
											if($chk != 'Sun')
												{
													$current = date('d/m/Y', $start);
													//$result[$current] = 7.5;
													$weekdays[] = $current;
												}
										}
							$start += 86400;
							}
					
					}
				if($_POST['sunday'] == 'sunday' && $_POST['saturday'] == 'saturday')
					{
					
						while ($start <= $end)
							{
							
								if (date('N', $start) <= 7) 
									{
									
										$current = date('d/m/Y', $start);
										//$result[$current] = 7.5;
										$weekdays[] = $current;
									}
							
							$start += 86400;
							}
					}
			}
		
		
			$job_crosshire = '0';
			$job_tbc = '0';
			$job_meetings = '0';
			$cross = $_POST['sports'];
			if($cross == 'cross')
				{
				
				$job_crosshire = '1';
				}
			if($cross == 'tbc')
				{
				
				$job_tbc = '1';
				}
		if($cross == 'meetings')
				{
				
				$job_meetings = '1';
				}
		
	
		$attachmenturl = $_POST['picedit'];
		$attachmentname = $_POST['piceditname'];
		
		$secureattachmenturl = $_POST['securepicedit'];
		$secureattachmentname = $_POST['securepiceditname'];
		
		$addcrane='';
		$addcranesize= '';
		$addclient = $_POST['addclient'];
		$createbyjobid = $_POST['createbyjobid'];
		$job_client = trim($_POST['client']); 
		$job_client_id = trim($_POST['client_id']);
		
		$job_date = trim($_POST['date']);
		$job_time = trim($_POST['time']);
		$jobaddress = $_POST['address'];
			if($jobaddress)
				{
				$job_address = $jobaddress;
				} 
			else 
				{
				$job_address = $_POST['searchaddress'];   
				}
		
		$job_lift = trim($_POST['lift']);
		$job_rporder = isset($_POST['rporder']) ? $_POST['rporder'] : '';
		$job_dogman = isset($_POST['dogman']) ? $_POST['dogman'] : '';
		$job_operator = isset($_POST['operator']) ? $_POST['operator'] : '';
		$job_auxequipment = isset($_POST['aux']) ? $_POST['aux'] : '';
		$job_contact = $_POST['searchcontact'];
		
		$addcontact = $_POST['contact'];
		$addcontact_id = $_POST['contact_id'];
		
		$job_detail = trim($_POST['detail']);
		
		$job_securenotes = trim($_POST['securenotes']);
		$job_cranetype = trim($_POST['crane']);
		$job_crane_size = trim($_POST['cranesize']);
		$job_crane = isset($_POST['craneus']) ? $_POST['craneus'] : ''; 
		
			if(is_array($weekdays))
				{		
					$sequence_id = 1;
						for($d = 0; $d < count($weekdays); $d++)
							{
								for($c = 0; $c < count($job_crane); $c++)
									{
									$job_cranesize = $_POST['size_'.$job_crane[$c]];
									$job_time = trim($_POST['sequencejobtime']);
									$job_date = $weekdays[$d];
									
									$jobss = $job->insert($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane[$c],$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id);
									$sequence_id++;}
							
							}
				}
			else
				{
					for($c = 0; $c < count($job_crane); $c++)
						{
						$job_cranesize = $_POST['size_'.$job_crane[$c]];
						$sequence_id = 0;
						$jobss = $job->insert($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane[$c],$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id);
						}
				
				}
			exit;
		
		}
	*/	
	/*if($actionss === 'edit')
		{
			$startdate = $_POST['sequencejobstartsdate'];
			$enddate = $_POST['sequencejobendsdate'];
			$startdate = str_replace('/', '-', $startdate);
			$enddate = str_replace('/', '-', $enddate);
			$start = strtotime($startdate);
			$end = strtotime($enddate);
			
			if($startdate)
				{
					$weekdays = array();
						if($_POST['sunday'] == '' && $_POST['saturday'] == '')
							{
								while ($start <= $end) 
									{
										if (date('N', $start) <= 5) 
											{
												$current = date('d/m/Y', $start);
												//$result[$current] = 7.5;
												$weekdays[] = $current;
											}
									$start += 86400;
									}
							}
					if($_POST['sunday'] == 'sunday' && $_POST['saturday'] == '')
						{
							while ($start <= $end) 
								{
									$chk = date('D', $start);
									if (date('N', $start) <= 7) 
										{
											if($chk != 'Sat')
												{
													$current = date('d/m/Y', $start);
													//$result[$current] = 7.5;
													$weekdays[] = $current;
												}
										}
									$start += 86400;
								}
						}
					if($_POST['sunday'] == '' && $_POST['saturday'] == 'saturday')
						{
							while ($start <= $end) 
								{
									$chk = date('D', $start);
										if (date('N', $start) <= 7) 
											{
												if($chk != 'Sun')
												{
													$current = date('d/m/Y', $start);
													//$result[$current] = 7.5;
													$weekdays[] = $current;
												}
											}
									$start += 86400;
								}
						}
					if($_POST['sunday'] == 'sunday' && $_POST['saturday'] == 'saturday')
						{
							while ($start <= $end) 
								{
								
									if (date('N', $start) <= 7) 
										{
										
											$current = date('d/m/Y', $start);
											//$result[$current] = 7.5;
											$weekdays[] = $current;
										}
									
									$start += 86400;
								}
						}
				}
			$attachmenturl = $_POST['picedit'];
			$attachmentname = $_POST['piceditname'];
			$createbyjobid = $_POST['currentuserid'];
			$secureattachmenturl = $_POST['securepicedit'];
			$secureattachmentname = $_POST['securepiceditname'];
			
			$addclient = $_POST['addclient']; 
			
			$job_id = trim($_POST['job_id']); 
			$job_client = trim($_POST['client']); 
			$job_client_id = trim($_POST['client_id']); 
			
			$job_crane = trim($_POST['crane']);
			$job_cranesize = trim($_POST['cranesize']);
			$job_cross_hire = trim($_POST['crosshirevalue']);
			
			
			$jobaddress = $_POST['address'];
			if($jobaddress)
				{
					$job_address = $jobaddress;
				} 
			else 
				{
					$job_address = $_POST['searchaddress'];   
				}
			
			$job_lift = trim($_POST['lift']);
			$job_dogman = isset($_POST['dogman']) ? $_POST['dogman'] : '';
			$job_operator = isset($_POST['operator']) ? $_POST['operator'] : '';
			$job_auxequipment = isset($_POST['aux']) ? $_POST['aux'] : '';
			$job_contact = $_POST['searchcontact'];
			$addcontact = $_POST['contact'];
			$addcontact_id = $_POST['contact_id'];
			$job_detail = trim($_POST['detail']);
			$job_finishtime = $_POST['finishtime'];
			$job_rporder = $_POST['rporder'];
			$job_securenotes = trim($_POST['securenotes']);
			$job_timeleavingyard = $_POST['timeleavingyard'];
			$job_timearriveyard = $_POST['timearriveyard'];
			if(isset($_POST['crosshire']))
				{
					$job_crosshire = 1;
				}
			else
				{
					$job_crosshire = 0;
				}
			if(isset($_POST['tbc']))
				{
					$job_tbc = 1;
				}
			else
				{
					$job_tbc = 0;
				}
			if(isset($_POST['meetings']))
				{
					$job_meetings = 1;
				}
			else
				{
					$job_meetings = 0;
				} 
			 
			$sequence_idss = $_POST['sequanceid'];
			if(is_array($weekdays))
				{
					$sequence_id = 1;
					for($d = 0; $d < count($weekdays); $d++)
						{
						
							$job_time = trim($_POST['sequencejobtimedate']);
							$job_date = $weekdays[$d];
							echo 'data';
							$jobss = $job->sequancedate($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane[$c],$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id,$sequence_idss);
							$sequence_id++;
						}
				
				
				}
			
			exit;
		
		}*/
	if($action === 'secureattachuploadimages')
		{
			$upload_dir = '../attachment/';
			$uploaddir = 'attachment/';
			
			if(isset($_FILES["myfile"]))
				{
					$imagepaths = '';
					//	This is for custom errors;	
					$error =$_FILES["myfile"]["error"];
					//You need to handle  both cases
					//If Any browser does not support serializing of multiple files using FormData() 
						if(!is_array($_FILES["myfile"]["name"])) //single file
							{
								$t=time();
								chmod($upload_dir, 777);
								
								$fileName = $_FILES["myfile"]["name"];
								$replacefor = array('-');
								$replacewith = array('#',' ');
								$fileName = str_replace($replacewith, $replacefor, $fileName);
								move_uploaded_file($_FILES["myfile"]["tmp_name"],$upload_dir.$t.'-'.$fileName);
								//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
								$imagepaths .='<input type="hidden" class="form-control" id="secureattach" name="secureattach[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
								<input type="hidden" class="form-control" id="secureattach" name="secureattachname[]"  value="'.$fileName.'">';
							}
						else  //Multiple files, file[]
							{
								$fileCount = count($_FILES["myfile"]["name"]);
								for($i=0; $i < $fileCount; $i++)
									{
										$t=time();
										chmod($upload_dir, 777);
										$fileName = $_FILES["myfile"]["name"][$i];
										move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$upload_dir.$t.'-'.$fileName);
										//$imagepaths[] = array('imagepath' => $uploaddir.$t.'-'.$fileName, 'imagename' => $fileName);
										$imagepaths .='<input type="hidden" class="form-control" id="secureattach" name="secureattach[]"  value="'.$uploaddir.$t.'-'.$fileName.'">
										<input type="hidden" class="form-control" id="secureattach" name="secureattachname[]"  value="'.$fileName.'">';
									}
							
							}
					echo $imagepaths;
				}
			
			exit;	
	}
/*function to inactive farm*/
if($action === 'removefarm')
{
	$farmId = $_POST['id'];
	$farms->deletefarms($farmId);
	exit;	
}
if($action === 'removefarmblock')
{
	$farmId = $_POST['fId'];
	$blockId = $_POST['blId'];
	$farms->deletefarmBlock($farmId, $blockId);
	exit;	
}
if($action === 'removeWorkType')
{
	$id = $_POST['id'];
	$setting->deleteWorkType($id);
	exit;	
}
if($action === 'getFarmBlocks')
{
	$fid = $_POST['fid'];
	$res = $farms->getAllBlocks($fid);
	echo json_encode($res);
	exit;
}
?>