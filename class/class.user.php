<?php
class USER
{
   private $db;
 
   function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
 public function register($fname,$lname,$uname,$umail,$upass,$uphone,$utype,$userattachmenturl,$userattachmentname,$cust_contactlist,$em_contact)
	{
	try
		{
			$result = count($userattachmenturl);
			if(is_array($userattachmenturl) && is_array($userattachmentname))
				{
				for($i=0; $i < $result; $i++)
					{
						$userattachmentdetail[] = array('imagepath' => $userattachmenturl[$i], 'imagename' => $userattachmentname[$i] ); 
					}
				} 
			$indexcount = count($cust_contactlist['name']);
			for($i=0;$i<$indexcount;$i++)
				{
					$contactlists[] = array( 'name' => $cust_contactlist['name'][$i], 'date' => $cust_contactlist['date'][$i], 'imagepath' => $userattachmenturl[$i], 'imagename' => $userattachmenturl[$i]);   
				}
			$imagepaths = $userattachmentdetail;
			$job_imagepaths = json_encode($imagepaths);
			$createdate= date("Y-m-d H:i:s"); 
			$utyp ='';
			foreach($utype as $utypestring)
				{
					$stmt = $this->db->prepare("SELECT * FROM roles WHERE role_id=:role_id LIMIT 1");
					$stmt->execute(array(':role_id'=>$utypestring));
					$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
					$utyp = $userRow['role_name'].','. $utyp;
				}
			$user_role = json_encode($utype);
			$new_password = password_hash($upass, PASSWORD_DEFAULT);
			if(is_array($em_contact))
				{
				for($i=0; $i < count($em_contact); $i++)
					{
						$em_contact_name[] = array('name' => $em_contact[name][$i]);
						$em_contact_phone[] = array('phone' => $em_contact[phone][$i]);
					}
				} 
			$em_contact_name = json_encode($em_contact_name);
			$em_contact_phone = json_encode($em_contact_phone);
			$stmt = $this->db->prepare("INSERT INTO users(user_name,user_email,user_pass,first_name,last_name,user_phone,type,created_date,user_role,emerengecy_contact,emerengecy_name)VALUES(:uname, :umail, :upass, :fname, :lname, :uphone, :utype, :createdate, :user_role, :emerengecy_contact, :emerengecy_name)");
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":upass", $new_password);
			$stmt->bindparam(":fname", $fname);
			$stmt->bindparam(":lname", $lname);
			$stmt->bindparam(":uphone", $uphone);
			$stmt->bindparam(":utype", $utyp);
			$stmt->bindparam(":createdate", $createdate);
			$stmt->bindparam(":user_role", $user_role);
			$stmt->bindparam(":emerengecy_contact", $em_contact_phone);
			$stmt->bindparam(":emerengecy_name", $em_contact_name);
			$stmt->execute(); 
			$user_id = $this->db->lastInsertId();
			if($user_id )
				{
				foreach($contactlists as $contactlist)
					{
						if($contactlist['name']!='')
							{
								$cont_name = $contactlist['name'];
								$cont_date = $contactlist['date'];
								$imagepaths = $contactlist['imagepath'];
								$imagenames = $contactlist['imagename'];
								$user_status = 'Active';
								$stmt = $this->db->prepare("INSERT INTO users_certificate(user_id,user_name,expire_date,attachment,licenses_name,user_status)VALUES(:user_id, :cont_name, :cont_date, :imagepaths, :licenses_name, :user_status)");
								$stmt->bindparam(":user_id", $user_id);  
								$stmt->bindparam(":cont_name", $uname);
								$stmt->bindparam(":cont_date", $cont_date);
								$stmt->bindparam(":licenses_name", $cont_name);
								$stmt->bindparam(":imagepaths", $imagepaths);
								$stmt->bindparam(":user_status", $user_status);
								$stmt->execute();
								 	
								$license_id = $this->db->lastInsertId();
							   $stmt = $this->db->prepare("INSERT INTO  users_certificate_date_history(user_id,user_name,expire_date,licenses_name,license_id)VALUES(:user_id, :cont_name, :cont_date, :licenses_name, :license_id)");
							   
							   $stmt->bindparam(":user_id", $user_id);  
							   $stmt->bindparam(":cont_name", $uname);
							   $stmt->bindparam(":cont_date", $cont_date);
							   $stmt->bindparam(":licenses_name", $cont_name);
							   $stmt->bindparam(":license_id", $license_id);
							   $stmt->execute();
							}
					}
				}
		return $stmt; 
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}    
	}
	
