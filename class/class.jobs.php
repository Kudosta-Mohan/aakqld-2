<?php 
class Jobs
{
	private $db;
			
	function __construct($DB_con)
		{
			$this->db = $DB_con;
		}
		
		
	public function insert($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane,$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id,$ewp_billing,$extend_id)
		{
		try
			{
			
				session_start();
				 $EWP_Bill = $ewp_billing;
				if($sequence_id == 1)
					{
						$stmt = $this->db->prepare("INSERT INTO sequencejob(job_client)VALUES(:client)");
						
						$stmt->bindparam(":client", $job_client_id);
						
						$stmt->execute();
						$sequencejob_id = $this->db->lastInsertId();
						$_SESSION['sequencejob_id'] = $sequencejob_id;
					}
			
			
				$stmt = $this->db->prepare("UPDATE job SET  sequence_id=:sequencejob_id WHERE id=:extend_id ");
				$stmt->bindparam(":sequencejob_id", $_SESSION['sequencejob_id']);
				$stmt->bindparam(":extend_id", $extend_id);
				$stmt->execute();
				
				$jobtimestamp = $job_date.' '. $job_time;
				$jobtimestamp = str_replace('/', '-', $jobtimestamp);
				$job_timestamp = strtotime($jobtimestamp);	
				$stmt = $this->db->prepare("SELECT cust_name FROM customer");
				
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
				$addlientsss = array();
				foreach($userRow as $userRows)
					{
						$addlientsss[]= $userRows['cust_name'];
					}
			
				if(!in_array($addclient,$addlientsss))
					{
					
						if(!empty($addclient) && empty($job_client))
							{
							
							$cust_status = 'Active';
							$createdate= date("Y-m-d H:i:s"); 
							
							
							$stmt = $this->db->prepare("INSERT INTO customer(cust_name,cust_address,cust_status,created_date,updated_date)VALUES(:cust_name, :cust_address, :cust_status, :created_date, :updated_date)");
							
							$stmt->bindparam(":cust_name", $addclient);
							$stmt->bindparam(":cust_address", $job_address);
							$stmt->bindparam(":cust_status", $cust_status);
							$stmt->bindparam(":created_date", $createdate);
							$stmt->bindparam(":updated_date", $createdate);
							
							$stmt->execute();
							
							$cust_id = $this->db->lastInsertId();
							$_SESSION['customer_id'] = $cust_id;
							
								if(!empty($addcontact) && !empty($cust_id) )
									{
									
									$cont_name = $addcontact;
									$cont_status = 'Active';
									$createdate1= date("Y-m-d H:i:s"); 
									
									
									$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_status,created_date,updated_date)VALUES(:cust_id, :cont_name, :cont_status, :created_date, :updated_date)");
									
									$stmt->bindparam(":cust_id", $cust_id);
									$stmt->bindparam(":cont_name", $cont_name);
									$stmt->bindparam(":cont_status", $cont_status);
									$stmt->bindparam(":created_date", $createdate1);
									$stmt->bindparam(":updated_date", $createdate1);
									$stmt->execute(); 	
									
									$cont_id = $this->db->lastInsertId();
									
									}
							
							
							} 
					} 
					else if(empty($addclient) && !empty($job_client) && !empty($job_client_id))
						{
					
							if(!empty($addcontact) && !empty($job_client_id) )
								{
								
								$cont_name = $addcontact;
								$cont_status = 'Active';
								$createdate1= date("Y-m-d H:i:s"); 
								
								
								$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_status,created_date,updated_date)VALUES(:cust_id, :cont_name, :cont_status, :created_date, :updated_date)");
								
								$stmt->bindparam(":cust_id", $job_client_id);
								$stmt->bindparam(":cont_name", $cont_name);
								$stmt->bindparam(":cont_status", $cont_status);
								$stmt->bindparam(":created_date", $createdate1);
								$stmt->bindparam(":updated_date", $createdate1);
								$stmt->execute(); 	
								
								$cont_id = $this->db->lastInsertId();
								
								}
					
						}
			
			
			
				if($job_client_id)
					{
					$job_clientid = $job_client_id;
					
					} 
				else if($cust_id)
					{
					$job_clientid = $cust_id;
					}	
				else if($_SESSION['customer_id'])
					{
					$job_clientid = $_SESSION['customer_id'];
					}
				if($job_contact)
					{
					$jobcontact = $addcontact_id;
					
					} 
				else if($cont_id)
					{
					$jobcontact = $cont_id;
					} 
				else 
					{
					$jobcontact = '';
					}		   
			
			
			
				$result = count($attachmenturl);
				
				if(is_array($attachmenturl) && is_array($attachmentname))
					{
						for($i=0; $i < $result; $i++)
							{
							$attachmentdetail[] = array('imagepath' => $attachmenturl[$i], 'imagename' => $attachmentname[$i] ); 
							}
					} 
				
				
				$secureresult = count($secureattachmenturl); 
				
				if(is_array($secureattachmenturl) && is_array($secureattachmentname))
					{
						for($j=0; $j < $secureresult; $j++)
							{
							$secureattachmentdetail[] = array('imagepath' => $secureattachmenturl[$j], 'imagename' => $secureattachmentname[$j] ); 
							}
					} 
			
			
			
				$imagepath = $attachmentdetail;
				$secureimagepath = $secureattachmentdetail;
				
				if($job_cranesize)
					{ 
					$jobcranesize = $job_cranesize; 
					} 
				else 
					{
					$jobcranesize = '';
					}
				
				$job_status = 'Active';
				$createdate= date("Y-m-d H:i:s"); 
				$job_imagepath = json_encode($imagepath);
				$job_secureimagepath = json_encode($secureimagepath);
				$job_dogman = json_encode($job_dogman);
				$job_operator = json_encode($job_operator);
				$job_auxequipmentas = json_encode($job_auxequipment);
					if($sequence_id == 0)
						{
							$sequence_ids = $sequence_id;
						}
					else
						{
							$sequence_ids = $_SESSION['sequencejob_id'];
						}
			
				$stmt = $this->db->prepare("INSERT INTO job(job_createdby,job_clie_id,job_equi_id,job_equi_size,job_cross_hire_size,job_date,job_time,job_timestamp,job_address,job_lift,job_purchase,job_dogm_id,job_oper_id,job_cont_name,job_detail,job_attachments,job_securedetail,job_secureattachment,job_cross_hire,job_tbc,job_meetings,job_cranetype,job_cranetype_size,job_status,created_date,updated_date,job_auxequipment,change_eq_id,change_eq_size,sequence_id,ewp_billing)VALUES(:job_createdby, :job_client, :job_crane, :job_equi_size, :job_cross_hire_size, :job_date, :job_time, :job_timestamp, :job_address, :job_lift, :job_purchase, :job_dogman, :job_operator, :job_contact, :job_detail, :job_imagepath, :job_securedetail, :job_secureattachment, :cross_hire, :tbc, :meetings, :job_addcrane, :job_addcranesize, :job_status, :created_date, :updated_date, :job_auxequipment, :job_crane1, :job_equi_size1, :sequence_id, :ewp_billing)");
				
				$stmt->bindparam(":job_createdby", $createbyjobid);
				$stmt->bindparam(":job_client", $job_clientid);
				$stmt->bindparam(":job_crane", $job_crane);
				$stmt->bindparam(":job_equi_size", $jobcranesize);
				$stmt->bindparam(":job_crane1", $job_crane);
				$stmt->bindparam(":job_equi_size1", $jobcranesize);
				$stmt->bindparam(":job_cross_hire_size", $job_cross_hire);
				$stmt->bindparam(":job_date", $job_date);
				$stmt->bindparam(":job_time", $job_time);
				$stmt->bindparam(":job_timestamp", $job_timestamp);
				$stmt->bindparam(":job_address", $job_address);
				$stmt->bindparam(":job_lift", $job_lift);
				$stmt->bindparam(":job_purchase", $job_rporder);
				$stmt->bindparam(":job_dogman", $job_dogman);
				$stmt->bindparam(":job_operator", $job_operator);
				$stmt->bindparam(":job_contact", $jobcontact);
				$stmt->bindparam(":job_detail", $job_detail);
				$stmt->bindparam(":job_imagepath", $job_imagepath);
				$stmt->bindparam(":job_securedetail", $job_securenotes);
				$stmt->bindparam(":job_secureattachment", $job_secureimagepath);
				$stmt->bindparam(":job_status", $job_status);
				$stmt->bindparam(":created_date", $createdate);
				$stmt->bindparam(":updated_date", $createdate);
				$stmt->bindparam(":cross_hire", $job_crosshire);
				$stmt->bindparam(":tbc", $job_tbc);
				$stmt->bindparam(":meetings", $job_meetings);
				$stmt->bindparam(":job_addcrane", $addcrane);
				$stmt->bindparam(":job_addcranesize", $addcranesize);
				$stmt->bindparam(":job_auxequipment", $job_auxequipmentas);
				$stmt->bindparam(":sequence_id", $sequence_ids);
				$stmt->bindparam(":ewp_billing", $EWP_Bill);
				
