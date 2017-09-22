<?php
class Customer
{
   private $db;
 
   function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
   public function register($cust_name,$cust_email,$cust_address,$cust_phone,$cust_contactlist,$cust_payment_term,$cust_rporder,$cust_flaggedpayer,$customerattachmenturl,$customerattachmentname)
	{
	try
		{
			$indexcount = count($cust_contactlist['name']);
			for($i=0;$i<$indexcount;$i++)
				{
					$contactlists[] = array( 'name' => $cust_contactlist['name'][$i], 'email' => $cust_contactlist['email'][$i], 'phone' => $cust_contactlist['phone'][$i]);
				}
			$cust_contactlist = json_encode($cust_contactlist);
			$cust_status = 'Active';
			$createdate= date("Y-m-d H:i:s"); 
			$result = count($customerattachmenturl);
			if(is_array($customerattachmenturl) && is_array($customerattachmentname))
				{
				for($i=0; $i < $result; $i++)
					{
						$customerattachmentdetail[] = array('imagepath' => $customerattachmenturl[$i], 'imagename' => $customerattachmentname[$i] ); 
					}
				} 
			$customerimagepaths = $customerattachmentdetail;
			$job_customerimagepaths = json_encode($customerimagepaths);
			$stmt = $this->db->prepare("INSERT INTO customer(cust_name,cust_email,cust_address,cust_phone,cust_payment_term,cust_rporder,cust_flaggedpayer,	cust_status,created_date,updated_date,attachment)VALUES(:cust_name, :cust_email, :cust_address, :cust_phone, :cust_payment_term, :cust_rporder, :cust_flaggedpayer, :cust_status, :created_date, :updated_date, :attachment)");
			$stmt->bindparam(":cust_name", $cust_name);
			$stmt->bindparam(":cust_email", $cust_email);
			$stmt->bindparam(":cust_address", $cust_address);
			$stmt->bindparam(":cust_phone", $cust_phone);
			$stmt->bindparam(":cust_payment_term", $cust_payment_term);
			$stmt->bindparam(":cust_rporder", $cust_rporder);
			$stmt->bindparam(":cust_flaggedpayer", $cust_flaggedpayer);
			$stmt->bindparam(":cust_status", $cust_status);
			$stmt->bindparam(":created_date", $createdate);
			$stmt->bindparam(":updated_date", $createdate);
			$stmt->bindparam(":attachment", $job_customerimagepaths);
			$stmt->execute();
			$cust_id = $this->db->lastInsertId();
			if(is_array($contactlists) && $cust_id )
				{
				foreach($contactlists as $contactlist)
					{
					$cont_name = $contactlist['name'];
					$cont_email = $contactlist['email'];
					$cont_phone = $contactlist['phone'];
					$md5_email = md5($contactlist['email']);
					$subscribe = 'Subscribe';
					$cont_status = 'Active';
					$createdate1= date("Y-m-d H:i:s"); 
					$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_email,cont_phone,cont_status,created_date,updated_date,md5_email,subscribe)VALUES(:cust_id, :cont_name, :cont_email, :cont_phone, :cont_status, :created_date, :updated_date, :md5_email, :subscribe)");
					$stmt->bindparam(":cust_id", $cust_id);
					$stmt->bindparam(":cont_name", $cont_name);
					$stmt->bindparam(":cont_email", $cont_email);
					$stmt->bindparam(":cont_phone", $cont_phone);
					$stmt->bindparam(":cont_status", $cont_status);
					$stmt->bindparam(":created_date", $createdate1);
					$stmt->bindparam(":updated_date", $createdate1);
					$stmt->bindparam(":md5_email", $md5_email);
					$stmt->bindparam(":subscribe", $subscribe);
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
	
	public function updated($cust_id,$cust_name,$cust_email,$cust_address,$cust_phone,$cust_contactlist,$cust_contactlistedit,$cust_contactlistdelete,$cust_payment_term,$cust_rporder,$cust_flaggedpayer,$cust_status,$customerattachmenturl,$customerattachmentname)
	{
	try
		{ 
		
			$deletcontacts = $cust_contactlistdelete['cont_id'];
			$indexcountedit = count($cust_contactlistedit['name']);
			for($i=0;$i<$indexcountedit;$i++)
				{ 
					$contactlistedits[] = array( 'cont_id' => $cust_contactlistedit['cont_id'][$i], 'name' => $cust_contactlistedit['name'][$i], 'email' => $cust_contactlistedit['email'][$i], 'phone' => $cust_contactlistedit['phone'][$i]);
				}
			$indexcount = count($cust_contactlist['name']);
			for($i=0;$i<$indexcount;$i++)
				{
					$contactlists[] = array( 'cont_id' => $cust_contactlist['cont_id'][$i], 'name' => $cust_contactlist['name'][$i], 'email' => $cust_contactlist['email'][$i], 'phone' => $cust_contactlist['phone'][$i]);
				}	   
			if($contactlists && $contactlistedits)
				{
					$editcontactlists = array_merge($contactlistedits, $contactlists);
				}
			else if(!$contactlists && $contactlistedits)
				{
					$editcontactlists = $contactlistedits;
				} 
			else
				{
					$editcontactlists = $contactlists;
				}
			$updated_date = date("Y-m-d H:i:s"); 
			$cust_contactlist = json_encode($cust_contactlist);
			$result = count($customerattachmenturl);
			if(is_array($customerattachmenturl) && is_array($customerattachmentname))
				{
				for($i=0; $i < $result; $i++)
					{
						$customerattachmentdetail[] = array('imagepath' => $customerattachmenturl[$i], 'imagename' => $customerattachmentname[$i] ); 
					}
				} 
			$customerimagepaths = $customerattachmentdetail;
			$job_customerimagepaths = json_encode($customerimagepaths);
			$stmt = $this->db->prepare("UPDATE customer SET cust_name=:cust_name, cust_email=:cust_email, cust_address=:cust_address, cust_phone=:cust_phone,  cust_payment_term=:cust_payment_term, cust_rporder=:cust_rporder, cust_flaggedpayer=:cust_flaggedpayer, cust_status=:cust_status, updated_date=:updated_date, attachment=:attachment WHERE cust_id=:cust_id");
			$stmt->bindparam(":cust_id", $cust_id);
			$stmt->bindparam(":cust_name", $cust_name);
			$stmt->bindparam(":cust_email", $cust_email);
			$stmt->bindparam(":cust_address", $cust_address);
			$stmt->bindparam(":cust_phone", $cust_phone);
			$stmt->bindparam(":cust_payment_term", $cust_payment_term);
			$stmt->bindparam(":cust_rporder", $cust_rporder);
			$stmt->bindparam(":cust_flaggedpayer", $cust_flaggedpayer);
			$stmt->bindparam(":cust_status", $cust_status);
			$stmt->bindparam(":updated_date", $updated_date);
			$stmt->bindparam(":attachment", $job_customerimagepaths);
			$stmt->execute(); 
			if(is_array($deletcontacts))
				{
				foreach($deletcontacts as $deletcontact)
					{
						$cont_status = 'Inactive';
						$stmt = $this->db->prepare("UPDATE contacts SET cont_status=:cont_status WHERE cont_id=:cont_id");
						$stmt->bindparam(":cont_id", $deletcontact);
						$stmt->bindparam(":cont_status", $cont_status);	  
						$stmt->execute(); 
					}
				}
			if(is_array($editcontactlists) && $cust_id )
				{
					$stmt = $this->db->prepare("SELECT * FROM contacts WHERE cust_id = :cust_id AND cont_status NOT IN ('Inactive')");
					$stmt->bindparam(":cust_id", $cust_id);
					$stmt->execute();
					$userRows=$stmt->fetchAll(PDO::FETCH_ASSOC);
					if($stmt->rowCount() > 0)
						{
						foreach($userRows as $userRows)
							{
								$contids[] = $userRows['cont_id'];
							}
						} 
					foreach($editcontactlists as $contactlist)
						{
							if(!empty($contactlist['cont_id']) && in_array($contactlist['cont_id'], $contids))
								{
									$cont_idu = $contactlist['cont_id'];	
									$cont_name = $contactlist['name'];
									$cont_email = $contactlist['email'];
									$cont_phone = $contactlist['phone'];
									$md5_email = md5($contactlist['email']);
									$createdate1= date("Y-m-d H:i:s"); 
									$stmt = $this->db->prepare("UPDATE contacts SET cont_name=:cont_name,cont_email=:cont_email,cont_phone=:cont_phone,updated_date=:updated_date,md5_email=:md5_email WHERE cont_id=:cont_id");
									$stmt->bindparam(":cont_id", $cont_idu);
									$stmt->bindparam(":cont_name", $cont_name);
									$stmt->bindparam(":cont_email", $cont_email);
									$stmt->bindparam(":cont_phone", $cont_phone);
									$stmt->bindparam(":updated_date", $createdate1);
									$stmt->bindparam(":md5_email", $md5_email);
									$stmt->execute(); 
								} 
							else 
								{
								if($contactlist['name'])
									{
										$cont_name = $contactlist['name'];
										$cont_email = $contactlist['email'];
										$cont_phone = $contactlist['phone'];
										$md5_email = md5($contactlist['email']);
										$subscribe = 'Subscribe';
										$cont_status = 'Active';
										$createdate1= date("Y-m-d H:i:s"); 
										$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_email,cont_phone,cont_status,created_date,updated_date,md5_email,subscribe)VALUES(:cust_id, :cont_name, :cont_email, :cont_phone, :cont_status, :created_date, :updated_date, :md5_email, :subscribe)");
										$stmt->bindparam(":cust_id", $cust_id);  
										$stmt->bindparam(":cont_name", $cont_name);
										$stmt->bindparam(":cont_email", $cont_email);
										$stmt->bindparam(":cont_phone", $cont_phone);
										$stmt->bindparam(":cont_status", $cont_status);
										$stmt->bindparam(":created_date", $createdate1);
										$stmt->bindparam(":updated_date", $createdate1);
										$stmt->bindparam(":md5_email", $md5_email);
										$stmt->bindparam(":subscribe", $subscribe);
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
	
public function deleted($cust_id)
    {
       try
       {   $cust_status = 'Inactive';
           $stmt = $this->db->prepare("UPDATE customer SET cust_status=:cust_status WHERE cust_id=:cust_id");
		   $stmt->bindparam(":cust_id", $cust_id);
		   $stmt->bindparam(":cust_status", $cust_status);	  
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
          $stmt = $this->db->prepare("SELECT * FROM customer WHERE ( user_name=:uname OR user_email=:umail ) AND type='Admin' LIMIT 1");
          $stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
             if(password_verify($upass, $userRow['user_pass'])) 
             {
                $_SESSION['user_session'] = $userRow['user_id'];
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
 

  public function redirect($url)
   {
       header("Location: $url");
   }
   
  public function customerdata($custid)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM customer WHERE cust_id=:custid LIMIT 1");
          $stmt->execute(array(':custid'=>$custid));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

		  return $userRow;
          
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
  public function contactdata($custid)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM contacts WHERE cont_id=:custid LIMIT 1");
          $stmt->execute(array(':custid'=>$custid));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

		  return $userRow;
          
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
   
  public function getcustomerslist()
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM customer WHERE cust_status NOT IN ('Inactive')");
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
   
  public function getcustomerscontactlist($cust_id)
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM contacts WHERE cust_id = :cust_id AND cont_status NOT IN ('Inactive') AND subscribe NOT IN ('Unsubscribe')");
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
   
 public function gecontactdata($cont_id)
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM contacts WHERE cont_id = :cont_id AND cont_status NOT IN ('Inactive') LIMIT 1");
		  $stmt->execute(array(':cont_id'=>$cont_id));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		  return $userRow;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
   }
   
 public function getcustomerscontactnamewiselist($search,$clientid)
   {
        try
		{
          $csearch = '%'.$search.'%';
		  $stmt = $this->db->prepare("SELECT * FROM contacts WHERE ( cont_name LIKE :cont_name AND cust_id = :cust_id ) AND cont_status NOT IN ('Inactive')");
		  $stmt->bindValue(':cust_id', $clientid, PDO::PARAM_INT);
		  $stmt->bindValue(':cont_name', $csearch, PDO::PARAM_STR);
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
   
   
 public function getcustomersnamewiselist($search)
   {
        try
		{
          $csearch = '%'.$search.'%';
		  $stmt = $this->db->prepare("SELECT * FROM customer WHERE cust_name LIKE :cust_name AND cust_status NOT IN ('Inactive')");
		  $stmt->bindparam(":cust_name", $csearch);
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
   
      
   
 public function searchcustomerslist($csearch)
   {
        try
		{
		 $csearch = '%'.$csearch.'%';
        $stmt = $this->db->prepare("select * from customer s  inner join contacts m on s.cust_id = m.cust_id WHERE ( cust_name LIKE :cust_name OR cust_address LIKE :cust_address OR cust_phone LIKE :cust_phone OR cust_email LIKE :cust_email OR cont_name LIKE :cont_name) AND cust_status NOT IN ('Inactive')");

		 $stmt->bindparam(":cust_name", $csearch);
		 $stmt->bindparam(":cust_address", $csearch);	
		 $stmt->bindparam(":cust_phone", $csearch);
		 $stmt->bindparam(":cust_email", $csearch); 
		 $stmt->bindparam(":cont_name", $csearch);
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
   
  public function searchjobslist($csearch)
   {
        try
		{
			$csearch = '%'.$csearch.'%';
			$stmt = $this->db->prepare("select * from job s  inner join customer m on s.job_clie_id = m.cust_id  inner join contacts c on s.job_cont_name = c.cont_id inner join equipment e on s.job_equi_id = e.equi_id WHERE ( id LIKE :id OR job_lift LIKE :job_lift OR job_clie_id LIKE :job_clie_id OR job_equi_id LIKE :job_equi_id OR job_date LIKE :job_date OR job_time LIKE :job_time OR job_address LIKE :job_address OR job_lift LIKE :job_lift OR job_detail LIKE :job_detail OR job_status LIKE :job_status OR cust_name LIKE :cust_name  OR cont_name LIKE :cont_name OR job_invoice LIKE :job_invoice OR equi_name LIKE :equi_name) AND job_status NOT IN ('Inactive')");
			
			$stmt->bindparam(":id", $csearch);
			$stmt->bindparam(":job_lift", $csearch);
			$stmt->bindparam(":job_clie_id", $csearch);	
			$stmt->bindparam(":job_equi_id", $csearch);
			$stmt->bindparam(":job_date", $csearch); 
			$stmt->bindparam(":job_time", $csearch);
			$stmt->bindparam(":job_address", $csearch);
			$stmt->bindparam(":job_detail", $csearch);
			$stmt->bindparam(":job_status", $csearch);
			$stmt->bindparam(":cust_name", $csearch);
			$stmt->bindparam(":cont_name", $csearch);
			$stmt->bindparam(":job_invoice", $csearch);
			$stmt->bindparam(":equi_name", $csearch);
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
   
 public function searchjobslistattachment($csearch)
   {
        try
		{
			$csearch = '%'.$csearch.'%';
			$stmt = $this->db->prepare("select * from job s  inner join customer m on s.job_clie_id = m.cust_id  WHERE ( id LIKE :id OR job_lift LIKE :job_lift OR job_clie_id LIKE :job_clie_id OR job_equi_id LIKE :job_equi_id OR job_date LIKE :job_date OR job_time LIKE :job_time OR job_address LIKE :job_address OR job_lift LIKE :job_lift OR job_detail LIKE :job_detail OR job_status LIKE :job_status OR cust_name LIKE :cust_name  OR job_attachments LIKE :job_attachments OR job_invoice LIKE :job_invoice) AND job_status NOT IN ('Inactive')");
			$stmt->bindparam(":id", $csearch);
			$stmt->bindparam(":job_lift", $csearch);
			$stmt->bindparam(":job_clie_id", $csearch);	
			$stmt->bindparam(":job_equi_id", $csearch);
			$stmt->bindparam(":job_date", $csearch); 
			$stmt->bindparam(":job_time", $csearch);
			$stmt->bindparam(":job_address", $csearch);
			$stmt->bindparam(":job_detail", $csearch);
			$stmt->bindparam(":job_status", $csearch);
			$stmt->bindparam(":cust_name", $csearch);
			$stmt->bindparam(":job_attachments", $csearch);
			$stmt->bindparam(":job_invoice", $csearch);
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
   
  public function searchjobslistuserattachment($csearch)
   {
        try
			{
				$csearch = '%'.$csearch.'%';
				$stmt = $this->db->prepare("select * from users WHERE ( first_name LIKE :first_name OR last_name LIKE :last_name OR user_name LIKE :user_name) AND status NOT IN ('Inactive')");
				$stmt->bindparam(":user_name", $csearch);
				$stmt->bindparam(":first_name", $csearch);
				$stmt->bindparam(":last_name", $csearch);
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
   
   
 public function searchjobslisttbc($csearch)
   {
        try
			{
				$job_tbc = 1;
				$csearch = '%'.$csearch.'%';
				$stmt = $this->db->prepare("select * from job s  inner join customer m on s.job_clie_id = m.cust_id  inner join contacts c on s.job_cont_name = c.cont_id WHERE ( id LIKE :id OR job_lift LIKE :job_lift OR job_clie_id LIKE :job_clie_id OR job_equi_id LIKE :job_equi_id OR job_date LIKE :job_date OR job_time LIKE :job_time OR job_address LIKE :job_address OR job_lift LIKE :job_lift OR job_detail LIKE :job_detail OR job_status LIKE :job_status OR cust_name LIKE :cust_name  OR cont_name LIKE :cont_name OR job_invoice LIKE :job_invoice) AND job_tbc=:job_tbc AND job_status NOT IN ('Inactive')");
				$stmt->bindparam(":id", $csearch);
				$stmt->bindparam(":job_lift", $csearch);
				$stmt->bindparam(":job_clie_id", $csearch);	
				$stmt->bindparam(":job_equi_id", $csearch);
				$stmt->bindparam(":job_date", $csearch); 
				$stmt->bindparam(":job_time", $csearch);
				$stmt->bindparam(":job_address", $csearch);
				$stmt->bindparam(":job_detail", $csearch);
				$stmt->bindparam(":job_status", $csearch);
				$stmt->bindparam(":cust_name", $csearch);
				$stmt->bindparam(":cont_name", $csearch);
				$stmt->bindparam(":job_invoice", $csearch);
				$stmt->bindparam(":job_tbc", $job_tbc);
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
   
   
    
 public function getaddressnamewiselist($clientidaddress,$searchaddress,$contactaddress)
   {
        try
		{
          $csearch = '%'.$searchaddress.'%';
		  $stmt = $this->db->prepare("SELECT * FROM job WHERE ( job_address LIKE :job_address AND job_clie_id = :job_clie_id AND job_cont_name = :job_cont_name) AND job_status NOT IN ('Inactive')");
		 // $stmt->bindparam(":cust_id", $clientid);
		 // $stmt->bindparam(":cont_name", $csearch);
		  $stmt->bindValue(':job_clie_id', $clientidaddress, PDO::PARAM_INT);
		  $stmt->bindValue(':job_address', $csearch, PDO::PARAM_STR);
		  $stmt->bindValue(':job_cont_name', $contactaddress, PDO::PARAM_STR);
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
  
  public function customerdatanotbilled($customerss)
   {
        try
		{
			$stmt = $this->db->prepare("SELECT * FROM customer WHERE cust_id=:cust_id AND cust_status NOT IN ('Inactive')");
			$stmt->bindparam(":cust_id", $customerss);
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
 public function customer_csv($cust_name,$cust_email,$cust_contact,$cust_phone)
   {
       try
		{
			$cust_status = 'Active';
			$createdate= date("Y-m-d H:i:s"); 
			$stmt = $this->db->prepare("INSERT INTO customer(cust_name,cust_status,created_date,updated_date)VALUES(:cust_name, :cust_status, :created_date, :updated_date)");
			$stmt->bindparam(":cust_name", $cust_name);
			$stmt->bindparam(":cust_status", $cust_status);
			$stmt->bindparam(":created_date", $createdate);
			$stmt->bindparam(":updated_date", $createdate);
			$stmt->execute();
			$cust_id = $this->db->lastInsertId();
			if($cust_id )
				{
					$cont_status = 'Active';
					$createdate1= date("Y-m-d H:i:s"); 
					$stmt = $this->db->prepare("INSERT INTO contacts(cust_id,cont_name,cont_email,cont_phone,cont_status,created_date,updated_date)VALUES(:cust_id, :cont_name, :cont_email, :cont_phone, :cont_status, :created_date, :updated_date)");
					$stmt->bindparam(":cust_id", $cust_id);
					$stmt->bindparam(":cont_name", $cust_contact);
					$stmt->bindparam(":cont_email", $cust_email);
					$stmt->bindparam(":cont_phone", $cust_phone);
					$stmt->bindparam(":cont_status", $cont_status);
					$stmt->bindparam(":created_date", $createdate1);
					$stmt->bindparam(":updated_date", $createdate1);
					$stmt->execute(); 	
				}
		return $stmt; 
		}
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
   }
	
	
 public function getcustomerslistinactive()
   {
        try
		{
			$stmt = $this->db->prepare("SELECT * FROM customer WHERE cust_status IN ('Inactive')");
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
   
 public function contactinactive($cust_id)
   {
       try
       {   
	   	   $cont_status = 'Inactive';
           $stmt = $this->db->prepare("UPDATE contacts SET cont_status=:cont_status WHERE cust_id=:cust_id");
		   $stmt->bindparam(":cust_id", $cust_id);
		   $stmt->bindparam(":cont_status", $cont_status);	  
           $stmt->execute(); 
		   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
   }
	
 public function gecontactdatass()
   {
        try
		{
          $stmt = $this->db->prepare("SELECT * FROM contacts WHERE cont_status NOT IN ('Inactive')");
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
   
   public function updatecontact($contactfiledsss)
    {
       try
       {   
	   	   $md5_email = md5($contactfiledsss);
           $stmt = $this->db->prepare("UPDATE contacts SET md5_email=:md5_email WHERE cont_email=:cont_email");
		   $stmt->bindparam(":cont_email", $contactfiledsss);
		   $stmt->bindparam(":md5_email", $md5_email);	  
           $stmt->execute(); 
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
 public function custactdata($custid)
   {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM customer WHERE cust_id=:custid LIMIT 1");
          $stmt->execute(array(':custid'=>$custid));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		  return $userRow;
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
   public function unsubscribecontactmailmd($md_email)
    {
       try
       { 
			$subscribe = 'Unsubscribe';
			$stmt = $this->db->prepare("UPDATE contacts SET subscribe=:subscribe WHERE md5_email=:md5_email");
			$stmt->bindparam(":md5_email", $md_email);
			$stmt->bindparam(":subscribe", $subscribe);
			$stmt->execute(); 
	   }
	    catch(PDOException $e)
       {
           echo $e->getMessage();
       } 
	}
}
?>