public function updated($rows_ids,$uid,$fname,$lname,$uname,$umail,$uphone,$status,$utype,$usersattachmenturl,$usersattachmentname,$cust_contactlist,$em_contact,$picedit_id)
    {
       try
		{
			$updatedate = date("Y-m-d H:i:s"); 
			$utyp ='';
			foreach($utype as $utypestring)
				{
					$stmt = $this->db->prepare("SELECT * FROM roles WHERE role_id=:role_id LIMIT 1");
					$stmt->execute(array(':role_id'=>$utypestring));
					$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
					$utyp = $userRow['role_name'].','. $utyp;
				}
			$user_role = json_encode($utype);
			$indexcount = count($cust_contactlist['name']);
			for($ii=0;$ii<$indexcount;$ii++)
				{
					$imagename = $cust_contactlist[$rows_ids[$ii]][1]; 
					$imagepath = $cust_contactlist[$rows_ids[$ii]][0];
					$contactlists[] = array( 
					'eid' => $rows_ids[$ii], 
					'name' => $cust_contactlist['name'][$ii],
					'date' => $cust_contactlist['date'][$ii], 
					'imagepath' =>$imagepath,
					'imagename' => $imagename);
				}
			$result = count($usersattachmenturl);
			if(is_array($usersattachmenturl) && is_array($usersattachmentname))
				{
				for($i=0; $i < $result; $i++)
					{
						$userattachmentdetails[] = array('imagepath' => $usersattachmenturl[$i], 'imagename' => $usersattachmentname[$i] ); 
					}
				} 
			if(is_array($em_contact))
				{
				for($i=0; $i < count($em_contact); $i++)
					{
						$em_contact_name[] = array('name' => $em_contact[name][$i]);
						$em_contact_phone[] = array('phone' => $em_contact[phone][$i]);
					}
				} 
			$em_contact_name = json_encode($em_contact_name);
			$em_contact_phone = json_encode($em_contact_phone);
			$userattachment = $userattachmentdetails;
			$user_imagepaths = json_encode($userattachment);
			$stmt = $this->db->prepare("UPDATE users SET user_id=:uid, user_name=:uname, user_email=:umail, first_name=:fname, last_name=:lname, user_phone=:uphone, status=:status, type=:utype, updated_date=:updatedate, user_role=:user_role, emerengecy_contact=:emerengecy_contact, emerengecy_name=:emerengecy_name WHERE user_id=:uid");
			$stmt->bindparam(":uid", $uid);	  
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":fname", $fname);
			$stmt->bindparam(":lname", $lname);
			$stmt->bindparam(":uphone", $uphone);
			$stmt->bindparam(":status", $status);
			$stmt->bindparam(":utype", $utyp);
			$stmt->bindparam(":updatedate", $updatedate);
			$stmt->bindparam(":user_role", $user_role);
			$stmt->bindparam(":emerengecy_contact", $em_contact_phone);
			$stmt->bindparam(":emerengecy_name", $em_contact_name);
			$stmt->execute(); 
			if($uid )
				{
					$stmt = $this->db->prepare("SELECT id FROM users_certificate WHERE user_id=:user_id");
					$stmt->bindparam(":user_id", $uid);
					$stmt->execute();
					$rowidss = $stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach($rowidss as $rowidss1)
						{ 
							$idsss[] = $rowidss1['id'];
						}  
					foreach($contactlists as $contactlist)
						{
							$cont_name = $contactlist['name'];
							$cont_date = $contactlist['date'];
							$attachimagepath = $contactlist['imagepath'];
							$attachimagename = $contactlist['imagename'];
							$ids = $contactlist['eid'];
							
							if (in_array($ids, $idsss) && $cont_name!='')
								{
									$stmt = $this->db->prepare("UPDATE users_certificate SET user_id=:uid, user_name=:uname, expire_date=:expire_date, attachment=:attachment, licenses_name=:licenses_name WHERE id=:id");
									
									$stmt->bindparam(":uid", $uid);  
									$stmt->bindparam(":uname", $uname);
									$stmt->bindparam(":expire_date", $cont_date);
									$stmt->bindparam(":attachment", $attachimagepath);
									$stmt->bindparam(":licenses_name", $cont_name);
									$stmt->bindparam(":id", $ids);
									$stmt->execute(); 	
									
									$stmt = $this->db->prepare("SELECT expire_date FROM users_certificate_date_history WHERE license_id=:license_id");
									   $stmt->bindparam(":license_id", $ids);
									   $stmt->execute();
									   $rowdates = $stmt->fetchAll(PDO::FETCH_ASSOC);
									   foreach($rowdates as $rowdates1)
										{ 
											$dates[] = $rowdates1['expire_date'];
										}
								   if(!empty($contactlist['date'])){
									if (!in_array($cont_date, $dates))
									{
								   $stmt = $this->db->prepare("INSERT INTO  users_certificate_date_history(user_id,user_name,expire_date,licenses_name,license_id)VALUES(:user_id, :cont_name, :cont_date, :licenses_name, :license_id)");
										   $stmt->bindparam(":user_id", $uid);  
										   $stmt->bindparam(":cont_name", $uname);
										   $stmt->bindparam(":cont_date", $cont_date);
										   $stmt->bindparam(":licenses_name", $cont_name);
										   $stmt->bindparam(":license_id", $ids);
										   $stmt->execute();
								   }
								   }	
								}
							else
								{
								if($cont_name!='')
									{
										$stmt = $this->db->prepare("INSERT INTO users_certificate(user_id,user_name,expire_date,attachment,licenses_name)VALUES(:uid, :uname, :expire_date, :attachment, :licenses_name)");
										
										$stmt->bindparam(":uid", $uid);  
										$stmt->bindparam(":uname", $uname);
										$stmt->bindparam(":expire_date", $cont_date);
										$stmt->bindparam(":attachment", $attachimagepath);
										$stmt->bindparam(":licenses_name", $cont_name);
										$stmt->execute(); 
									}
								}
						
						}
				}
		
		
		return $stmt; 
		}
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
	
	public function updatedpassword($uid,$upass)
    {
       try
       {
		   $updatedate = date("Y-m-d H:i:s"); 
		   $new_password = password_hash($upass, PASSWORD_DEFAULT); 
           $stmt = $this->db->prepare("UPDATE users SET user_pass=:upass, updated_date=:updatedate WHERE user_id=:uid");
		   $stmt->bindparam(":uid", $uid);	  
           $stmt->bindparam(":upass", $new_password);
		   $stmt->bindparam(":updatedate", $updatedate);
           $stmt->execute(); 
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
	
	public function deleted($uid)
    {
       try
       {
		   $status = 'Inactive';
		   $stmt = $this->db->prepare("UPDATE users SET status=:status WHERE user_id=:uid");
		   $stmt->bindparam(":uid", $uid);	
		   $stmt->bindparam(":status", $status);  
           $stmt->execute(); 
		   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
	
	
 
   public function login($uname,$umail,$upass)
    {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM users WHERE ( user_name=:uname OR user_email=:umail )");
          $stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
             if(password_verify($upass, $userRow['user_pass'])) 
             {
                $_SESSION['user_session'] = $userRow['user_id'];
				$_SESSION['user_type'] = $userRow['type'];
				$_SESSION['user_role'] = json_decode($userRow['user_role']);
                return true;
				
             }
             else
             {
				 return false;
             }
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
   public function is_loggedin()
   {
      if(isset($_SESSION['user_session']))
      {
         return true;
      }
   }
 
   public function redirect($url)
   {
       header("Location: $url");
   }
   
   public function loginuserdata($usersession)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id=:usersession LIMIT 1");
          $stmt->execute(array(':usersession'=>$usersession));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

		  return $userRow;
          
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
   public function logout()
   {
        session_destroy();
        unset($_SESSION['user_session']);
		unset($_SESSION['user_type']);
		unset($_SESSION['user_role']);
        return true;
   }
   
    public function getuserslist()
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM users WHERE	status NOT IN ('Inactive')");
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
   
   public function getuserslistcrti()
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM users_certificate");
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
   
    public function gettypeuserslist($type)
   {
        try
		{
		  $type = '%'.$type.'%';
          $stmt = $this->db->prepare("SELECT * FROM users WHERE type LIKE :type AND status NOT IN ('Inactive')");
		  $stmt->bindparam(":type", $type); 
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
public function usernotification($userid,$userid_expire)
	{ 

	try 
		{
			$stmt = $this->db->prepare("SELECT * FROM equipment_notification");
			$stmt->execute();
			$userRow=$stmt->fetchAll(PDO::FETCH_ASSOC);
			$noti_status = array();
			foreach($userRow as $userRows)
				{
					$noti_status[]= array('equi_noti_type'=>$userRows['equi_noti_type'],'userid_expire'=>$userRows['userid_expire']);
				}
			foreach($noti_status as $noti_statuss)
				{
				if($noti_statuss['userid_expire'] == $userid_expire)
					{
					$notityupes = array();
					$notityupes[] = $noti_statuss['equi_noti_type'];
					}
				}
			
			if(is_array($notityupes))
				{
					$noti_status = 'Unseen';
					$stmt = $this->db->prepare("UPDATE equipment_notification SET equi_noti_status=:noti_status WHERE userid_expire=:userid_expire");
					$stmt->bindparam(":noti_status", $noti_status);
					$stmt->bindparam(":userid_expire", $userid_expire);	
					$stmt->execute(); 
					return $stmt;
				}
			else
				{
					$stmt = $this->db->prepare("SELECT * FROM users WHERE type in ('SuperAdmin','Admin','General','Dispatch') AND	status NOT IN ('Inactive')");
					$stmt->execute();
					$userfields=$stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach($userfields as $userfield)
						{
							$noti_status = 'Unseen';
							$userid = $userfield['user_id'];
							$createdatenoti= date("Y-m-d H:i:s"); 
							$noti_jobtimestamp = strtotime($createdatenoti);	
							$expiry_date = 'Expiry date';
							$stmt = $this->db->prepare("INSERT INTO equipment_notification(	equi_noti_user_id,equi_noti_jobtimestamp,equi_noti_type,equi_noti_status,created_date,updated_date,userid_expire)VALUES(:noti_user_id, :noti_jobtimestamp, :noti_type, :noti_status, :created_date, :updated_date, :userid_expire)");
							$stmt->bindparam(":noti_user_id", $userid);  
							$stmt->bindparam(":userid_expire", $userid_expire);
							$stmt->bindparam(":noti_jobtimestamp", $noti_jobtimestamp);	
							$stmt->bindparam(":noti_type", $expiry_date);
							$stmt->bindparam(":noti_status", $noti_status);
							$stmt->bindparam(":created_date", $createdatenoti);
							$stmt->bindparam(":updated_date", $createdatenoti);
							$stmt->execute();
						}
					return $stmt; 
				
				}
		}
	
	catch(PDOException $e)
	{
	echo $e->getMessage();
	}  
}

 public function loginusercertificatedata($usersession)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM users_certificate WHERE user_id=:usersession LIMIT 1");
          $stmt->execute(array(':usersession'=>$usersession));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

		  return $userRow;
          
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
   
   public function getloginusercertificatedata($usersession)
       {
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM users_certificate WHERE user_id=:user_id AND user_status NOT IN ('Inactive')");
			  $stmt->bindparam(":user_id", $usersession);
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


public function deleteuserattachment($deleteuserattachment_id)
	{
	try
		{
			$user_status = 'Inactive';
			$stmt = $this->db->prepare("UPDATE users_certificate SET user_status=:user_status WHERE id=:id");
			$stmt->bindparam(":id", $deleteuserattachment_id);
			$stmt->bindparam(":user_status", $user_status);
			$stmt->execute();
			return $stmt;
		}
	catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function userexpirehistory($license_id)
	{
		try
		{
		 
	   $stmt = $this->db->prepare("SELECT * FROM users_certificate_date_history WHERE license_id=:license_id ");
		  $stmt->bindparam(":license_id", $license_id);
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