				$stmt->execute(); 
				
				$job_id = $this->db->lastInsertId();
				
			
				if(empty($job_tbc) )
					{
					
						if($sequence_id == 1)
							{
							
							$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
							$stmt->execute();
							$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
							
								foreach($userfields as $userfield)
									{
									
									$userid = $userfield['user_id'];
									
									$noti_type = 'Added a new job';
									$noti_status = 'Unseen';
									
									$createdatenoti= date("Y-m-d H:i:s"); 
									$noti_jobtimestamp = strtotime($createdatenoti);	
									
									
									$stmt = $this->db->prepare("INSERT INTO notifications(noti_user_id,noti_job_id,noti_jobtimestamp,noti_type,noti_status,created_date,updated_date,sequence_id)VALUES(:noti_user_id, :noti_job_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :sequence_id)");
									$stmt->bindparam(":noti_user_id", $userid);  
									$stmt->bindparam(":noti_job_id", $job_id);
									$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
									$stmt->bindparam(":noti_type", $noti_type);
									$stmt->bindparam(":noti_status", $noti_status);
									$stmt->bindparam(":created_date", $createdatenoti);
									$stmt->bindparam(":updated_date", $createdatenoti);
									$stmt->bindparam(":sequence_id", $sequence_id);
									$stmt->execute(); 	
									}
							
							}
					if($sequence_id == 0)
							{
							
							$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
							$stmt->execute();
							$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
							
							
								foreach($userfields as $userfield)
									{
									
									$userid = $userfield['user_id'];
									
									$noti_type = 'Added a new job';
									$noti_status = 'Unseen';
									
									$createdatenoti= date("Y-m-d H:i:s"); 
									$noti_jobtimestamp = strtotime($createdatenoti);	
									
									
									$stmt = $this->db->prepare("INSERT INTO notifications(noti_user_id,noti_job_id,noti_jobtimestamp,noti_type,noti_status,created_date,updated_date,sequence_id)VALUES(:noti_user_id, :noti_job_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :sequence_id)");
									$stmt->bindparam(":noti_user_id", $userid);  
									$stmt->bindparam(":noti_job_id", $job_id);
									$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
									$stmt->bindparam(":noti_type", $noti_type);
									$stmt->bindparam(":noti_status", $noti_status);
									$stmt->bindparam(":created_date", $createdatenoti);
									$stmt->bindparam(":updated_date", $createdatenoti);
									$stmt->bindparam(":sequence_id", $sequence_id);
									$stmt->execute(); 	
									}
							
							}
					
					}
				if($job_tbc == '1')
					{
					
					$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
					$stmt->execute();
					$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
					
						foreach($userfields as $userfield)
								{
								$userid = $userfield['user_id'];
								
								$noti_type = 'Added a new job';
								$noti_status = 'Unseen';
								
								$createdatenoti= date("Y-m-d H:i:s"); 
								$noti_jobtimestamp = strtotime($createdatenoti);	
								
								
								$stmt = $this->db->prepare("INSERT INTO notifications(noti_user_id,noti_job_id,noti_jobtimestamp,noti_type,noti_status,created_date,updated_date,tbc_notification)VALUES(:noti_user_id, :noti_job_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :job_tbc)");
								$stmt->bindparam(":noti_user_id", $userid);  
								$stmt->bindparam(":noti_job_id", $job_id);
								$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
								$stmt->bindparam(":noti_type", $noti_type);
								$stmt->bindparam(":noti_status", $noti_status);
								$stmt->bindparam(":created_date", $createdatenoti);
								$stmt->bindparam(":updated_date", $createdatenoti);
								$stmt->bindparam(":job_tbc", $job_tbc);
								$stmt->execute(); 
								}
					}
				
				return $stmt; 
			}
		catch(PDOException $e)
			{
			echo $e->getMessage();
			}    
		}
		
		public function updated($jobeditby,$job_id,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane,$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_purchase,$job_dogman,$job_operator,$job_contact,$job_detail,$job_finishtime,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$job_timeleavingyard,$job_timearriveyard,$job_auxequipment,$ewp)
			{
			try
				{
				
					// notification job 
					$stmt = $this->db->prepare("SELECT * FROM job WHERE id=:job_id LIMIT 1");
					$stmt->execute(array(':job_id'=>$job_id));
					$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
					
						if($job_tbc != $userRow['job_tbc'])
							{
							
							
							$userid = $userfield['user_id'];
							
							$noti_type = 'Added a new job';
							$noti_status = 'Unseen';
							
							$createdatenoti= date("Y-m-d H:i:s"); 
							$noti_jobtimestamp = strtotime($createdatenoti);	
							
							$stmt = $this->db->prepare("UPDATE notifications SET noti_jobtimestamp=:noti_jobtimestamp, noti_type=:noti_type, noti_status=:noti_status, updated_date=:updated_date, tbc_notification=:job_tbc WHERE noti_job_id=:job_id"); 
							$stmt->bindparam(":job_id", $job_id);
							$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
							$stmt->bindparam(":noti_type", $noti_type);
							$stmt->bindparam(":noti_status", $noti_status);
							$stmt->bindparam(":updated_date", $createdatenoti);
							$stmt->bindparam(":job_tbc", $job_tbc);
							$stmt->execute(); 
							}			 
					if($userRow['job_date'] != $job_date) 
						{
						$ext_mgs_edit='0'; 
						$date_msg = '1'; 
						$noti_status = 'Unseen';
						$createdatenoti= date("Y-m-d H:i:s"); 
						$noti_jobtimestamp = strtotime($createdatenoti);	
						$stmt = $this->db->prepare("UPDATE notifications SET noti_jobtimestamp=:noti_jobtimestamp, noti_status=:noti_status, updated_date=:updated_date, job_date_change=:date_msg, ext_mgs_edit=:ext_mgs_edit WHERE noti_job_id=:noti_job_id");
						$stmt->bindparam(":noti_job_id", $job_id);
						$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
						$stmt->bindparam(":noti_status", $noti_status);
						$stmt->bindparam(":updated_date", $createdatenoti);
						$stmt->bindparam(":date_msg", $date_msg);
						$stmt->bindparam(":ext_mgs_edit", $ext_mgs_edit);
						
						$stmt->execute();
						} 
					else 
						{
						$ext_mgs_edit='1';
						$noti_type = 'Changed Job';
						$noti_status = 'Unseen';
						$createdatenoti= date("Y-m-d H:i:s"); 
						$noti_jobtimestamp = strtotime($createdatenoti);	
						$stmt = $this->db->prepare("UPDATE notifications SET noti_jobtimestamp=:noti_jobtimestamp, noti_type=:noti_type,  noti_status=:noti_status, updated_date=:updated_date, ext_mgs_edit=:ext_mgs_edit WHERE noti_job_id=:noti_job_id");
						$stmt->bindparam(":noti_job_id", $job_id);
						$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
						$stmt->bindparam(":noti_type", $noti_type);
						$stmt->bindparam(":noti_status", $noti_status);
						$stmt->bindparam(":updated_date", $createdatenoti);
						$stmt->bindparam(":ext_mgs_edit", $ext_mgs_edit);
						$stmt->execute();
						}
					
					// end notification job 	
					if(!empty($addclient) && empty($job_client))
						{
						
						$cust_status = 'Active';
						$createdate= date("Y-m-d H:i:s"); 
						
						
						$stmt = $this->db->prepare("INSERT INTO customer(cust_name,cust_status,created_date,updated_date)VALUES(:cust_name, :cust_status, :created_date, :updated_date)");
						
						$stmt->bindparam(":cust_name", $addclient);
						$stmt->bindparam(":cust_status", $cust_status);
						$stmt->bindparam(":created_date", $createdate);
						$stmt->bindparam(":updated_date", $createdate);
						$stmt->execute();
						
						$cust_id = $this->db->lastInsertId();
						
						
							if(!empty($addcontact) && !empty($cust_id) )
								{
								
								$cont_name = $addcontact;
								$cont_status = 'Active';
								$createdate1= date("Y-m-d H:i:s"); 
								
								
								$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_status,created_date,updated_date)VALUES(:cust_id, :cont_name, :cont_status, :created_date, :updated_date)");
								
								$stmt->bindparam(":cust_id", $cust_id);
								$stmt->bindparam(":cont_name", $cont_name);
								$stmt->bindparam(":cont_status", $cont_status);
								$stmt->bindparam(":created_date", $createdate1);
								$stmt->bindparam(":updated_date", $createdate1);
								$stmt->execute(); 	
								
								$cont_id = $this->db->lastInsertId();
								
								}
						
						
						} 
					else if(empty($addclient) && !empty($job_client) && !empty($job_client_id))
						{
						
						if(!empty($addcontact) && !empty($job_client_id) )
							{
							
							$cont_name = $addcontact;
							$cont_status = 'Active';
							$createdate1= date("Y-m-d H:i:s"); 
							
							
							$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_status,created_date,updated_date)VALUES(:cust_id, :cont_name, :cont_status, :created_date, :updated_date)");
							
							$stmt->bindparam(":cust_id", $job_client_id);
							$stmt->bindparam(":cont_name", $cont_name);
							$stmt->bindparam(":cont_status", $cont_status);
							$stmt->bindparam(":created_date", $createdate1);
							$stmt->bindparam(":updated_date", $createdate1);
							$stmt->execute(); 	
							
							$cont_id = $this->db->lastInsertId();
							
							}
						
						}
					if($job_client_id)
						{
							$job_clientid = $job_client_id;
						
						} 
					else if($cust_id)
						{
							$job_clientid = $cust_id;
						}	
					
					if($job_contact)
						{
							$jobcontact = $addcontact_id;
						
						} 
					else if($cont_id)
						{
							$jobcontact = $cont_id;
						} 
					else 
						{
							$jobcontact = '';
						}		   
					
					$result = count($attachmenturl);
					
					if(is_array($attachmenturl) && is_array($attachmentname))
						{
							for($i=0; $i < $result; $i++)
								{
									$attachmentdetail[] = array('imagepath' => $attachmenturl[$i], 'imagename' => $attachmentname[$i] ); 
								}
						} 
					
					$secureresult = count($secureattachmenturl); 
					
					if(is_array($secureattachmenturl) && is_array($secureattachmentname))
						{
							for($j=0; $j < $secureresult; $j++)
								{
									$secureattachmentdetail[] = array('imagepath' => $secureattachmenturl[$j], 'imagename' => $secureattachmentname[$j] ); 
								}
						} 	  
					
					if($userRow['sequence_id']!= 0)
						{
						
						$sequence_id = $userRow['sequence_id'];
						$jobtimestamp = $job_date.' '. $job_time;
						$jobtimestamp = str_replace('/', '-', $jobtimestamp);
						$job_timestamp = strtotime($jobtimestamp);				  
						
						
						$imagepath = $attachmentdetail;
						$secureimagepath = $secureattachmentdetail;
						
						
						/*$job_status = $jobmarkbilled;*/
						$createdate= date("Y-m-d H:i:s"); 
						$job_imagepath = json_encode($imagepath);
						$job_secureimagepath = json_encode($secureimagepath);
						$job_dogman = json_encode($job_dogman);
						$job_operator = json_encode($job_operator);
						$job_auxequipment = json_encode($job_auxequipment);
						if($userRow['job_time']!= $job_time){
						
						$stmt = $this->db->prepare("UPDATE job SET job_createdby=:jobeditby, job_clie_id=:job_client, job_equi_id=:job_crane, job_equi_size=:job_cranesize, job_cross_hire_size=:cross_hire, job_time=:job_time, job_timestamp=:job_timestamp, job_address=:job_address, job_lift=:job_lift, job_purchase=:job_purchase, job_dogm_id=:job_dogman, job_oper_id=:job_operator, job_cont_name=:job_contact, job_detail=:job_detail, job_finishtime=:job_finishtime, job_attachments=:job_imagepath, job_securedetail=:job_securedetail, job_secureattachment=:job_secureattachment,  job_cross_hire=:job_crosshire, job_tbc=:job_tbc, job_meetings=:job_meetings, job_time_leaving_yard=:job_timeleavingyard, job_time_arrived_back_at_yard=:job_timearrivedbackatyard, updated_date=:updated_date, job_auxequipment=:job_auxequipment, ewp_billing=:ewp_billing WHERE id=:job_id");
						
						$stmt->bindparam(":jobeditby", $jobeditby);
						$stmt->bindparam(":job_id", $job_id);
						$stmt->bindparam(":job_client", $job_clientid);
						$stmt->bindparam(":job_crane", $job_crane);
						$stmt->bindparam(":job_cranesize", $job_cranesize);
						$stmt->bindparam(":cross_hire", $job_cross_hire);
						//$stmt->bindparam(":job_date", $job_date);
						$stmt->bindparam(":job_time", $job_time);
						$stmt->bindparam(":job_timestamp", $job_timestamp);
						$stmt->bindparam(":job_address", $job_address);
						$stmt->bindparam(":job_lift", $job_lift);
						$stmt->bindparam(":job_purchase", $job_purchase);
						$stmt->bindparam(":job_dogman", $job_dogman);
						$stmt->bindparam(":job_operator", $job_operator);
						$stmt->bindparam(":job_contact", $jobcontact);
						$stmt->bindparam(":job_detail", $job_detail);
						$stmt->bindparam(":job_finishtime", $job_finishtime);
						$stmt->bindparam(":job_imagepath", $job_imagepath);
						$stmt->bindparam(":job_securedetail", $job_securenotes);
						$stmt->bindparam(":job_secureattachment", $job_secureimagepath);
						$stmt->bindparam(":job_crosshire", $job_crosshire);
						$stmt->bindparam(":job_tbc", $job_tbc);
						$stmt->bindparam(":job_meetings", $job_meetings);
						/*$stmt->bindparam(":editby", $jobeditby);
						$stmt->bindparam(":job_status", $job_status);*/
						$stmt->bindparam(":updated_date", $createdate);
						$stmt->bindparam(":job_timeleavingyard", $job_timeleavingyard);
						$stmt->bindparam(":job_timearrivedbackatyard", $job_timearriveyard);
						$stmt->bindparam(":job_auxequipment", $job_auxequipment);
						$stmt->bindparam(":ewp_billing", $ewp);
						$stmt->execute(); 
						
						
						
						return $stmt;
						
						}
					else 
						{
						
						$stmt = $this->db->prepare("UPDATE job SET job_createdby=:jobeditby, job_clie_id=:job_client, job_equi_id=:job_crane, job_equi_size=:job_cranesize, job_cross_hire_size=:cross_hire, job_timestamp=:job_timestamp, job_address=:job_address, job_lift=:job_lift, job_purchase=:job_purchase, job_dogm_id=:job_dogman, job_oper_id=:job_operator, job_cont_name=:job_contact, job_detail=:job_detail, job_finishtime=:job_finishtime, job_attachments=:job_imagepath, job_securedetail=:job_securedetail, job_secureattachment=:job_secureattachment,  job_cross_hire=:job_crosshire, job_tbc=:job_tbc, job_meetings=:job_meetings, job_time_leaving_yard=:job_timeleavingyard, job_time_arrived_back_at_yard=:job_timearrivedbackatyard, updated_date=:updated_date, job_auxequipment=:job_auxequipment, ewp_billing=:ewp_billing WHERE sequence_id=:sequence_id");
						
						$stmt->bindparam(":jobeditby", $jobeditby);
						$stmt->bindparam(":sequence_id", $sequence_id);
						$stmt->bindparam(":job_client", $job_clientid);
						$stmt->bindparam(":job_crane", $job_crane);
						$stmt->bindparam(":job_cranesize", $job_cranesize);
						$stmt->bindparam(":cross_hire", $job_cross_hire);
						//$stmt->bindparam(":job_date", $job_date);
						//$stmt->bindparam(":job_time", $job_time);
						$stmt->bindparam(":job_timestamp", $job_timestamp);
						$stmt->bindparam(":job_address", $job_address);
						$stmt->bindparam(":job_lift", $job_lift);
						$stmt->bindparam(":job_purchase", $job_purchase);
						$stmt->bindparam(":job_dogman", $job_dogman);
						$stmt->bindparam(":job_operator", $job_operator);
						$stmt->bindparam(":job_contact", $jobcontact);
						$stmt->bindparam(":job_detail", $job_detail);
						$stmt->bindparam(":job_finishtime", $job_finishtime);
						$stmt->bindparam(":job_imagepath", $job_imagepath);
						$stmt->bindparam(":job_securedetail", $job_securenotes);
						$stmt->bindparam(":job_secureattachment", $job_secureimagepath);
						$stmt->bindparam(":job_crosshire", $job_crosshire);
						$stmt->bindparam(":job_tbc", $job_tbc);
						$stmt->bindparam(":job_meetings", $job_meetings);
						/*$stmt->bindparam(":editby", $jobeditby);
						$stmt->bindparam(":job_status", $job_status);*/
						$stmt->bindparam(":updated_date", $createdate);
						$stmt->bindparam(":job_timeleavingyard", $job_timeleavingyard);
						$stmt->bindparam(":job_timearrivedbackatyard", $job_timearriveyard);
						$stmt->bindparam(":job_auxequipment", $job_auxequipment);
						$stmt->bindparam(":ewp_billing", $ewp);
						$stmt->execute(); 
						
						
						
						return $stmt;
						
						} 
						}
					
					else
						{
						
						$jobtimestamp = $job_date.' '. $job_time;
						$jobtimestamp = str_replace('/', '-', $jobtimestamp);
						$job_timestamp = strtotime($jobtimestamp);				  
						
						
						$imagepath = $attachmentdetail;
						$secureimagepath = $secureattachmentdetail;
						
						
						/*$job_status = $jobmarkbilled;*/
						$createdate= date("Y-m-d H:i:s"); 
						$job_imagepath = json_encode($imagepath);
						$job_secureimagepath = json_encode($secureimagepath);
						$job_dogman = json_encode($job_dogman);
						$job_operator = json_encode($job_operator);
						$job_auxequipment = json_encode($job_auxequipment);
						
						
						$stmt = $this->db->prepare("UPDATE job SET job_createdby=:jobeditby, job_clie_id=:job_client, job_equi_id=:job_crane, job_equi_size=:job_cranesize, job_cross_hire_size=:cross_hire, job_date=:job_date, job_time=:job_time, job_timestamp=:job_timestamp, job_address=:job_address, job_lift=:job_lift, job_purchase=:job_purchase, job_dogm_id=:job_dogman, job_oper_id=:job_operator, job_cont_name=:job_contact, job_detail=:job_detail, job_finishtime=:job_finishtime, job_attachments=:job_imagepath, job_securedetail=:job_securedetail, job_secureattachment=:job_secureattachment,  job_cross_hire=:job_crosshire, job_tbc=:job_tbc, job_meetings=:job_meetings, job_time_leaving_yard=:job_timeleavingyard, job_time_arrived_back_at_yard=:job_timearrivedbackatyard, updated_date=:updated_date, job_auxequipment=:job_auxequipment, ewp_billing=:ewp_billing WHERE id=:job_id");
						
						$stmt->bindparam(":jobeditby", $jobeditby);
						$stmt->bindparam(":job_id", $job_id);
						$stmt->bindparam(":job_client", $job_clientid);
						$stmt->bindparam(":job_crane", $job_crane);
						$stmt->bindparam(":job_cranesize", $job_cranesize);
						$stmt->bindparam(":cross_hire", $job_cross_hire);
						$stmt->bindparam(":job_date", $job_date);
						$stmt->bindparam(":job_time", $job_time);
						$stmt->bindparam(":job_timestamp", $job_timestamp);
						$stmt->bindparam(":job_address", $job_address);
						$stmt->bindparam(":job_lift", $job_lift);
						$stmt->bindparam(":job_purchase", $job_purchase);
						$stmt->bindparam(":job_dogman", $job_dogman);
						$stmt->bindparam(":job_operator", $job_operator);
						$stmt->bindparam(":job_contact", $jobcontact);
						$stmt->bindparam(":job_detail", $job_detail);
						$stmt->bindparam(":job_finishtime", $job_finishtime);
						$stmt->bindparam(":job_imagepath", $job_imagepath);
						$stmt->bindparam(":job_securedetail", $job_securenotes);
						$stmt->bindparam(":job_secureattachment", $job_secureimagepath);
						$stmt->bindparam(":job_crosshire", $job_crosshire);
						$stmt->bindparam(":job_tbc", $job_tbc);
						$stmt->bindparam(":job_meetings", $job_meetings);
						/*$stmt->bindparam(":editby", $jobeditby);
						$stmt->bindparam(":job_status", $job_status);*/
						$stmt->bindparam(":updated_date", $createdate);
						$stmt->bindparam(":job_timeleavingyard", $job_timeleavingyard);
						$stmt->bindparam(":job_timearrivedbackatyard", $job_timearriveyard);
						$stmt->bindparam(":job_auxequipment", $job_auxequipment);
						$stmt->bindparam(":ewp_billing", $ewp);
						$stmt->execute(); 
						
						
						
						return $stmt;
						}
				}
			catch(PDOException $e)
				{
				echo $e->getMessage();
				}    
			}
		
		public function updatedjobstatusprocess($job_id,$job_status_processed,$editby)
	{
	try
	{ 
	
	
	$updated_date = date("Y-m-d H:i:s"); 
	
	$stmt = $this->db->prepare("UPDATE job SET job_completedby=:editby, job_status_processed=:job_status_processed, updated_date=:updated_date, job_processed_user_name=:job_processed_user_name WHERE id=:job_id");
	
	$stmt->bindparam(":job_id", $job_id);
	$stmt->bindparam(":editby", $editby);
	$stmt->bindparam(":job_status_processed", $job_status_processed);
	$stmt->bindparam(":updated_date", $updated_date);
	$stmt->bindparam(":job_processed_user_name", $editby);
	$stmt->execute(); 
	
	return $stmt; 
	}
	catch(PDOException $e)
	{
	echo $e->getMessage();
	}    
	}
	
	public function updatedjobstatusprocesssequence($job_status_processed,$editby,$jobidsequence)
	{
	try
	{ 
	
	
	$updated_date = date("Y-m-d H:i:s"); 
	
	$stmt = $this->db->prepare("UPDATE job SET job_completedby=:editby, job_status_processed=:job_status_processed, updated_date=:updated_date, job_processed_user_name=:job_processed_user_name WHERE sequence_id=:jobidsequence");
	
	$stmt->bindparam(":editby", $editby);
	$stmt->bindparam(":job_status_processed", $job_status_processed);
	$stmt->bindparam(":updated_date", $updated_date);
	$stmt->bindparam(":jobidsequence", $jobidsequence);
	$stmt->bindparam(":job_processed_user_name", $editby);
	$stmt->execute(); 
	
	return $stmt; 
	}
	catch(PDOException $e)
	{
	echo $e->getMessage();
	}    
	}
	
	public function updatedjobstatus($job_id,$job_status_billed,$editby,$jobinvoice)
	{
	try
	{ 
	
	
	$updated_date = date("Y-m-d H:i:s"); 
	
	$stmt = $this->db->prepare("UPDATE job SET job_completedby=:editby, job_status_billed=:job_status_billed, updated_date=:updated_date, job_invoice=:job_invoice, job_billed_user_name=:job_billed_user_name WHERE id=:job_id");
	
	$stmt->bindparam(":job_id", $job_id);
	$stmt->bindparam(":editby", $editby);
	$stmt->bindparam(":job_status_billed", $job_status_billed);
	$stmt->bindparam(":updated_date", $updated_date);
	$stmt->bindparam(":job_invoice", $jobinvoice);
	$stmt->bindparam(":job_billed_user_name", $editby);
	$stmt->execute(); 
	
	return $stmt; 
	}
	catch(PDOException $e)
	{
	echo $e->getMessage();
	}    
	}
	
	public function updatedjobstatusprocesssequencebilled($job_status_billed,$editby,$jobinvoice,$jobidsequencebilled)
	{
	try
	{ 
	
	
	$updated_date = date("Y-m-d H:i:s"); 
	
	$stmt = $this->db->prepare("UPDATE job SET job_completedby=:editby, job_status_billed=:job_status_billed, updated_date=:updated_date, job_invoice=:job_invoice, job_billed_user_name=:job_billed_user_name WHERE sequence_id=:jobidsequencebilled");
	
	$stmt->bindparam(":editby", $editby);
	$stmt->bindparam(":job_status_billed", $job_status_billed);
	$stmt->bindparam(":updated_date", $updated_date);
	$stmt->bindparam(":job_invoice", $jobinvoice);
	$stmt->bindparam(":jobidsequencebilled", $jobidsequencebilled);
	$stmt->bindparam(":job_billed_user_name", $editby);
	$stmt->execute(); 
	
	return $stmt; 
	}
	catch(PDOException $e)
	{
	echo $e->getMessage();
	}    
	}
	
		public function getjoblist()
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM job");
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
							return $userRow;
							} 
					}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		public function updatejobtimestamp($job_id,$job_date,$job_time)
			{
			try
				{   	   
					$jobtimestamp = $job_date.' '. $job_time;
					$jobtimestamp = str_replace('/', '-', $jobtimestamp);
					$job_timestamp = strtotime($jobtimestamp);
					
					$stmt = $this->db->prepare("UPDATE job SET job_timestamp=:job_timestamp WHERE id=:job_id");
					$stmt->bindparam(":job_id", $job_id);
					$stmt->bindparam(":job_timestamp", $job_timestamp);	  
					$stmt->execute(); 
					
					return $stmt; 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}    
			}
		
		
		public function getjobnotificationlist()
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM notifications");
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
				}
			catch(PDOException $e)
				{
				echo $e->getMessage();
				}
			}
		public function updatejobnoticationtimestamp($noti_id,$noti_created)
			{
			try
				{   	   
				
					$jobtimestamp = strtotime($noti_created);
					
					$stmt = $this->db->prepare("UPDATE notifications SET noti_jobtimestamp=:noti_jobtimestamp WHERE noti_id=:noti_id");
					$stmt->bindparam(":noti_id", $noti_id);
					$stmt->bindparam(":noti_jobtimestamp", $jobtimestamp);	  
					$stmt->execute(); 
					
					return $stmt; 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}    
			}
		
		
		public function deleted($job_id,$reason)
			{
			try
				{   
					$job_status = 'Inactive';
					$stmt = $this->db->prepare("UPDATE job SET job_status=:job_status, job_reason=:job_reason WHERE id=:job_id");
					$stmt->bindparam(":job_id", $job_id);
					$stmt->bindparam(":job_status", $job_status);
					$stmt->bindparam(":job_reason", $reason);	
					$stmt->execute();
					
					// delete job notification
						if($job_id)
							{
								$crn = '0';
								$crn_date = '0';
								$noti_type = 'Removed Job';
								$noti_status = 'Unseen';
								$createdatenoti= date("Y-m-d H:i:s"); 
								$noti_jobtimestamp = strtotime($createdatenoti);	
								
								$stmt = $this->db->prepare("UPDATE notifications SET noti_jobtimestamp=:noti_jobtimestamp, noti_type=:noti_type, noti_status=:noti_status, updated_date=:updated_date, job_date_change=:crn_date, job_crn_change=:crn WHERE noti_job_id=:noti_job_id");
								$stmt->bindparam(":noti_job_id", $job_id);
								$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
								$stmt->bindparam(":noti_type", $noti_type);
								$stmt->bindparam(":noti_status", $noti_status);
								$stmt->bindparam(":updated_date", $createdatenoti);
								$stmt->bindparam(":crn_date", $crn_date);
								$stmt->bindparam(":crn", $crn);
								$stmt->execute(); 
						}
				
				// end of delete job notifications 
				
				return $stmt; 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}    
			}
		
		
		public function redirect($url)
			{
			header("Location: $url");
			}
		
		/*** get notification jobs ***/ 
		public function getnotificationjob($currentuserid)
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM notifications WHERE noti_user_id=:noti_user_id AND noti_status in ('Unseen') ORDER BY noti_jobtimestamp DESC");
					$stmt->bindparam(":noti_user_id", $currentuserid); 
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
					{
					return $userRow;
					} 
					
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		/*** get notification all jobs ***/ 
		public function getnotificationalljob($currentuserid)
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM notifications WHERE noti_user_id=:noti_user_id ORDER BY noti_jobtimestamp DESC LIMIT 50");
					$stmt->bindparam(":noti_user_id", $currentuserid); 
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		/*** update notification jobs ***/ 
		public function updatednotificationstatus($userid,$notification_status)
			{
			try
				{
					$stmt = $this->db->prepare("UPDATE notifications SET noti_status=:noti_status WHERE noti_user_id=:noti_user_id");
					$stmt->bindparam(":noti_user_id", $userid);
					$stmt->bindparam(":noti_status", $notification_status);	  
					$stmt->execute(); 
					
					return $stmt; 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		/*** get notification jobs last 7 days ago ***/ 
		public function getnotificationjoblastsevendays($currentuserid,$lastsevenimestamp)
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM notifications WHERE noti_user_id=:noti_user_id AND noti_jobtimestamp > :noti_jobtimestamp ORDER BY noti_jobtimestamp DESC");
					$stmt->bindparam(":noti_user_id", $currentuserid);
					$stmt->bindparam(":noti_jobtimestamp", $lastsevenimestamp); 
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		public function getjobbycranetypelistaccess($equi_id,$date)
			{
			try
				{
					$hiretbcmeeting = 0;
					$ewp_billing = 0;
					$stmt = $this->db->prepare("SELECT * FROM job WHERE ewp_billing=:ewp_billing AND job_equi_id=:equi_id AND job_date=:job_date AND job_status in ('Active','Processed','Billed','Closed') AND job_cross_hire=:job_hiretbcmeeting AND job_tbc=:job_hiretbcmeeting AND job_meetings=:job_hiretbcmeeting ORDER BY 	job_timestamp ASC");
					$stmt->bindparam(":equi_id", $equi_id);
					$stmt->bindparam(":job_date", $date);
					$stmt->bindparam(":job_hiretbcmeeting", $hiretbcmeeting);
					$stmt->bindparam(":ewp_billing", $ewp_billing);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		
		public function getjobbycranetypelistmonthview($equi_id)
			{
			try
				{
					$hiretbcmeeting = 0;
					$stmt = $this->db->prepare("SELECT * FROM job WHERE job_equi_id=:equi_id AND job_status in ('Active','Processed','Billed','Closed') AND job_cross_hire=:job_hiretbcmeeting AND job_tbc=:job_hiretbcmeeting AND job_meetings=:job_hiretbcmeeting ORDER BY 	job_timestamp ASC");
					$stmt->bindparam(":equi_id", $equi_id);
					$stmt->bindparam(":job_date", $date);
					$stmt->bindparam(":job_hiretbcmeeting", $hiretbcmeeting);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		
		public function getjobbycranetypelist($equi_id,$date)
			{
			try
				{
					$hiretbcmeeting = 0;
					$stmt = $this->db->prepare("SELECT * FROM job WHERE job_equi_id=:equi_id AND job_date=:job_date AND job_status in ('Active','Processed','Billed','Closed') AND job_cross_hire=:job_hiretbcmeeting AND job_tbc=:job_hiretbcmeeting AND job_meetings=:job_hiretbcmeeting ORDER BY 	job_timestamp ASC");
					$stmt->bindparam(":equi_id", $equi_id);
					$stmt->bindparam(":job_date", $date);
					$stmt->bindparam(":job_hiretbcmeeting", $hiretbcmeeting);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		public function getjobbytbclist($equi_id)
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM job WHERE job_equi_id=:equi_id AND job_status in ('Active','Processed','Billed','Closed') ORDER BY 	job_timestamp ASC");
					$stmt->bindparam(":equi_id", $equi_id);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		public function getjobbycustomerlist($cust_id)
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM job WHERE job_clie_id=:cust_id AND job_status in ('Active','Processed','Billed','Closed')");
					$stmt->bindparam(":cust_id", $cust_id);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
				}
			catch(PDOException $e)
				{	
					echo $e->getMessage();
				}
			}
		
		public function getjobbyid($job_id)
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM job WHERE id=:job_id LIMIT 1");
					$stmt->bindparam(":job_id", $job_id);
					$stmt->execute();
					$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		public function getjobtimeforcronjoblist()
			{
			try
				{
					$date = date('d/m/Y'); 
					$date = str_replace('/', '-', $date);
					$job_date = date('d/m/Y', strtotime($date .' +1 day')); 
					$job_tbc = 0;
					$ewp_billing = 0;
					$stmt = $this->db->prepare("SELECT * FROM job WHERE ewp_billing=:ewp_billing AND job_date=:job_date AND job_equi_id NOT IN ('7','9') AND job_tbc=:job_tbc AND job_status in ('Active')");
					$stmt->bindparam(":job_date", $job_date);
					$stmt->bindparam(":job_tbc", $job_tbc);
					$stmt->bindparam(":ewp_billing", $ewp_billing);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		public function getjoblists()
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM job WHERE job_status NOT IN ('Inactive') ORDER BY job_timestamp DESC");
					//$stmt->bindparam(":job_equi_id", $equiname);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		public function getjobbycranetypelist1($date)
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM job WHERE job_status in ('Active','Processed','Billed','Closed') ORDER BY 	job_timestamp ASC");
					//$stmt->bindparam(":equi_id", $equi_id);
					$stmt->bindparam(":job_date", $date);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		public function getjobbycranetypelist2($date)
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM job WHERE job_date=:job_date AND job_status in ('Active','Processed','Billed','Closed') ORDER BY 	job_timestamp ASC");
					//$stmt->bindparam(":equi_id", $equi_id);
					$stmt->bindparam(":job_date", $date);
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		public function getjobdeletelist()
			{
			try
				{
					$stmt = $this->db->prepare("SELECT * FROM job WHERE job_status in ('Inactive') ORDER BY job_timestamp DESC");
					$stmt->execute();
					$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
						if($stmt->rowCount() > 0)
							{
								return $userRow;
							} 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
		
		public function updatedcranesizes($job_id,$selectval,$cranesizes)
			{
			try
				{   
					$stmt = $this->db->prepare("UPDATE job SET job_cranetype=:selectval, job_cranetype_size=:cranesizes, change_eq_id=:selectval_ch, change_eq_size=:cranesizes_ch WHERE id=:job_id");
					$stmt->bindparam(":job_id", $job_id);
					$stmt->bindparam(":selectval", $selectval);
					$stmt->bindparam(":cranesizes", $cranesizes);
					$stmt->bindparam(":selectval_ch", $selectval);
					$stmt->bindparam(":cranesizes_ch", $cranesizes);
					
					$stmt->execute(); 
					
					//$noti_type = 'Changed the Crane type on job';
					$noti_status = 'Unseen';
					$createdatenoti= date("Y-m-d H:i:s"); 
					$noti_jobtimestamp = strtotime($createdatenoti);	
					
					$ext_mgs_edit='0'; 		   
					$date_msg = '1';
					$stmt = $this->db->prepare("UPDATE notifications SET noti_jobtimestamp=:noti_jobtimestamp, noti_type=:noti_type, noti_status=:noti_status, updated_date=:updated_date, job_crn_change=:date_msg, ext_mgs_edit=:ext_mgs_edit WHERE noti_job_id=:noti_job_id");
					$stmt->bindparam(":noti_job_id", $job_id);
					$stmt->bindparam(":date_msg", $date_msg);
					$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
					$stmt->bindparam(":noti_type", $noti_type);
					$stmt->bindparam(":noti_status", $noti_status);
					$stmt->bindparam(":updated_date", $createdatenoti);
					$stmt->bindparam(":ext_mgs_edit", $ext_mgs_edit);
					$stmt->execute();
				
					return $stmt; 
				}
			catch(PDOException $e)
				{
					echo $e->getMessage();
				}    
			}
		
		// for equipment value 
		
	public function equipmentdata_value($equi_id_value)
		{
		try
			{
				$stmt = $this->db->prepare("SELECT * FROM equipment WHERE equi_id=:equi_id LIMIT 1");
				$stmt->execute(array(':equi_id'=>$equi_id_value));
				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				
				return $userRow;
			
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		
		}
	public function getlistfgd()
		{
		try
			{
				$stmt = $this->db->prepare("SELECT * FROM equipment WHERE	equi_status NOT IN ('Inactive') ORDER by equi_order ASC");
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		} 
		
	public function getnotificationjodid($currentuserid,$jobid)
		{
		try
			{
				$stmt = $this->db->prepare("SELECT * FROM notifications WHERE noti_user_id=:noti_user_id AND noti_job_id=:jobid ORDER BY noti_jobtimestamp DESC");
				$stmt->bindparam(":noti_user_id", $currentuserid);
				$stmt->bindparam(":jobid", $jobid); 
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
			
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}
		
	public function tbcnotification($created_date,$tbc_notification)
		{
		try
			{
			
				$stmt = $this->db->prepare("SELECT * FROM job WHERE job_tbc=:tbc_notification AND job_timestamp > :next_date AND job_status in ('Active','Processed','Billed','Closed') ORDER BY 	job_timestamp ASC");
				$stmt->bindparam(":next_date", $created_date);
				$stmt->bindparam(":tbc_notification", $tbc_notification);
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}
	public function sessionvalue($jobids)
		{
		try
			{   
				$noti_status = 'Unseen';
				
				$stmt = $this->db->prepare("UPDATE notifications SET noti_status=:noti_status WHERE noti_job_id=:noti_job_id");
				$stmt->bindparam(":noti_job_id", $jobids);
				$stmt->bindparam(":noti_status", $noti_status);	
				$stmt->execute();
				
				
				return $stmt; 
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}    
		}
		
		
		
	public function getnotificationalltbcjob($currentuserid,$notystaus)
		{
		try
			{
				$stmt = $this->db->prepare("SELECT * FROM notifications WHERE noti_job_id=:currentuserid AND noti_user_id=:notystaus ORDER BY noti_jobtimestamp DESC");
				$stmt->bindparam(":currentuserid", $currentuserid); 
				$stmt->bindparam(":notystaus", $notystaus); 
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
			
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}
	public function accountspage($query)
		{
		try
			{
			
				$job_status_billed= 0;
				$ewp_billing = 0;
				$stmt = $this->db->prepare("SELECT * FROM job WHERE ewp_billing=:ewp_billing AND job_status_billed=:job_status_billed AND job_tbc=:job_status_billed AND job_meetings=:job_status_billed AND job_status in ('Active','Processed','Billed','Closed') $query ORDER BY job_timestamp DESC");
				$stmt->bindparam(":job_status_billed", $job_status_billed);
				$stmt->bindparam(":ewp_billing", $ewp_billing); 
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}
	public function sequencejobs($sequencejob)
		{
		try
			{
			
				$stmt = $this->db->prepare("SELECT * FROM job WHERE sequence_id=:sequencejob AND job_status in ('Active','Processed','Billed','Closed') $query ORDER BY job_timestamp DESC");
				$stmt->bindparam(":sequencejob", $sequencejob); 
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}
		
	public function jobattachemnt()
		{
		try
			{ 
			
				$asd = '#';
				$csearch = '%'.$asd.'%';
				$stmt = $this->db->prepare("SELECT * FROM job WHERE (job_secureattachment LIKE :job_attachments) AND job_status NOT IN ('Inactive')");
				
				$stmt->bindparam(":job_attachments", $csearch);
				
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}   	
		
	public function not_have_time_jobs()
		{
		try
			{ 
			
			  
				$stmt = $this->db->prepare("SELECT * FROM `job` WHERE job_tbc='0' and job_status in ('Active','Processed','Billed') ORDER BY job_timestamp DESC");
				// $stmt->bindparam(":job_timestamp", $not_timestamp);
				
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
				
				return $userRow;
			  
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}  
		
	public function operatordogmanjobsss($job_oper_id)
		{
		try
			{ 
			
			
				$job_oper_id1 = '%'.$job_oper_id.'%';
				$stmt = $this->db->prepare("SELECT * FROM job WHERE (job_oper_id LIKE :job_oper_id) ORDER BY job_timestamp DESC");
				$stmt->bindparam(":job_oper_id", $job_oper_id1);
				
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
				
				return $userRow;
			
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}  
		
	public function operatordogmanjobs($job_oper_id,$equi_id,$date)
		{
		try
			{ 
			 
			  $hiretbcmeeting = 0;
			  $job_oper_id1 = '%'.$job_oper_id.'%';
			  $stmt = $this->db->prepare("SELECT * FROM job WHERE job_equi_id=:equi_id AND job_date=:job_date AND job_status in ('Active','Processed','Billed','Closed') AND job_cross_hire=:job_hiretbcmeeting AND job_tbc=:job_hiretbcmeeting AND job_meetings=:job_hiretbcmeeting AND (job_oper_id LIKE :job_oper_id) ORDER BY job_timestamp DESC");
			  $stmt->bindparam(":job_oper_id", $job_oper_id1);
			  $stmt->bindparam(":equi_id", $equi_id);
			  $stmt->bindparam(":job_date", $date);
			  $stmt->bindparam(":job_hiretbcmeeting", $hiretbcmeeting);
			  $stmt->execute();
			  $userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
			  
				return $userRow;
			  
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		} 
		
	public function opretordogmanupdated($jobeditby,$job_id,$attachmenturl,$attachmentname)
		{	
		try
			{
			
				// notification job 
				$stmt = $this->db->prepare("SELECT * FROM job WHERE id=:job_id LIMIT 1");
				$stmt->execute(array(':job_id'=>$job_id));
				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				
				// end notification job 	
				if($job_client_id)
					{
						$job_clientid = $job_client_id;
					} 
				else if($cust_id)
					{
						$job_clientid = $cust_id;
					}	
				if($job_contact)
					{
						$jobcontact = $addcontact_id;
					} 
				else if($cont_id)
					{
						$jobcontact = $cont_id;
					} 
				else 
					{
						$jobcontact = '';
					}		   
				$result = count($attachmenturl);
				if(is_array($attachmenturl) && is_array($attachmentname))
					{
						for($i=0; $i < $result; $i++)
							{
								$attachmentdetail[] = array('imagepath' => $attachmenturl[$i], 'imagename' => $attachmentname[$i] ); 
							}
					} 
			
				 if($userRow['sequence_id']!= 0)
					{
					
						$sequence_id = $userRow['sequence_id'];
						$jobtimestamp = $job_date.' '. $job_time;
						$jobtimestamp = str_replace('/', '-', $jobtimestamp);
						$job_timestamp = strtotime($jobtimestamp);				  
						$imagepath = $attachmentdetail;
						$secureimagepath = $secureattachmentdetail;
						/*$job_status = $jobmarkbilled;*/
						$createdate= date("Y-m-d H:i:s"); 
						$job_imagepath = json_encode($imagepath);
						$stmt = $this->db->prepare("UPDATE job SET job_createdby=:jobeditby, job_timestamp=:job_timestamp, job_attachments=:job_imagepath, updated_date=:updated_date WHERE sequence_id=:sequence_id");
						$stmt->bindparam(":jobeditby", $jobeditby);
						$stmt->bindparam(":sequence_id", $sequence_id);
						$stmt->bindparam(":job_timestamp", $job_timestamp);
						$stmt->bindparam(":job_imagepath", $job_imagepath);
						$stmt->bindparam(":updated_date", $createdate);
						$stmt->execute(); 
						return $stmt; 
					
					}
			 else
					{
						$jobtimestamp = $job_date.' '. $job_time;
						$jobtimestamp = str_replace('/', '-', $jobtimestamp);
						$job_timestamp = strtotime($jobtimestamp);				  
						$imagepath = $attachmentdetail;
						
						$createdate= date("Y-m-d H:i:s"); 
						$job_imagepath = json_encode($imagepath);
						$stmt = $this->db->prepare("UPDATE job SET job_createdby=:jobeditby, job_timestamp=:job_timestamp, job_attachments=:job_imagepath, updated_date=:updated_date WHERE id=:job_id");
						$stmt->bindparam(":jobeditby", $jobeditby);
						$stmt->bindparam(":job_id", $job_id);
						$stmt->bindparam(":job_timestamp", $job_timestamp);
						$stmt->bindparam(":job_imagepath", $job_imagepath);
						$stmt->bindparam(":updated_date", $createdate);
						$stmt->execute(); 
						return $stmt; 
					}
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}    
		}
		
	public function getattachment()
		{
		try
			{
			
				$stmt = $this->db->prepare("SELECT job_attachments,id FROM job WHERE job_status in ('Active')");
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
							return $userRow;
						} 
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		} 
		
	public function sequancedate($createbyjobid,$job_client,$job_client_id,$addclient,$addcontact,$addcontact_id,$job_crane,$job_cranesize,$job_cross_hire,$job_date,$job_time,$job_address,$job_lift,$job_rporder,$job_dogman,$job_operator,$job_contact,$job_detail,$attachmenturl,$attachmentname,$job_securenotes,$secureattachmenturl,$secureattachmentname,$job_crosshire,$job_tbc,$job_meetings,$addcrane,$addcranesize,$job_auxequipment,$sequence_id,$sequence_idss)
		{
		try
			{
				if($sequence_id == 1)
					{
						$stmt = $this->db->prepare("DELETE FROM job WHERE sequence_id=:sequence_id");
						$stmt->bindparam(":sequence_id", $sequence_idss);
						$stmt->execute();
					}
				$jobtimestamp = $job_date.' '. $job_time;
				$jobtimestamp = str_replace('/', '-', $jobtimestamp);
				$job_timestamp = strtotime($jobtimestamp);	
				$stmt = $this->db->prepare("SELECT cust_name FROM customer");
				$stmt->execute();
				$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
				$addlientsss = array();
				foreach($userRow as $userRows)
					{
						$addlientsss[]= $userRows['cust_name'];
					}
				
				if(!in_array($addclient,$addlientsss))
					{
					
						if(!empty($addclient) && empty($job_client))
							{
							
								$cust_status = 'Active';
								$createdate= date("Y-m-d H:i:s"); 
								
								
								$stmt = $this->db->prepare("INSERT INTO customer(cust_name,cust_address,cust_status,created_date,updated_date)VALUES(:cust_name, :cust_address, :cust_status, :created_date, :updated_date)");
								
								$stmt->bindparam(":cust_name", $addclient);
								$stmt->bindparam(":cust_address", $job_address);
								$stmt->bindparam(":cust_status", $cust_status);
								$stmt->bindparam(":created_date", $createdate);
								$stmt->bindparam(":updated_date", $createdate);
								
								$stmt->execute();
								
								$cust_id = $this->db->lastInsertId();
								$_SESSION['customer_id'] = $cust_id;
							
								if(!empty($addcontact) && !empty($cust_id) )
									{
									
										$cont_name = $addcontact;
										$cont_status = 'Active';
										$createdate1= date("Y-m-d H:i:s"); 
										
										
										$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_status,created_date,updated_date)VALUES(:cust_id, :cont_name, :cont_status, :created_date, :updated_date)");
										
										$stmt->bindparam(":cust_id", $cust_id);
										$stmt->bindparam(":cont_name", $cont_name);
										$stmt->bindparam(":cont_status", $cont_status);
										$stmt->bindparam(":created_date", $createdate1);
										$stmt->bindparam(":updated_date", $createdate1);
										$stmt->execute(); 	
										
										$cont_id = $this->db->lastInsertId();
									
									}
							
							
							} 
					} 
				else if(empty($addclient) && !empty($job_client) && !empty($job_client_id))
					{
					
						if(!empty($addcontact) && !empty($job_client_id) )
							{
							
								$cont_name = $addcontact;
								$cont_status = 'Active';
								$createdate1= date("Y-m-d H:i:s"); 
								
								
								$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_status,created_date,updated_date)VALUES(:cust_id, :cont_name, :cont_status, :created_date, :updated_date)");
								
								$stmt->bindparam(":cust_id", $job_client_id);
								$stmt->bindparam(":cont_name", $cont_name);
								$stmt->bindparam(":cont_status", $cont_status);
								$stmt->bindparam(":created_date", $createdate1);
								$stmt->bindparam(":updated_date", $createdate1);
								$stmt->execute(); 	
								
								$cont_id = $this->db->lastInsertId();
							
							}
					
					}
				
				
				
				if($job_client_id)
					{
						$job_clientid = $job_client_id;
					} 
				else if($cust_id)
					{
						$job_clientid = $cust_id;
					}	
				else if($_SESSION['customer_id'])
					{
						$job_clientid = $_SESSION['customer_id'];
					}
				if($job_contact)
					{
						$jobcontact = $addcontact_id;
					} 
				else if($cont_id)
					{
						$jobcontact = $cont_id;
					} 
				else
					{
						$jobcontact = '';
					}		   
				
				
				
				$result = count($attachmenturl);
				
				if(is_array($attachmenturl) && is_array($attachmentname))
					{
						for($i=0; $i < $result; $i++)
						{
							$attachmentdetail[] = array('imagepath' => $attachmenturl[$i], 'imagename' => $attachmentname[$i] ); 
						}
					} 
				
				
				$secureresult = count($secureattachmenturl); 
				
				if(is_array($secureattachmenturl) && is_array($secureattachmentname))
					{
						for($j=0; $j < $secureresult; $j++)
							{
								$secureattachmentdetail[] = array('imagepath' => $secureattachmenturl[$j], 'imagename' => $secureattachmentname[$j] ); 
							}
					} 
				
				
				
				$imagepath = $attachmentdetail;
				$secureimagepath = $secureattachmentdetail;
				
				if($job_cranesize)
					{ 
						$jobcranesize = $job_cranesize; 
					} 
				else 
					{
						$jobcranesize = '';
					}
				
				$job_status = 'Active';
				$createdate= date("Y-m-d H:i:s"); 
				$job_imagepath = json_encode($imagepath);
				$job_secureimagepath = json_encode($secureimagepath);
				$job_dogman = json_encode($job_dogman);
				$job_operator = json_encode($job_operator);
				$job_auxequipmentas = json_encode($job_auxequipment);
				
				$sequence_ids = $_SESSION['sequencejob_id'];
				
				
				$stmt = $this->db->prepare("INSERT INTO job(job_createdby,job_clie_id,job_equi_id,job_equi_size,job_cross_hire_size,job_date,job_time,job_timestamp,job_address,job_lift,job_purchase,job_dogm_id,job_oper_id,job_cont_name,job_detail,job_attachments,job_securedetail,job_secureattachment,job_cross_hire,job_tbc,job_meetings,job_cranetype,job_cranetype_size,job_status,created_date,updated_date,job_auxequipment,change_eq_id,change_eq_size,sequence_id)VALUES(:job_createdby, :job_client, :job_crane, :job_equi_size, :job_cross_hire_size, :job_date, :job_time, :job_timestamp, :job_address, :job_lift, :job_purchase, :job_dogman, :job_operator, :job_contact, :job_detail, :job_imagepath, :job_securedetail, :job_secureattachment, :cross_hire, :tbc, :meetings, :job_addcrane, :job_addcranesize, :job_status, :created_date, :updated_date, :job_auxequipment, :job_crane1, :job_equi_size1, :sequence_id)");
				
				$stmt->bindparam(":job_createdby", $createbyjobid);
				$stmt->bindparam(":job_client", $job_clientid);
				$stmt->bindparam(":job_crane", $job_crane);
				$stmt->bindparam(":job_equi_size", $jobcranesize);
				$stmt->bindparam(":job_crane1", $job_crane);
				$stmt->bindparam(":job_equi_size1", $jobcranesize);
				$stmt->bindparam(":job_cross_hire_size", $job_cross_hire);
				$stmt->bindparam(":job_date", $job_date);
				$stmt->bindparam(":job_time", $job_time);
				$stmt->bindparam(":job_timestamp", $job_timestamp);
				$stmt->bindparam(":job_address", $job_address);
				$stmt->bindparam(":job_lift", $job_lift);
				$stmt->bindparam(":job_purchase", $job_rporder);
				$stmt->bindparam(":job_dogman", $job_dogman);
				$stmt->bindparam(":job_operator", $job_operator);
				$stmt->bindparam(":job_contact", $jobcontact);
				$stmt->bindparam(":job_detail", $job_detail);
				$stmt->bindparam(":job_imagepath", $job_imagepath);
				$stmt->bindparam(":job_securedetail", $job_securenotes);
				$stmt->bindparam(":job_secureattachment", $job_secureimagepath);
				$stmt->bindparam(":job_status", $job_status);
				$stmt->bindparam(":created_date", $createdate);
				$stmt->bindparam(":updated_date", $createdate);
				$stmt->bindparam(":cross_hire", $job_crosshire);
				$stmt->bindparam(":tbc", $job_tbc);
				$stmt->bindparam(":meetings", $job_meetings);
				$stmt->bindparam(":job_addcrane", $addcrane);
				$stmt->bindparam(":job_addcranesize", $addcranesize);
				$stmt->bindparam(":job_auxequipment", $job_auxequipmentas);
				$stmt->bindparam(":sequence_id", $sequence_idss);
				$stmt->execute(); 
				
				$job_id = $this->db->lastInsertId();
				
				
				if(empty($job_tbc) )
					{
					
						if($sequence_id == 1)
							{
							
								$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
								$stmt->execute();
								$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
								
									foreach($userfields as $userfield)
										{
										
											$userid = $userfield['user_id'];
											
											$noti_type = 'Added a new job';
											$noti_status = 'Unseen';
											
											$createdatenoti= date("Y-m-d H:i:s"); 
											$noti_jobtimestamp = strtotime($createdatenoti);	
											
											
											$stmt = $this->db->prepare("INSERT INTO notifications(noti_user_id,noti_job_id,noti_jobtimestamp,noti_type,noti_status,created_date,updated_date,sequence_id)VALUES(:noti_user_id, :noti_job_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :sequence_id)");
											$stmt->bindparam(":noti_user_id", $userid);  
											$stmt->bindparam(":noti_job_id", $job_id);
											$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
											$stmt->bindparam(":noti_type", $noti_type);
											$stmt->bindparam(":noti_status", $noti_status);
											$stmt->bindparam(":created_date", $createdatenoti);
											$stmt->bindparam(":updated_date", $createdatenoti);
											$stmt->bindparam(":sequence_id", $sequence_id);
											$stmt->execute(); 	
										}
								
							}
					
					
					}
				if($job_tbc == '1')
					{
					
						$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
						$stmt->execute();
						$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
						
							foreach($userfields as $userfield)
								{
									$userid = $userfield['user_id'];
									
									$noti_type = 'Added a new job';
									$noti_status = 'Unseen';
									
									$createdatenoti= date("Y-m-d H:i:s"); 
									$noti_jobtimestamp = strtotime($createdatenoti);	
									
									
									$stmt = $this->db->prepare("INSERT INTO notifications(noti_user_id,noti_job_id,noti_jobtimestamp,noti_type,noti_status,created_date,updated_date,tbc_notification)VALUES(:noti_user_id, :noti_job_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :job_tbc)");
									$stmt->bindparam(":noti_user_id", $userid);  
									$stmt->bindparam(":noti_job_id", $job_id);
									$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
									$stmt->bindparam(":noti_type", $noti_type);
									$stmt->bindparam(":noti_status", $noti_status);
									$stmt->bindparam(":created_date", $createdatenoti);
									$stmt->bindparam(":updated_date", $createdatenoti);
									$stmt->bindparam(":job_tbc", $job_tbc);
									$stmt->execute(); 
								}
					}
				
				return $stmt; 
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}    
		} 
		public function getjobbydatelist($date)
	{
		try
		{
			
			$hiretbcmeeting = 0;
			$stmt = $this->db->prepare("SELECT * FROM job WHERE job_date=:job_date AND job_status in ('Active','Processed','Billed','Closed') AND job_cross_hire=:job_hiretbcmeeting AND job_tbc=:job_hiretbcmeeting AND job_meetings=:job_hiretbcmeeting ORDER BY job_timestamp ASC");
			//$stmt->bindparam(":equi_id", $equi_id);
			$stmt->bindparam(":job_date", $date);
			$stmt->bindparam(":job_hiretbcmeeting", $hiretbcmeeting);
			$stmt->execute();
			$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0)
			{
			return $userRow;
			} 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}    	
}
	
	?>